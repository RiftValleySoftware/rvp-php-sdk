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

function run_test_07_harness_location_obfuscation_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            $standard_tests = run_test_07_harness_location_obfuscation_tests_load_locations($test_harness_instance->sdk_instance);
            $test_count = run_test_07_harness_location_obfuscation_tests_evaluate_results($test_harness_instance, $standard_tests, $test_count, $all_pass);
            foreach (__TEST_LOGINS__ as $category => $list) {
                echo('<h5>'.$category.':</h5>');
                $timeout = ('God User' != $category) ? CO_Config::$session_timeout_in_seconds : CO_Config::$god_session_timeout_in_seconds;
                foreach ($list as $login_id) {
                    echo('<h6>Logging In '.$login_id.':</h6>');
                    $temp_sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__, $login_id, __PASSWORD__, $timeout);
                    if ($temp_sdk_instance->is_logged_in()) {
                        $logged_in_tests = run_test_07_harness_location_obfuscation_tests_load_locations($temp_sdk_instance);
                        $test_count = run_test_07_harness_location_obfuscation_tests_evaluate_results($test_harness_instance, $logged_in_tests, $test_count, $all_pass);
                        $temp_sdk_instance->logout();
                    } else {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('LOGIN FAILURE FOR "'.$login_id.'"!', $test_count++, false);
                        echo('<h4 style="color:red">LOGIN FAILURE FOR "'.$login_id.'"!</h4>');
                        break;
                    }
                    $temp_sdk_instance = NULL;
                }
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

function run_test_07_harness_location_obfuscation_tests_load_locations($sdk_instance) {
    $ids = __TEST_07_IDS__;
    $ret = [];

    foreach ($ids as $id) {
        for ($c = 0; $c < 3; $c++) {
            $object = $sdk_instance->get_place_info($id);
            
            if (isset($object) && ($object instanceof RVP_PHP_SDK_Place)) {
                $ret[$id][$c]['id'] = $id;
                $ret[$id][$c]['coords'] = $object->coords();
                $raw_coords = $object->raw_coords();
                if (isset($raw_coords) && is_array($raw_coords)) {
                    $distance = RVP_PHP_SDK_Test_Harness::get_accurate_distance($raw_coords['latitude'], $raw_coords['longitude'], $ret[$id][$c]['coords']['latitude'], $ret[$id][$c]['coords']['longitude']);
                    $ret[$id][$c]['raw_coords'] = $raw_coords;
                    $ret[$id][$c]['distance'] = $distance;
                }
            } else {
                break;
            }
        }
    }
    
    return $ret;
}

function run_test_07_harness_location_obfuscation_tests_evaluate_results($test_harness_instance, $results, $test_count, &$all_pass) {
    foreach ($results as $location) {
        $test_long = [];
        $test_lat = [];
        $test_id = [];
        $test_raw_long = [];
        $test_raw_lat = [];
        $test_distance = [];
        $count = 0;
        $key = $location[0]['id'];
        
        foreach ($location as $result) {
            if ($result['coords']) {
                $count++;
                $test_long[] = floatval($result['coords']['longitude']);
                $test_lat[] = floatval($result['coords']['latitude']);
            }
            
            if (isset($result['raw_coords'])) {
                $test_raw_long[] = floatval($result['raw_coords']['longitude']);
                $test_raw_lat[] = floatval($result['raw_coords']['latitude']);
            }
            
            if (isset($result['distance'])) {
                $test_distance[] = floatval($result['distance']);
            }
        }
    
        if (3 == $count) {
            $different_long_lats = (($test_long[0] != $test_long[1]) || ($test_lat[0] != $test_lat[1])) && (($test_long[0] != $test_long[2]) || ($test_lat[0] != $test_lat[2])) && (($test_long[1] != $test_long[2]) || ($test_lat[1] != $test_lat[2]));
        
            if ($different_long_lats) {
                $test_harness_instance->write_log_entry('OBFUSCATED COORDINATES FOR PLACE ID '.$key, $test_count++, true);
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('OBFUSCATED COORDINATES FOR PLACE ID '.$key, $test_count++, false);
                echo('<h4 style="color:red">COORDINATES THE SAME!</h4>');
            }
        
            if (count($test_raw_long) && count($test_raw_lat)) {
                $same_raw_data = ($test_raw_long[0] == $test_raw_long[1]) && ($test_raw_lat[0] == $test_raw_lat[1]) && ($test_raw_long[0] == $test_raw_long[2]) && ($test_raw_lat[0] == $test_raw_lat[2]) && ($test_raw_long[1] == $test_raw_long[2]) && ($test_raw_lat[1] == $test_raw_lat[2]);
                if ($same_raw_data) {
                    $test_harness_instance->write_log_entry('CONSISTENT RAW LOCATION FOR PLACE ID '.$key, $test_count++, true);
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('CONSISTENT RAW LOCATION FOR PLACE ID '.$key, $test_count++, false);
                    echo('<h4 style="color:red">RAW COORDINATES ARE NOT THE SAME FOR PLACE ID '.$key.'!</h4>');
                }
            }
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('TEST SAMPLE COUNT', $test_count++, false);
            echo('<h4 style="color:red">INCORRECT SAMPLE COUNT!</h4>');
        }
    }
    
    return $test_count;
}
?>