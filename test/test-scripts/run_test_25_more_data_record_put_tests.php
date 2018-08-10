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
defined('__TEST_25_ID__') or define('__TEST_25_ID__', 5403);
defined('__TEST_25_MANAGER_LOGIN__') or define('__TEST_25_MANAGER_LOGIN__', 'login-311');
defined('__TEST_25_MANAGER_LOGIN_ID__') or define('__TEST_25_MANAGER_LOGIN_ID__', 14);
defined('__TEST_25_ACCESSIBLE_LOGIN__') or define('__TEST_25_ACCESSIBLE_LOGIN__', 'login-310');
defined('__TEST_25_ACCESSIBLE_LOGIN_ID__') or define('__TEST_25_ACCESSIBLE_LOGIN_ID__', 13);
defined('__TEST_25_NON_ACCESSIBLE_LOGIN__') or define('__TEST_25_NON_ACCESSIBLE_LOGIN__', 'login-309');
defined('__TEST_25_NON_ACCESSIBLE_LOGIN_ID__') or define('__TEST_25_NON_ACCESSIBLE_LOGIN_ID__', 12);

function run_test_25_more_data_record_put_tests($test_harness_instance) {
    $all_pass = false;
    $test_count = $test_harness_instance->test_count;
    
    if (isset($test_harness_instance->sdk_instance)) {
        $all_pass = true;
        if ($test_harness_instance->sdk_instance->valid()) {
            $non_logged_in_sdk_instance = $test_harness_instance->sdk_instance;
            $manager_sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__, __TEST_25_MANAGER_LOGIN__, __PASSWORD__, CO_Config::$session_timeout_in_seconds);
            if (isset($manager_sdk_instance) && ($manager_sdk_instance instanceof RVP_PHP_SDK) && $manager_sdk_instance->valid() && $manager_sdk_instance->is_manager()) {
                $accessible_sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__, __TEST_25_ACCESSIBLE_LOGIN__, __PASSWORD__, CO_Config::$session_timeout_in_seconds);
                if (isset($accessible_sdk_instance) && ($accessible_sdk_instance instanceof RVP_PHP_SDK) && $accessible_sdk_instance->valid() && $accessible_sdk_instance->is_logged_in()) {
                    $non_accessible_sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__, __TEST_25_NON_ACCESSIBLE_LOGIN__, __PASSWORD__, CO_Config::$session_timeout_in_seconds);
                    if (isset($non_accessible_sdk_instance) && ($non_accessible_sdk_instance instanceof RVP_PHP_SDK) && $non_accessible_sdk_instance->valid() && $non_accessible_sdk_instance->is_logged_in()) {
                        $all_pass = run_permission_tests($test_harness_instance, $manager_sdk_instance, $accessible_sdk_instance, $non_accessible_sdk_instance, $non_logged_in_sdk_instance, $test_count);
                    } else {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('FAILED TO INSTANTIATE SDK INSTANCE', $test_count++, false);
                        echo('<h4 style="color:red">FAILED TO INSTANTIATE SDK INSTANCE!</h4>');
                    }
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('FAILED TO INSTANTIATE SDK INSTANCE', $test_count++, false);
                    echo('<h4 style="color:red">FAILED TO INSTANTIATE SDK INSTANCE!</h4>');
                }
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('FAILED TO INSTANTIATE MANAGER SDK INSTANCE', $test_count++, false);
                echo('<h4 style="color:red">FAILED TO INSTANTIATE MANAGER SDK INSTANCE!</h4>');
            }
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('VALIDITY CHECK', $test_count++, false);
            echo('<h4 style="color:red">SERVER NOT VALID!</h4>');
        }
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('INSTANTIATION CHECK', $test_count++, false);
        echo('<h4 style="color:red">NO SDK INSTANCE!</h4>');
    }
    
    if ($all_pass) {
        echo('<h4 style="color:green">TEST SUCCESSFUL!</h4>');
    }
    
    $test_harness_instance->test_count = $test_count;
    
    return $all_pass;     
}

function run_permission_tests($in_test_harness_instance, $in_manager_sdk_instance, $in_accessible_sdk_instance, $in_non_accessible_sdk_instance, $in_non_logged_in_sdk_instance, &$test_count) {
    $all_pass = run_basic_access_tests($in_test_harness_instance, $in_manager_sdk_instance, $in_accessible_sdk_instance, $in_non_accessible_sdk_instance, $in_non_logged_in_sdk_instance, $test_count);
    
    $records = $in_accessible_sdk_instance->get_objects(__TEST_25_ID__);
    if (isset($records) && is_array($records) && (1 == count($records)) && ($records[0] instanceof RVP_PHP_SDK_Place) && (__TEST_25_ID__ == $records[0]->id())) {
        $record = $records[0];
        
        if ($record->set_object_access(1)) {
            $in_test_harness_instance->write_log_entry('OBJECT ACCESS TO LOGGED-IN-ONLY', $test_count++, true);
            $all_pass = run_login_access_tests($in_test_harness_instance, $in_manager_sdk_instance, $in_accessible_sdk_instance, $in_non_accessible_sdk_instance, $in_non_logged_in_sdk_instance, $test_count);
        } else {
            $all_pass = false;
            $in_test_harness_instance->write_log_entry('FAILED TO CHANGE OBJECT ACCESS', $test_count++, false);
            echo('<h4 style="color:red">FAILED TO CHANGE OBJECT ACCESS!</h4>');
        }
        
        if ($record->set_object_access(0)) {
            $in_test_harness_instance->write_log_entry('OBJECT ACCESS TO ALL-READABLE', $test_count++, true);
            $all_pass = run_basic_access_tests($in_test_harness_instance, $in_manager_sdk_instance, $in_accessible_sdk_instance, $in_non_accessible_sdk_instance, $in_non_logged_in_sdk_instance, $test_count);
        } else {
            $all_pass = false;
            $in_test_harness_instance->write_log_entry('FAILED TO CHANGE OBJECT ACCESS', $test_count++, false);
            echo('<h4 style="color:red">FAILED TO CHANGE OBJECT ACCESS!</h4>');
        }
        
        if ($record->set_object_access(__TEST_25_NON_ACCESSIBLE_LOGIN_ID__)) {
            $in_test_harness_instance->write_log_entry('TRY CHANGING OBJECT ACCESS TO TOKEN WE DON\'T HAVE', $test_count++, true);
            $all_pass = run_basic_access_tests($in_test_harness_instance, $in_manager_sdk_instance, $in_accessible_sdk_instance, $in_non_accessible_sdk_instance, $in_non_logged_in_sdk_instance, $test_count);
        } else {
            $all_pass = false;
            $in_test_harness_instance->write_log_entry('FAILED TO CHANGE OBJECT ACCESS', $test_count++, false);
            echo('<h4 style="color:red">FAILED TO CHANGE OBJECT ACCESS!</h4>');
        }
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('ACCESS-LOGIN RECORD ACCESS', $test_count++, false);
        echo('<h4 style="color:red">ACCESS-LOGIN RECORD ACCESS FAILED!</h4>');
    }
    
    $records = $in_manager_sdk_instance->get_objects(__TEST_25_ID__);
    if (isset($records) && is_array($records) && (1 == count($records)) && ($records[0] instanceof RVP_PHP_SDK_Place) && (__TEST_25_ID__ == $records[0]->id())) {
        $record = $records[0];
        
        if ($record->set_object_access(__TEST_25_NON_ACCESSIBLE_LOGIN_ID__)) {
            $in_test_harness_instance->write_log_entry('USE THE MANAGER TO CHANGE THE TOKEN', $test_count++, true);
            $all_pass = run_login_access_tests($in_test_harness_instance, $in_manager_sdk_instance, $in_accessible_sdk_instance, $in_non_accessible_sdk_instance, $in_non_logged_in_sdk_instance, $test_count);
        } else {
            $all_pass = false;
            $in_test_harness_instance->write_log_entry('FAILED TO CHANGE OBJECT ACCESS', $test_count++, false);
            echo('<h4 style="color:red">FAILED TO CHANGE OBJECT ACCESS!</h4>');
        }
        
        if ($record->set_object_access(__TEST_25_ACCESSIBLE_LOGIN_ID__)) {
            $in_test_harness_instance->write_log_entry('USE THE MANAGER TO CHANGE THE TOKEN SO ONLY THE ACCESSIBLE (AND MANAGER) CAN SEE IT', $test_count++, true);
            $all_pass = run_special_access_tests($in_test_harness_instance, $in_manager_sdk_instance, $in_accessible_sdk_instance, $in_non_accessible_sdk_instance, $in_non_logged_in_sdk_instance, $test_count);
        } else {
            $all_pass = false;
            $in_test_harness_instance->write_log_entry('FAILED TO CHANGE OBJECT ACCESS', $test_count++, false);
            echo('<h4 style="color:red">FAILED TO CHANGE OBJECT ACCESS!</h4>');
        }
        
        if ($record->set_object_access(__TEST_25_NON_ACCESSIBLE_LOGIN_ID__, __TEST_25_NON_ACCESSIBLE_LOGIN_ID__)) {
            $in_test_harness_instance->write_log_entry('USE THE MANAGER TO CHANGE THE TOKEN SO ONLY THE NON-ACCESSIBLE (AND MANAGER) CAN SEE IT', $test_count++, true);
            $all_pass = run_non_accessible_access_tests($in_test_harness_instance, $in_manager_sdk_instance, $in_accessible_sdk_instance, $in_non_accessible_sdk_instance, $in_non_logged_in_sdk_instance, $test_count);
        } else {
            $all_pass = false;
            $in_test_harness_instance->write_log_entry('FAILED TO CHANGE OBJECT ACCESS', $test_count++, false);
            echo('<h4 style="color:red">FAILED TO CHANGE OBJECT ACCESS!</h4>');
        }
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('MANAGER LOGIN RECORD ACCESS', $test_count++, false);
        echo('<h4 style="color:red">MANAGER LOGIN RECORD ACCESS FAILED!</h4>');
    }
    
    return $all_pass;    
}

function run_basic_access_tests($in_test_harness_instance, $in_manager_sdk_instance, $in_accessible_sdk_instance, $in_non_accessible_sdk_instance, $in_non_logged_in_sdk_instance, &$test_count) {
    $all_pass = true;
    
    $records = $in_non_logged_in_sdk_instance->get_objects(__TEST_25_ID__);
    if (isset($records) && is_array($records) && (1 == count($records)) && ($records[0] instanceof RVP_PHP_SDK_Place) && (__TEST_25_ID__ == $records[0]->id())) {
        $in_test_harness_instance->write_log_entry('NON-LOGGED-IN RECORD ACCESS', $test_count++, true);
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('NON-LOGGED-IN RECORD ACCESS', $test_count++, false);
        echo('<h4 style="color:red">NON-LOGGED-IN RECORD ACCESS FAILED!</h4>');
    }
    
    $records = $in_non_accessible_sdk_instance->get_objects(__TEST_25_ID__);
    if (isset($records) && is_array($records) && (1 == count($records)) && ($records[0] instanceof RVP_PHP_SDK_Place) && (__TEST_25_ID__ == $records[0]->id())) {
        $in_test_harness_instance->write_log_entry('NON-ACCESS-LOGIN RECORD ACCESS', $test_count++, true);
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('NON-ACCESS-LOGIN RECORD ACCESS', $test_count++, false);
        echo('<h4 style="color:red">NON-ACCESS-LOGIN RECORD ACCESS FAILED!</h4>');
    }
    
    $records = $in_accessible_sdk_instance->get_objects(__TEST_25_ID__);
    if (isset($records) && is_array($records) && (1 == count($records)) && ($records[0] instanceof RVP_PHP_SDK_Place) && (__TEST_25_ID__ == $records[0]->id())) {
        $in_test_harness_instance->write_log_entry('ACCESS-LOGIN RECORD ACCESS', $test_count++, true);
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('ACCESS-LOGIN RECORD ACCESS', $test_count++, false);
        echo('<h4 style="color:red">ACCESS-LOGIN RECORD ACCESS FAILED!</h4>');
    }
    
    $records = $in_manager_sdk_instance->get_objects(__TEST_25_ID__);
    if (isset($records) && is_array($records) && (1 == count($records)) && ($records[0] instanceof RVP_PHP_SDK_Place) && (__TEST_25_ID__ == $records[0]->id())) {
        $in_test_harness_instance->write_log_entry('MANAGER LOGIN RECORD ACCESS', $test_count++, true);
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('MANAGER LOGIN RECORD ACCESS', $test_count++, false);
        echo('<h4 style="color:red">MANAGER LOGIN RECORD ACCESS FAILED!</h4>');
    }
    
    return $all_pass;
}

function run_login_access_tests($in_test_harness_instance, $in_manager_sdk_instance, $in_accessible_sdk_instance, $in_non_accessible_sdk_instance, $in_non_logged_in_sdk_instance, &$test_count) {
    $all_pass = true;
    
    $records = $in_non_logged_in_sdk_instance->get_objects(__TEST_25_ID__);
    if (!isset($records) || (is_array($records) && (0 == count($records)))) {
        $in_test_harness_instance->write_log_entry('NON-LOGGED-IN RECORD ACCESS (SHOULD FAIL, HERE)', $test_count++, true);
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('NON-LOGGED-IN RECORD ACCESS (GOT THE RECORD -THAT SHOULD NOT HAPPEN)', $test_count++, false);
        echo('<h4 style="color:red">NON-LOGGED-IN RECORD ACCESS FAILED (GOT THE RECORD -THAT SHOULD NOT HAPPEN)!</h4>');
    }
    
    $records = $in_non_accessible_sdk_instance->get_objects(__TEST_25_ID__);
    if (isset($records) && is_array($records) && (1 == count($records)) && ($records[0] instanceof RVP_PHP_SDK_Place) && (__TEST_25_ID__ == $records[0]->id())) {
        $in_test_harness_instance->write_log_entry('NON-ACCESS-LOGIN RECORD ACCESS', $test_count++, true);
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('NON-ACCESS-LOGIN RECORD ACCESS', $test_count++, false);
        echo('<h4 style="color:red">NON-ACCESS-LOGIN RECORD ACCESS FAILED!</h4>');
    }
    
    $records = $in_accessible_sdk_instance->get_objects(__TEST_25_ID__);
    if (isset($records) && is_array($records) && (1 == count($records)) && ($records[0] instanceof RVP_PHP_SDK_Place) && (__TEST_25_ID__ == $records[0]->id())) {
        $in_test_harness_instance->write_log_entry('ACCESS-LOGIN RECORD ACCESS', $test_count++, true);
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('ACCESS-LOGIN RECORD ACCESS', $test_count++, false);
        echo('<h4 style="color:red">ACCESS-LOGIN RECORD ACCESS FAILED!</h4>');
    }
    
    $records = $in_manager_sdk_instance->get_objects(__TEST_25_ID__);
    if (isset($records) && is_array($records) && (1 == count($records)) && ($records[0] instanceof RVP_PHP_SDK_Place) && (__TEST_25_ID__ == $records[0]->id())) {
        $in_test_harness_instance->write_log_entry('MANAGER LOGIN RECORD ACCESS', $test_count++, true);
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('MANAGER LOGIN RECORD ACCESS', $test_count++, false);
        echo('<h4 style="color:red">MANAGER LOGIN RECORD ACCESS FAILED!</h4>');
    }
    
    return $all_pass;
}

function run_special_access_tests($in_test_harness_instance, $in_manager_sdk_instance, $in_accessible_sdk_instance, $in_non_accessible_sdk_instance, $in_non_logged_in_sdk_instance, &$test_count) {
    $all_pass = true;
    
    $records = $in_non_logged_in_sdk_instance->get_objects(__TEST_25_ID__);
    if (!isset($records) || (is_array($records) && (0 == count($records)))) {
        $in_test_harness_instance->write_log_entry('NON-LOGGED-IN RECORD ACCESS (SHOULD FAIL, HERE)', $test_count++, true);
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('NON-LOGGED-IN RECORD ACCESS (GOT THE RECORD -THAT SHOULD NOT HAPPEN)', $test_count++, false);
        echo('<h4 style="color:red">NON-LOGGED-IN RECORD ACCESS FAILED (GOT THE RECORD -THAT SHOULD NOT HAPPEN)!</h4>');
    }
    
    $records = $in_non_accessible_sdk_instance->get_objects(__TEST_25_ID__);
    if (!isset($records) || (is_array($records) && (0 == count($records)))) {
        $in_test_harness_instance->write_log_entry('NON-ACCESS RECORD ACCESS (SHOULD FAIL, HERE)', $test_count++, true);
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('NON-ACCESS-LOGIN RECORD ACCESS (GOT THE RECORD -THAT SHOULD NOT HAPPEN)', $test_count++, false);
        echo('<h4 style="color:red">NON-ACCESS-LOGIN RECORD ACCESS FAILED (GOT THE RECORD -THAT SHOULD NOT HAPPEN)!</h4>');
    }
    
    $records = $in_accessible_sdk_instance->get_objects(__TEST_25_ID__);
    if (isset($records) && is_array($records) && (1 == count($records)) && ($records[0] instanceof RVP_PHP_SDK_Place) && (__TEST_25_ID__ == $records[0]->id())) {
        $in_test_harness_instance->write_log_entry('ACCESS-LOGIN RECORD ACCESS', $test_count++, true);
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('ACCESS-LOGIN RECORD ACCESS', $test_count++, false);
        echo('<h4 style="color:red">ACCESS-LOGIN RECORD ACCESS FAILED!</h4>');
    }
    
    $records = $in_manager_sdk_instance->get_objects(__TEST_25_ID__);
    if (isset($records) && is_array($records) && (1 == count($records)) && ($records[0] instanceof RVP_PHP_SDK_Place) && (__TEST_25_ID__ == $records[0]->id())) {
        $in_test_harness_instance->write_log_entry('MANAGER LOGIN RECORD ACCESS', $test_count++, true);
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('MANAGER LOGIN RECORD ACCESS', $test_count++, false);
        echo('<h4 style="color:red">MANAGER LOGIN RECORD ACCESS FAILED!</h4>');
    }
    
    return $all_pass;
}

function run_non_accessible_access_tests($in_test_harness_instance, $in_manager_sdk_instance, $in_accessible_sdk_instance, $in_non_accessible_sdk_instance, $in_non_logged_in_sdk_instance, &$test_count) {
    $all_pass = true;
    
    $records = $in_non_logged_in_sdk_instance->get_objects(__TEST_25_ID__);
    if (!isset($records) || (is_array($records) && (0 == count($records)))) {
        $in_test_harness_instance->write_log_entry('NON-LOGGED-IN RECORD ACCESS (SHOULD FAIL, HERE)', $test_count++, true);
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('NON-LOGGED-IN RECORD ACCESS (GOT THE RECORD -THAT SHOULD NOT HAPPEN)', $test_count++, false);
        echo('<h4 style="color:red">NON-LOGGED-IN RECORD ACCESS FAILED (GOT THE RECORD -THAT SHOULD NOT HAPPEN)!</h4>');
    }
    
    $records = $in_non_accessible_sdk_instance->get_objects(__TEST_25_ID__);
    if (isset($records) && is_array($records) && (1 == count($records)) && ($records[0] instanceof RVP_PHP_SDK_Place) && (__TEST_25_ID__ == $records[0]->id())) {
        $in_test_harness_instance->write_log_entry('NON-ACCESS-LOGIN RECORD ACCESS', $test_count++, true);
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('NON-ACCESS-LOGIN RECORD ACCESS', $test_count++, false);
        echo('<h4 style="color:red">NON-ACCESS-LOGIN RECORD ACCESS FAILED!</h4>');
    }
    
    $records = $in_accessible_sdk_instance->get_objects(__TEST_25_ID__);
    if (!isset($records) || (is_array($records) && (0 == count($records)))) {
        $in_test_harness_instance->write_log_entry('ACCESS-LOGIN RECORD ACCESS', $test_count++, true);
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('ACCESS-LOGIN RECORD ACCESS (GOT THE RECORD -THAT SHOULD NOT HAPPEN)', $test_count++, false);
        echo('<h4 style="color:red">ACCESS-LOGIN RECORD ACCESS FAILED (GOT THE RECORD -THAT SHOULD NOT HAPPEN)!</h4>');
    }
    
    $records = $in_manager_sdk_instance->get_objects(__TEST_25_ID__);
    if (isset($records) && is_array($records) && (1 == count($records)) && ($records[0] instanceof RVP_PHP_SDK_Place) && (__TEST_25_ID__ == $records[0]->id())) {
        $in_test_harness_instance->write_log_entry('MANAGER LOGIN RECORD ACCESS', $test_count++, true);
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('MANAGER LOGIN RECORD ACCESS', $test_count++, false);
        echo('<h4 style="color:red">MANAGER LOGIN RECORD ACCESS FAILED!</h4>');
    }
    
    return $all_pass;
}
?>