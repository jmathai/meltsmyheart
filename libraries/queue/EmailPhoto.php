<?php
class EmailPhoto extends Email
{
  private $photoId;
  private $userId;
  private $childId;
  private $attachment;

  public function setUp()
  {
    parent::setUp();
    $this->photoId = $this->args['entryId'];
    $this->userId = $this->args['userId'];
    $this->childId = $this->args['childId'];

    if(isset($this->args['attachment']) && !empty($this->args['attachment']))
    {
      $this->attachment = $this->args['attachment'];
      $attachment = Swift_Attachment::fromPath($this->attachment['source'], $this->attachment['type'])
        ->setFilename($this->attachment['name']);
      $this->message->attach($attachment);
    }

    // modify template and recipient email
    $photo = Photo::getById($this->userId, $this->photoId);
    $child = Child::getById($this->userId, $this->childId);
    $photoUrl = Child::getPhotoUrl($child, $photo);
    $age = sprintf('I took this photo when %s was %s old.', ucwords($child['c_name']), displayAge($child['c_birthdate'], $photo['p_dateTaken']));
    $this->template = str_replace(array('{AGE}', '{URL}'), array($age, $photoUrl), $this->template);

    $from = array_pop($this->from);
    $this->from = array(Comment::generateEmailAddress($from, $photo['p_id'], Comment::typePhoto) => $from);
  }
}
