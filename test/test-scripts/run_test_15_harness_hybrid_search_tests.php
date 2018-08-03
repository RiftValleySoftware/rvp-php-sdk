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
defined('__EMPTY_SHA__') or define('__EMPTY_SHA__', '8739602554c7f3241958e3cc9b57fdecb474d508');
function run_test_15_harness_hybrid_search_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            $all_pass = run_test_15_harness_hybrid_search_tests_tag_test($test_harness_instance, $test_count, "name", "Worth Enough", '8eb06181b85e8b15dfd256e3195832349cd353d4');
            $all_pass = run_test_15_harness_hybrid_search_tests_tag_test($test_harness_instance, $test_count, "name", "%o%", '3088defab6a1fa46641918eb559d12f06933c908');
            $all_pass = run_test_15_harness_hybrid_search_tests_tag_test($test_harness_instance, $test_count, "name", "%", '18caf3087d7b0c556a06e71aa7a1cb463e08b426');
            $all_pass = run_test_15_harness_hybrid_search_tests_tag_test($test_harness_instance, $test_count, "name", "", __EMPTY_SHA__);
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

function run_test_15_harness_hybrid_search_tests_tag_test($test_harness_instance, &$test_count, $in_name, $in_value, $in_sha) {
    $all_pass = true;
    
    echo('<h4>Searching '.htmlspecialchars($in_name).' for "'.htmlspecialchars($in_value).'".</h4>');
    $things = $test_harness_instance->sdk_instance->things_text_search([$in_name => $in_value]);
    $dump = [];
    if (isset($things) && is_array($things) && count($things)) {
        foreach ($things as $thing) {
            $dump[] = ['id' => $thing->id(), 'type' => get_class($thing), 'name' => $thing->name()];
        }
    }
    echo('<p><strong>SHA:</strong> <big><code>'.htmlspecialchars(print_r(sha1(serialize($dump)), true)).'</code></big></p>');
// echo('<p><strong>RESPONSE:</strong> <pre>'.htmlspecialchars(print_r($dump, true)).'</pre></p>');
    if ($in_sha == sha1(serialize($dump))) {
        echo('<h4 style="color:green">Success!</h4>');
        $test_harness_instance->write_log_entry('Things Text '.$in_name.' Search for "'.$in_value.'".', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('Things Text '.$in_name.' Search for "'.$in_value.'".', $test_count++, false);
        echo('<h4 style="color:red">THING NOT VALID!</h4>');
    }
    
    return $all_pass;     
}
?>