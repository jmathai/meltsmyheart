<?php
class CSite
{
  public static function home()
  {
    getTemplate()->display('template.php', array('body' => 'home.php', 'date' => date('Y-m-d h:i:s')));
  }
}
