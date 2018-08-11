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
defined('__TEST_27_ID_1_') or define('__TEST_27_ID_1_', 7400);
defined('__TEST_27_ID_2_') or define('__TEST_27_ID_2_', 7401);
defined('__TEST_27_EXPECTED_REV_ADDR__') or define('__TEST_27_EXPECTED_REV_ADDR__', '401 U.S. 209, Pottsville, PA 17901-2930 US');
defined('__TEST_27_EXPECTED_COORDS__') or define('__TEST_27_EXPECTED_COORDS__', ['latitude' => 40.685164, 'longitude' => -76.197515]);

function run_test_27_place_record_geocode_tests($test_harness_instance) {
    $all_pass = false;
    $test_count = $test_harness_instance->test_count;
    
    if (isset($test_harness_instance->sdk_instance)) {
        $all_pass = true;
        if ($test_harness_instance->sdk_instance->valid()) {
            $record = $test_harness_instance->sdk_instance->get_place_info(__TEST_27_ID_1_);
            if (isset($record) && ($record instanceof RVP_PHP_SDK_Place)) {
                echo('<h5>Modifying record ID '.__TEST_27_ID_1_.' ('.$record->name().').</h5>');
                $coords = $record->coords();
                echo('<h6>Basic Address: '.htmlspecialchars($record->basic_address()).' ('.$coords['latitude'].', '.$coords['longitude'].')</h6>');
                $record->reverse_geocode();
                $new_basic_address = $record->basic_address();
                echo('<h6>Basic Address: '.htmlspecialchars($new_basic_address).' ('.$coords['latitude'].', '.$coords['longitude'].')</h6>');
                
                if (__TEST_27_EXPECTED_REV_ADDR__ == $new_basic_address) {
                    $test_harness_instance->write_log_entry('REVERSE GEOCODE', $test_count++, true);
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('REVERSE GEOCODE', $test_count++, false);
                    echo('<h4 style="color:red">REVERSE GEOCODE FAILED!</h4>');
                }
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('FAILED TO GET RECORD '.__TEST_27_ID_1_, $test_count++, false);
                echo('<h4 style="color:red">FAILED TO GET RECORD '.__TEST_27_ID_1_.'!</h4>');
            }
            
            $record = $test_harness_instance->sdk_instance->get_place_info(__TEST_27_ID_2_);
            if (isset($record) && ($record instanceof RVP_PHP_SDK_Place)) {
                echo('<h5>Modifying record ID '.__TEST_27_ID_2_.' ('.$record->name().').</h5>');
                $coords = $record->coords();
                echo('<h6>Basic Address: '.htmlspecialchars($record->basic_address()).' ('.$coords['latitude'].', '.$coords['longitude'].')</h6>');
                $record->geocode();
                $new_basic_address = $record->basic_address();
                $coords = $record->coords();
                echo('<h6>Basic Address: '.htmlspecialchars($new_basic_address).' ('.$coords['latitude'].', '.$coords['longitude'].')</h6>');
                
                if (__TEST_27_EXPECTED_COORDS__ == $coords) {
                    $test_harness_instance->write_log_entry('GEOCODE', $test_count++, true);
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('GEOCODE', $test_count++, false);
                    echo('<h4 style="color:red">GEOCODE FAILED!</h4>');
                }
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('FAILED TO GET RECORD '.__TEST_27_ID_2_, $test_count++, false);
                echo('<h4 style="color:red">FAILED TO GET RECORD '.__TEST_27_ID_2_.'!</h4>');
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