<?php
date_default_timezone_set('America/Los_Angeles');

// framework
include_once '../epi/Epi.php';
Epi::setPath('base', dirname(dirname(__FILE__)).'/epi');
Epi::setPath('config', dirname(dirname(__FILE__)).'/configs');
Epi::setPath('view', dirname(dirname(__FILE__)).'/views');
Epi::init('cache','config','database','route','session','template');

// configs
getConfig()->load('prod.ini');
if(getenv('CONF'))
  getConfig()->load(getenv('CONF'));

// controllers
include_once getConfig()->get('paths')->controllers . '/CSite.php';
include_once getConfig()->get('paths')->controllers . '/CApi.php';
include_once getConfig()->get('paths')->libraries . '/facebook.php';

// routes
getRoute()->load('routes.ini');
getRoute()->run(); 
