<?php
chdir(dirname(__FILE__));
ini_set('include_path', '.');
require '../configs/init.php';
$systemEmailAddress = sprintf('%s@%s', getConfig()->get('emailer')->username, getConfig()->get('emailer')->domain);
$mail = new GmailReader($systemEmailAddress, getSecret('emailer_password'));
$mail->openMailBox('INBOX');
$emails = $mail->getAllEmail();
if(count($emails) == 0)
{
  getLogger()->info('No emails');
  exit;
}

$mail->expunge();
foreach($emails as $email)
{
  if(empty($email['body']))
  {
    $mail->delete($email['messageid']);
    continue;
  }

  // Recipient
  $decodedTo = Comment::getGeneratedEmailAddress($email['toaddress']);

  // Sender
  $newFromEmail = Comment::parseOutEmail($email['fromaddress']);
  $newFromEmailEncoded = Comment::generateEmailAddress($newFromEmail, $decodedTo['eh_itemId'], $decodedTo['eh_itemType']);

  $response = parseMessage($email['identifier'], $email['body'], $email['fromaddress']);
  if(!empty($response))
  {
    $commentEmailId = Comment::addEmail($email['toaddress'], $email['fromaddress'], $email['subject'], $email['body'], $response, $email['date']);
    if($commentEmailId)
    {
      $photo = Photo::getByIdNoUserId($decodedTo['eh_itemId']);
      getLogger()->info("New email from {$email['fromaddress']} to {$decodedTo['eh_email']}");
      // TODO put queue classes in subdir
      $queueMessage = array('commentEmailId' => $commentEmailId, 'email' => $decodedTo['eh_email'], 'fromEmail' => $newFromEmailEncoded, 
        'fromName' => $newFromEmail, 'subject' => $email['subject'], 'template' => $response, 'date' => $email['date'], 
        'childId' => $photo['p_c_id'], 'userId' => $photo['p_u_id']);
      Resque::enqueue('mmh_reply', 'EmailReply', $queueMessage);
      $mail->delete($email['messageid']);
    }
  }
  else
  {
    getLogger()->warn("Could not parse a response from {$email['body']}");
  }
}
$mail->expunge();

function parseMessage($identifier, $body, $from)
{
  if(preg_match('/^On [A-Z]{1}[a-z]{2}( \d+|, [A-Z][a-z]{2}).+$/m', $body))
  {
    preg_match('/(.*)^On [A-Z]{1}[a-z]{2,3}( |,)/ms', $body, $matches); 
    if(isset($matches[1]))
    {
      $body = trim($matches[1]);
      return $body;
    }

  }
  $patterns = array(
    '/^\>.+/ms',
    '/^(-|_){5}.+/ms',
    '/^[A-Z][a-z]+:.+/ms',
    '/^O.+$/ms'
  );
  $body = preg_replace($patterns, '', $body);
  return trim($body);

  if(preg_match('/.+gmail\.com>/', $identifier))
  {
    preg_match('/^(.+)<.+@.+>\s*wrote:.*$/s', $body, $matches);
    $lines = explode("\n", trim($matches[1]));
    array_pop($lines);
    $body = trim(implode("\n", $lines));
    return "GMAIL: $body";
  }

  if(preg_match('/.+yahoo\.com>/', $identifier))
  {
    preg_match('/^([^_]{5,})\n_+.+From:.+/s', $body, $matches);
    if(isset($matches[1]))
    {
      $body = trim($matches[1]);
      return "YAHOO: $body";
    }
    return "YAHOO ????";
  }
  
  if(preg_match('/<.+@(hotmail|live).com>/', $identifier))
  {
    preg_match('/(.+)\nDate:.+/s', $body, $matches);
    $body = trim($matches[1]);
    return "HOTMAIL: $body";
  }

  // Outlook Express
  if(preg_match('/^(.[^_]{5})----- Original Message -----/', $body, $matches))
  {
    $body = trim($matches[1]);
    return $body;
  }

  return "HIUH? $body";
}
