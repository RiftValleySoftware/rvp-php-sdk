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
define('__CREATE_FILE__', false);

function run_test_12_harness_people_text_search_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            $all_pass = run_test_12_harness_people_text_search_tests_name_test($test_harness_instance, $test_count);
// echo('RESPONSE:<pre>'.htmlspecialchars(print_r($people, true)).'</pre>');
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

function run_test_12_harness_people_text_search_tests_name_test($test_harness_instance, &$test_count) {
    $all_pass = true;
    
    echo('<h4>Searching for any user named "MDAdmin"</h4>');
    $people = $test_harness_instance->sdk_instance->people_text_search(['name' => 'MDAdmin']);
    if ((1 == count($people)) && (1725 == $people[0]->id()) && ('MDAdmin' == $people[0]->name())) {
        echo('<h4 style="color:green">Success!</h4>');
        $test_harness_instance->write_log_entry('Single Person Exact Name Search', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('Single Person Exact Name Search', $test_count++, false);
        echo('<h4 style="color:red">USER NOT VALID!</h4>');
    }
    
    echo('<h4>Searching for users named "%Admin"</h4>');
    $people = $test_harness_instance->sdk_instance->people_text_search(['name' => '%Admin']);
    if (4 == count($people)) {
        echo('<h4>Checking "MDAdmin"</h4>');
        if ((1725 == $people[0]->id()) && ('MDAdmin' == $people[0]->name())) {
            echo('<h4 style="color:green">Success!</h4>');
            $test_harness_instance->write_log_entry('Multiple Person Wildcard Name Search (MDAdmin)', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('Multiple Person Wildcard Name Search (MDAdmin)', $test_count++, false);
            echo('<h4 style="color:red">PERSON NOT VALID!</h4>');
        }
        
        echo('<h4>Checking "VAAdmin"</h4>');
        if ((1726 == $people[0]->id()) && ('VAAdmin' == $people[0]->name())) {
            echo('<h4 style="color:green">Success!</h4>');
            $test_harness_instance->write_log_entry('Multiple Person Wildcard Name Search (VAAdmin)', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('Multiple Person Wildcard Name Search (VAAdmin)', $test_count++, false);
            echo('<h4 style="color:red">PERSON NOT VALID!</h4>');
        }
        
        echo('<h4>Checking "DCAdmin"</h4>');
        if ((1727 == $people[0]->id()) && ('DCAdmin' == $people[0]->name())) {
            echo('<h4 style="color:green">Success!</h4>');
            $test_harness_instance->write_log_entry('Multiple Person Wildcard Name Search (DCAdmin)', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('Multiple Person Wildcard Name Search (DCAdmin)', $test_count++, false);
            echo('<h4 style="color:red">PERSON NOT VALID!</h4>');
        }
        
        echo('<h4>Checking "WVAdmin"</h4>');
        if ((1728 == $people[0]->id()) && ('WVAdmin' == $people[0]->name())) {
            echo('<h4 style="color:green">Success!</h4>');
            $test_harness_instance->write_log_entry('Multiple Person Wildcard Name Search (WVAdmin)', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('Multiple Person Wildcard Name Search (WVAdmin)', $test_count++, false);
            echo('<h4 style="color:red">PERSON NOT VALID!</h4>');
        }
    }
    
    return $all_pass;
}
?>