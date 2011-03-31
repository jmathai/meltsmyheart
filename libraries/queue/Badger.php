<?php
class Badger extends Email
{
  private $badgeId;
  private $userId;
  private $childId;
  private $user;
  private $child;
  private $badge;
  private $attachmentSource;
  private $attachmentType;
  private $attachmentName;

  public function setUp()
  {
    parent::setUp();
    $this->badgeId = $this->args['badgeId'];
    $this->userId = $this->args['userId'];
    $this->childId = $this->args['childId'];
    $this->user = User::getById($this->userId);
    $this->child = Child::getById($this->userId, $this->childId);

    $this->badge = Badge::getById($this->badgeId);

    $this->attachmentSource = getConfig()->get('paths')->docroot . "/img/badges/{$this->badge['bm_icon']}";
    $this->attachmentType = 'image/png';
    $this->attachmentName = $this->badge['bm_icon'];
    $attachment = Swift_Attachment::fromPath($this->attachmentSource, $this->attachmentType)
      ->setFilename($this->attachmentName);
    $this->message->attach($attachment);
    
    $this->to = $this->user['u_email'];
    $this->subject = sprintf('A new badge for %s', $this->child['c_name']);
    $this->template = getTemplate()->get('email/badge.php', 
      array('childName' => $this->child['c_name'], 'badgeName' => $this->badge['bm_name'], 'pageUrl' => Child::getPageUrl($this->child)));
  }

  public function perform()
  {
    parent::perform();
    Badge::add($this->childId, $this->badgeId);
  }
}
