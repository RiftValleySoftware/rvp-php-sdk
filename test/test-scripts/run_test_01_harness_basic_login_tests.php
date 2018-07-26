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
function run_test_01_harness_basic_login_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            $test_harness_instance->write_log_entry('INSTANTIATION CHECK', $test_count++, true);
            $test_harness_instance->write_log_entry('VALIDITY CHECK', $test_count++, true);
            if ($test_harness_instance->sdk_instance->is_logged_in()) {
                $test_harness_instance->write_log_entry('LOGIN CHECK', $test_count++, false);
                echo('<h4 style="color:red">SHOULD NOT BE LOGGED IN!</h4>');
                $all_pass = false;
            } else {
                if ($test_harness_instance->sdk_instance->plugins() == ['baseline', 'people', 'places', 'things']) {
                    $test_harness_instance->write_log_entry('PLUGIN CHECK', $test_count++, true);
                    $test_harness_instance->write_log_entry('LOGIN CHECK', $test_count++, true);
                    echo('<h4 style="color:green">NO LOGIN AND VALID PLUGINS!</h4>');
                } else {
                    $test_harness_instance->write_log_entry('PLUGIN CHECK', $test_count++, false);
                    echo('<h4 style="color:red">PLUGINS NOT VALID!</h4>');
                }
            }
        } else {
            $test_harness_instance->write_log_entry('VALIDITY CHECK', $test_count++, false);
            echo('<h4 style="color:red">SERVER NOT VALID!</h4>');
        }
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('INSTANTIATION CHECK', $test_count++, false);
        echo('<h4 style="color:red">NO SDK INSTANCE!</h4>');
    }
    
    $test_harness_instance->test_count = $test_count;
    return $all_pass;     
}
?>