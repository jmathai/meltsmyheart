<?php
require getConfig()->get('paths')->libraries . '/swiftmailer/lib/swift_required.php';
class Email
{
  public function setUp() {}

  public function perform()
  {
    $message = Swift_Message::newInstance($this->args['subject']) 
      ->setFrom(array(getConfig()->get('email')->from_email => getConfig()->get('email')->from_name))
      ->setTo($this->args['email'])
      ->setBody($this->args['template'], 'text/html')
      ->addPart($this->stripHtml($this->args['template']), 'text/plain');

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
