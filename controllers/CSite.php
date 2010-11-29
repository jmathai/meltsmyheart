<?php
class CSite
{
  public static function childNew()
  {
    getTemplate()->display('template.php', array('body' => 'childNew.php'));
  }

  public static function childNewPost()
  {
    // maybe add to db or something
    $name = urlencode($_POST['childName']);
    $bday = urlencode($_POST['childBirthDate']);
    getRoute()->redirect("/photos/source?name={$name}&bday={$bday}");
  }

  public static function connectFacebook()
  {
    if(isset($_GET['error']))
    {
      echo 'some error response';
    }
    elseif(isset($_GET['code']))
    {
      $redirectUrl = getConfig()->get('urls')->base.'/connect/facebook';
      $resp = getFacebook()->fetchAccessToken($_GET['code'], $redirectUrl);
      $access_token = $resp['access_token'];
      var_dump($_GET);
      if(empty($access_token))
        throw new Exception('Could not get Facebook session', 500);
      echo $access_token;
    }
  }

  public static function home()
  {
    getTemplate()->display('template.php', array('body' => 'home.php', 'date' => date('Y-m-d h:i:s')));
  }

  public static function photosSource()
  {
    $fbUrl = getFacebook()->getAuthorizeUrl(
                getConfig()->get('urls')->base.'/connect/facebook',
                array('scope' => 'email,offline_access,publish_stream,friends_photos,user_photos')
              );
    getTemplate()->display('template.php', array('body' => 'photosSource.php', 'fbUrl' => $fbUrl));
  }

  public static function join()
  {
    getTemplate()->display('template.php', array('body' => 'join.php'));
  }

  public static function joinPost()
  {
    $userId = CUser::add($_POST['email'], $_POST['password']);
echo $userId;
    //$user = CUser::getById($userId);
    //CUser::startSession($user);
  }
}
