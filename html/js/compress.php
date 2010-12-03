<?php
  header('Content-Type: text/javascript');
  ini_set('include_path', '.');
  require '../../lib/config.php';
  require '../../lib/functions.php';
  require '../../lib/scripts/jsmin.php';
  
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
        if(COMBINE_JS)
          $cache .= JSMin::minify(file_get_contents($fullPath)) . "\n";
        else
          $cache .= file_get_contents($fullPath) . "\n";
      }
    }

    if(COMBINE_JS)
      file_put_contents(PATH_WWW . "/js/static/{$hash}.js", "/* Cache of {$_SERVER['REQUEST_URI']} */\n{$cache}");

    echo $cache;
  }
?>
