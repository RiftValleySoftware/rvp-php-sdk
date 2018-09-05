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
function run_test_34_post_places_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance) && $test_harness_instance->sdk_instance->valid()) {
        $test_harness_instance->write_log_entry('INSTANTIATION CHECK', $test_count++, true);
        
        $new_place = $test_harness_instance->sdk_instance->new_place('Istanbul, Not Constantinople', ['read' => 1, 'write' => 1], 41.01224, 28.976018, 10);
        
        if (isset($new_place) && ($new_place instanceof RVP_PHP_SDK_Place)) {
            $test_harness_instance->write_log_entry('GET NEW PLACE', $test_count++, true);
            
            $raw_coords = $new_place->raw_coords();
            
            if (isset($raw_coords)) {
                $test_harness_instance->write_log_entry('CHECK RAW COORDS', $test_count++, true);
                
                if ((41.01224 == $raw_coords['latitude']) && (28.976018 == $raw_coords['longitude'])) {
                    $test_harness_instance->write_log_entry('LONG/LAT CHECK', $test_count++, true);
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('LONG/LAT CHECK', $test_count++, false);
                    echo('<h4 style="color:red">LONGITUDE AND LATITUDE ARE INCORRECT!</h4>');
                }
        
                if ($new_place->coords() != $raw_coords) {
                    $test_harness_instance->write_log_entry('LOCATION OBFUSCATION CHECK', $test_count++, true);
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('LOCATION OBFUSCATION CHECK', $test_count++, false);
                    echo('<h4 style="color:red">LOCATION OBFUSCATION FAILED!</h4>');
                }
        
                if (1 == $new_place->object_access()['read']) {
                    $test_harness_instance->write_log_entry('READ PERMISSION CHECK', $test_count++, true);
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('READ PERMISSION CHECK', $test_count++, false);
                    echo('<h4 style="color:red">INCORRECT READ PERMISSION!</h4>');
                }
        
                if (1 == $new_place->object_access()['write']) {
                    $test_harness_instance->write_log_entry('WRITE PERMISSION CHECK', $test_count++, true);
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('WRITE PERMISSION CHECK', $test_count++, false);
                    echo('<h4 style="color:red">INCORRECT WRITE PERMISSION!</h4>');
                }
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('CHECK RAW COORDS', $test_count++, false);
                echo('<h4 style="color:red">INCORRECT WRITE PERMISSION!</h4>');
            }
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('GET NEW PLACE', $test_count++, false);
            echo('<h4 style="color:red">FAILED TO GET A NEW PLACE!</h4>');
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