<?php
require getConfig()->get('paths')->libraries . '/swiftmailer/lib/swift_required.php';
class Email
{
  public $to;
  public $from;
  public $subject;
  public $message;
  public $template;
  public function setUp()
  {
    if(isset($this->args['from']) && !empty($this->args['from']))
      $this->from = array($this->args['from']);
    else
      $this->from = array(getConfig()->get('email')->from_email => getConfig()->get('email')->from_name);

    $this->to = $this->args['email'];
    $this->template = $this->args['template'];
    $this->subject = $this->args['subject'];
    $this->message = Swift_Message::newInstance();
  }

  public function perform()
  {
    $this->message
      ->setFrom($this->from)
      ->setTo($this->to)
      ->setSubject($this->subject)
      ->setBody($this->template, 'text/html')
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
