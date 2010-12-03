<?php
class SmugMug
{
  public static function getAlbums($token, $secret, $uid)
  {
    $sig = 'smga'.md5(implode('-', func_get_args()));
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
        'link' => '/proxy/p/'.Credential::serviceSmugMug."?method=photos&AlbumID={$album['id']}&AlbumKey={$album['Key']}");
    }
    getCache()->set($sig, $retval, time()+3600);
    return $retval;
  }

  public static function getPhotos($token, $secret, $id, $key)
  {
    $sig = 'smga'.md5(implode('-', func_get_args()));
    $cache = getCache()->get($sig);
    if(!empty($cache))
      return $cache;

    $retval = array();
    getSmugMug()->setToken("id={$token}", "Secret={$secret}");
    $photos = getSmugMug()->images_get('Heavy=True', "AlbumID={$id}", "AlbumKey={$key}");	
    foreach($photos['Images'] as $photo)
    {
      $retval[] = array('id' => $photo['id'], 'title' => $photo['Caption'], 
        'thumbUrl' => $photo['ThumbURL'], 'mediumUrl' => $photo['MediumURL'],
        'originalUrl' => $photo['OriginalURL']);
    }
    getCache()->set($sig, $retval, time()+3600);
    return $retval;
  }
}
