<?php
header('Content-Type: text/css');
chdir(dirname(__FILE__));
ini_set('include_path', '.');
require '../configs/init.php';
Resque::enqueue('mmh_dummy', 'QueueDummy', array());
