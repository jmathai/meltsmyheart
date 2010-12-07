<?php
class Photo
{
  public static function add($userId, $childId, $thumbPath, $basePath, $originalPath)
  {
    return getDatabase()->execute('INSERT INTO photo(p_u_id, p_c_id, p_thumbPath, p_basePath, p_originalPath, p_dateCreated)
      VALUES(:userId, :childId, :thumbPath, :basePath, :originalPath, :time)',
      array(':userId' => $userId, ':childId' => $childId, ':thumbPath' => $thumbPath, ':basePath' => $basePath, ':originalPath' => $originalPath, ':time' => time()));
  }

  public static function generateHash($options)
  {
    if(is_array($options))
      $options = implode(',', $options);
    return self::saltedHash($options);
  }

  public static function generatePhoto($datePart, $fileName)
  {
    // /photos/custom/foo{450,500,bw,hash}.jpg
    if(!preg_match('/\{(.*)\}/', $fileName, $parts))
      return false;

    $options = (array)explode(',', $parts[1]);
    $hash = array_pop($options);

    if(!Photo::validateHash($options, $hash))
      return false;

    $width = (int)array_shift($options);
    $height = (int)array_shift($options);

    if(empty($width) || empty($height))
      return false;

    // valid request
    echo "resize photo to $width x $height<br>";
    if(count($options) > 0)
    {
      foreach($options as $option)
      {
        switch($option)
        {
          case 'bw':
            echo "make photo black and white<br>";
            break;
          case 'sp':
            echo "make photo sepia<br>";
            break;
        }
      }
    }
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

  public static function validateHash($options, $hash)
  {
    if(is_array($options))
      $options = implode(',', $options);
    return (self::saltedHash($options) == $hash);
  }

  private static function saltedHash($string)
  {
    return substr(md5("a{$string}9"), -5);
  }
}
