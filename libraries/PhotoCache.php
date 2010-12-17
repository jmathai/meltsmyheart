<?php
class PhotoCache implements ArrayAccess
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

  public function offsetExists($offset) { return isset($this->$offset); }
  public function offsetGet($offset) { return isset($this->$offset) ? $this->$offset : null; }
  public function offsetSet($offset, $value) { if(isset($this->$offset)) $this->$offset = $value; }
  public function offsetUnset($offset) { if(isset($this->$offset)) unset($this->$offset);  }
}
