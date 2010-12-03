<?php
class Facebook
{
  public static function getAlbums($token, $uid)
  {
    $retval = array();
    $albums = getFacebook()->api("/{$uid}/albums", 'GET', array('access_token' => $token));
    foreach($albums['data'] as $album)
    {
      $retval[] = array('id' => $album['id'], 'name' => $album['name'], 'cover' => "/proxy/r/facebook/{$album['id']}/picture",
        'link' => '/proxy/p/'.Credential::serviceFacebook."/?id={$album['id']}&method=photos");
    }
    return $retval;
  }

  public static function getPhotos($token, $uid)
  {
    $retval = array();
    $photos = getFacebook()->api("/{$uid}/photos", 'GET', array('access_token' => $token));
    foreach($photos['data'] as $photo)
    {
      $retval[] = array('id' => $photo['id'], 'title' => $photo['Caption'], 
        'thumbUrl' => $photo['picture'], 'mediumUrl' => null,
        'originalUrl' => $photo['source']);
    }
    return $retval;
 }
}
