<?php
class Photo
{
  public static function add($userId, $childId, $thumbPath, $basePath, $originalPath)
  {
    return getDatabase()->execute('INSERT INTO photo(p_u_id, p_c_id, p_thumbPath, p_basePath, p_originalPath, p_dateCreated)
      VALUES(:userId, :childId, :thumbPath, :basePath, :originalPath, :time)',
      array(':userId' => $userId, ':childId' => $childId, ':thumbPath' => $thumbPath, ':basePath' => $basePath, ':originalPath' => $originalPath, ':time' => time()));
  }

  public static function getByChild($userId, $childId)
  {
    return getDatabase()->all('SELECT * FROM photo WHERE p_u_id=:userId AND p_c_id=:childId',
      array(':userId' => $userId, ':childId' => $childId));
  }

  public static function update($userId, $entryId, $thumbPath, $basePath, $originalPath)
  {
    return getDatabase()->execute('UPDATE photo SET p_thumbPath=:thumbPath, p_basePath=:basePath, p_originalPath=:originalPath WHERE p_id=:entryId AND p_u_id=:userId',
      array(':entryId' => $entryId, ':userId' => $userId, ':thumbPath' => $thumbPath, ':basePath' => $basePath, ':originalPath' => $originalPath));
  }
}
