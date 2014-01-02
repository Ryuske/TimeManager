<?php
/**
 * @Author: Kenyon Haliwell
 * @URL: http://khdev.net/
 * @Date Created: 2/20/11
 * @Date Modified: 1/2/14
 * @Purpose: Shared Site Configurations
 * @Version: 1.0
 *
 * Shared configuration between sites, can be overwritten in site-specific configs.
 * Note: The mysql values must be defined somewhere, either here or in your site-specific
 * config in-order for the database to work.
 */

/**
 * Set configuration values array
 */
$config = array(
    /**
     * TimeClock Config Values
     */
    'timemanager_root' => '/timemanager/timemanager/',          //Web root for timemanager
    'timemanager_assets' => '/timemanager/timemanagerAssets/',  //Actual web-accessible location for timemanagerAssets
    'timemanager_uploads' => '/timemanager/uploads/',
    'timemanager_subdirectories' => 'timemanager',              //Framework subdirectory (application/site/controller/timemanager)

    /**
     * General config values
     */
    'admin_name' => 'Kenyon Haliwell',
    'admin_email' => 'masta.hacks@gmail.com',
    'email_errors' => true,
    'errors_from' => 'FW Errors',
    'errors_from_email' => 'errors@framework',

    /**
     * MySQL config values
     */
    'mysql_host' => 'localhost',
    'mysql_port' => '3306',
    'mysql_database' => 'timeclock',
    'mysql_username' => '',
    'mysql_password' => ''
);

return $config;

//End File