<?php

$f3=require('lib/base.php');
$f3->config('config/config.ini');
$f3->config('config/routes.ini');

$db = new DB\SQL(
            $f3->get('db_dns').$f3->get('db_name'),
            $f3->get('db_user'),
            $f3->get('db_pass')
        );
\Registry::set('DB', $db);

$f3->run();
