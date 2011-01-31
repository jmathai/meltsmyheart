<?php
class Affiliate
{
  const view = 'view';
  const signup = 'signup';
  const upgrade = 'upgrade';
  const cookie = 'af';
  public static function add($userId)
  {
    $params = array(':userId' => $userId, ':key' => uniqid(), ':dateCreated' => time());
    return getDatabase()->execute('INSERT INTO `affiliate`(a_u_id, a_key, a_dateCreated)
      VALUES(:userId, :key, :dateCreated)', $params);
  }

  public static function getByUserId($userId)
  {
    return getDatabase()->one('SELECT * FROM affiliate WHERE a_u_id=:userId', array(':userId' => $userId));
  }

  public static function getByKey($key)
  {
    return getDatabase()->one('SELECT * FROM affiliate WHERE a_key=:key', array(':key' => $key));
  }

  public static function getStat($affiliateId, $userToken)
  {
    return getDatabase()->one('SELECT * FROM affiliate_stat WHERE as_a_id=:affiliateId AND as_userToken=:userToken', 
      array(':affiliateId' => $affiliateId, ':userToken' => $userToken));
  }

  public static function logUser($action, $userToken, $affiliateId)
  {
    $affiliateStat = self::getStat($affiliateId, $userToken);
    $params = array(':action' => $action, ':affiliateId' => $affiliateId, ':userToken' => $userToken);
    if($affiliateStat)
    {
      return getDatabase()->execute("UPDATE affiliate_stat SET as_actions=CONCAT_WS(',',as_actions,:action) 
        WHERE as_a_id=:affiliateId AND as_userToken=:userToken", $params);
    }
    else
    {
      $params[':dateCreated'] = time();
      return getDatabase()->execute('INSERT INTO affiliate_stat(as_a_id, as_userToken, as_actions, as_dateCreated)
        VALUES(:affiliateId, :userToken, :action, :dateCreated)', $params);
    }
  }

  public static function parseCookie()
  {
    if(!isset($_COOKIE[self::cookie]))
      return false;

    $parts = (array)explode('|', $_COOKIE[self::cookie]);
    if(count($parts) != 3)
      return false;

    return array('userToken' => $parts[0], 'affiliateKey' => $parts[1], 'affiliateId' => $parts[2]);
  }

  public static function setCookie($userToken, $affiliateKey, $affiliateId)
  {
    setcookie(AFfiliate::cookie, "{$userToken}|{$affiliateKey}|{$affiliateId}", strtotime('+1 year'), '/');
  }
}
