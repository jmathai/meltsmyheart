<?php
class Entry
{
  public static function add($userId, $childId, $thumbPath, $basePath, $originalPath)
  {
    return getDatabase()->execute('INSERT INTO entry(e_u_id, e_c_id, e_thumbPath, e_basePath, e_originalPath, e_dateCreated)
      VALUES(:userId, :childId, :thumbPath, :basePath, :originalPath, :time)',
      array(':userId' => $userId, ':childId' => $childId, ':thumbPath' => $thumbPath, ':basePath' => $basePath, ':originalPath' => $originalPath, ':time' => time()));
  }

  public static function getByChild($userId, $childId)
  {
    return getDatabase()->all('SELECT * FROM entry WHERE e_u_id=:userId AND e_c_id=:childId',
      array(':userId' => $userId, ':childId' => $childId));
  }

  public static function update($userId, $entryId, $thumbPath, $basePath, $originalPath)
  {
    return getDatabase()->execute('UPDATE entry SET e_thumbPath=:thumbPath, e_basePath=:basePath, e_originalPath=:originalPath WHERE e_id=:entryId AND e_u_id=:userId',
      array(':entryId' => $entryId, ':userId' => $userId, ':thumbPath' => $thumbPath, ':basePath' => $basePath, ':originalPath' => $originalPath));
  }
}
