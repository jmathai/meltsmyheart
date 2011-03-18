<?php
class Api
{
  const statusSuccess = 200;
  const statusForbidden = 403;
  const statusNotFound = 404;
  const statusError = 500;

  public static function children()
  {
    $userId = Site::requireUserCredentials($_POST);
    if(!$userId)
      self::forbidden('Sorry, you do not seem to have permissions for this page');
    $children = Child::getByUserId($userId);
    foreach($children as $key => $child)
    {
      $photoRow = Photo::getByChild($userId, $child['c_id']);
      $photoUrl = Photo::generateUrl($photoRow[count($photoRow)-1]['p_basePath'], 100, 100, array(Photo::square));
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

  public static function mobileInitPost()
  {
    $userId = Site::requireUserCredentials($_POST);
    if(!$userId)
      self::forbidden('Sorry, you do not seem to have permissions for this page');

    $recipients = Recipient::getByUserId($userId);
    $children = Child::getByUserId($userId);
    self::success('Init success', array('recipientCount' => count($recipients), 'childrenCount' => count($children)));
  }

  public static function recipientModifyPost()
  {
    $userId = Site::requireUserCredentials($_POST);
    if(!$userId)
      self::forbidden('Sorry, you do not seem to have permissions for this page');

    $recipient = Recipient::getByEmail($userId, $_POST['email']);
    if($recipient)
    {
      Recipient::delete($userId, $recipient['r_id']);
      $action = 'removed';
    } else {
      Recipient::add($userId, $_POST['name'], $_POST['email'], $_POST['mobile']);
      $action = 'added';
    }
    self::success("Recipient {$action}", array('action' => $action, 'rowIndex' => intval($_POST['rowIndex']), 'name' => $_POST['name'], 'email' => $_POST['email'], 'mobile' => $_POST['mobile']));
  }

  public static function recipients()
  {
    $userId = Site::requireUserCredentials($_POST);
    if(!$userId)
      self::forbidden('Sorry, you do not seem to have permissions for this page');

    $recipients = Recipient::getByUserId($userId);
    self::success('Recipients', array('recipients' => $recipients));
  }

  public static function userCreate()
  {
    $user = User::getByEmailAndPassword($_POST['email'], false);
    if($user)
    {
      self::forbidden('Email exists');
    }

    // TODO validate password and password confirm match
    $status = User::add($_POST['email'], $_POST['password']);
    if($status)
    {
      $user = User::getByEmailAndPassword($_POST['email'], $_POST['password']);
      if($user)
      {
        $token = User::generateToken($user['u_id'], $_POST['device']);
        if($token)
          self::success('Creation was successful', array('userId' => $user['u_id'], 'userToken' => $token));
      }
    }
    self::error('Could not create account');
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
