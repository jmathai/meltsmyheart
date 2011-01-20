<?php
date_default_timezone_set('America/Los_Angeles');

// framework
$epiPath = dirname(dirname(__FILE__)).'/epi';
include "{$epiPath}/Epi.php";
Epi::setPath('base', $epiPath);
Epi::setPath('config', dirname(dirname(__FILE__)).'/configs');
Epi::setPath('view', dirname(dirname(__FILE__)).'/views');
Epi::init('cache','config','database','route','session','template');

// configs
getConfig()->load('prod.ini');
if(getenv('CONF'))
  getConfig()->load(getenv('CONF'));

include getConfig()->get('paths')->libraries . '/functions.php';

$dbConfig = getConfig()->get('db');
EpiDatabase::employ($dbConfig->type, $dbConfig->name, $dbConfig->host, $dbConfig->user, getSecret('mysql_pass'));
EpiSession::employ(EpiSession::PHP);
EpiCache::employ(EpiCache::MEMCACHED);

