<?php
class PhotoCache
{
  public $id, $thumbUrl, $originalUrl, $dateCreated, $dateTaken, $title, $internalId;
  public function __construct($id, $thumbUrl, $originalUrl, $dateCreated, $dateTaken, $title)
  {
    $this->id = $id;
    $this->thumbUrl = $thumbUrl;
    $this->originalUrl = $originalUrl;
    $this->dateCreated = $dateCreated;
    $this->dateTaken = $dateTaken;
    $this->title = $title;
  }
}
