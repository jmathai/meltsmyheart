<?php
class Credential
{
  const serviceFacebook = 'facebook';
  const servicePhotagious = 'photagious';
  const serviceSmugMug = 'smugmug';
  public static function add($userId, $service, $token, $secret=null, $uid=null)
  {
    $params = array(':userId' => $userId, ':service' => $service, ':token' => $token, ':secret' => $secret, ':uid' => $uid);
    return getDatabase()->execute('REPLACE INTO credential(c_u_id, c_service, c_token, c_secret, c_uid)
      VALUES(:userId, :service, :token, :secret, :uid)', $params);
  }

  public static function getByService($userId, $service)
  {
    return getDatabase()->one('SELECT * FROM credential WHERE c_u_id=:userId AND c_service=:service',
      array(':userId' => $userId, ':service' => $service));
  }

  public static function getByUserId($userId)
  {
    return getDatabase()->all('SELECT * FROM credential WHERE c_u_id=:userId',
      array(':userId' => $userId));

  }
}
