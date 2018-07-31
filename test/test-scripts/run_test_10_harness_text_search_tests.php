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
define('__SEARCH_SPEC_3__', ['name' => 'Just for Today']);
define('__RESULTS_3__', [['id' => 36, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just For Today'], ['id' => 126, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just For Today'], ['id' => 252, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just For Today'], ['id' => 907, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just For Today'], ['id' => 909, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just For Today'], ['id' => 1124, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just For Today'], ['id' => 1356, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just For Today'], ['id' => 1387, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just for Today'], ['id' => 1462, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just For Today'], ['id' => 1676, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just For Today'], ['id' => 1680, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just for Today']]);
define('__SEARCH_SPEC_4__', ['name' => 'Just % Today%']);
define('__RESULTS_4__', [['id' => 36, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just For Today'], ['id' => 126, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just For Today'], ['id' => 252, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just For Today'], ['id' => 907, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just For Today'], ['id' => 909, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just For Today'], ['id' => 1000, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just 4 Today'], ['id' => 1124, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just For Today'], ['id' => 1353, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just For Today Group'], ['id' => 1356, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just For Today'], ['id' => 1387, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just for Today'], ['id' => 1462, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just For Today'], ['id' => 1676, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just For Today'], ['id' => 1680, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just for Today']]);

function run_test_10_harness_text_search_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            echo('<h4>Do A Search for Records with the EXACT Name "Just For Today".</h4>');
            $results = $test_harness_instance->sdk_instance->general_text_search(__SEARCH_SPEC_3__);
            $dump = [];
            foreach ($results as $node) {
                $dump[] = ['id' => $node->id(), 'type' => get_class($node), 'name' => $node->name()];
            }
            if (RVP_PHP_SDK_Test_Harness::are_arrays_equal(__RESULTS_3__, $dump)) {
                $test_harness_instance->write_log_entry('Simple Baseline General Exact Text Search', $test_count++, true);
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('Simple Baseline General Exact Text Search', $test_count++, false);
                echo('<h4 style="color:red">RESPONSE DATA INVALID!</h4>');
            }
            
            echo('<h4>Do A Search for Records with the Wildcard Name "Just % Today%".</h4>');
            $results = $test_harness_instance->sdk_instance->general_text_search(__SEARCH_SPEC_4__);
            $dump = [];
            foreach ($results as $node) {
                $dump[] = ['id' => $node->id(), 'type' => get_class($node), 'name' => $node->name()];
            }

            if (RVP_PHP_SDK_Test_Harness::are_arrays_equal(__RESULTS_4__, $dump)) {
                $test_harness_instance->write_log_entry('Simple Baseline General Wildcard Text Search', $test_count++, true);
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('Simple Baseline General Wildcard Text Search', $test_count++, false);
                echo('<h4 style="color:red">RESPONSE DATA INVALID!</h4>');
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