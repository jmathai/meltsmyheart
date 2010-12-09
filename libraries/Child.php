<?php
class Child
{
  public static function add($userId, $name, $birthdate, $domain)
  {
    $params = array(':userId' => $userId, ':name' => $name, ':birthdate' => $birthdate, ':domain' => $domain);
    return getDatabase()->execute('INSERT INTO child(c_u_id, c_name, c_birthdate, c_domain)
      VALUES(:userId, :name, :birthdate, :domain)', $params);
  }

  public static function getByDomain($domain)
  {
    $params = array(':domain' => $domain);
    return getDatabase()->one('SELECT * FROM child WHERE c_domain=:domain', $params);
  }

  public static function getByUserId($userId)
  {
    $params = array(':userId' => $userId);
    return getDatabase()->all('SELECT * FROM child WHERE c_u_id=:userId', $params);
  }
}
