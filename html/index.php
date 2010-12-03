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
$dbConfig = getConfig()->get('db');
EpiDatabase::employ($dbConfig->type, $dbConfig->name, $dbConfig->host, $dbConfig->user, $dbConfig->pass);
EpiSession::employ(EpiSession::MEMCACHED);
EpiCache::employ(EpiCache::MEMCACHED);

// controllers
include getConfig()->get('paths')->controllers . '/Site.php';
include getConfig()->get('paths')->controllers . '/Api.php';
include getConfig()->get('paths')->libraries . '/functions.php';

// routes
getRoute()->load('routes.ini');
getRoute()->run(); 
