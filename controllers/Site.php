<?php
class Site
{
  public static function childNew()
  {
    getTemplate()->display('template.php', array('body' => 'childNew.php'));
  }

  public static function childNewPost()
  {
    $childId = Child::add(getSession()->get('userId'), $_POST['childName'], $_POST['childBirthDate']);
    getRoute()->redirect("/photos/source/{$childId}");
  }

  public static function connectFacebook($childId)
  {
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

  public static function photosSource($childId)
  {
    $fbUrl = getFacebook()->getAuthorizeUrl(
                getConfig()->get('urls')->base."/connect/facebook/{$childId}",
                array('scope' => 'email,offline_access,publish_stream,friends_photos,user_photos')
              );
    getTemplate()->display('template.php', array('body' => 'photosSource.php', 'fbUrl' => $fbUrl));
  }

  public static function photosSelectFacebook($childId)
  {
    $credential = Credential::getByService(getSession()->get('userId'), Credential::serviceFacebook);
    $albums = FacebookPhotos::getAlbums($credential['c_token'], $credential['c_uid']);
    getTemplate()->display('template.php', array('body' => 'photosSelect.php', 'service' => Credential::serviceFacebook, 'albums' => $albums,
      'javascript' => getTemplate()->get('javascript/photoSelect.js.php')));
  }

  public static function proxy($type, $service, $path)
  {
    switch($service)
    {
      case Credential::serviceFacebook:
        $credential = Credential::getByService(getSession()->get('userId'), Credential::serviceFacebook);
        $url = "https://graph.facebook.com/{$path}?access_token={$credential['c_token']}";
        break;
    }

    if($type == 'r')
    {
      getRoute()->redirect($url);
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
