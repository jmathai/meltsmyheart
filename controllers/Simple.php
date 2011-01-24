<?php
class Simple
{
  public static function about()
  {
    self::generic('about');
  }

  public static function help()
  {
    self::generic('help');
  }

  public static function privacy()
  {
    self::generic('privacy');
  }

  public static function terms()
  {
    self::generic('terms');
  }

  private static function generic($body, $params = array())
  {
    getTemplate()->display('template.php', array_merge(array('body' => "{$body}.php"), $params));
  }
}
