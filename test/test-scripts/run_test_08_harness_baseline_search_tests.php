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
define('__SEARCH_SPEC__', ['latitude' => 38.881317, 'longitude' => -77.036635, 'radius' => 10]);
define('__USER_SEARCH_RESPONSE__', [['id' => 1727, 'type' => 'RVP_PHP_SDK_User', 'name' => 'DCAdmin'], ['id' => 1726, 'type' => 'RVP_PHP_SDK_User', 'name' => 'VAAdmin']]);
define('__LOGIN_SEARCH_RESPONSE__', []);
define('__THING_SEARCH_RESPONSE__', [['id' => 1741, 'type' => 'RVP_PHP_SDK_Thing', 'name' => 'Singing Pete']]);

function run_test_08_harness_baseline_search_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            echo('<h4>Do A Simple Location Radius Search Within 10Km of the Jefferson Memorial, in the Potomac River. This baseline plugin search will return all types of assets.</h4>');
            $results = $test_harness_instance->sdk_instance->general_location_search(__SEARCH_SPEC__);
            $dump = [];
            foreach ($results as $node) {
                $dump[] = ['id' => $node->id(), 'type' => get_class($node), 'name' => $node->name()];
            }
            
            include_once(dirname(__FILE__).'/run_test_08_harness_baseline_location_search_tests_results.php');
            if (RVP_PHP_SDK_Test_Harness::are_arrays_equal($expected_results, $dump)) {
                $test_harness_instance->write_log_entry('Simple Location General Radius Search', $test_count++, true);
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('Simple Location General Radius Search', $test_count++, false);
                echo('<h4 style="color:red">RESPONSE DATA INVALID!</h4>');
            }
            
            echo('<h4>Do A Simple Location Radius Search Within 10Km of the Jefferson Memorial, in the Potomac River. This people plugin search will return only users.</h4>');
            $results = $test_harness_instance->sdk_instance->people_location_search(__SEARCH_SPEC__);
            $dump = [];
            foreach ($results as $node) {
                $dump[] = ['id' => $node->id(), 'type' => get_class($node), 'name' => $node->name()];
            }

            if (RVP_PHP_SDK_Test_Harness::are_arrays_equal(__USER_SEARCH_RESPONSE__, $dump)) {
                $test_harness_instance->write_log_entry('Simple Location User Radius Search', $test_count++, true);
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('Simple Location User Radius Search', $test_count++, false);
                echo('<h4 style="color:red">RESPONSE DATA INVALID!</h4>');
            }
    
            echo('<h4>Do A Simple Location Radius Search Within 10Km of the Jefferson Memorial, in the Potomac River. This people plugin search will return only logins. We should get nothing, as we are not logged in.</h4>');
            $results = $test_harness_instance->sdk_instance->people_location_search(__SEARCH_SPEC__, true);
            $dump = [];
            if (is_array($results) && count($results)) {
                foreach ($results as $node) {
                    $dump[] = ['id' => $node->id(), 'type' => get_class($node), 'name' => $node->name()];
                }
            }
            if (RVP_PHP_SDK_Test_Harness::are_arrays_equal(__LOGIN_SEARCH_RESPONSE__, $dump)) {
                $test_harness_instance->write_log_entry('Simple Location Login Radius Search', $test_count++, true);
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('Simple Location Login Radius Search', $test_count++, false);
                echo('<h4 style="color:red">RESPONSE DATA INVALID!</h4>');
            }
            
            echo('<h4>Do A Simple Location Radius Search Within 10Km of the Jefferson Memorial, in the Potomac River. This places plugin search will return only places.</h4>');
            $results = $test_harness_instance->sdk_instance->place_location_search(__SEARCH_SPEC__);
            $dump = [];
            foreach ($results as $node) {
                $dump[] = ['id' => $node->id(), 'type' => get_class($node), 'name' => $node->name()];
            }
            
            include_once(dirname(__FILE__).'/run_test_08_harness_baseline_location_search_place_tests_results.php');
            if (RVP_PHP_SDK_Test_Harness::are_arrays_equal($expected_results_2, $dump)) {
                $test_harness_instance->write_log_entry('Simple Location Place Radius Search', $test_count++, true);
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('Simple Location place Radius Search', $test_count++, false);
                echo('<h4 style="color:red">RESPONSE DATA INVALID!</h4>');
            }
            
            echo('<h4>Do A Simple Location Radius Search Within 10Km of the Jefferson Memorial, in the Potomac River. This things plugin search will return only things.</h4>');
            $results = $test_harness_instance->sdk_instance->thing_location_search(__SEARCH_SPEC__);
            $dump = [];
            foreach ($results as $node) {
                $dump[] = ['id' => $node->id(), 'type' => get_class($node), 'name' => $node->name()];
            }

            if (RVP_PHP_SDK_Test_Harness::are_arrays_equal(__THING_SEARCH_RESPONSE__, $dump)) {
                $test_harness_instance->write_log_entry('Simple Location Place Radius Search', $test_count++, true);
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('Simple Location place Radius Search', $test_count++, false);
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