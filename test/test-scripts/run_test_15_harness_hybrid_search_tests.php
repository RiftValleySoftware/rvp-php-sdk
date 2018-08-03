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
            $sha = '8eb06181b85e8b15dfd256e3195832349cd353d4';
            $text_search = ['name' => 'Worth Enough'];
            $location = ['longitude' => -75.55162, 'latitude' => 39.74635, 'radius' => 10];
            $logins_only = false;
            
            $search_type = '';
            $all_pass &= $test_harness_instance->hybrid_search_test($test_count, $search_type, $sha, $text_search, $location, $logins_only);
            
            $search_type = 'things';
            $all_pass &= $test_harness_instance->hybrid_search_test($test_count, $search_type, $sha, $text_search, $location, $logins_only);
            
            $text_search = NULL;
            $search_type = 'places';
            $sha = '93ebf32cafc9f54fd5dc55e4a0d00138ca2fd504';
            $all_pass &= $test_harness_instance->hybrid_search_test($test_count, $search_type, $sha, $text_search, $location, $logins_only);
            
            $search_type = '';
            $sha = 'ca524670ba540e59d33de8943b49a4bf60791649';
            $all_pass &= $test_harness_instance->hybrid_search_test($test_count, $search_type, $sha, $text_search, $location, $logins_only);
            
            $search_type = 'things';
            $sha = '8eb06181b85e8b15dfd256e3195832349cd353d4';
            $all_pass &= $test_harness_instance->hybrid_search_test($test_count, $search_type, $sha, $text_search, $location, $logins_only);
            
            $search_type = 'people';
            $sha = 'd571cdb4e29c42180a3b18064e782dc70c3ac293';
            $all_pass &= $test_harness_instance->hybrid_search_test($test_count, $search_type, $sha, $text_search, $location, $logins_only);
            
            $logins_only = true;
            $sha = __EMPTY_SHA__;
            $all_pass &= $test_harness_instance->hybrid_search_test($test_count, $search_type, $sha, $text_search, $location, $logins_only);
            
            echo('<h4>Logging In MainAdmin.</h4>');
            if ($test_harness_instance->sdk_instance->login('MainAdmin', 'CoreysGoryStory', CO_Config::$session_timeout_in_seconds)) {
                $sha = '21da2716b0cf1e82a8e6997c86ff428c390a3e11';
                $all_pass &= $test_harness_instance->hybrid_search_test($test_count, $search_type, $sha, $text_search, $location, $logins_only);
                
                $text_search = ['name' => 'DCAdmin'];
                $logins_only = false;
                $sha = __EMPTY_SHA__;
                $all_pass &= $test_harness_instance->hybrid_search_test($test_count, $search_type, $sha, $text_search, $location, $logins_only);
                
                $text_search = ['name' => 'D%Admin'];
                $sha = 'd571cdb4e29c42180a3b18064e782dc70c3ac293';
                $all_pass &= $test_harness_instance->hybrid_search_test($test_count, $search_type, $sha, $text_search, $location, $logins_only);
                
                $logins_only = true;
                $sha = '21da2716b0cf1e82a8e6997c86ff428c390a3e11';
                $all_pass &= $test_harness_instance->hybrid_search_test($test_count, $search_type, $sha, $text_search, $location, $logins_only);
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