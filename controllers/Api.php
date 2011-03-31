<?php
class Api
{
  const statusSuccess = 200;
  const statusForbidden = 403;
  const statusNotFound = 404;
  const statusError = 500;

public static function childNewPost()
{

    $userId = Site::requireUserCredentials($_POST);
    if(!$userId)
      self::forbidden('Sorry, you do not seem to have permissions for this page');

    $date = strtotime(substr($_POST['childBirthDate'], 0, 10)) - 43200;
    $domain = $_POST['childDomain'];
    if(preg_match('/^([a-zA-Z0-9-]+)\..*$/', $domain, $matches))
      $domain = $matches[1];

    if($date === false || empty($_POST['childName']) || empty($domain))
      self::error('Invalid form fields', array('childBirthDate' => $_POST['childBirthDate'], 'childName' => $_POST['childName'], 'childDomain' => $domain));
    elseif(Child::getByDomain($domain))
      self::error('Domain exists', array('domainExists' => true));

    $childId = Child::add($userId, $_POST['childName'], $date, $domain);
    Resque::enqueue('mmh_badge', 'Badger', array('childId' => $childId, 'userId' => $userId, 'badgeId' => Badge::getIdByTag('imnew')));
    self::success('Child added', array('childId' => $childId));
}

  public static function children()
  {
    $userId = Site::requireUserCredentials($_POST);
    if(!$userId)
      self::forbidden('Sorry, you do not seem to have permissions for this page');
    $children = Child::getByUserId($userId);
    foreach($children as $key => $child)
    {
      $photoRow = Photo::getByChild($userId, $child['c_id']);
      $photo = $photoRow[count($photoRow)-1];
      $children[$key]['thumb'] = null;
      if(isset($photo['p_basePath']) && file_exists(getConfig()->get('paths')->photos."/{$photo['p_basePath']}")) {
        $photoUrl = Photo::generateUrl($photo['p_basePath'], 100, 100, array(Photo::square));
        $children[$key]['thumb'] = str_replace(array('{','}'), array('%7B','%7D'), $photoUrl);
      }
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
    elseif(empty($_POST['email']) || empty($_POST['password']) || $_POST['password'] !== $_POST['passwordConfirm'])
    {
      self::forbidden('Invalid parameters', array('email' => $_POST['email'], 'password' => $_POST['password'], 'passwordConfirm' => $_POST['passwordConfirm']));
    }

    $status = User::add($_POST['email'], $_POST['password']);
    if($status)
    {
      $user = User::getByEmailAndPassword($_POST['email'], $_POST['password']);
      if($user)
      {
        $token = User::generateToken($user['u_id'], $_POST['device']);
        if($token)
        {
          $args = array('subject' => 'Welcome to '.getConfig()->get('site')->name, 'email' => $user['u_email'], 'template' => getTemplate()->get('email/join.php', array('email' => $user['u_email'])));
          Resque::enqueue('mmh_email', 'Email', $args);
          self::success('Creation was successful', array('userId' => $user['u_id'], 'userToken' => $token));
        }
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
