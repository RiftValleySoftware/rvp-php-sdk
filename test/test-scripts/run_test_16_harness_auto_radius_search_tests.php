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
function run_test_16_harness_auto_radius_search_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {

            $sha = 'ca524670ba540e59d33de8943b49a4bf60791649';
            $text_search = [];
            $center_point = ['longitude' => -75.55162, 'latitude' => 39.74635];
            $search_type = '';
            $step_size = 0.1;
            $max_radius = 10;
            $target_number = 60;
            $callback = 'global_scope_auto_radius_callback';
            $debug_display = false;

            $all_pass &= run_test_16_harness_auto_radius_search_tests_auto_radius_search_test($test_harness_instance, $test_count, $center_point, $search_type, $sha, $text_search, $step_size, $max_radius, $target_number, $callback, $debug_display);

            $sha = 'c5301063130660bc756ffb54b8ab1429654a8b9f';
            $text_search = ['name' => 'D%'];
            $search_type = 'people';
            $step_size = 2;
            $max_radius = 500;
            $debug_display = false;
            $callback = [new Auto_Radius_Test($debug_display, 20), 'auto_radius_callback'];

            $all_pass &= run_test_16_harness_auto_radius_search_tests_auto_radius_search_test($test_harness_instance, $test_count, $center_point, $search_type, $sha, $text_search, $step_size, $max_radius, $target_number, $callback, $debug_display);

            $sha = 'a4b45d1197e01a79e2a8dc852b218ecec9f3b28c';
            $text_search = ['venue' => '%Church'];
            $search_type = 'places';
            $target_number = 20;
            $debug_display = false;
            $callback = [new Auto_Radius_Test($debug_display, 30), 'auto_radius_callback'];

            $all_pass &= run_test_16_harness_auto_radius_search_tests_auto_radius_search_test($test_harness_instance, $test_count, $center_point, $search_type, $sha, $text_search, $step_size, $max_radius, $target_number, $callback, $debug_display);

            $sha = '4e10b6bca22b0df1c4750976948435c00ffdfdc7';
            $text_search = ['description' => 'IMAGE'];
            $search_type = 'things';
            $target_number = 3;
            $debug_display = false;
            $callback = [new Auto_Radius_Test($debug_display, 60), 'auto_radius_callback'];

            $all_pass &= run_test_16_harness_auto_radius_search_tests_auto_radius_search_test($test_harness_instance, $test_count, $center_point, $search_type, $sha, $text_search, $step_size, $max_radius, $target_number, $callback, $debug_display);

            $sha = __EMPTY_SHA__;
            $text_search = ['name' => '%V%'];
            $step_size = 11.5;
            $search_type = 'logins';
            $debug_display = false;
            $callback = [new Auto_Radius_Test($debug_display, 10), 'auto_radius_callback'];

            $all_pass &= run_test_16_harness_auto_radius_search_tests_auto_radius_search_test($test_harness_instance, $test_count, $center_point, $search_type, $sha, $text_search, $step_size, $max_radius, $target_number, $callback, $debug_display);

            echo('<h4>Logging In MainAdmin.</h4>');
            if ($test_harness_instance->sdk_instance->login('MainAdmin', 'CoreysGoryStory', CO_Config::$session_timeout_in_seconds)) {
                $step_size = 1;
                $sha = __EMPTY_SHA__;
                $callback[0]->initial_time = time();
                $callback[0]->timeout = 16;
                $all_pass &= run_test_16_harness_auto_radius_search_tests_auto_radius_search_test($test_harness_instance, $test_count, $center_point, $search_type, $sha, $text_search, $step_size, $max_radius, $target_number, $callback, $debug_display);
                $test_harness_instance->sdk_instance->logout();
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

function run_test_16_harness_auto_radius_search_tests_auto_radius_search_test($in_test_harness_instance, &$test_count, $in_center_point, $in_search_type, $in_sha, $in_text_search, $in_step_size = 0.5, $in_max_radius = 500, $in_target_number = 10, $in_callback = NULL, $in_debug_display = false) {
    $all_pass = true;

    if (isset($in_text_search) && is_array($in_text_search) && count($in_text_search)) {
        foreach ($in_text_search as $key => $value) {
            echo('<h4>Searching '.htmlspecialchars($key).' for "'.htmlspecialchars($value).'".</h4>');
        }
    } else {
        $in_text_search = NULL;
    }

    if (isset($in_location) && is_array($in_location) && (3 == count($in_location))) {
        echo('<h4>Search center: ('.$in_center_point['latitude'].', '.$in_center_point['longitude'].').</h4>');
    } else {
        $in_location = NULL;
    }

    $in_test_harness_instance->write_log_entry('Auto-Radius Text/Location Search START', $test_count, true);
    $results = $in_test_harness_instance->sdk_instance->auto_radius_search($in_center_point, $in_target_number, $in_search_type, $in_text_search, $in_step_size, $in_max_radius, $in_callback);
    $in_test_harness_instance->write_log_entry('Auto-Radius Text/Location Search END', $test_count, true);

    $dump = [];
    if (isset($results) && is_array($results) && count($results)) {
        foreach ($results as $result) {
            $dump[] = ['id' => $result->id(), 'type' => get_class($result), 'name' => $result->name()];
        }
    }

    echo('<p><strong>SHA:</strong> <big><code>'.htmlspecialchars(print_r(sha1(serialize($dump)), true)).'</code></big></p>');

    if ($in_debug_display) {
        echo('<p><strong>RESPONSE DATA:</strong> <pre>'.htmlspecialchars(print_r($dump, true)).'</pre></p>');
    }

    if ($in_sha == sha1(serialize($dump))) {
        echo('<h4 style="color:green">Success!</h4>');
        $in_test_harness_instance->write_log_entry('Auto-Radius Text/Location Search.', $test_count++, true);
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('Auto-Radius Text/Location Search.', $test_count++, false);
        echo('<h4 style="color:red">SEARCH RESULTS NOT VALID!</h4>');
    }

    return $all_pass;     
}

function global_scope_auto_radius_callback($in_sdk_instance, $in_results, $in_type, $in_target_number, $in_step_size_in_km, $in_max_width_in_km, $in_location, $in_search_criteria) {
    $ret = false;
    
    echo('<div><h5>GLOBAL CALLBACK: Current Radius: '.$in_location['radius'].', Number of Results: '.count($in_results));
    
    if ($in_max_width_in_km < ($in_location['radius'] + $in_step_size_in_km)) {
        echo ('. -LAST CALL FOR ALCOHOL');
    }
    echo('.</h5></div>');
    
    return $ret;
}

class Auto_Radius_Test {
    var $initial_time;
    var $show_debug;
    var $timeout;
    
    function __construct($in_show_debug, $in_timeout) {
        $this->initial_time = time();
        $this->show_debug = $in_show_debug;
        $this->timeout = $in_timeout;
    }
    
    function auto_radius_callback($in_sdk_instance, $in_results, $in_type, $in_target_number, $in_step_size_in_km, $in_max_width_in_km, $in_location, $in_search_criteria) {
        $ret = $this->timeout < (time() - $this->initial_time);
    
        echo('<div><h5>OBJECT CALLBACK: Current Radius: '.$in_location['radius'].', Number of Results: '.count($in_results));
        
        if ($in_max_width_in_km < ($in_location['radius'] + $in_step_size_in_km)) {
            echo ('. -LAST CALL FOR ALCOHOL.');
        }
        
        if ($ret) {
            echo ('. ABORTING (Too Long).');
        }
        
        echo('.</h5>');
        
        if ($this->show_debug) {
            $dump = [];
            if (isset($in_results) && is_array($in_results) && count($in_results)) {
                foreach ($in_results as $result) {
                    $dump[] = ['id' => $result->id(), 'type' => get_class($result), 'name' => $result->name()];
                }
            }
            echo('<p><strong>RESPONSE DATA:</strong> <pre>'.htmlspecialchars(print_r($dump, true)).'</pre></p>');
        }
        
        echo('</div>');
        
        return $ret;
    }
}
?>