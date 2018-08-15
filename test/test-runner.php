<?php
/***************************************************************************************************************************/
/**
    BLUE DRAGON PHP SDK
    
    © Copyright 2018, Little Green Viper Software Development LLC.
    
    This code is proprietary and confidential code, 
    It is NOT to be reused or combined into any application,
    unless done so, specifically under written license from Little Green Viper Software Development LLC.

    Little Green Viper Software Development: https://littlegreenviper.com
*/
    set_time_limit(3600);
    
    require_once(dirname(__FILE__).'/rvp_php_sdk_test_harness.class.php');
    require_once(dirname(__FILE__).'/rvp_php_sdk_test_manifest.php');
    
    $first = isset($_GET['first_test']);
    $last = isset($_GET['last_test']);
    $start = intval($_GET['start_index']);
    $end = intval($_GET['end_index']);
    $allpass = intval($_GET['allpass']);
    $current_total = intval($_GET['current_total']);
    
    $test = new RVP_PHP_SDK_Test_Harness($rvp_php_sdk_test_manifest, (1 == $allpass) ? true : false, $current_total, $start, $end, $first, $last);
?>