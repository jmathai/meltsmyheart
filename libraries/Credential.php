<?php
class Credential
{
  const serviceFacebook = 'facebook';
  const serviceSmugmug = 'smugmug';
  public static function add($userId, $service, $token, $secret=null)
  {
    $params = array(':userId' => $userId, ':service' => $service, ':token' => $token, ':secret' => $secret);
    return getDatabase()->execute('REPLACE INTO credential(c_u_id, c_service, c_token, c_secret)
      VALUES(:userId, :service, :token, :secret)', $params);
  }
}
