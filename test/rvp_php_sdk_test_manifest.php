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
$rvp_php_sdk_test_manifest = [
                                [
                                    'blurb'     =>  '01 - INITIAL LOGIN TEST',
                                    'explain'   =>  'Set Up The SDK, And Make Sure the Initial Information Is Correct.',
                                    'db'        =>  'things_tests_2',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_01_harness_basic_login_tests',
                                                    'file'      =>  'test-scripts/run_test_01_harness_basic_login_tests.php'
                                                    ],
                                    'login'     =>  [
                                                    'login_id'  =>  'MainAdmin',
                                                    'password'  =>  'CoreysGoryStory',
                                                    'timeout'   =>  CO_Config::$session_timeout_in_seconds,
                                                    'logout'    =>  true
                                                    ]
                                ]
                            ];
?>