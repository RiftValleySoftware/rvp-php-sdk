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
defined('__CSV_TEST_30_FILE__') or define('__CSV_TEST_30_FILE__', 'test-30-small-worth-enough');
function run_test_35_post_things_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance) && $test_harness_instance->sdk_instance->valid()) {
        $test_harness_instance->write_log_entry('INSTANTIATION CHECK', $test_count++, true);
        $file = dirname(__FILE__).'/worth-enough-test-30.png';
        $payload = file_get_contents($file);

        $not_a_new_thing = $test_harness_instance->sdk_instance->new_thing('basalt-test-0171: Worth Enough', $payload);
        
        if (isset($not_a_new_thing)) {
            $all_pass = false;
            $test_harness_instance->write_log_entry('DUPLICATE KEY CHECK', $test_count++, false);
            echo('<h4 style="color:red">THIS SHOULD NOT HAVE SUCCEEDED (DUPLICATE)!</h4>');
        } else {
            $test_harness_instance->write_log_entry('DUPLICATE KEY CHECK', $test_count++, true);
        }

        $has_a_comma_thing = $test_harness_instance->sdk_instance->new_thing('basalt-test-0171,2: Worth Enough', $payload);
        
        if (isset($has_a_comma_thing)) {
            $all_pass = false;
            $test_harness_instance->write_log_entry('BAD (COMMA) KEY CHECK', $test_count++, false);
            echo('<h4 style="color:red">THIS SHOULD NOT HAVE SUCCEEDED (COMMA)!</h4>');
        } else {
            $test_harness_instance->write_log_entry('BAD (COMMA) KEY CHECK', $test_count++, true);
        }
        
        $new_thing = $test_harness_instance->sdk_instance->new_thing('basalt-test-0171: Worth Peanuts', $payload, ['read' => 1, 'write' => 1], 'Black and White Worth Enough', 'A smaller version of \'Worth Enough,\' in greyscale.');
        
        if (isset($new_thing)) {
            $test_harness_instance->write_log_entry('UNIQUE KEY CHECK', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('UNIQUE KEY CHECK', $test_count++, false);
            echo('<h4 style="color:red">THIS SHOULD HAVE SUCCEEDED!</h4>');
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