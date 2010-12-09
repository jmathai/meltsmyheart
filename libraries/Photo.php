<?php
class Photo
{
  const greyscale = 'bw';
  const sepia = 'sp';
  public static function add($userId, $childId, $thumbPath, $basePath, $originalPath)
  {
    return getDatabase()->execute('INSERT INTO photo(p_u_id, p_c_id, p_thumbPath, p_basePath, p_originalPath, p_dateCreated)
      VALUES(:userId, :childId, :thumbPath, :basePath, :originalPath, :time)',
      array(':userId' => $userId, ':childId' => $childId, ':thumbPath' => $thumbPath, ':basePath' => $basePath, ':originalPath' => $originalPath, ':time' => time()));
  }

  public static function exif($photoFile)
  {
    $photoData = exif_read_data($photoFile);
    if(!$photoData)
      return null;

    $size = getimagesize($photoFile);
    return array('dateTaken' => $photoData['FileDateTime'], 'width' => $size[0], 'height' => $size[1],
      'cameraModel' => $photoData['Model'], 'cameraMake' => $photoData['Make']);
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

    $width = array_shift($options);
    if($width == '*')
      $width = PHP_INT_MAX;
    else
      $width = (int)$width;

    $height = array_shift($options);
    if($height == '*')
      $height = PHP_INT_MAX;
    else
      $height = (int)$height;

    if(empty($width) || empty($height) || ($width.$height) == '**')
      return false;

    // valid request
    $pathExe = getConfig()->get('paths')->exe;
    $basePath = "/base/{$datePart}/" . self::getBaseNameFromCustomName($fileName);
    $fullBasePath = getConfig()->get('paths')->photos . $basePath;
    $customPath = "/custom/{$datePart}/{$fileName}";
    $fullCustomPath = getConfig()->get('paths')->photos . $customPath;
    $im = new ImageMagick($fullBasePath, $pathExe);
    $im->scale($width, $height, $fullCustomPath, true);   

    if(count($options) > 0)
    {
      foreach($options as $option)
      {
        switch($option)
        {
          case self::greyscale:
            $im = new ImageMagick($fullCustomPath, $pathExe);
            $im->desaturate();
            break;
          case self::sepia:
            $im = new ImageMagick($fullCustomPath, $pathExe);
            $im->sepia();
            break;
        }
      }
    }
    $basePhoto = Photo::getByBasePath($basePath);
    Photo::addCustom($basePhoto['p_u_id'], $basePhoto['p_id'], $customPath, $basePath);
    return $fullCustomPath;
  }

  public static function generateUrl($basePath, $width, $height, $options = array())
  {
    $customPath = preg_replace('#^/base/#', '/custom/', $basePath);
    $params = '{'.intval($width).','.intval($height); // {1,2
    if(!empty($options))
    {
      sort($options);
      $params .= ','.implode(',', $options);
    }
    $hash = self::generateHash(array_merge(array($width, $height), $options));
    $params .= ",{$hash}}";
    $fileParts = pathinfo($customPath);
    return "{$fileParts['dirname']}/{$fileParts['filename']}{$params}.{$fileParts['extension']}";
  }

  public static function getByBasePath($basePath)
  {
    $retval = getDatabase()->one('SELECT * FROM photo WHERE p_basePath=:basePath', array(':basePath' => $basePath));
    $retval['p_exif'] = json_decode($retval['p_exif'], 1);
    return $retval;
  }

  public static function getByChild($userId, $childId)
  {
    $photos = getDatabase()->all('SELECT * FROM photo WHERE p_u_id=:userId AND p_c_id=:childId',
      array(':userId' => $userId, ':childId' => $childId));
    foreach($photos as $key => $value)
      $photos[$key]['p_exif'] = json_decode($value['p_exif'], 1);

    return $photos;
  }

  public static function update($userId, $entryId, $thumbPath, $basePath, $originalPath, $exif, $dateTaken)
  {
    return getDatabase()->execute('UPDATE photo SET p_thumbPath=:thumbPath, p_basePath=:basePath, 
      p_originalPath=:originalPath, p_exif=:exif, p_dateTaken=:dateTaken WHERE p_id=:entryId AND p_u_id=:userId',
      array(':entryId' => $entryId, ':userId' => $userId, ':thumbPath' => $thumbPath, ':basePath' => $basePath, 
        ':originalPath' => $originalPath, ':exif' => $exif, ':dateTaken' => $dateTaken));
  }

  public static function validateHash($options, $hash)
  {
    if(is_array($options))
      $options = implode(',', $options);
    return (self::saltedHash($options) == $hash);
  }

  private static function addCustom($userId, $photoId, $path, $basePath)
  {
    return getDatabase()->execute('INSERT INTO custom(c_u_id, c_p_id, c_path, c_basePath, c_dateCreated) VALUES(:userId, :photoId, :path, :basePath, :time)',
      array(':userId' => $userId, ':photoId' => $photoId, ':path' => $path, ':basePath' => $basePath, ':time' => time()));
  }

  private static function getBaseNameFromCustomName($customPath)
  {
    return preg_replace('/\{.*\}/', '', $customPath);
  }

  private static function saltedHash($string)
  {
    return substr(md5("{$string[0]}{$string}{$string[1]}"), -5);
  }
}
