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

  public static function add($userId, $key, $value)
  {
    $id = getDatabase()->execute('INSERT INTO photo_cache(pc_u_id, pc_key, pc_meta, pc_dateCreated) VALUES(:userId, :key, :meta, :time) ON DUPLICATE KEY UPDATE pc_meta=:meta, pc_dateCreated=:time',
      array(':userId' => $userId, ':key' => $key, ':meta' => json_encode($value), ':time' => time()));
    if(!$id)
    {
      $photo = self::one($userId, $key);
      $id = $photo['pc_id'];
    }
    return $id;
  }

  public static function all($userId, $key)
  {
    $retval = getDatabase()->all('SELECT * FROM photo_cache WHERE pc_u_id=:userId AND pc_key=:key', 
      array(':userId' => $userId, ':key' => $key));
    foreach($retval as $key => $value)
    {
      $retval[$key]['pc_meta'] = json_decode($value['pc_meta'], 1);
      $retval[$key]['pc_meta']['internalId'] = $value['pc_id'];
    }

    return $retval;
  }

  public static function deleteOne($userId, $id)
  {

  }

  public static function deleteAll($userId, $key)
  {

  }

  public static function getById($userId, $id)
  {
    $retval = getDatabase()->one('SELECT * FROM photo_cache WHERE pc_u_id=:userId AND pc_id=:id', 
      array(':userId' => $userId, ':id' => $id));
    $retval['pc_meta'] = json_decode($retval['pc_meta'], 1);
    $retval['pc_meta']['internalId'] = $retval['pc_id'];
    return $retval;
  }

  public static function one($userId, $key)
  {
    $retval = getDatabase()->one('SELECT * FROM photo_cache WHERE pc_u_id=:userId AND pc_key=:key', 
      array(':userId' => $userId, ':key' => $key));
    $retval['pc_meta'] = json_decode($retval['pc_meta'], 1);
    $retval['pc_meta']['internalId'] = $retval['pc_id'];
    return $retval;
  }
  

}
