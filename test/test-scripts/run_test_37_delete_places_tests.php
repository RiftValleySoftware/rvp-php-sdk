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
defined('__TEST_37_PLACE_ID_1__') or define('__TEST_37_PLACE_ID_1__', 2);
defined('__TEST_37_PLACE_ID_2__') or define('__TEST_37_PLACE_ID_2__', 3);

function run_test_37_delete_places_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance) && $test_harness_instance->sdk_instance->valid()) {
        $test_harness_instance->write_log_entry('INSTANTIATION CHECK', $test_count++, true);
        
        $before_place = $test_harness_instance->sdk_instance->get_place_info(__TEST_37_PLACE_ID_1__);
        
        if (($before_place instanceof RVP_PHP_SDK_Place)) {
            $test_harness_instance->write_log_entry('FETCH BEFORE PLACE', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH BEFORE PLACE', $test_count++, false);
            echo('<h4 style="color:red">INVALID BEFORE PLACE!</h4>');
        }
        
        $success = $test_harness_instance->sdk_instance->delete_place(__TEST_37_PLACE_ID_1__);
        
        if ($success) {
            $test_harness_instance->write_log_entry('SIMPLE ID DELETE', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('SIMPLE ID DELETE', $test_count++, false);
            echo('<h4 style="color:red">DELETE FAILED!</h4>');
        }
        
        $after_place = $test_harness_instance->sdk_instance->get_place_info(__TEST_37_PLACE_ID_1__);
        
        if (($after_place instanceof RVP_PHP_SDK_Place)) {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH AFTER PLACE', $test_count++, false);
            echo('<h4 style="color:red">THERE SHOULD BE NO AFTER PLACE!</h4>');
        } else {
            $test_harness_instance->write_log_entry('FETCH AFTER PLACE', $test_count++, true);
        }
        
        $before_place = $test_harness_instance->sdk_instance->get_place_info(__TEST_37_PLACE_ID_2__);
        $after_place = NULL;
        
        if (($before_place instanceof RVP_PHP_SDK_Place)) {
            $test_harness_instance->write_log_entry('FETCH BEFORE PLACE', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH BEFORE PLACE', $test_count++, false);
            echo('<h4 style="color:red">INVALID BEFORE PLACE!</h4>');
        }
        
        $success = $test_harness_instance->sdk_instance->delete_place($before_place);
        
        if ($success) {
            $test_harness_instance->write_log_entry('OBJECT DELETE', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('OBJECT DELETE', $test_count++, false);
            echo('<h4 style="color:red">DELETE FAILED!</h4>');
        }
        
        $after_place = $test_harness_instance->sdk_instance->get_place_info(__TEST_37_PLACE_ID_2__);
        
        if (($after_place instanceof RVP_PHP_SDK_Place)) {
            $all_pass = false;
            $test_harness_instance->write_log_entry('FETCH AFTER PLACE', $test_count++, false);
            echo('<h4 style="color:red">THERE SHOULD BE NO AFTER PLACE!</h4>');
        } else {
            $test_harness_instance->write_log_entry('FETCH AFTER PLACE', $test_count++, true);
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