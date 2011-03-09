<?php
require getConfig()->get('paths')->libraries . '/swiftmailer/lib/swift_required.php';
class Email
{
  public function setUp() {}

  public function perform()
  {
    if(isset($this->args['from']) && !empty($this->args['from']))
      $from = array($this->args['from']);
    else
      $from = array(getConfig()->get('email')->from_email => getConfig()->get('email')->from_name);

    $message = Swift_Message::newInstance($this->args['subject']) 
      ->setFrom($from)
      ->setTo($this->args['email'])
      ->setBody($this->args['template'], 'text/html')
      ->addPart($this->stripHtml($this->args['template']), 'text/plain');
    if(isset($this->args['attachment']) && !empty($this->args['attachment']))
    {
      $attachment = Swift_Attachment::fromPath($this->args['attachment']['source'], $this->args['attachment']['type'])
        ->setFilename($this->args['attachment']['name']);
      $message->attach($attachment);
    }

    $sendmail = getConfig()->get('email')->sendmail_path;
    $transport = Swift_SendmailTransport::newInstance("{$sendmail} -bs");

    $mailer = Swift_Mailer::newInstance($transport);
    $mailer->send($message);
  }

  public function tearDown() {}

  private function stripHtml($template)
  {
    return strip_tags($template);
  }
}
