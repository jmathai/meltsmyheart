<?php
class Photagious
{
  public function get($action, $key, $params = array())
  {
    $ch = curl_init(getConfig()->get('thirdparty')->ptg_host . "/api/json?action={$action}&authenticationKey={$key}&".http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $resp = curl_exec($ch);
    curl_close($ch);
    $resp = json_decode($resp, 1);
    return $resp;   
  }

  public static function getAlbums($childId, $key)
  {
    $args = func_get_args();
    $sig = 'ptgga'.md5(implode('-', $args));
    $cache = getCache()->get($sig);
    if(!empty($cache))
      return $cache;

    $retval = array();
    $albums = getPhotagious()->get('user.getTags', $key);
    foreach($albums as $album)
    {
      $retval[] = array('id' => $album['tag'], 'name' => $album['tag'], 'cover' => '',
        'link' => '/album/photos/'.Credential::servicePhotagious."/{$childId}/{$album['tag']}");
    }
    //getCache()->set($sig, $retval, time()+3600); // TODO: recache
    return $retval;
  }

  public static function getPhotos($userId, $childId, $key, $tag)
  {
    $args = func_get_args();
    $sig = 'ptggp'.md5(implode('-', $args));
    $cache = getCache()->get($sig);
    if(!empty($cache))
      return $cache;

    $retval = array();
    $photos = getPhotagious()->get("image.search", $key, array('tags' => $tag));
    array_pop($photos); // pop info element
    foreach($photos as $photo)
    {
      $cacheKey = self::key($photo['id']);
      $exists = Photo::getByKey($userId, $childId, $cacheKey);
      if(!$exists)
      {
        $meta = new PhotoCache(
          $photo['id'], 
          getConfig()->get('thirdparty')->ptg_host . "/photos{$photo['thumbnailPath']}",
          getConfig()->get('thirdparty')->ptg_source_host . "{$photo['originalPath']}",
          $photo['dateTaken'], // date taken
          $photo['dateCreated'], // date created
          $photo['name']
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
    //getCache()->set($sig, $retval, time()+3600); // TODO: recache
    return $retval;
  }

  public static function key($id)
  {
    return Credential::servicePhotagious."-{$id}";
  }
}

function getPhotagious()
{
  static $ptg;
  if(!$ptg)
    $ptg = new Photagious;

  return $ptg;
}
