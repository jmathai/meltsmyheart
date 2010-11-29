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

function getFacebook()
{
  static $facebook;
  if($facebook)
    return $facebook;

  $config = array('appId' => getConfig()->get('thirdparty')->fb_appId, 'secret' => getConfig()->get('thirdparty')->fb_secret);
  $facebook = new Facebook($config);
  return $facebook;
}
