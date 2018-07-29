<?php
/***************************************************************************************************************************/
/**
    BAOBAB PHP SDK
    
    © Copyright 2018, Little Green Viper Software Development LLC.
    
    This code is proprietary and confidential code, 
    It is NOT to be reused or combined into any application,
    unless done so, specifically under written license from Little Green Viper Software Development LLC.

    Little Green Viper Software Development: https://littlegreenviper.com
*/
define('__CRICKETS_SHA__', '11194cafa6f43c790d9125c414965cdfbd9054ba');
define('__DIVINE_COMEDY_SHA__', '49aaef8684cf794b035c2c0522a6d1ac71ee029f');
define('__PASSWORD__', 'CoreysGoryStory');
define('__TEST_LOGINS__', ['Login Only' => ['MeLeet'], 'Regular User Login' => ['MDAdmin','DCAdmin','VAAdmin','WVAdmin','DEAdmin','Dilbert','Wally','Ted','Alice','Tina'],'Manager Login' => ['MainAdmin','PHB'],'God User' => ['admin']]);
define('__TEST_07_IDS__', [100,101,102,103,104,105,106,1725,1726,1727,1728,1729,1730,1732,1733,1734,1735]);

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
                                
                                [
                                    'blurb'     =>  'MULTIPLE SUCCESSFUL LOGIN TESTS',
                                    'explain'   =>  'Set Up The SDK With No Login, Then Log In And Out With Multiple Logins.',
                                    'db'        =>  'sdk_1',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_05_harness_multiple_logins_tests',
                                                    'file'      =>  'test-scripts/run_test_05_harness_multiple_logins_tests.php'
                                                    ]
                                ],
                                
                                [
                                    'blurb'     =>  'MULTIPLE SDK INSTANCE LOGIN TESTS',
                                    'explain'   =>  'Stand Up Multiple SDK Instances With Logins.',
                                    'db'        =>  'sdk_1',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_06_harness_multiple_logins_tests',
                                                    'file'      =>  'test-scripts/run_test_06_harness_multiple_logins_tests.php'
                                                    ],
                                    'login'     =>  [
                                                    'login_id'  =>  'admin',
                                                    'password'  =>  'CoreysGoryStory',
                                                    'timeout'   =>  CO_Config::$god_session_timeout_in_seconds,
                                                    'logout'    =>  true
                                                    ]
                                ],
                                
                                [
                                    'blurb'     =>  'LOCATION OBFUSCATION TESTS',
                                    'explain'   =>  'Stand Up A Non-Logged-In Instance, And Test Some Specific Places For Location "Fuzzing."',
                                    'db'        =>  'sdk_1',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_07_harness_location_obfuscation_tests',
                                                    'file'      =>  'test-scripts/run_test_07_harness_location_obfuscation_tests.php'
                                                    ]
                                ],
                                
                            ];
?>