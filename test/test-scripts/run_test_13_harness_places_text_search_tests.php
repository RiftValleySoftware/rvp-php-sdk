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
function run_test_13_harness_places_text_search_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "name", "Just for Today", 'aa77c7924f774fb7bb6d6b74d39713f851308f1b');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "name", "Just % Today%", '58d50a6925927c32b5523d85592afaf3f4d9682b');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "name", "%", 'afcd5a0e66ecb96cdb692b9bfa0deeb73ac902cb');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "name", "", __EMPTY_SHA__);
        
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "venue", "Grace United Methodist Church", '27f811696812577f37721c69d5cdc0a2cae9eeac');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "venue", "Grace % Church%", '0073db8cb020c59444887a9200f09d384f7a1262');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "venue", "%", '1b63d7d335b01745a8d3bc5c9848d7d4f52c8a78');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "venue", "", 'acaf5f190dbe45ca8706dea24b9177ce56d0d47e');
        
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "street_address", "123 Main Street", '8739602554c7f3241958e3cc9b57fdecb474d508');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "street_address", "123 Main St%", '58c5e0602dbb96500ea5af4d59a817159f5fdb2a');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "street_address", "%", '02cbe54efbc656b5bb50748efe570643ba95fb29');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "street_address", "", '55ff813f2b37ee1ba7008c34407c781f779b87af');
        
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "extra_information", "TEST_EXTRA_INFO-2", '3e94f1c35d3a4ee1e9d5ae8fc9140a9dd121b557');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "extra_information", "TEST_EXTRA_INFO-%", '0fd9598a12f75f7100375bc1778c24c3ab7fcb32');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "extra_information", "%", '171635dc7659ed969c92e95f83d96957a183d2de');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "extra_information", "", '921b29515121e2ce10375e69db16d8b88290cfb8');
        
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "town", "Dundalk", '023b70be38a6d478a0068ff5572938e22dec0b24');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "city", "Du%", '6b7cd0411bb21d618181ec6600e0ec17a55aa746');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "town", "%", '2bb7aa45949e60fdfd3b1d2fe5579789c53b1270');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "city", "", '533285dcf74a5ae4fa57e3bdff96f2aebc1aede7');
        
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "county", "Montgomery", 'a6e4d4619c318bc3b588b5fdccbc69f1e78b111f');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "county", "Montgomery%", '81106845f88d2c3f0b4fe83b5128658b82e49fd1');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "county", "%", '4fc49049efed0c1d121c8331e40d3ef17cf84b49');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "county", "", '61d59c5a0433f6401fab31e4ca79ee89e487c7c6');
        
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "state", "DE", '52084b9741e927bf6ea692bf2749cc999aaf9978');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "province", "D%", '32d5e5186645fa13a9fd6b8845f994db4b0fbe07');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "state", "%", 'afcd5a0e66ecb96cdb692b9bfa0deeb73ac902cb');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "province", "", __EMPTY_SHA__);
        
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "postal_code", "20001", 'ef6db1067897737a284ac44ecb876819bc89306f');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "zip_code", "2000%", '58f710e52f46ef228c654cc80763962ecb3afa28');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "postal_code", "%", '0f7ee4101443b861cf84e22c4518169be4c18fc2');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "zip_code", "", '3cf8c8f4828760da0c2e88b181971e45522c76dd');
        
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "nation", "USA", 'afcd5a0e66ecb96cdb692b9bfa0deeb73ac902cb');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "nation", "U%", 'afcd5a0e66ecb96cdb692b9bfa0deeb73ac902cb');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "nation", "%", 'afcd5a0e66ecb96cdb692b9bfa0deeb73ac902cb');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "nation", "", __EMPTY_SHA__);
        
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "tag8", "12:00:00", 'e735d8ff1e9c12530314cc32ad270960379d4753');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "tag8", "12:%", 'af259aa278594312da567375beafb87b5ea884b0');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "tag8", "%", 'afcd5a0e66ecb96cdb692b9bfa0deeb73ac902cb');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "tag8", "", __EMPTY_SHA__);
        
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "tag9", "TEST_TAG-9-2", '3e94f1c35d3a4ee1e9d5ae8fc9140a9dd121b557');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "tag9", "TEST_TAG-9-2%", '3e94f1c35d3a4ee1e9d5ae8fc9140a9dd121b557');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "tag9", "%", '0fd9598a12f75f7100375bc1778c24c3ab7fcb32');
            $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "tag9", "", 'aefb57fbaea108857726ed2c6575edaeb2004214');

            echo('<h4>Logging In MainAdmin.</h4>');
            if ($test_harness_instance->sdk_instance->login('MainAdmin', 'CoreysGoryStory', CO_Config::$session_timeout_in_seconds)) {
                $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "venue", "Grace United Methodist Church", '27f811696812577f37721c69d5cdc0a2cae9eeac');
                $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "venue", "Grace % Church%", '36812003835daa567b0ef9f433c6516563685d22');
                $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "venue", "%", 'ab5bd4ed299d7ebbec7130020c83769c7c9cd7f9');
                $all_pass &= run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, $test_count, "venue", "", 'cd4687986ec9088429a001cdf49a2e97b41550ae');
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

function run_test_13_harness_places_text_search_tests_tag_test($test_harness_instance, &$test_count, $in_name, $in_value, $in_sha) {
    $all_pass = true;
    
    echo('<h4>Searching '.htmlspecialchars($in_name).' for "'.htmlspecialchars($in_value).'".</h4>');
    $places = $test_harness_instance->sdk_instance->places_text_search([$in_name => $in_value]);
    $dump = [];
    if (isset($places) && is_array($places) && count($places)) {
        foreach ($places as $place) {
            $dump[] = ['id' => $place->id(), 'type' => get_class($place), 'name' => $place->name()];
        }
    }
    echo('<p><strong>SHA:</strong> <big><code>'.htmlspecialchars(print_r(sha1(serialize($dump)), true)).'</code></big></p>');
// echo('<p><strong>RESPONSE:</strong> <pre>'.htmlspecialchars(print_r($dump, true)).'</pre></p>');
    if ($in_sha == sha1(serialize($dump))) {
        echo('<h4 style="color:green">Success!</h4>');
        $test_harness_instance->write_log_entry('Places Text '.$in_name.' Search for "'.$in_value.'".', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('Places Text '.$in_name.' Search for "'.$in_value.'".', $test_count++, false);
        echo('<h4 style="color:red">PLACE NOT VALID!</h4>');
    }
    
    return $all_pass;     
}
?>