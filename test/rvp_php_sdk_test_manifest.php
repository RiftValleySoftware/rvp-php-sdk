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
                                    'blurb'     =>  'INITIAL INSTANTIATION ONLY TEST',
                                    'explain'   =>  'Set Up The SDK With No Login, And Make Sure the Initial Information Is Correct.',
                                    'db'        =>  'sdk_1',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_01_harness_basic_login_tests',
                                                    'file'      =>  'test-scripts/run_test_01_harness_basic_login_tests.php'
                                                    ]
                                ],
                                
                                [
                                    'blurb'     =>  'INITIAL INSTANTIATION AND LOGIN TEST',
                                    'explain'   =>  'Set Up The SDK With A Login, And Make Sure the Initial Information Is Correct.',
                                    'db'        =>  'sdk_1',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_02_harness_basic_login_tests',
                                                    'file'      =>  'test-scripts/run_test_02_harness_basic_login_tests.php'
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