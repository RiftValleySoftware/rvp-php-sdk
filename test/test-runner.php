<?php
/***************************************************************************************************************************/
/**
    BASALT Extension Layer
    
    © Copyright 2018, Little Green Viper Software Development LLC.
    
    This code is proprietary and confidential code, 
    It is NOT to be reused or combined into any application,
    unless done so, specifically under written license from Little Green Viper Software Development LLC.

    Little Green Viper Software Development: https://littlegreenviper.com
*/
    date_default_timezone_set ( 'UTC' );
    set_time_limit(3600);
    
    require_once(dirname(__FILE__).'/rvp_php_sdk_test_harness.class.php');
    require_once(dirname(__FILE__).'/rvp_php_sdk_test_manifest.php');

    $test = new RVP_PHP_SDK_Test_Harness($rvp_php_sdk_test_manifest);
?>