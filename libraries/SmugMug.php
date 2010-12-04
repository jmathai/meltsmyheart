<?php
class SmugMug
{
  public static function getAlbums($token, $secret, $uid)
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
        'link' => '/proxy/p/'.Credential::serviceSmugMug."?method=photos&AlbumID={$album['id']}&AlbumKey={$album['Key']}");
    }
    getCache()->set($sig, $retval, time()+3600);
    return $retval;
  }

  public static function getPhotos($token, $secret, $id, $key)
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
      $retval[] = new Photo(
        $photo['id'], 
        $photo['ThumbURL'],
        $photo['MediumURL'],
        $photo['OriginalURL'],
        null, // date taken
        $photo['Date'], // date created TODO strtotime
        $photo['Caption']
      );
    }
    getCache()->set($sig, $retval, time()+3600);
    return $retval;
  }
}
