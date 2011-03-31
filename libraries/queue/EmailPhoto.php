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

    // modify template and recipient email
    $photo = Photo::getById($this->userId, $this->photoId);
    $child = Child::getById($this->userId, $this->childId);
    $photoUrl = Child::getPhotoUrl($child, $photo);
    $age = sprintf('I took this photo when %s was %s old.', ucwords($child['c_name']), displayAge($child['c_birthdate'], $photo['p_dateTaken']));
    $this->template = str_replace(array('{AGE}', '{URL}'), array($age, $photoUrl), $this->template);

    $from = array_pop($this->from);
    $this->from = array(Comment::generateEmailAddress($from, $photo['p_id'], Comment::typePhoto) => $from);

    // working code for post processing
    /*preg_match_all('#[^/]+#', $photo['p_basePath'], $matches);
    $photoPath = Photo::generateUrl($photo['p_basePath'], 800, 800, array(Photo::contrast));
    $photoPath = str_replace(getConfig()->get('urls')->base, '', $photoPath);
    error_log(var_export($matches, 1));
    error_log("PHOTO PATH: {$photoPath}");
    $attachmentPhoto = Photo::generatePhoto($matches[0][1], basename($photoPath));
    if(file_exists($attachmentPhoto))
    {
      $this->attachment = $this->args['attachment'];
      $attachment = Swift_Attachment::fromPath($attachmentPhoto, 'image/jpeg')
        ->setFilename(basename($attachmentPhoto));
      $this->message->attach($attachment);
    }*/
    if(isset($this->args['attachment']) && !empty($this->args['attachment']))
    {
      $this->attachment = $this->args['attachment'];
      $attachment = Swift_Attachment::fromPath($this->attachment['source'], $this->attachment['type'])
        ->setFilename($this->attachment['name']);
      $this->message->attach($attachment);
    }
  }
}
