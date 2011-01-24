<?php
header('Content-Type: text/css');
ini_set('include_path', '.');
require '../configs/init.php';
Resque::enqueue('mmh_dummy', 'QueueDummy', array());
