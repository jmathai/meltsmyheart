<?php
try
{
  include '../configs/init.php';

  // controllers
  include getConfig()->get('paths')->controllers . '/Affiliate.php';
  include getConfig()->get('paths')->controllers . '/Api.php';
  include getConfig()->get('paths')->controllers . '/Simple.php';
  include getConfig()->get('paths')->controllers . '/Site.php';

  // routes
  getRoute()->load('routes.ini');
  if(preg_match('/^([a-zA-Z0-9-]+)\.meltsmyheart\.com$/', $_SERVER['HTTP_HOST'], $matches))
  {
    if(!isset($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI'] == '/')
      getRoute()->run("/child/{$matches[1]}"); 
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
