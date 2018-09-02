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
        
        $new_place = $test_harness_instance->sdk_instance->new_place('Istanbul, Not Constantinople', 41.01224, 28.976018, 10);
        
        if ((41.01224 == $new_place->raw_coords()['latitude']) && (28.976018 == $new_place->raw_coords()['longitude'])) {
            $test_harness_instance->write_log_entry('LONG/LAT CHECK', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('LONG/LAT CHECK', $test_count++, false);
            echo('<h4 style="color:red">LONGITUDE AND LATITUDE ARE INCORRECT!</h4>');
        }
        
        if ($new_place->coords() != $new_place->raw_coords()) {
            $test_harness_instance->write_log_entry('LOCATION OBFUSCATION CHECK', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('LOCATION OBFUSCATION CHECK', $test_count++, false);
            echo('<h4 style="color:red">LOCATION OBFUSCATION FAILED!</h4>');
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