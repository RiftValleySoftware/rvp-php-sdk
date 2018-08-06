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
defined('__CSV_TEST_FILE__') or define('__CSV_TEST_FILE__','BMLT/bmlt.csv');

function run_test_18_harness_baseline_bulk_loader_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            $test_file_loc = dirname(dirname(__FILE__)).'/'.__CSV_TEST_FILE__;
            if (file_exists($test_file_loc)) {
                $get_file = file_get_contents($test_file_loc);
                if (isset($get_file) && $get_file) {
                    $control_sha = '5810ed562bebac11ceda46e848611cf87338057d';
                    $response = $test_harness_instance->sdk_instance->bulk_upload($get_file);
                    $variable_sha = sha1(serialize($response));
                    echo('<p><strong>SHA:</strong> <big><code>'.$variable_sha.'</code></big>');
                    if ($variable_sha != $control_sha) {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('BULK UPLOADER ID REPORT SHA CHECK', $test_count++, false);
                        echo('<h4 style="color:red">SHAS DO NOT MATCH!</h4>');
                    } else {
                        $test_harness_instance->write_log_entry('BULK UPLOADER ID REPORT SHA CHECK', $test_count++, true);
                    }
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('TEST FILE CONTENTS CHECK', $test_count++, false);
                    echo('<h4 style="color:red">TEST FILE "'.$test_file_loc.'" DOES NOT CONTAIN VALID DATA!</h4>');
                }
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('TEST FILE CHECK', $test_count++, false);
                echo('<h4 style="color:red">TEST FILE "'.$test_file_loc.'" DOES NOT EXIST!</h4>');
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