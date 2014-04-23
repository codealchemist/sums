<?php

$config = array(
    'version' => '1.0.1',
    'appName' => 'SUMS',
    'cors' => false,
    'itemsPerPage' => 4,
    'paginationInterval' => 10,
    'ipWhitelist' => array(
        'b3rt' => '181.46.22.37'
    ),
    'access' => array(
        'protectedApps' => array('admin', 'private', 'public')
    ),
    'cors' => array(
        'domains' => array(
            'http://apolo.sums.dev',
            'http://private.sums.dev',
            'http://sums.dev'
        )
    ),
    'imagesUploadPath' => 'www/public/__uploads',
    'mongodb' => array(
        'hostname' => 'localhost',
        'port' => '27017',
        'username' => 'sums',
        'password' => '----.plus.----',
        'database' => 'sums'
    ),
    'twig' => array(
        'templatesPath' => '../../application/themes/' . APPLICATION,
        'cachePath' => '../../application/cache-twig'
    ),
    'tokenPrefixes' => array(
        'login' => 'login-token'
    )
);