<?php
/***************************************************************************************************************************/
/**
    BAOBAB PHP SDK
    
    © Copyright 2018, Little Green Viper Software Development LLC.
    
    This code is proprietary and confidential code, 
    It is NOT to be reused or combined into any application,
    unless done so, specifically under written license from Little Green Viper Software Development LLC.

    Little Green Viper Software Development: https://littlegreenviper.com
*/
define('__SEARCH_SPEC_3__', ['name' => 'Just for Today']);
function run_test_10_harness_text_search_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            echo('<h4></h4>');
            $results = $test_harness_instance->sdk_instance->general_text_search(__SEARCH_SPEC_3__);
            $dump = [];
            foreach ($results as $node) {
                $dump[] = ['id' => $node->id(), 'type' => get_class($node), 'name' => $node->name()];
            }
echo('<pre>'.htmlspecialchars(print_r($dump, true)).'</pre>');            
//             $expected_results = [];
// 
//             if (RVP_PHP_SDK_Test_Harness::are_arrays_equal($expected_results, $dump)) {
//                 $test_harness_instance->write_log_entry('Simple Location General Radius Search', $test_count++, true);
//             } else {
//                 $all_pass = false;
//                 $test_harness_instance->write_log_entry('Simple Location General Radius Search', $test_count++, false);
//                 echo('<h4 style="color:red">RESPONSE DATA INVALID!</h4>');
//             }
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