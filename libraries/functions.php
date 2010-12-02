<?php
function __autoload($className)
{
  switch($className)
  {
    case 'Facebook':
      include getConfig()->get('paths')->libraries . '/facebook.php';
      break;
    case 'phpSmug':
      include getConfig()->get('paths')->libraries . '/phpSmug.php';
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

function getSmugMug($userToken=null, $userSecret=null)
{
  static $smugMug;
  if($smugMug)
    return $smugMug;

  $config = array('APIKey' => getConfig()->get('thirdparty')->sm_key, 'OAuthSecret' => getConfig()->get('thirdparty')->sm_secret, 'AppName' => getConfig()->get('thirdparty')->sm_name);
  $smugMug = new phpSmug($config);
  if(!empty($userToken) && !empty($userSecret))
    $smugMug->setToken("id={$userToken}", "Secret={$userSecret}");
  return $smugMug;
}

function quoteEncode($str)
{
  return htmlentities($str);
}

function quoteDecode($str)
{
  return html_entity_decode($str);
}
