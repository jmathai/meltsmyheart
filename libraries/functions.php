<?php
function __autoload($className)
{
  if($className == 'Resque')
  {
    include getConfig()->get('paths')->libraries . '/php-resque/lib/Resque.php';
  }
  elseif(preg_match('/^Swift/', $className))
  {
    $path = str_replace('_', '/', $className);
    include getConfig()->get('paths')->libraries . "/swiftmailer/lib/classes/{$path}.php";
  }
  else
  {
    if(file_exists($filename = getConfig()->get('paths')->libraries . "/{$className}.php"))
      include $filename;
  }
}

function displayAge($born, $taken)
{
  return '3 days';
}

function getFacebook()
{
  static $facebook;
  if($facebook)
    return $facebook;

  $config = array('appId' => getConfig()->get('thirdparty')->fb_appId, 'secret' => getConfig()->get('thirdparty')->fb_secret);
  $facebook = new FacebookGraph($config);
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

function getString($token)
{
  $strings = include getConfig()->get('paths')->libraries . '/strings.php';
  if(isset($strings[$token]))
    return $strings[$token];

  return '';
}

function safe($str)
{
  return strip_tags($str);
}

function quoteEncode($str)
{
  return htmlentities($str);
}

function quoteDecode($str)
{
  return html_entity_decode($str);
}
