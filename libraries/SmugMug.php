<?php
class SmugMug
{
  public static function getAlbums($childId, $token, $secret, $uid)
  {
    $args = func_get_args();
    $sig = 'smga'.md5(implode('-', $args));
    $cache = getCache()->get($sig);
    if(!empty($cache))
      return $cache;

    $retval = array();
    getSmugMug()->setToken("id={$token}", "Secret={$secret}");
    $albums = getSmugMug()->albums_get('Heavy=True');	
    foreach($albums as $album)
    {
      $cover = '/img/album-cover.jpeg';
      if(isset($album['Highlight']))
      {
        $coverPhoto = getSmugMug()->images_getURLs("ImageID={$album['Highlight']['id']}", "ImageKey={$album['Highlight']['Key']}");
        $cover = $coverPhoto['ThumbURL'];
      }
      $retval[] = array('id' => $album['id'], 'name' => $album['Title'], 'cover' => $cover,
        'link' => '/album/photos/'.Credential::serviceSmugMug."/{$childId}/{$album['id']}/{$album['Key']}");
    }
    getCache()->set($sig, $retval, time()+3600);
    return $retval;
  }

  public static function getPhotos($userId, $childId, $token, $secret, $id, $key)
  {
    $args = func_get_args();
    $sig = 'smgp'.md5(implode('-', $args));
    $cache = getCache()->get($sig);
    if(!empty($cache))
      return $cache;

    $retval = array();
    getSmugMug()->setToken("id={$token}", "Secret={$secret}");
    $photos = getSmugMug()->images_get('Heavy=True', "AlbumID={$id}", "AlbumKey={$key}");	
    foreach($photos['Images'] as $photo)
    {
      $cacheKey = self::key($photo['id']);
      $exists = Photo::getByKey($userId, $childId, $cacheKey);
      if(!$exists)
      {
        $meta = new PhotoCache(
          $photo['id'], 
          $photo['ThumbURL'],
          $photo['OriginalURL'],
          null, // date taken
          strtotime($photo['Date']),
          $photo['Caption']
        );
        $photoId = Photo::add($userId, $childId, $cacheKey, null, null, null);
        if(!$photoId)
          return false;

        $meta->internalId = $photoId;
        Photo::addMeta($userId, $photoId, $meta);
      }
      else
      {
        $meta = $exists['p_meta'];
      }

      $retval[] = $meta;
    }
    getCache()->set($sig, $retval, time()+3600);
    return $retval;
  }

  public static function key($id)
  {
    return Credential::serviceSmugMug."-{$id}";
  }
}
