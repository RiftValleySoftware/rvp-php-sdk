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
define('__TEST_32_PASSWORD__', 'CoreysGoryStory');
define('__TEST_32_LOGIN_1__', 'MDAdmin');

function run_test_32_child_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            $login_id = __TEST_32_LOGIN_1__;
            $timeout = CO_Config::$session_timeout_in_seconds;
            echo('<h6>Logging In '.$login_id.':</h6>');
            if ($test_harness_instance->sdk_instance->login($login_id, __TEST_32_PASSWORD__, $timeout)) {
                if ($test_harness_instance->sdk_instance->is_logged_in()) {
                    $test_harness_instance->write_log_entry('Log In "'.$login_id.'"', $test_count++, true);
                    $info = $test_harness_instance->sdk_instance->my_info();
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('Verify Log In "'.$login_id.'"', $test_count++, false);
                    echo('<h4 style="color:red">LOGIN DIDN\'T TAKE!</h4>');
                }
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('Log In "'.$login_id.'"', $test_count++, false);
                echo('<h4 style="color:red">LOGIN FAILED!</h4>');
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
?>