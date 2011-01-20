<?php
include '../configs/init.php';

// controllers
include getConfig()->get('paths')->controllers . '/Site.php';
include getConfig()->get('paths')->controllers . '/Api.php';

// routes
if(preg_match('/^([a-zA-Z0-9-]+)\.meltsmyhert\.com$/', $_SERVER['HTTP_HOST'], $matches))
{
  getRoute()->load('routes-child.ini');
  getRoute()->run("/{$matches[1]}"); 
}
else
{
  getRoute()->load('routes.ini');
  getRoute()->run(); 
}
