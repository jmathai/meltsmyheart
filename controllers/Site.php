<?php
class Site
{
  public static function albumPhotosFacebook($childId, $albumId)
  {
    $userId = getSession()->get('userId');
    $credential = Credential::getByService($userId, Credential::serviceFacebook);
    $ids = Photo::extractIds(Photo::getByChild($userId, $childId));
    $photos = Facebook::getPhotos($userId, $childId, $credential['c_token'], $albumId);
    $markup = getTemplate()->get('photosList.php', array('childId' => $childId, 'photos' => $photos, 'ids' => $ids));
    Api::success($markup);
  }

  public static function albumPhotosPhotagious($childId, $tag)
  {
    $userId = getSession()->get('userId');
    $credential = Credential::getByService($userId, Credential::servicePhotagious);
    $ids = Photo::extractIds(Photo::getByChild($userId, $childId));
    $photos = Photagious::getPhotos($userId, $childId, $credential['c_token'], $tag);
    $markup = getTemplate()->get('photosList.php', array('childId' => $childId, 'photos' => $photos, 'ids' => $ids));
    Api::success($markup);
  }

  public static function albumPhotosSmugMug($childId, $albumId, $albumKey)
  {
    $userId = getSession()->get('userId');
    $credential = Credential::getByService($userId, Credential::serviceSmugMug);
    $ids = Photo::extractIds(Photo::getByChild($userId, $childId));
    $photos = SmugMug::getPhotos($userId, $childId, $credential['c_token'], $credential['c_secret'], $albumId, $albumKey);
    $markup = getTemplate()->get('photosList.php', array('childId' => $childId, 'photos' => $photos, 'ids' => $ids));
    Api::success($markup);
  }

  public static function albumsListFacebook($childId)
  {
    self::requireLogin();
    $userId = getSession()->get('userId');
    $credential = Credential::getByService($userId, Credential::serviceFacebook);
    $albums = Facebook::getAlbums($childId, $credential['c_token'], $credential['c_uid']);
    $ids = Photo::extractIds(Photo::getByChild($userId, $childId));
    getTemplate()->display('template.php', array('body' => 'albumsList.php', 'service' => Credential::serviceFacebook, 'albums' => $albums,
      'javascript' => getTemplate()->get('javascript/albumsList.js.php', array('childId' => $childId, 'ids' => $ids))));
  }

  public static function albumsListPhotagious($childId)
  {
    self::requireLogin();
    $userId = getSession()->get('userId');
    $credential = Credential::getByService($userId, Credential::servicePhotagious);
    $albums = Photagious::getAlbums($childId, $credential['c_token']);
    $ids = Photo::extractIds(Photo::getByChild($userId, $childId));
    getTemplate()->display('template.php', array('body' => 'albumsList.php', 'service' => Credential::serviceFacebook, 'albums' => $albums,
      'javascript' => getTemplate()->get('javascript/albumsList.js.php', array('childId' => $childId, 'ids' => $ids))));
  }

  public static function albumsListSmugMug($childId)
  {
    self::requireLogin();
    $userId = getSession()->get('userId');
    $credential = Credential::getByService($userId, Credential::serviceSmugMug);
    getSmugMug()->setToken("id={$credential['c_token']}", "Secret={$credential['c_secret']}");
    $albums = SmugMug::getAlbums($childId, $credential['c_token'], $credential['c_secret'], $credential['c_uid']);
    getTemplate()->display('template.php', array('body' => 'albumsList.php', 'service' => Credential::serviceSmugMug, 'albums' => $albums,
      'javascript' => getTemplate()->get('javascript/albumsList.js.php', array('childId' => $childId))));
  }

  public static function childNew()
  {
    self::requireLogin();
    getTemplate()->display('template.php', array('body' => 'childNew.php'));
  }

  public static function childPage($name)
  {
    $child = Child::getByDomain($name);
    $photos = Photo::getByChild($child['c_u_id'], $child['c_id']);
    $photosByGroup = Photo::photosByGroup($child['c_birthdate'], $photos);
    getTemplate()->display('template.php', array('body' => 'childPage.php', 'child' => $child, 'photos' => $photosByGroup));
  }

  public static function childNewPost()
  {
    self::requireLogin();
    $childId = Child::add(getSession()->get('userId'), $_POST['childName'], strtotime($_POST['childBirthDate']), $_POST['childDomain']);
    getRoute()->redirect("/photos/source/{$childId}");
  }

  public static function connectFacebook($childId)
  {
    self::requireLogin();
    $credentialId = 0;
    if(isset($_GET['code']))
    {
      $redirectUrl = getConfig()->get('urls')->base."/connect/facebook/{$childId}";
      $resp = getFacebook()->fetchAccessToken($_GET['code'], $redirectUrl);
      $token = $resp['access_token'];
      $profile = getFacebook()->api('/me', 'GET', array('access_token' => $token));
      if(empty($token) || !isset($profile['id']))
        throw new Exception('Could not get Facebook session', 500);
      $credentialId = Credential::add(getSession()->get('userId'), Credential::serviceFacebook, $token, null, $profile['id']);
    }

    if($credentialId)
    {
      getRoute()->redirect("/albums/list/facebook/{$childId}");
    }
    /* TODO else
    {

    }*/
  }

  public static function connectPhotagious()
  {
    self::requireLogin();   
    $credentialId = 0;
    if(isset($_GET['key']))
    {
      $credentialId = Credential::add(getSession()->get('userId'), Credential::servicePhotagious, $_GET['key']);
    }

    if($credentialId)
    {
      $childId = getSession()->get('currentChildId');
      getRoute()->redirect("/albums/list/photagious/{$childId}");
    }
    /* TODO else
    {

    }*/
  }

  public static function connectSmugMug()
  {
    self::requireLogin();   
    $credentialId = 0;
    if(isset($_GET['oauth_token']))
    {
      $smugReqTok = unserialize(getSession()->get('smugReqTok'));
      getSession()->set('smugReqTok', null);
      getSmugMug()->setToken("id={$smugReqTok['Token']['id']}", "Secret={$smugReqTok['Token']['Secret']}");
      $token = getSmugMug()->auth_getAccessToken();
      if(empty($token['Token']['id']))
        throw new Exception('Could not get SmugMug session', 500);
      $credentialId = Credential::add(getSession()->get('userId'), Credential::serviceSmugMug, $token['Token']['id'], $token['Token']['Secret'], $token['User']['id']);
    }
    if($credentialId)
    {
      $childId = getSession()->get('currentChildId');
      getRoute()->redirect("/albums/list/smugmug/{$childId}");
    }
    /* TODO else
    {

    }*/
  }

  public static function error404($ajax = null)
  {
    header('HTTP/1.0 404 Not Found');
    header('Status: 404 Not Found');
    if($ajax == 'ajax')
      Api::notFound(getTemplate()->get('error404.php', array('page' => $_SERVER['REDIRECT_URL'])));
    else
      getTemplate()->display('template.php', array('body' => 'error404.php', 'page' => $_SERVER['REDIRECT_URL']));
    die();
  }

  public static function errorGeneral($ajax = false)
  {
    $body = getTemplate()->get('errorGeneral.php', array('page' => $_SERVER['REDIRECT_URL']));
    die();
  }

  public static function home()
  {
    $template = 'splash.php';
    $children = null;
    if(User::isLoggedIn())
    {
      $userId = getSession()->get('userId');
      $template = 'home.php';
      $children = Child::getByUserId($userId);
      // TODO remove this crap
      foreach($children as $key => $value)
      {
        $children[$key]['photos'] = Photo::getByChild($userId, $value['c_id']);
      }
    }
    getTemplate()->display('template.php', array('body' => $template, 'children' => $children));
  }

  public static function join()
  {
    getTemplate()->display('template.php', array('body' => 'join.php'));
  }
 
  public static function joinPost()
  {
    $userId = User::add($_POST['email'], $_POST['password']);
    $redirectUrl = '/join?e=couldNotCreate';
    if($userId)
    {
      $user = User::getById($userId);
      User::startSession($user);
      $redirectUrl = isset($_POST['r']) ? $_POST['r'] : '/';
    }
    getRoute()->redirect($redirectUrl);
  }

  public static function login()
  {
    $r = isset($_GET['r']) ? $_GET['r'] : '/';
    getTemplate()->display('template.php', array('body' => 'login.php', 'r' => quoteEncode($r)));
  }

  public static function loginPost()
  {
    $redirectUrl = '/login?r=' . quoteDecode($_POST['r']);
    $user = User::getByEmailAndPassword($_POST['email'], $_POST['password']);
    if($user)
    {
      User::startSession($user);
      $redirectUrl = quoteDecode($_POST['r']);
    }
    getRoute()->redirect($redirectUrl);
  }

  public static function photoCustom($datePart, $fileName)
  {
    if($datePart && $fileName)
      $photoPath = Photo::generatePhoto($datePart, $fileName);
    if(!isset($photoPath) || empty($photoPath))
      getRoute()->run('/error/404'); 

    header('Content-Type: image/jpeg');
    readfile($photoPath);
  }

  public static function photoSelectAdd($childId, $photoId)
  {
    $userId = getSession()->get('userId');
    $internal = Photo::getById($userId, $photoId);
    if(!$internal)
      Api::error('There was a problem adding your photo. Please try again.');

    Photo::setUse($userId, $photoId, 1);
    $photo = $internal['p_meta'];

    // only queue if we haven't fetched the image already
    if(empty($internal['p_basePath']))
    {
      $args = array('userId' => $userId, 'childId' => $childId, 'entryId' => $photoId, 'photo' => $photo);
      Resque::enqueue('mmh_fetch', 'Fetcher', $args);
    }
    // return the opposite state (if adding return remove)
    $markup = getTemplate()->get('partials/photoSelectItemAction.php', array('action' => 'remove', 'childId' => $childId, 'photoId' => $photoId));
    Api::success($markup);
  }

  public static function photoSelectRemove($childId, $photoId)
  {
    $userId = getSession()->get('userId');
    $internal = Photo::getById($userId, $photoId);
    if(!$internal)
      Api::error('There was a problem removing your photo. Please try again.');

    Photo::setUse($userId, $photoId, 0);
    // return the opposite state (if removing return add)
    $markup = getTemplate()->get('partials/photoSelectItemAction.php', array('action' => 'add', 'childId' => $childId, 'photoId' => $photoId));
    Api::success($markup);
  }

  public static function photosSelectSmugMug($childId)
  {
    self::requireLogin();
    $userId = getSession()->get('userId');
    $credential = Credential::getByService($userId, Credential::serviceSmugMug);
    getSmugMug()->setToken("id={$credential['c_token']}", "Secret={$credential['c_secret']}");
    $albums = SmugMug::getAlbums($childId, $credential['c_token'], $credential['c_secret'], $credential['c_uid']);
    $ids = Photo::extractIds(Photo::getByChild($userId, $childId));
    getTemplate()->display('template.php', array('body' => 'photosSelect.php', 'service' => Credential::serviceSmugMug, 'albums' => $albums,
      'javascript' => getTemplate()->get('javascript/photoSelect.js.php', array('childId' => $childId, 'ids' => $ids))));
  }

  public static function photosSource($childId)
  {
    self::requireLogin();
    getSession()->set('currentChildId', $childId);
    $credentials = Credential::getByUserId(getSession()->get('userId'));
    foreach($credentials as $credential)
    {
      if($credential['c_service'] == Credential::serviceFacebook)
        $fbUrl = "/albums/list/facebook/{$childId}";
      if($credential['c_service'] == Credential::serviceSmugMug)
        $smugUrl = "/albums/list/smugmug/{$childId}";
      if($credential['c_service'] == Credential::servicePhotagious)
        $ptgUrl = "/albums/list/photagious/{$childId}";
    }
    if(!isset($fbUrl))
    {
      $fbUrl = getFacebook()->getAuthorizeUrl(
                  getConfig()->get('urls')->base."/connect/facebook/{$childId}",
                  array('scope' => 'email,offline_access,publish_stream,friends_photos,user_photos')
                );
    }
    if(!isset($smugUrl))
    {
      $smugReqTok = getSmugMug()->auth_getRequestToken();
      getSession()->set('smugReqTok', serialize($smugReqTok));
      $smugUrl = getSmugMug()->authorize('Access=Full', 'Permissions=Read');
    }
    if(!isset($ptgUrl))
    {
      $ptgUrl = getConfig()->get('thirdparty')->ptg_host . '/?action=account.auth.act&callbackurl=' . urlencode(getConfig()->get('urls')->base . "/connect/photagious/{$childId}");
    }
    getTemplate()->display('template.php', array('body' => 'photosSource.php', 'fbUrl' => $fbUrl, 'smugUrl' => $smugUrl, 'ptgUrl' => $ptgUrl));
  }

  public static function photosAdd($childId)
  {
    self::requireLogin();
    $js = getTemplate()->get('javascript/photosAdd.js.php', array('userId' => getSession()->get('userId'), 'childId' => $childId));
    getTemplate()->display('template.php', array('body' => 'photosAdd.php', 'javascript' => $js));
  }

  public static function photosAddPost($childId)
  {
    // swfupload doesn't send proper cookies
    // self::requireLogin();
    $userId = User::postHash($_POST['usrhsh']);
    if(!$userId)
      Api::forbidden('Could not authenticate user');
    
    $destPath = getConfig()->get('paths')->photos.'/original/'.date('Ym');
    $destName = Uploader::safeName($_FILES['photo']['name']);
    move_uploaded_file($_FILES['photo']['tmp_name'], "{$destPath}/{$destName}");
    $args = array('userId' => $userId, 'childId' => $childId, 'photoPath' => "{$destPath}/{$destName}");
    Resque::enqueue('mmh_fetch', 'Uploader', $args);
    error_log(var_export($args, 1));
    Api::success('Photo uploaded successfully', $args);
  }

  public static function proxy($type, $service, $childId, $path)
  {
    $userId = getSession()->get('userId');
    $passThrough = null;
    $offDomain = false;
    switch($service)
    {
      case Credential::serviceFacebook:
        $offDomain = true;
        $credential = Credential::getByService($userId, Credential::serviceFacebook);
        $method = isset($_GET['method']) ? $_GET['method'] : null;
        switch($method)
        {
          default:
            $url = "https://graph.facebook.com/{$path}?access_token={$credential['c_token']}";
            break;
        }
        break;
    }

    if($type == 'r')
    {
      getRoute()->redirect($url, 301, $offDomain);
    }
    elseif($type == 'p')
    {
      if(!empty($passThrough))
      {
        echo $passThrough;
      }
      else
      {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_exec($ch);
      }
    }
  }

  private static function requireLogin()
  {
    if(!getSession()->get('userId'))
    {
      if($_SERVER['REQUEST_METHOD'] == 'GET')
        $url = '/login?r='.urlencode($_SERVER['REDIRECT_URL']);
      else
        $url = '/login';
      getRoute()->redirect($url);
    }
  }
}
