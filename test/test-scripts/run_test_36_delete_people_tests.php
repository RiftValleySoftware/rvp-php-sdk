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
defined('__TEST_36_USER_ID_1__') or define('__TEST_36_USER_ID_1__', 1725);
defined('__TEST_36_LOGIN_ID_1__') or define('__TEST_36_LOGIN_ID_1__', 7);
defined('__TEST_36_USER_ID_2__') or define('__TEST_36_USER_ID_2__', 1726);
defined('__TEST_36_LOGIN_ID_2__') or define('__TEST_36_LOGIN_ID_2__', 8);
defined('__TEST_36_USER_ID_3__') or define('__TEST_36_USER_ID_3__', 1727);
defined('__TEST_36_LOGIN_ID_3__') or define('__TEST_36_LOGIN_ID_3__', 9);

function run_test_36_delete_people_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance) && $test_harness_instance->sdk_instance->valid()) {
        $test_harness_instance->write_log_entry('INSTANTIATION CHECK', $test_count++, true);
        
        $before_user = $test_harness_instance->sdk_instance->get_user_info(__TEST_36_USER_ID_1__);
        
        if (($before_user instanceof RVP_PHP_SDK_User)) {
            $test_harness_instance->write_log_entry('FETCH BEFORE USER', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH BEFORE USER', $test_count++, false);
            echo('<h4 style="color:red">INVALID BEFORE USER!</h4>');
        }
        
        $before_login = $test_harness_instance->sdk_instance->get_login_info(__TEST_36_LOGIN_ID_1__);
        
        if (($before_login instanceof RVP_PHP_SDK_Login)) {
            $test_harness_instance->write_log_entry('FETCH BEFORE LOGIN', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH BEFORE LOGIN', $test_count++, false);
            echo('<h4 style="color:red">INVALID BEFORE USER!</h4>');
        }
        
        $success = $test_harness_instance->sdk_instance->delete_user(__TEST_36_USER_ID_1__);
        
        if ($success) {
            $test_harness_instance->write_log_entry('BASIC DELETE SUCCESS CHECK', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('BASIC DELETE SUCCESS CHECK', $test_count++, false);
            echo('<h4 style="color:red">FAILED BASIC USER DELETE!</h4>');
        }
        
        $after_user = $test_harness_instance->sdk_instance->get_user_info(__TEST_36_USER_ID_1__);
        
        if (($after_user instanceof RVP_PHP_SDK_User)) {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH AFTER USER', $test_count++, false);
            echo('<h4 style="color:red">USER SHOULD BE GONE!</h4>');
        } else {
            $test_harness_instance->write_log_entry('FETCH AFTER USER', $test_count++, true);
        }
        
        $after_login = $test_harness_instance->sdk_instance->get_login_info(__TEST_36_LOGIN_ID_1__);
        
        if (($after_login instanceof RVP_PHP_SDK_Login)) {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH AFTER LOGIN', $test_count++, false);
            echo('<h4 style="color:red">LOGIN SHOULD BE GONE!</h4>');
        } else {
            $test_harness_instance->write_log_entry('FETCH AFTER LOGIN', $test_count++, true);
        }
        
        $before_user = $test_harness_instance->sdk_instance->get_user_info(__TEST_36_USER_ID_2__);
        $after_user = NULL;
        
        if (($before_user instanceof RVP_PHP_SDK_User)) {
            $test_harness_instance->write_log_entry('FETCH BEFORE USER', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH BEFORE USER', $test_count++, false);
            echo('<h4 style="color:red">INVALID BEFORE USER!</h4>');
        }
        
        $before_login = $test_harness_instance->sdk_instance->get_login_info(__TEST_36_LOGIN_ID_2__);
        $after_login = NULL;
        
        if (($before_login instanceof RVP_PHP_SDK_Login)) {
            $test_harness_instance->write_log_entry('FETCH BEFORE LOGIN', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH BEFORE LOGIN', $test_count++, false);
            echo('<h4 style="color:red">INVALID BEFORE USER!</h4>');
        }
        
        $success = $test_harness_instance->sdk_instance->delete_user($before_user);
        
        if ($success) {
            $test_harness_instance->write_log_entry('BASIC DELETE SUCCESS CHECK', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('BASIC DELETE SUCCESS CHECK', $test_count++, false);
            echo('<h4 style="color:red">FAILED BASIC USER DELETE!</h4>');
        }
        
        $after_user = $test_harness_instance->sdk_instance->get_user_info(__TEST_36_USER_ID_2__);
        
        if (($after_user instanceof RVP_PHP_SDK_User)) {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH AFTER USER', $test_count++, false);
            echo('<h4 style="color:red">USER SHOULD BE GONE!</h4>');
        } else {
            $test_harness_instance->write_log_entry('FETCH AFTER USER', $test_count++, true);
        }
        
        $after_login = $test_harness_instance->sdk_instance->get_login_info(__TEST_36_LOGIN_ID_2__);
        
        if (($after_login instanceof RVP_PHP_SDK_Login)) {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH AFTER LOGIN', $test_count++, false);
            echo('<h4 style="color:red">LOGIN SHOULD BE GONE!</h4>');
        } else {
            $test_harness_instance->write_log_entry('FETCH AFTER LOGIN', $test_count++, true);
        }
        
        $before_user = $test_harness_instance->sdk_instance->get_user_info(__TEST_36_USER_ID_3__);
        $after_user = NULL;
        
        if (($before_user instanceof RVP_PHP_SDK_User)) {
            $test_harness_instance->write_log_entry('FETCH BEFORE USER', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH BEFORE USER', $test_count++, false);
            echo('<h4 style="color:red">INVALID BEFORE USER!</h4>');
        }
        
        $before_login = $test_harness_instance->sdk_instance->get_login_info(__TEST_36_LOGIN_ID_3__);
        $after_login = NULL;
        
        if (($before_login instanceof RVP_PHP_SDK_Login)) {
            $test_harness_instance->write_log_entry('FETCH BEFORE LOGIN', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH BEFORE LOGIN', $test_count++, false);
            echo('<h4 style="color:red">INVALID BEFORE USER!</h4>');
        }
        
        $success = $test_harness_instance->sdk_instance->delete_user($before_login);
        
        if ($success) {
            $test_harness_instance->write_log_entry('BASIC DELETE SUCCESS CHECK', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('BASIC DELETE SUCCESS CHECK', $test_count++, false);
            echo('<h4 style="color:red">FAILED BASIC USER DELETE!</h4>');
        }
        
        $after_user = $test_harness_instance->sdk_instance->get_user_info(__TEST_36_USER_ID_3__);
        
        if (($after_user instanceof RVP_PHP_SDK_User)) {
            $test_harness_instance->write_log_entry('FETCH AFTER USER', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH AFTER USER', $test_count++, false);
            echo('<h4 style="color:red">USER SHOULD STILL BE THERE!</h4>');
        }
        
        $after_login = $test_harness_instance->sdk_instance->get_login_info(__TEST_36_LOGIN_ID_3__);
        
        if (($after_login instanceof RVP_PHP_SDK_Login)) {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH AFTER LOGIN', $test_count++, false);
            echo('<h4 style="color:red">LOGIN SHOULD BE GONE!</h4>');
        } else {
            $test_harness_instance->write_log_entry('FETCH AFTER LOGIN', $test_count++, true);
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
?>