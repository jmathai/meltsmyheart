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

  public static function home()
  {
    getTemplate()->display('template.php', array('body' => 'home.php', 'date' => date('Y-m-d h:i:s')));
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

  public static function photosSource($childId)
  {
    self::requireLogin();
    $fbUrl = getFacebook()->getAuthorizeUrl(
                getConfig()->get('urls')->base."/connect/facebook/{$childId}",
                array('scope' => 'email,offline_access,publish_stream,friends_photos,user_photos')
              );
    getTemplate()->display('template.php', array('body' => 'photosSource.php', 'fbUrl' => $fbUrl));
  }

  public static function photosSelectFacebook($childId)
  {
    self::requireLogin();
    $credential = Credential::getByService(getSession()->get('userId'), Credential::serviceFacebook);
    $albums = FacebookPhotos::getAlbums($credential['c_token'], $credential['c_uid']);
    getTemplate()->display('template.php', array('body' => 'photosSelect.php', 'service' => Credential::serviceFacebook, 'albums' => $albums,
      'javascript' => getTemplate()->get('javascript/photoSelect.js.php')));
  }

  public static function proxy($type, $service, $path)
  {
    $offDomain = false;
    switch($service)
    {
      case Credential::serviceFacebook:
        $credential = Credential::getByService(getSession()->get('userId'), Credential::serviceFacebook);
        $url = "https://graph.facebook.com/{$path}?access_token={$credential['c_token']}";
        $offDomain = true;
        break;
    }

    if($type == 'r')
    {
      getRoute()->redirect($url, 301, $offDomain);
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
