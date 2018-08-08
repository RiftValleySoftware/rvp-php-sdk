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
define('__CRICKETS_SHA__', '11194cafa6f43c790d9125c414965cdfbd9054ba');
define('__DIVINE_COMEDY_SHA__', '49aaef8684cf794b035c2c0522a6d1ac71ee029f');
define('__PASSWORD__', 'CoreysGoryStory');
define('__TEST_LOGINS__', ['Login Only' => ['MeLeet'], 'Regular User Login' => ['MDAdmin'],'Manager Login' => ['MainAdmin'],'God User' => ['admin']]);
define('__TEST_07_IDS__', [100,101,102,106,1725,1726,1727,1731,1732,1733,1734,1735]);
define('__TEST_11_OBJECT_IDS__', [2,835,874,1729,1730,1731,1732,1734,1738,1739,1744,1745,1750,1751]);
define('__TEST_11_TOKEN_IDS__', [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18]);

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
                                
                                [
                                    'blurb'     =>  'NON-LOGGED-IN LOCATION SEARCH TESTS',
                                    'explain'   =>  'Without logging in, try doing a series of simple location-based searches, using each of the plugins.',
                                    'db'        =>  'sdk_1',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_08_harness_baseline_search_tests',
                                                    'file'      =>  'test-scripts/run_test_08_harness_baseline_search_tests.php'
                                                    ]
                                ],
                                
                                [
                                    'blurb'     =>  'LOGGED-IN LOCATION SEARCH TESTS',
                                    'explain'   =>  'After logging in, try doing a series of simple location-based searches, using each of the plugins.',
                                    'db'        =>  'sdk_1',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_09_harness_baseline_search_tests',
                                                    'file'      =>  'test-scripts/run_test_09_harness_baseline_search_tests.php'
                                                    ],
                                    'login'     =>  [
                                                    'login_id'  =>  'MainAdmin',
                                                    'password'  =>  'CoreysGoryStory',
                                                    'timeout'   =>  CO_Config::$session_timeout_in_seconds,
                                                    'logout'    =>  true
                                                    ]
                                ],
                                
                                [
                                    'blurb'     =>  'SIMPLE BASELINE TEXT SEARCH TESTS',
                                    'explain'   =>  'Try various text searches.',
                                    'db'        =>  'sdk_1',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_10_harness_text_search_tests',
                                                    'file'      =>  'test-scripts/run_test_10_harness_text_search_tests.php'
                                                    ]
                                ],
                                
                                [
                                    'blurb'     =>  'BASELINE VISIBILITY COMMAND TESTS',
                                    'explain'   =>  'Test the baseline plugin\'s \'visibility\' command.',
                                    'db'        =>  'sdk_1',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_11_harness_baseline_visibility_tests',
                                                    'file'      =>  'test-scripts/run_test_11_harness_baseline_visibility_tests.php'
                                                    ]
                                ],
                                
                                [
                                    'blurb'     =>  'SIMPLE PEOPLE TEXT SEARCH TESTS',
                                    'explain'   =>  'Test the people plugin\'s text search capabilities.',
                                    'db'        =>  'sdk_1',
                                    'db'        =>  'sdk_1',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_12_harness_people_text_search_tests',
                                                    'file'      =>  'test-scripts/run_test_12_harness_people_text_search_tests.php'
                                                    ]
                                ],
                                
                                [
                                    'blurb'     =>  'SIMPLE PLACES TEXT SEARCH TESTS',
                                    'explain'   =>  'Test the places plugin\'s text search capabilities.',
                                    'db'        =>  'sdk_1',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_13_harness_places_text_search_tests',
                                                    'file'      =>  'test-scripts/run_test_13_harness_places_text_search_tests.php'
                                                    ]
                                ],
                                
                                [
                                    'blurb'     =>  'SIMPLE THINGS TEXT SEARCH TESTS',
                                    'explain'   =>  'Test the things plugin\'s text search capabilities.',
                                    'db'        =>  'sdk_1',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_14_harness_things_text_search_tests',
                                                    'file'      =>  'test-scripts/run_test_14_harness_things_text_search_tests.php'
                                                    ]
                                ],
                                
                                [
                                    'blurb'     =>  'HYBRID TEXT AND LOCATION SEARCH TESTS',
                                    'explain'   =>  'Test Mixed Searches.',
                                    'db'        =>  'sdk_1',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_15_harness_hybrid_search_tests',
                                                    'file'      =>  'test-scripts/run_test_15_harness_hybrid_search_tests.php'
                                                    ]
                                ],
                                
                                [
                                    'blurb'     =>  'AUTO-RADIUS SEARCH TESTS',
                                    'explain'   =>  'Test Auto-Radius Searches.',
                                    'db'        =>  'sdk_1',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_16_harness_auto_radius_search_tests',
                                                    'file'      =>  'test-scripts/run_test_16_harness_auto_radius_search_tests.php'
                                                    ]
                                ],
                                
                                [
                                    'blurb'     =>  'GET PAYLOAD TESTS',
                                    'explain'   =>  'Fetch varied payloads from the sample data.',
                                    'db'        =>  'sdk_1',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_17_harness_get_payload_tests',
                                                    'file'      =>  'test-scripts/run_test_17_harness_get_payload_tests.php'
                                                    ],
                                    'login'     =>  [
                                                    'login_id'  =>  'MainAdmin',
                                                    'password'  =>  'CoreysGoryStory',
                                                    'timeout'   =>  CO_Config::$session_timeout_in_seconds,
                                                    'logout'    =>  true
                                                    ]
                                ],
                                
                                [
                                    'blurb'     =>  'BULK LOADER TEST',
                                    'explain'   =>  'Upload a large, complex CSV file to an empty database.',
                                    'db'        =>  'sdk_2',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_18_harness_baseline_bulk_loader_tests',
                                                    'file'      =>  'test-scripts/run_test_18_harness_baseline_bulk_loader_tests.php'
                                                    ],
                                    'login'     =>  [
                                                    'login_id'  =>  'admin',
                                                    'password'  =>  'CoreysGoryStory',
                                                    'timeout'   =>  CO_Config::$god_session_timeout_in_seconds,
                                                    'logout'    =>  true
                                                    ]
                                ],
                                
                                [
                                    'blurb'     =>  'BULK LOADER TEST 2',
                                    'explain'   =>  'Upload a large, complex CSV file to a database that already has a massive dataset.',
                                    'db'        =>  'sdk_3',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_19_harness_baseline_bulk_loader_tests',
                                                    'file'      =>  'test-scripts/run_test_19_harness_baseline_bulk_loader_tests.php'
                                                    ],
                                    'login'     =>  [
                                                    'login_id'  =>  'admin',
                                                    'password'  =>  'CoreysGoryStory',
                                                    'timeout'   =>  CO_Config::$god_session_timeout_in_seconds,
                                                    'logout'    =>  true
                                                    ]
                                ],
                                
                                [
                                    'blurb'     =>  'TEST MULTI-LOGINS',
                                    'explain'   =>  'Log In New Logins, and Check Info.',
                                    'db'        =>  'sdk_3',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_20_harness_login_tests',
                                                    'file'      =>  'test-scripts/run_test_20_harness_login_tests.php'
                                                    ]
                                ],
                                
                                [
                                    'blurb'     =>  'TEST CSV BACKUP',
                                    'explain'   =>  'Log In As God, and fetch a backup of EVERYTHING.',
                                    'db'        =>  'sdk_3',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_21_harness_test_backup',
                                                    'file'      =>  'test-scripts/run_test_21_harness_test_backup.php'
                                                    ],
                                    'login'     =>  [
                                                    'login_id'  =>  'admin',
                                                    'password'  =>  'CoreysGoryStory',
                                                    'timeout'   =>  CO_Config::$god_session_timeout_in_seconds,
                                                    'logout'    =>  true
                                                    ]
                                ],
                                
                                [
                                    'blurb'     =>  'TEST CSV BACKUP RESTORE',
                                    'explain'   =>  'Log In As God, and fetch a backup of EVERYTHING.',
                                    'db'        =>  'sdk_2',
                                    'closure'   =>  [
                                                    'function'  =>  'run_test_22_harness_test_restore',
                                                    'file'      =>  'test-scripts/run_test_22_harness_test_restore.php'
                                                    ],
                                    'login'     =>  [
                                                    'login_id'  =>  'admin',
                                                    'password'  =>  'CoreysGoryStory',
                                                    'timeout'   =>  CO_Config::$god_session_timeout_in_seconds,
                                                    'logout'    =>  true
                                                    ]
                                ],

    ];
?>