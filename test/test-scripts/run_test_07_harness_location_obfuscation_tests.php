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
            foreach (__TEST_LOGINS__ as $category => $list) {
                echo('<h5>'.$category.':</h5>');
                $timeout = ('God User' != $category) ? CO_Config::$session_timeout_in_seconds : CO_Config::$god_session_timeout_in_seconds;
                foreach ($list as $login_id) {
                    echo('<h6>Logging In '.$login_id.':</h6>');
                    $temp_sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__, $login_id, __PASSWORD__, $timeout);
                    if ($temp_sdk_instance->is_logged_in()) {
                        $logged_in_tests = run_test_07_harness_location_obfuscation_tests_load_locations($temp_sdk_instance);
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
    
echo('<pre>'.htmlspecialchars(print_r($ret, true)).'</pre>');
    return $ret;
}
?>