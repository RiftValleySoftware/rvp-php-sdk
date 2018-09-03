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
defined('__TEST_38_THING_ID_1__') or define('__TEST_38_THING_ID_1__', 1732);
defined('__TEST_38_THING_ID_2__') or define('__TEST_38_THING_ID_2__', 1733);
defined('__TEST_38_THING_ID_3__') or define('__TEST_38_THING_ID_3__', 'basalt-test-0171: Top Shot');

function run_test_38_delete_things_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance) && $test_harness_instance->sdk_instance->valid()) {
        $test_harness_instance->write_log_entry('INSTANTIATION CHECK', $test_count++, true);
        
        $before_thing = $test_harness_instance->sdk_instance->get_thing_info(__TEST_38_THING_ID_1__);
        
        if (($before_thing instanceof RVP_PHP_SDK_Thing)) {
            $test_harness_instance->write_log_entry('FETCH BEFORE THING', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH BEFORE THING', $test_count++, false);
            echo('<h4 style="color:red">INVALID BEFORE THING!</h4>');
        }
        
        $success = $test_harness_instance->sdk_instance->delete_thing(__TEST_38_THING_ID_1__);
        
        if ($success) {
            $test_harness_instance->write_log_entry('SIMPLE ID DELETE', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('SIMPLE ID DELETE', $test_count++, false);
            echo('<h4 style="color:red">DELETE FAILED!</h4>');
        }
        
        $after_thing = $test_harness_instance->sdk_instance->get_thing_info(__TEST_38_THING_ID_1__);
        
        if (($after_thing instanceof RVP_PHP_SDK_Thing)) {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH AFTER THING', $test_count++, false);
            echo('<h4 style="color:red">THERE SHOULD BE NO AFTER THING!</h4>');
        } else {
            $test_harness_instance->write_log_entry('FETCH AFTER THING', $test_count++, true);
        }
        
        $before_thing = $test_harness_instance->sdk_instance->get_thing_info(__TEST_38_THING_ID_2__);
        $after_thing = NULL;
        
        if (($before_thing instanceof RVP_PHP_SDK_Thing)) {
            $test_harness_instance->write_log_entry('FETCH BEFORE THING', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH BEFORE THING', $test_count++, false);
            echo('<h4 style="color:red">INVALID BEFORE THING!</h4>');
        }
        
        $success = $test_harness_instance->sdk_instance->delete_thing($before_thing);
        
        if ($success) {
            $test_harness_instance->write_log_entry('OBJECT DELETE', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('OBJECT DELETE', $test_count++, false);
            echo('<h4 style="color:red">DELETE FAILED!</h4>');
        }
        
        $after_thing = $test_harness_instance->sdk_instance->get_thing_info(__TEST_38_THING_ID_2__);
        
        if (($after_thing instanceof RVP_PHP_SDK_Thing)) {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH AFTER THING', $test_count++, false);
            echo('<h4 style="color:red">THERE SHOULD BE NO AFTER THING!</h4>');
        } else {
            $test_harness_instance->write_log_entry('FETCH AFTER THING', $test_count++, true);
        }

        $before_thing = $test_harness_instance->sdk_instance->get_thing_info(__TEST_38_THING_ID_3__);
        
        if (($before_thing instanceof RVP_PHP_SDK_Thing)) {
            $test_harness_instance->write_log_entry('FETCH BEFORE THING', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH BEFORE THING', $test_count++, false);
            echo('<h4 style="color:red">INVALID BEFORE THING!</h4>');
        }
        
        $success = $test_harness_instance->sdk_instance->delete_thing(__TEST_38_THING_ID_3__);
        
        if ($success) {
            $test_harness_instance->write_log_entry('SIMPLE ID DELETE', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('SIMPLE ID DELETE', $test_count++, false);
            echo('<h4 style="color:red">DELETE FAILED!</h4>');
        }
        
        $after_thing = $test_harness_instance->sdk_instance->get_thing_info(__TEST_38_THING_ID_3__);
        
        if (($after_thing instanceof RVP_PHP_SDK_Thing)) {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH AFTER THING', $test_count++, false);
            echo('<h4 style="color:red">THERE SHOULD BE NO AFTER THING!</h4>');
        } else {
            $test_harness_instance->write_log_entry('FETCH AFTER THING', $test_count++, true);
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