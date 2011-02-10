<?php
class Mobile
{
  public static function camera($childId)
  {
    Site::requireLogin();
    getTemplate()->display('mobile/template.php', array('body' => 'camera.php', 'title' => 'Take a Picture'));
  }

  public static function childConfirmDelete($childId)
  {
    getTemplate()->display('mobile/template.php', array('body' => 'childConfirmDelete.php', 'childId' => $childId, 'title' => 'Remove child', 'noHeaderButtons' => true));
  }

  public static function childNew()
  {
    getTemplate()->display('mobile/template.php', array('body' => '../childNew.php', 'title' => 'New Child', 'r' => '/camera'));
  }

  public static function error404()
  {
    getTemplate()->display('mobile/template.php', array('body' => 'error404.php', 'title' => 'Opps'));
  }

  public static function forgot()
  {
    $r = isset($_GET['r']) ? quoteEncode($_GET['r']) : '/child/new';
    getTemplate()->display('mobile/template.php', array('body' => '../forgot.php', 'title' => 'Forgot Password', 'r' => $r, 'confirm' => null));
  }

  public static function home()
  {
    if(User::isLoggedIn())
    {
      $userId = getSession()->get('userId');
      $children = Child::getByUserId($userId);
      foreach($children as $key => $child)
      {
        $photos = Photo::getByChild($userId, $child['c_id']);
        if(count($photos) > 0)
          $children[$key]['thumbnail'] = Photo::generateUrl($photos[0]['p_basePath'], 100, 100, array(Photo::square));
        else
          $children[$key]['thumbnail'] = '/img/logo-heart-100.png'; // TODO - why this? replace.
      }
      $params = array('body' => 'home.php', 'title' => 'Welcome', 'children' => $children);
    }
    else
    {
      $params = array('body' => '../login.php', 'title' => 'Login', 'r' => '/');
    }
    getTemplate()->display('mobile/template.php', $params);
  }

  public static function join()
  {
    $r = isset($_GET['r']) ? quoteEncode($_GET['r']) : '/child/new';
    getTemplate()->display('mobile/template.php', array('body' => '../join.php', 'title' => 'Join', 'r' => $r, 'context' => null));
  }
}
