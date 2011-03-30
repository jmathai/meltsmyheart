<?php
class EmailReply extends Email
{
  private $userId;
  private $childId;
  public function setUp()
  {
    parent::setUp();
    $this->userId = $this->args['userId'];
    $this->childId = $this->args['childId'];

    $this->from = array($this->args['fromEmail'] => $this->args['fromName']);
    $child = Child::getById($this->userId, $this->childId);
    $this->template = getTemplate()->get('email/reply.php', array('msg' => $this->template, 'url' => Child::getPageUrl($child)));
  }
}
