<?php
function __autoload($className)
{
  switch($className)
  {
    case 'Facebook':
      include getConfig()->get('paths')->libraries . '/facebook.php';
      break;
    default:
      if(file_exists($filename = getConfig()->get('paths')->libraries . "/{$className}.php"))
      {
        include $filename;
      }
      break;
  
  }
}
