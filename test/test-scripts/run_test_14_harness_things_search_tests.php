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
function run_test_14_harness_things_search_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "name", "Worth Enough", '8eb06181b85e8b15dfd256e3195832349cd353d4');
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "name", "%o%", '3088defab6a1fa46641918eb559d12f06933c908');
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "name", "%", '18caf3087d7b0c556a06e71aa7a1cb463e08b426');
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "name", "", __EMPTY_SHA__);
            
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "description", "IMagE", 'fc89e25a33d10ffa34a7139170ea3fec0f43a2e5');
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "description", "%O", '0de67dffb7b53dbfa06504c5611a8bc9c42bcfe2');
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "description", "%", '18caf3087d7b0c556a06e71aa7a1cb463e08b426');
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "description", "", __EMPTY_SHA__);
            
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "tag2", "TAG-2-TEST-THINGS", '18caf3087d7b0c556a06e71aa7a1cb463e08b426');
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "tag2", "TAG-2-TEST-%", '18caf3087d7b0c556a06e71aa7a1cb463e08b426');
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "tag2", "%", '18caf3087d7b0c556a06e71aa7a1cb463e08b426');
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "tag2", "", __EMPTY_SHA__);
            
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "tag3", "TAG-3-TEST-THINGS-1733", 'd3d141250965328f0695697b95752360b2e894bf');
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "tag3", "TAG-3-TEST-THINGS-%", '18caf3087d7b0c556a06e71aa7a1cb463e08b426');
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "tag3", "%", '18caf3087d7b0c556a06e71aa7a1cb463e08b426');
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "tag3", "", __EMPTY_SHA__);
            
            // We skip the in-betweens, because the test set does not have them.
            
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "tag9", "TAG-9-TEST-THINGS", '19131f6963f8e94b4a98722ada70357ddb951947');
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "tag9", "TAG-9-TEST-%", '19131f6963f8e94b4a98722ada70357ddb951947');
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "tag9", "%", '19131f6963f8e94b4a98722ada70357ddb951947');
            $all_pass &= run_test_14_harness_things_search_tests_tag_test($test_harness_instance, $test_count, "tag9", "", '7aeb69b634d6f1921a2c9f18c8a331af943201ae');
        } else {
            $all_pass &= false;
            $test_harness_instance->write_log_entry('VALIDITY CHECK', $test_count++, false);
            echo('<h4 style="color:red">SERVER NOT VALID!</h4>');
        }
    } else {
        $all_pass &= false;
        $test_harness_instance->write_log_entry('INSTANTIATION CHECK', $test_count++, false);
        echo('<h4 style="color:red">NO SDK INSTANCE!</h4>');
    }
    
    if ($all_pass) {
        echo('<h4 style="color:green">TEST SUCCESSFUL!</h4>');
    }
    
    $test_harness_instance->test_count = $test_count;
    
    return $all_pass;     
}

function run_test_14_harness_things_search_tests_tag_test($test_harness_instance, &$test_count, $in_name, $in_value, $in_sha) {
    $all_pass = true;
    
    echo('<h4>Searching '.htmlspecialchars($in_name).' for "'.htmlspecialchars($in_value).'".</h4>');
    $things = $test_harness_instance->sdk_instance->things_search([$in_name => $in_value]);
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