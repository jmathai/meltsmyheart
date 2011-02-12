<?php
ob_start();
header('Content-Type: text/css');
ini_set('include_path', '.');
require '../../configs/init.php';
require getConfig()->get('paths')->scripts . '/cssmin.php';

if(!empty($_GET['__args__']))
{
  $hash = md5($_SERVER['REQUEST_URI']);

  $files = (array)explode(',',$_GET['files']);
  $baseDir = dirname(__FILE__);
  $cache = '';
  foreach($files as $file)
  {
    $fullPath = $baseDir . '/' . $file;
    if(file_exists($fullPath) && validCacheInclude(__FILE__, $fullPath, '.css'))
    {
      include $fullPath;
      $css = ob_get_contents();
      ob_flush();
      if(getConfig()->get('assets')->minify)
      {
        $cache .= "{$file}\n" . CssMin::minify($css, array(
                      'remove-empty-blocks'           => true,
                      'remove-empty-rulesets'         => true,
                      'remove-last-semicolons'        => true,
                      'convert-css3-properties'       => true,
                      'convert-font-weight-values'    => true, // new in v2.0.2
                      'convert-named-color-values'    => true, // new in v2.0.2
                      'convert-hsl-color-values'      => true, // new in v2.0.2
                      'convert-rgb-color-values'      => true, // new in v2.0.2; was "convert-color-values" in v2.0.1
                      'compress-color-values'         => true,
                      'compress-unit-values'          => true,
                      'emulate-css3-variables'        => true)
                    ) . "\n";
      }
      else
      {
        $cache .= $css . "\n";
      }
    }
  }

  if(getConfig()->get('assets')->static)
    file_put_contents(getConfig()->get('paths')->docroot. "/css/static/{$hash}.css", "/* Cache of {$_SERVER['REQUEST_URI']} */\n{$cache}");

  echo $cache;
}
ob_end_flush();
