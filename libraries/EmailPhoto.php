<?php
class EmailPhoto extends Email
{
  public function setUp()
  {
    parent::setUp();
    if(isset($this->args['attachment']) && !empty($this->args['attachment']))
    {
      $attachment = Swift_Attachment::fromPath($this->args['attachment']['source'], $this->args['attachment']['type'])
        ->setFilename($this->args['attachment']['name']);
      $this->message->attach($attachment);
    }

    // modify template
    $basePath = str_replace(getConfig()->get('paths')->photos, '', $this->args['attachment']['source']);
    $photo = Photo::getByBasePath($basePath);
    $child = Child::getById($this->args['userId'], $this->args['childId']);
    $photoUrl = Child::getPhotoUrl($child, $photo);
    $age = sprintf('I took this photo when %s was %s old.', ucwords($child['c_name']), displayAge($child['c_birthdate'], $photo['p_dateTaken']));
    $this->template = str_replace(array('{AGE}', '{URL}'), array($age, $photoUrl), $this->template);
  }
}
