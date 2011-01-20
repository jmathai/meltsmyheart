<?php
include '../configs/init.php';

// controllers
include getConfig()->get('paths')->controllers . '/Site.php';
include getConfig()->get('paths')->controllers . '/Api.php';

// routes
getRoute()->load('routes.ini');
if(preg_match('/^([a-zA-Z0-9-]+)\.meltsmyheart\.com$/', $_SERVER['HTTP_HOST'], $matches))
  getRoute()->run("/child/{$matches[1]}"); 
else
  getRoute()->run(); 
