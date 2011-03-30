#!/bin/bash

cd ../libraries/php-resque/
VERBOSE=1 APP_INCLUDE=../../configs/init.php CONF=$1 QUEUE=mmh_fetch,mmh_email,mmh_reply,mmh_dummy php -c ./ resque.php
