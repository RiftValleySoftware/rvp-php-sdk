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
defined('__TEST_27_ID__') or define('__TEST_27_ID__', 7400);

function run_test_27_place_record_geocode_tests($test_harness_instance) {
    $all_pass = false;
    $test_count = $test_harness_instance->test_count;
    
    if (isset($test_harness_instance->sdk_instance)) {
        $all_pass = true;
        if ($test_harness_instance->sdk_instance->valid()) {
            $record = $test_harness_instance->sdk_instance->get_place_info(__TEST_27_ID__);
            if (isset($record) && ($record instanceof RVP_PHP_SDK_Place)) {
                echo('<h5>Modifying record ID '.__TEST_27_ID__.' ('.$record->name().').</h5>');
                echo('<h6>Basic Address: '.htmlspecialchars($record->basic_address()).'</h6>');
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('FAILED TO GET RECORD '.__TEST_27_ID__, $test_count++, false);
                echo('<h4 style="color:red">FAILED TO GET RECORD '.__TEST_27_ID__.'!</h4>');
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