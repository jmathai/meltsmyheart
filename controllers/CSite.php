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
    var_dump($_GET);
  }

  public static function photosSource()
  {
    $fbUrl = getFacebook()->getAuthorizeUrl(
                getConfig()->get('urls')->base.'/connect/facebook',
                array('scope' => 'email,offline_access,publish_stream,friends_photos,user_photos')
              );
    getTemplate()->display('template.php', array('body' => 'photosSource.php', 'fbUrl' => $fbUrl));
  }

  public static function home()
  {
    getTemplate()->display('template.php', array('body' => 'home.php', 'date' => date('Y-m-d h:i:s')));
  }
}
