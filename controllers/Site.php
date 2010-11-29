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
      if(empty($token))
        throw new Exception('Could not get Facebook session', 500);
      $credentialId = Credential::add(getSession()->get('userId'), Credential::serviceFacebook, $token);
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

  public static function photosSelect($service, $childId)
  {
    echo "Select photos from {$service} for {$childId}";
  }
}
