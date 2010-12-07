<?php
class Facebook
{
  public static function getAlbums($token, $uid)
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
      $retval[] = array('id' => $album['id'], 'name' => $album['name'], 'cover' => "/proxy/r/facebook/{$album['id']}/picture",
        'link' => '/proxy/p/'.Credential::serviceFacebook."/?id={$album['id']}&method=photos");
    }
    getCache()->set($sig, $retval, time()+3600);
    return $retval;
  }

  public static function getPhotos($userId, $token, $uid)
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
      $cacheKey = self::cacheKey($photo['id']);
      $internalPhoto = new Photo(
        $photo['id'], 
        $photo['picture'],
        $photo['source'],
        null, // date taken
        strtotime($photo['created_time']), // date created
        isset($photo['name']) ? $photo['name'] : ''
      );
      $internalPhoto->internalId = Photo::add($userId, $cacheKey, $internalPhoto);
      $retval[] = $internalPhoto;
    }
    getCache()->set($sig, $retval, time()+3600);
    return $retval;
  }

  public static function cacheKey($id)
  {
    return Credential::serviceFacebook."-{$id}";
  }
}
