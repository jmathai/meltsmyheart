<?php
class EmailReply extends Email
{
  private $userId;
  private $childId;
  private $photoId;

  public function setUp()
  {
    parent::setUp();
    $this->userId = $this->args['userId'];
    $this->childId = $this->args['childId'];

    $this->from = array($this->args['fromEmail'] => $this->args['fromName']);
    $child = Child::getById($this->userId, $this->childId);
    $photo = Photo::getById($this->userId, $this->photoId);
    $this->template = getTemplate()->get('email/reply.php', array('msg' => $this->template, 'url' => Child::getPhotoUrl($child, $photo)));
  }
}
