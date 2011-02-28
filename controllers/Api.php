<?php
class Api
{
  const statusSuccess = 200;
  const statusForbidden = 403;
  const statusNotFound = 404;
  const statusError = 500;

  public static function children()
  {
    $userToken = User::checkToken($_POST['userId'], $_POST['userToken']);
    if(!$userToken)
      self::forbidden('Sorry, you do not seem to have permissions for this page');
    $children = Child::getByUserId($_POST['userId']);
    foreach($children as $key => $child)
    {
      $photoRow = Photo::getByChild($_POST['userId'], $child['c_id']);
      $photoUrl = Photo::generateUrl($photoRow[count($photoRow)-1]['p_basePath'], 100, 100, array(Photo::greyscale,Photo::square));
      $children[$key]['thumb'] = str_replace(array('{','}'), array('%7B','%7D'), $photoUrl);
      //$children[$key]['thumb'] = $photoUrl;
    }
    self::success('Successful request', array('children' => $children));
  }

  public static function loginTokenPost()
  {
    $user = User::getByEmailAndPassword($_POST['email'], $_POST['password']);
    if($user)
    {
      $token = User::generateToken($user['u_id'], $_POST['device']);
      if($token)
        self::success('Login was successful', array('userId' => $user['u_id'], 'userToken' => $token));
    }
    self::error('Could not login', false);
  }

  // response handlers
  public static function error($message, $params = null)
  {
    self::json($message, self::statusError, $params);
  }

  public static function success($message, $params = null)
  {
    self::json($message, self::statusSuccess, $params);
  }

  public static function forbidden($message, $params = null)
  {
    self::json($message, self::statusForbidden, $params);
  }

  public static function notFound($message, $params = null)
  {
    self::json($message, self::statusNotFound, $params);
  }

  private static function json($message, $code, $params = null)
  {
    echo getTemplate()->json(array('status' => $code, 'message' => $message, 'params' => $params));
    if(!isset($_SERVER['argc']) || $_SERVER['argc'] == 0)
      die();
  }

}
