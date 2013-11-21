<?php
/**
 * @Author: Kenyon Haliwell
 * @URL: http://khdev.net/
 * @Date Created: 2/20/11
 * @Date Modified: 11/21/13
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
    'timeclock_root' => '/tnbtimeclock/timeclock/',
    'timeclock_assets' => '/tnbtimeclock/timeclockAssets/',
    'timeclock_subdirectories' => 'timeclock',
    
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
    'mysql_username' => 'username',
    'mysql_password' => 'password'
);

return $config;

//End File
