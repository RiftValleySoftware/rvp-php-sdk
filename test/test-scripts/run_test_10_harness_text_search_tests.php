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
define('__SEARCH_SPEC_5__', ['tag0' => 'Grace United Methodist Church']);
define('__RESULTS_5__', [['id' => 1592, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Hope Not Dope'],['id' => 1721, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Recovery in the AM']]);
define('__SEARCH_SPEC_6__', ['tag0' => 'Grace % Church']);
define('__RESULTS_6__', [['id' => 17, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'You Get What You Need'], ['id' => 158, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Just the Desire'], ['id' => 274, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Lighthouse'], ['id' => 280, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Circle of Hope'], ['id' => 400, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Work Em Or Die'], ['id' => 949, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Last Connection'], ['id' => 1050, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Spiritual Principles'], ['id' => 1278, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'NA @ Mill Creek'], ['id' => 1330, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Steps to Recovery'], ['id' => 1476, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'New Gift Called Life'], ['id' => 1592, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Hope Not Dope'], ['id' => 1621, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Recovery in the AM'], ['id' => 1626, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Recovery in the AM'], ['id' => 1646, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Recovery in the AM'], ['id' => 1704, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Recovery in the AM'], ['id' => 1721, 'type' => 'RVP_PHP_SDK_Place', 'name' => 'Recovery in the AM']]);

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
            
            echo('<h4>Do A Search for Records with Tag 0 of "Grace United Methodist Church".</h4>');
            $results = $test_harness_instance->sdk_instance->general_text_search(__SEARCH_SPEC_5__);
            $dump = [];
            foreach ($results as $node) {
                $dump[] = ['id' => $node->id(), 'type' => get_class($node), 'name' => $node->name()];
            }

            if (RVP_PHP_SDK_Test_Harness::are_arrays_equal(__RESULTS_5__, $dump)) {
                $test_harness_instance->write_log_entry('Simple Baseline General Wildcard Text Search', $test_count++, true);
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('Simple Baseline General Wildcard Text Search', $test_count++, false);
                echo('<h4 style="color:red">RESPONSE DATA INVALID!</h4>');
            }
            
            echo('<h4>Do A Search for Records with Tag 0 Using a Wildcard of "Grace % Church".</h4>');
            $results = $test_harness_instance->sdk_instance->general_text_search(__SEARCH_SPEC_6__);
            $dump = [];
            foreach ($results as $node) {
                $dump[] = ['id' => $node->id(), 'type' => get_class($node), 'name' => $node->name()];
            }

            if (RVP_PHP_SDK_Test_Harness::are_arrays_equal(__RESULTS_6__, $dump)) {
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