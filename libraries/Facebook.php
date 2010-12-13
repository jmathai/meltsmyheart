<?php
class Facebook
{
  public static function getAlbums($childId, $token, $uid)
  {
    $args = func_get_args();
    $sig = 'fbga'.md5(implode('-', $args));
    $cache = getCache()->get($sig);
    if(!empty($cache))
      return $cache;

    $retval = array();
    $albums = getFacebook()->api("/{$uid}/albums", 'GET', array('access_token' => $token));
    foreach($albums['data'] as $album)
    {
      $retval[] = array('id' => $album['id'], 'name' => $album['name'], 'cover' => '/proxy/r/'.Credential::serviceFacebook."/{$childId}/{$album['id']}/picture",
        'link' => '/album/photos/'.Credential::serviceFacebook."/{$childId}/{$album['id']}");
    }
    getCache()->set($sig, $retval, time()+3600);
    return $retval;
  }

  public static function getPhotos($userId, $childId, $token, $uid)
  {
    $args = func_get_args();
    $sig = 'fbgp'.md5(implode('-', $args));
    $cache = getCache()->get($sig);
    if(!empty($cache))
      return $cache;

    $retval = array();
    $photos = getFacebook()->api("/{$uid}/photos", 'GET', array('access_token' => $token));
    foreach($photos['data'] as $photo)
    {
      $cacheKey = self::key($photo['id']);
      $exists = Photo::getByKey($userId, $childId, $cacheKey);
      if(!$exists)
      {
        $meta = new PhotoCache(
          $photo['id'], 
          $photo['picture'],
          $photo['source'],
          null, // date taken
          strtotime($photo['created_time']), // date created
          isset($photo['name']) ? $photo['name'] : ''
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
    return Credential::serviceFacebook."-{$id}";
  }
}
