<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'ENVNEWS.ORG',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),
    // application components
    'components' => array(
        'db' => array(
            'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../data/testdrive.db',
        ),
        // uncomment the following to use a MySQL database
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=envnews_newdb',
            'emulatePrepare' => true,
            'username' => 'envnews_newadmin',
            'password' => 'Dhaka123$',
            'charset' => 'utf8',
            'tablePrefix' => 'os_'
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
    ),
    // using Yii::app()->params['paramName']
    'params' => array(
    // this is used in contact page
        'adminName' => 'ENVNEWS.ORG',
        'adminEmail' => 'info@envnews.org',
        'noreply' => 'noreply@envnews.org',
    ),
);