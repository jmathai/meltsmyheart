<?php
require getConfig()->get('paths')->libraries . '/swiftmailer/lib/swift_required.php';
class Email
{
  public $from;
  public $message;
  public $template;
  public function setUp()
  {
    if(isset($this->args['from']) && !empty($this->args['from']))
      $this->from = array($this->args['from']);
    else
      $this->from = array(getConfig()->get('email')->from_email => getConfig()->get('email')->from_name);

    $this->template = $this->args['template'];
    $this->message = Swift_Message::newInstance($this->args['subject']) 
      ->setFrom($this->from)
      ->setTo($this->args['email']);
  }

  public function perform()
  {
    $this->message->setBody($this->template, 'text/html')
      ->addPart($this->stripHtml($this->template), 'text/plain');

    $sendmail = getConfig()->get('email')->sendmail_path;
    $transport = Swift_SendmailTransport::newInstance("{$sendmail} -bs");

    $mailer = Swift_Mailer::newInstance($transport);
    $mailer->send($this->message);
  }

  public function tearDown() {}

  private function stripHtml($template)
  {
    return strip_tags($template);
  }
}
