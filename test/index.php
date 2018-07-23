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
require_once(dirname(__FILE__).'/rvp_php_sdk_test_harness.class.php');
require_once(dirname(__FILE__).'/test_scripts/run_test_01_harness_basic_login_tests.php');

$function_list = [
                    [   'blurb' => '01 - INITIAL LOGIN TEST',
                        'db' => 'things_tests_2',
                        'login' => ['MainAdmin', 'CoreysGoryStory', CO_Config::$session_timeout_in_seconds],
                        'closure' => 'run_test_01_harness_basic_login_tests'
                    ]
                ];

$test_harness_instance = new RVP_PHP_SDK_Test_Harness($function_list);
?>