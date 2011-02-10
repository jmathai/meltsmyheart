<?php
header('Content-Type: text/javascript');
ini_set('include_path', '.');
require '../../configs/init.php';
require getConfig()->get('paths')->scripts . '/jsmin.php';

if(!empty($_GET['__args__']))
{
  $hash = md5($_SERVER['REQUEST_URI']);

  $files = (array)explode(',',$_GET['files']);
  $baseDir = dirname(__FILE__);
  $cache = '';
  foreach($files as $file)
  {
    $fullPath = $baseDir . '/' . $file;
    if(file_exists($fullPath) && validCacheInclude(__FILE__, $fullPath, '.js'))
    {
      if(getConfig()->get('assets')->minify && !isMobile())
        $cache .= JSMin::minify(file_get_contents($fullPath)) . "\n";
      else
        $cache .= file_get_contents($fullPath) . "\n";
    }
  }

  if(getConfig()->get('assets')->minify)
    file_put_contents(getConfig()->get('paths')->docroot . "/js/static/{$hash}.js", "/* Cache of {$_SERVER['REQUEST_URI']} */\n{$cache}");

  echo $cache;
}
