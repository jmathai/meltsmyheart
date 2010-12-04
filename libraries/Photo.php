<?php
class Photo
{
  public $id, $thumbUrl, $mediumUrl, $originalUrl, $dateCreated, $dateTaken, $title;
  public function __construct($id, $thumbUrl, $mediumUrl, $originalUrl, $dateCreated, $dateTaken, $title)
  {
    $this->id = $id;
    $this->thumbUrl = $thumbUrl;
    $this->mediumUrl = $mediumUrl;
    $this->originalUrl = $originalUrl;
    $this->dateCreated = $dateCreated;
    $this->dateTaken = $dateTaken;
    $this->title = $title;
  }
}
