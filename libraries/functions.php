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
    elseif(file_exists($filename = getConfig()->get('paths')->libraries . "/queue/{$className}.php"))
      include $filename;
  }
}

function displayAge($born, $taken)
{
  $seconds = intval($taken - $born);
  $hours = intval($seconds / 3600);
  if($hours < 0)
    return '--';
  elseif($hours < 1)
    return 'a few minutes';
  elseif($hours < 24)
    return "{$hours} hour" . plural($hours);

  $days = intval($seconds / 86400);
  if($days <= 7)
    return "{$days} day" . plural($days);

  $weeks = intval($days / 7);
  if($weeks <= 4)
    return "{$weeks} week" . plural($weeks);

  $months = intval($days / 30);
  if($months < 12)
    return "{$months} month" . plural($months);

  $years = intval($days / 365);
  return "{$years} year" . plural($years);
}

function emailLink()
{
  return '<a href="mailto:' . getConfig()->get('email')->from_email . '">' . getConfig()->get('email')->from_email . '</a>';
}

function getAsset($type, $files)
{
  $uri    = sprintf('/%s/compress-%s.%s?files=%s', $type, getConfig()->get('assets')->$type, $type, implode(',', $files));
  if(getConfig()->get('assets')->minify)
  {
    $hash   = md5($uri);
    $relativePath = "/{$type}/static/{$hash}.{$type}";
    if(file_exists(getConfig()->get('paths')->docroot . $relativePath))
    {
      if(getConfig()->get('site')->mode == 'prod')
        $uri = getConfig()->get('urls')->cdn.$relativePath;
      else
        $uri = $relativePath;
    }
  }

  return $uri;
}

function getAssetCssMember()
{
  return array('styles.css','shared.css');
}

function getAssetCssVisitor()
{

}

function getAssetJsMember()
{
  return array('plugins/swfupload.js','plugins/swfupload.queue.js','plugins/jquery.cross-slide.min.js','plugins/jquery.lightbox-0.5.min.js','plugins/jquery.tools.min.js','javascript.js','internal.js','page.js');
}

function getAssetJsVisitor()
{
  return array('plugins/jquery.tools.min.js','plugins/jquery.lightbox-0.5.min.js','javascript.js','page.js');
}

function getAssetImage($path)
{
  $fullPath = getConfig()->get('paths')->docroot . $path;
  $md5 = sha1_file($fullPath);
  preg_match('/\.([a-zA-Z]{3,4})$/', $path, $matches);
  $ext = $matches[1];
  $dest = "/img/static/{$md5}.{$ext}";
  $fullDest = getConfig()->get('paths')->docroot . $dest;
  if(!file_exists($fullDest))
    copy($fullPath, $fullDest);
  return $dest;
}

function getFacebook()
{
  static $facebook;
  if($facebook)
    return $facebook;

  $config = array('appId' => getSecret('fb_appId'), 'secret' => getSecret('fb_secret'));
  $facebook = new FacebookGraph($config);
  return $facebook;
}

function getFacebookPhoto($token)
{
  $ch = curl_init("https://graph.facebook.com/me/picture?access_token={$token}");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPGET, 1);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $result = curl_exec($ch);
  $headers = explode("\r\n", $result);
  foreach($headers as $header)
  {
    if(preg_match('/^Location: +(.*)?/', $header, $matches))
      return $matches[1];
  }
  return '/img/facebook-profile-pic.jpg';
}

function getQuote()
{
  $quotes = getString('quotes');
  $quote = $quotes[array_rand($quotes)];
  return "{$quote['quote']} - <em>{$quote['by']}</em>";
}

function getSecret($name)
{
  static $secrets = array();
  if(isset($secrets[$name]))
    return $secrets[$name];

  $filename = getConfig()->get('paths')->secret."/{$name}";
  if(file_exists($filename))
    $secrets[$name] = trim(file_get_contents($filename));
  else
    $secrets[$name] = null;

  return $secrets[$name];
}

function getSmugMug($userToken=null, $userSecret=null)
{
  static $smugMug;
  if($smugMug)
    return $smugMug;

  $config = array('APIKey' => getSecret('sm_key'), 'OAuthSecret' => getSecret('sm_secret'), 'AppName' => getConfig()->get('thirdparty')->sm_name);
  $smugMug = new phpSmug($config);
  if(!empty($userToken) && !empty($userSecret))
    $smugMug->setToken("id={$userToken}", "Secret={$userSecret}");
  return $smugMug;
}

function getString($token, $params = array())
{ // do not make $strings static because of extract
  extract($params);
  $strings = include getConfig()->get('paths')->libraries . '/strings.php';
  if(isset($strings[$token]))
    return $strings[$token];

  return '';
}

function isMobile()
{
  static $isMobile = null;
  if($isMobile !== null)
    return $isMobile;

  $md = new Mobile_Detect();
  $isMobile = $md->isMobile() || getConfig()->get('site')->force_mobile == 1;
  return $isMobile;
}

function normalizeRoute($route)
{
  if($route == '/child/new')
    return $route;
  return preg_replace(array('#(/child)/[a-zA-Z0-9-]+#', '#/[^/]?\d+([^/])?#'), '\\1', $route);
}

function plural($int)
{
  return $int > 1 ? 's' : '';
}

function posessive($word)
{
  if($word[strlen($word)-1] == 's')
    return quoteEncode("{$word}'");
  else
    return quoteEncode("{$word}'s");
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

function validCacheInclude($parent, $child, $ext)
{
  $extLen = -1 * (int)strlen($ext);
  return substr($child, $extLen) == $ext;
}
