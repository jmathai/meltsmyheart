<?php
class Child
{
  const limitFree = 1;
  public static function add($userId, $name, $birthdate, $domain)
  {
    $domain = strtolower($domain);
    if(!preg_match('/^[a-zA-Z0-9-]+$/', $domain))
      return false;
    $params = array(':userId' => $userId, ':name' => $name, ':birthdate' => $birthdate, ':domain' => $domain);
    return getDatabase()->execute('INSERT INTO child(c_u_id, c_name, c_birthdate, c_domain)
      VALUES(:userId, :name, :birthdate, :domain)', $params);
  }

  public static function getByDomain($domain)
  {
    $params = array(':domain' => $domain, ':active' => 1);
    return getDatabase()->one('SELECT * FROM child WHERE c_domain=:domain AND c_isActive=:active', $params);
  }

  public static function getById($userId, $childId)
  {
    $params = array(':userId' => $userId, ':childId' => $childId, ':active' => 1);
    return getDatabase()->one('SELECT * FROM child WHERE c_id=:childId AND c_u_id=:userId AND c_isActive=:active', $params);
  }

  public static function getByUserId($userId)
  {
    $params = array(':userId' => $userId, ':active' => 1);
    return getDatabase()->all('SELECT * FROM child WHERE c_u_id=:userId AND c_isActive=:active', $params);
  }

  public static function getPageUrl($child)
  {
    if(getConfig()->get('site')->mode == 'prod')
      return "http://{$child['c_domain']}.meltsmyheart.com";
    else
      return "/child/{$child['c_domain']}";
  }
}
