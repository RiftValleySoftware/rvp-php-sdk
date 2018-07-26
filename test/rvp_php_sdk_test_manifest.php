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
define('__CRICKETS_SHA__', '11194cafa6f43c790d9125c414965cdfbd9054ba');
define('__DIVINE_COMEDY_SHA__', '49aaef8684cf794b035c2c0522a6d1ac71ee029f');

$rvp_php_sdk_test_manifest = [
                                [
                                    'blurb'     =>  'INITIAL INSTANTIATION TEST (NO LOGIN)',
                                    'explain'   =>  'Set Up The SDK With No Login, And Make Sure the Initial Information Is Correct.',
                                    'db'        =>  'sdk_1',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_01_harness_basic_login_tests',
                                                    'file'      =>  'test-scripts/run_test_01_harness_basic_login_tests.php'
                                                    ]
                                ],
                                
                                [
                                    'blurb'     =>  'INITIAL INSTANTIATION TEST (WITH LOGIN)',
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
                                ],
                                
                                [
                                    'blurb'     =>  'ACCESS OBJECTS TEST (NO LOGIN)',
                                    'explain'   =>  'Set Up The SDK With No Login, And Access Some Objects By ID.',
                                    'db'        =>  'sdk_1',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_03_harness_basic_access_tests',
                                                    'file'      =>  'test-scripts/run_test_03_harness_basic_access_tests.php'
                                                    ]
                                ],
                                
                                [
                                    'blurb'     =>  'ACCESS OBJECTS TEST (WITH LOGIN)',
                                    'explain'   =>  'Set Up The SDK With A Login, And Access Some Objects By ID. There will be one additional Thing Object.',
                                    'db'        =>  'sdk_1',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_04_harness_basic_access_tests',
                                                    'file'      =>  'test-scripts/run_test_04_harness_basic_access_tests.php'
                                                    ],
                                    'login'     =>  [
                                                    'login_id'  =>  'MainAdmin',
                                                    'password'  =>  'CoreysGoryStory',
                                                    'timeout'   =>  CO_Config::$session_timeout_in_seconds,
                                                    'logout'    =>  true
                                                    ]
                                ],
                            ];
?>