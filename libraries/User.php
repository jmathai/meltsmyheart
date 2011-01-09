<?php
class User
{
  const accountTypeFree = 'free';
  const accountTypePaid = 'paid';
  const hashAlgorithm = 'sha512';
  public static function add($email, $password, $type=self::accountTypeFree)
  {
    $params = array(':email' => $email, ':password' => self::generatePasswordHash($password, $email),
                    ':accountType' => $type, ':key' => md5(str_repeat($email, 2)), ':dateCreated' => time());
    return getDatabase()->execute('INSERT INTO `user`(u_key, u_email, u_password, u_accountType, u_dateCreated)
      VALUES(:key, :email, :password, :accountType, :dateCreated)', $params);
  }

  public static function password($userId, $email, $password)
  {
    $params = array(':userId' => $userId, ':password' => self::generatePasswordHash($password, $email));
    return getDatabase()->execute('UPDATE `user` SET u_password=:password WHERE u_id=:userId', $params);
  }

  public static function getById($userId)
  {
    $retval = getDatabase()->one('SELECT * FROM user WHERE u_id=:userId', array(':userId' => $userId));
    if(!$retval)
      return false;
    $retval['u_prefs'] = json_decode($retval['u_prefs'], true);
    return $retval;
  }

  public static function getByEmailAndPassword($email, $password)
  {
    if($password === false)
      $retval = getDatabase()->one('SELECT * FROM user WHERE u_email=:email', array(':email' => $email));
    else
      $retval = getDatabase()->one('SELECT * FROM user WHERE u_email=:email AND u_password=:password', array(':email' => $email, ':password' => self::generatePasswordHash($password, $email)));

    if(!$retval)
      return false;

    $retval['u_prefs'] = json_decode($retval['u_prefs'], true);
    return $retval;
  }

  public static function isLoggedIn()
  {
    $userId = getSession()->get('userId');
    return !empty($userId);
  }

  // cookieless authentication (i.e. photo uploads)
  public static function postHash($check = null)
  {
    $userId = getSession()->get('userId');
    $hash = md5('usrhsh'.$_SERVER['REMOTE_ADDR']);
    if($check === null && $userId)
      return "{$userId},{$hash}";
    
    $parts = explode(',', $check);
    if(count($parts) == 2 && $parts[1] == $hash)
      return $parts[0];
    else
      return false;
  }

  public static function startSession($user)
  {
    if(!array_key_exists('u_id', $user) || !array_key_exists('u_prefs', $user) || empty($user['u_id']))
      return;

    getSession()->set('userId', $user['u_id']);
    getSession()->set('prefs', $user['u_prefs']);
  }

  public static function upgrade($userId)
  {
    return getDatabase()->execute('UPDATE `user` SET u_accountType=:type WHERE u_id=:userId', array(':type' => self::accountTypePaid, ':userId' => $userId));
  }

  private static function generatePasswordHash($password, $email)
  {
    return self::hash($password, md5($email));
  }

  private static function hash($str, $salt)
  {
    return hash(self::hashAlgorithm, "{$str}-{$salt}");
  }
}
