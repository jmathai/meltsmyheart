<?php
class Site
{
  public static function childNew()
  {
    self::requireLogin();
    getTemplate()->display('template.php', array('body' => 'childNew.php'));
  }

  public static function childNewPost()
  {
    self::requireLogin();
    $childId = Child::add(getSession()->get('userId'), $_POST['childName'], $_POST['childBirthDate']);
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
      getRoute()->redirect("/photos/select/facebook/{$childId}");
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
      getRoute()->redirect("/photos/select/smugmug/{$childId}");
    }
    /* TODO else
    {

    }*/
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
        $children[$key]['photos'] = Entry::getByChild($userId, $value['c_id']);
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

  public static function photoSelectAdd($childId, $internalPhotoId)
  {
    $userId = getSession()->get('userId');
    $internal = Photo::getById($userId, $internalPhotoId);
    if(stristr($internal['p_key'], Credential::serviceSmugMug))
    {
      $photo = new Photo($internal['p_meta']['id'], $internal['p_id'], $internal['p_meta']['ThumbURL'], 
        $internal['p_meta']['OriginalURL'], strtotime($internal['p_meta']['Date']), null, $internal['p_meta']['Caption']);
    }
    elseif(stristr($internal['p_key'], Credential::serviceFacebook))
    {
      $photo = new Photo($internal['p_meta']['id'], $internal['p_id'], $internal['p_meta']['picture'], 
        $internal['p_meta']['source'], strtotime($internal['p_meta']['created_time']), null, $internal['p_meta']['name']);
    }

    if($photo)
    {
      $entryId = Entry::add($userId, $childId, null, null, null);
      $args = array('userId' => $userId, 'childId' => $childId, 'entryId' => $entryId, 'photo' => $photo);
      Resque::enqueue('mmh_fetch', 'Fetcher', $args);
      echo json_encode("booyah");
    }
  }

  public static function photosSelectFacebook($childId)
  {
    self::requireLogin();
    $credential = Credential::getByService(getSession()->get('userId'), Credential::serviceFacebook);
    $albums = Facebook::getAlbums($credential['c_token'], $credential['c_uid']);
    getTemplate()->display('template.php', array('body' => 'photosSelect.php', 'service' => Credential::serviceFacebook, 'albums' => $albums,
      'javascript' => getTemplate()->get('javascript/photoSelect.js.php', array('childId' => $childId))));
  }

  public static function photosSelectSmugMug($childId)
  {
    self::requireLogin();
    $credential = Credential::getByService(getSession()->get('userId'), Credential::serviceSmugMug);
    getSmugMug()->setToken("id={$credential['c_token']}", "Secret={$credential['c_secret']}");
    $albums = SmugMug::getAlbums($credential['c_token'], $credential['c_secret'], $credential['c_uid']);
    getTemplate()->display('template.php', array('body' => 'photosSelect.php', 'service' => Credential::serviceSmugMug, 'albums' => $albums,
      'javascript' => getTemplate()->get('javascript/photoSelect.js.php', array('childId' => $childId))));
  }

  public static function photosSource($childId)
  {
    self::requireLogin();
    getSession()->set('currentChildId', $childId);
    $credentials = Credential::getByUserId(getSession()->get('userId'));
    foreach($credentials as $credential)
    {
      if($credential['c_service'] == Credential::serviceFacebook)
        $fbUrl = "/photos/select/facebook/{$childId}";
      if($credential['c_service'] == Credential::serviceSmugMug)
        $smugUrl = "/photos/select/smugmug/{$childId}";
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
    getTemplate()->display('template.php', array('body' => 'photosSource.php', 'fbUrl' => $fbUrl, 'smugUrl' => $smugUrl));
  }

  public static function proxy($type, $service, $path)
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
          case 'photos':
            $passThrough = getTemplate()->json(Facebook::getPhotos($userId, $credential['c_token'], $_GET['id']));
            break;
          default:
            $url = "https://graph.facebook.com/{$path}?access_token={$credential['c_token']}";
            break;
        }
        break;
      case Credential::serviceSmugMug:
        $credential = Credential::getByService($userId, Credential::serviceSmugMug);
        switch($_GET['method'])
        {
          case 'photos':
            $passThrough = getTemplate()->json(SmugMug::getPhotos($userId, $credential['c_token'], $credential['c_secret'], $_GET['AlbumID'], $_GET['AlbumKey']));
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
