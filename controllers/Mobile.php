<?php
class Mobile
{
  public static function home()
  {
    getTemplate()->display('mobile-template.php', array('title' => 'foo'));
  }
}
