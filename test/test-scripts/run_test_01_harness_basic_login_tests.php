<?php
/***************************************************************************************************************************/
/**
    BASALT Extension Layer
    
    © Copyright 2018, Little Green Viper Software Development LLC.
    
    This code is proprietary and confidential code, 
    It is NOT to be reused or combined into any application,
    unless done so, specifically under written license from Little Green Viper Software Development LLC.

    Little Green Viper Software Development: https://littlegreenviper.com
*/
function run_test_01_harness_basic_login_tests($test_harness_instance) {
    $all_pass = true;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            $test_harness_instance->write_log_entry('1- INSTANTIATION CHECK', true);
            $test_harness_instance->write_log_entry('2- VALIDITY CHECK', true);
            if ($test_harness_instance->sdk_instance->is_logged_in()) {
                $test_harness_instance->write_log_entry('3- LOGIN CHECK', false);
                echo('<h4 style="color:red">SHOULD NOT BE LOGGED IN!</h4>');
                $all_pass = false;
            } else {
                if ($test_harness_instance->sdk_instance->plugins() == ['baseline', 'people', 'places', 'things']) {
                    $test_harness_instance->write_log_entry('3- PLUGIN CHECK', true);
                    $test_harness_instance->write_log_entry('4- LOGIN CHECK', true);
                    echo('<h4 style="color:green">NO LOGIN AND VALID PLUGINS!</h4>');
                } else {
                    $test_harness_instance->write_log_entry('3- PLUGIN CHECK', false);
                    echo('<h4 style="color:red">PLUGINS NOT VALID!</h4>');
                }
            }
        } else {
            $test_harness_instance->write_log_entry('2- VALIDITY CHECK', false);
            echo('<h4 style="color:red">SERVER NOT VALID!</h4>');
        }
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('1- INSTANTIATION CHECK', false);
        echo('<h4 style="color:red">NO SDK INSTANCE!</h4>');
    }
    
    return $all_pass;     
}
?>