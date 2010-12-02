<?php
class User
{
  const accountTypeFree = 'free';
  const accountTypePaid = 'paid';
  const hashAlgorithm = 'sha512';
  public static function add($email, $password, $type=self::accountTypeFree)
  {
    $params = array(':email' => $email, ':password' => self::generatePasswordHash($password, $email),
                    ':accountType' => $type, ':key' => md5($email), ':dateCreated' => time());
    return getDatabase()->execute('INSERT INTO `user`(u_key, u_email, u_password, u_accountType, u_dateCreated)
      VALUES(:key, :email, :password, :accountType, :dateCreated)', $params);
  }

  public static function getById($userId)
  {
    $retval = getDatabase()->one('SELECT * FROM user WHERE u_id=:userId', array(':userId' => $userId));
    $retval['u_prefs'] = json_decode($retval['u_prefs'], true);
    return $retval;
  }

  public static function getByEmailAndPassword($email, $password)
  {
    $retval = getDatabase()->one('SELECT * FROM user WHERE u_email=:email AND u_password=:password', array(':email' => $email, ':password' => self::generatePasswordHash($password, $email)));
    $retval['u_prefs'] = json_decode($retval['u_prefs'], true);
    return $retval;
  }

  public static function isLoggedIn()
  {
    $userId = getSession()->get('userId');
    return !empty($userId);
  }

  public static function startSession($user)
  {
    if(!array_key_exists('u_id', $user) || !array_key_exists('u_prefs', $user) || empty($user['u_id']))
      return;

    getSession()->set('userId', $user['u_id']);
    getSession()->set('prefs', $user['u_prefs']);
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
