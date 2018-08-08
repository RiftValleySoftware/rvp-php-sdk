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
defined('__CSV_TEST_BACKUP_FILE__') or define('__CSV_TEST_BACKUP_FILE__','tmp/backup-dump');

function run_test_22_harness_test_restore($test_harness_instance) {
    $all_pass = false;
    $test_count = $test_harness_instance->test_count;
    
    if (isset($test_harness_instance->sdk_instance)) {
        $all_pass = true;
        if ($test_harness_instance->sdk_instance->valid()) {
            $test_file_loc = dirname(dirname(__FILE__)).'/'.__CSV_TEST_BACKUP_FILE__.'.csv';
            if (file_exists($test_file_loc)) {
                $csv_data = file_get_contents($test_file_loc);
                if (isset($csv_data) && $csv_data) {
                    $control_sha = 'a03e9ce134099d2bd410bdc53e8abb7d3f95c397';
                    $response = $test_harness_instance->sdk_instance->bulk_upload($csv_data);
                    $variable_sha = sha1(serialize($response));
                    echo('<p><strong>SHA:</strong> <big><code>'.$variable_sha.'</code></big></p>');
                    if ($variable_sha != $control_sha) {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('BACKUP RESTORE SHA CHECK', $test_count++, false);
                        echo('<h4 style="color:red">SHAS DO NOT MATCH!</h4>');
                    } else {
                        $test_harness_instance->write_log_entry('BACKUP RESTORE SHA CHECK', $test_count++, true);
                    }
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('BACKUP FILE VALIDITY CHECK', $test_count++, false);
                    echo('<h4 style="color:red">BACKUP FILE ('.htmlspecialchars($test_file_loc).') DATA INVALID!</h4>');
                }
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('BACKUP FILE CHECK', $test_count++, false);
                echo('<h4 style="color:red">NO BACKUP FILE ('.htmlspecialchars($test_file_loc).')!</h4>');
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