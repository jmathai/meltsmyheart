<?php
class FacebookPhotos
{
  public static function getAlbums($token, $uid)
  {
    $retval = array();
    $albums = getFacebook()->api("/{$uid}/albums", 'GET', array('access_token' => $token));
    foreach($albums['data'] as $album)
    {
      $retval[] = array('id' => $album['id'], 'name' => $album['name'], 'cover' => "/proxy/r/facebook/{$album['id']}/picture");
    }
    return $retval;
  }
}
