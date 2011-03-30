<?php
chdir(dirname(__FILE__));
ini_set('include_path', '.');
require '../configs/init.php';
Resque::enqueue('mmh_dummy', 'Dummy', array());
