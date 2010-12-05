<?php
include '../configs/init.php';

// controllers
include getConfig()->get('paths')->controllers . '/Site.php';
include getConfig()->get('paths')->controllers . '/Api.php';

// routes
getRoute()->load('routes.ini');
getRoute()->run(); 
