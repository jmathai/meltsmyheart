<?php
class SmugMug
{
  public static function getAlbums($token, $secret, $uid)
  {
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
      $retval[] = array('id' => $album['id'], 'name' => $album['Title'], 'cover' => $cover);
    }
    return $retval;
  }
}
