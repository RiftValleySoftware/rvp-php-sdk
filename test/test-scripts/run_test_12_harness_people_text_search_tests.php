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
define('__EMPTY_SHA__', '8739602554c7f3241958e3cc9b57fdecb474d508');
function run_test_12_harness_people_text_search_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "name", "MDAdmin", '3d2888a118b13049c051332eec96a981757c9cf7');
            $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "name", "%Admin", '074145a54b30debe1ec632492a47f254fb0c8716');
            $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "name", "%", '3f7b8f55b6953d856f7b28f1a3bf04fc1d5dc297');
            $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "name", "", __EMPTY_SHA__);
            
            $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "surname", "Garcia", '3dd7a14c8b9ecf5de591c0ca449fe3b5296fa0e2');
            $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "surname", "gar%", '3dd7a14c8b9ecf5de591c0ca449fe3b5296fa0e2');
            $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "surname", "%", '3dd7a14c8b9ecf5de591c0ca449fe3b5296fa0e2');
            $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "surname", "", '074145a54b30debe1ec632492a47f254fb0c8716');

            echo('<h4>Logging In PHB.</h4>');
            if ($test_harness_instance->sdk_instance->login('PHB', 'CoreysGoryStory', CO_Config::$session_timeout_in_seconds)) {
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "middle_name", "De", 'f8ba245520a51143ce09632e5b8fb40349171400');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "middle_name", "%e", 'e65abc173f99fda616cb1d92a1247e84a0ee62ec');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "middle_name", "%", '8641f52b20ab9cb2af7b9f08e88b7a021c1fde2b');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "middle_name", "", '0df9793e745b866a397fc3a5a8ddc534bd95a7e8');
                
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "given_name", "Dilbert", '3fcc96ef6f25923e4b9639af26f798216056c701');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "given_name", "%ert", '5f02cd1fea777a57534d595ade1f65d70bce46c1');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "given_name", "%", '7376ddcde8ab6f4ada0e95090001cb9be1dc46b4');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "given_name", "", '7cf9f63ca549206fdfddedf2e580f562b645cf52');
                
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "nickname", "Marquis", 'c8ecc69b7f6c427f492e42fa883f16529186d66c');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "nickname", "%U%", 'e07413aab747637b9c5d1b85656c4520df0cca52');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "nickname", "%", 'e07413aab747637b9c5d1b85656c4520df0cca52');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "nickname", "", '15ae4e371ef303fe38df12b4420ce4bf8e7c3d17');
                
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "prefix", "Sir", 'f8ba245520a51143ce09632e5b8fb40349171400');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "prefix", "%45", 'c9029db9fb7f0e40f4a22b7aa918465b45f5bbd8');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "prefix", "%", 'e07413aab747637b9c5d1b85656c4520df0cca52');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "prefix", "", '15ae4e371ef303fe38df12b4420ce4bf8e7c3d17');
                
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "suffix", "Esq.", 'f8ba245520a51143ce09632e5b8fb40349171400');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "suffix", "%i%", 'c9029db9fb7f0e40f4a22b7aa918465b45f5bbd8');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "suffix", "%", 'e07413aab747637b9c5d1b85656c4520df0cca52');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "suffix", "", '15ae4e371ef303fe38df12b4420ce4bf8e7c3d17');
                
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "tag7", "Kat", '5540bebaeba9cd516695f60b121433768aa4159b');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "tag7", "%a%", 'be1006d4ed6cc7cdf41e7c037de65242715fa01f');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "tag7", "%", 'e07413aab747637b9c5d1b85656c4520df0cca52');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "tag7", "", '15ae4e371ef303fe38df12b4420ce4bf8e7c3d17');
                
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "tag8", "Dilbert Co.", '7376ddcde8ab6f4ada0e95090001cb9be1dc46b4');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "tag8", "%o.", '7376ddcde8ab6f4ada0e95090001cb9be1dc46b4');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "tag8", "%", '7376ddcde8ab6f4ada0e95090001cb9be1dc46b4');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "tag8", "", '7cf9f63ca549206fdfddedf2e580f562b645cf52');
                
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "tag9", "Engineering", '14bf7d3c41f89919d217380dbee6d974fd7cd63d');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "tag9", "TAG-9-TEST-%.", '8739602554c7f3241958e3cc9b57fdecb474d508');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "tag9", "%", 'bec80a39ad54c2ffb4e79cd0bdb1a08ed42afce7');
                $all_pass = run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, $test_count, "tag9", "", '9ae1af4c86c66dfec64e07ec736ab3d9f91ad353');
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('LOGIN ATTEMPT', $test_count++, false);
                echo('<h4 style="color:red">LOGIN NOT SUCCESSFUL!</h4>');
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

function run_test_12_harness_people_text_search_tests_tag_test($test_harness_instance, &$test_count, $in_name, $in_value, $in_sha) {
    echo('<h4>Searching '.htmlspecialchars($in_name).' for "'.htmlspecialchars($in_value).'".</h4>');
    $people = $test_harness_instance->sdk_instance->people_text_search([$in_name => $in_value]);
    $dump = [];
    if (isset($people) && is_array($people) && count($people)) {
        foreach ($people as $person) {
            $dump[] = ['id' => $person->id(), 'type' => get_class($person), 'name' => $person->name()];
        }
    }
    echo('<p><strong>SHA:</strong> <big><code>'.htmlspecialchars(print_r(sha1(serialize($dump)), true)).'</code></big></p>');
// echo('<p><strong>RESPONSE:</strong> <pre>'.htmlspecialchars(print_r($dump, true)).'</pre></p>');
    if ($in_sha == sha1(serialize($dump))) {
        echo('<h4 style="color:green">Success!</h4>');
        $test_harness_instance->write_log_entry('People Text '.$in_name.' Search for "'.$in_value.'".', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('People Text '.$in_name.' Search for "'.$in_value.'".', $test_count++, false);
        echo('<h4 style="color:red">USER NOT VALID!</h4>');
    }
}
?>