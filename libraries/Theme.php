<?php
class Theme
{
  public static function getByName($name) {
    $params = array(':name' => $name, ':active' => 1);
    $theme = getDatabase()->one('SELECT * FROM theme WHERE t_name=:name AND t_isActive=:active', $params);
    if(isset($theme['t_settings']))
      $theme['t_settings'] = json_decode($theme['t_settings'], 1);

    return $theme;
  }

  public static function getThemeCss($theme)
  {

  }
}
