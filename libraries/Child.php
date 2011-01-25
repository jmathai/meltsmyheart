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
    $child = getDatabase()->one('SELECT * FROM child WHERE c_domain=:domain AND c_isActive=:active', $params);
    if(!empty($child['c_pageSettings']))
      $child['c_pageSettings'] = json_decode($child['c_pageSettings'], 1);
    return $child;
  }

  public static function getById($userId, $childId)
  {
    $params = array(':userId' => $userId, ':childId' => $childId, ':active' => 1);
    $child = getDatabase()->one('SELECT * FROM child WHERE c_id=:childId AND c_u_id=:userId AND c_isActive=:active', $params);
    if(!empty($child['c_pageSettings']))
      $child['c_pageSettings'] = json_decode($child['c_pageSettings'], 1);
    return $child;
  }

  public static function getByUserId($userId)
  {
    $params = array(':userId' => $userId, ':active' => 1);
    $children = getDatabase()->all('SELECT * FROM child WHERE c_u_id=:userId AND c_isActive=:active', $params);
    foreach($children as $key => $child)
    {
      if(!empty($child['c_pageSettings']))
        $children[$key]['c_pageSettings'] = json_decode($child['c_pageSettings'], 1);
    }
    return $children;
  }

  public static function getPageUrl($child)
  {
    if(getConfig()->get('site')->mode == 'prod')
      return "http://{$child['c_domain']}.meltsmyheart.com";
    else
      return "/child/{$child['c_domain']}";
  }

  public static function getTheme($child)
  {
    $theme = array('css' => array('plugins/jquery.lightbox-0.5.css','shared.css','theme-default.css'));
    if(isset($child['c_pageSettings']['theme']['css']))
    {
      foreach($child['c_pageSettings']['theme']['css'] as $css)
        $theme['css'][] = $css;
    }
    return $theme;
  }

  public static function updateSettings($userId, $childId, $settings)
  {
    $params = array(':userId' => $userId, ':childId' => $childId, ':settings' => json_encode($settings));
    return getDatabase()->execute('UPDATE child SET c_pageSettings=:settings WHERE c_id=:childId AND c_u_id=:userId', $params);
  }
}
