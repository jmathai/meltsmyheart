<?php
try
{
  include '../configs/init.php';

  getRoute()->load('shared-routes.ini');
  include getConfig()->get('paths')->controllers . '/Site.php';

  $isMobile = isMobile();
  if($isMobile)
  {
    include getConfig()->get('paths')->controllers . '/Mobile.php';
    getRoute()->load('mobile-routes.ini');
  }
  else
  {
    include getConfig()->get('paths')->controllers . '/Api.php';
    include getConfig()->get('paths')->controllers . '/Partner.php';
    include getConfig()->get('paths')->controllers . '/Simple.php';
    getRoute()->load('routes.ini');
  }

  if(preg_match('/^([a-zA-Z0-9-]+)\.meltsmyheart\.com$/', $_SERVER['HTTP_HOST'], $matches))
  {
    if(!isset($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI'] == '/')
      getRoute()->run("/child/page/{$matches[1]}"); 
    else
      getRoute()->run($_SERVER['REQUEST_URI']);
  }
  else
  {
    getRoute()->run(); 
  }
}
catch(Exception $e)
{
  getLogger()->crit('Uncaught exception in '.__FILE__.':'.__LINE__, $e);
  getRoute()->run('/error/general');
}
