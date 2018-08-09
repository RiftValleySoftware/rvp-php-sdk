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

function run_test_21_harness_test_backup($test_harness_instance) {
    $all_pass = false;
    $test_count = $test_harness_instance->test_count;
    
    if (isset($test_harness_instance->sdk_instance)) {
        $all_pass = true;
        if ($test_harness_instance->sdk_instance->valid()) {
            $test_file_loc = dirname(dirname(__FILE__)).'/'.__CSV_TEST_BACKUP_FILE__.'.csv';
            if (file_exists($test_file_loc)) {
                unlink($test_file_loc);
            }
            $handle = fopen($test_file_loc, 'w');
            
            if ($handle) {
                $data = $test_harness_instance->sdk_instance->backup();
                $test_data = [];
                foreach (explode("\n", $data) as $line) {
                    $line_ex = explode(',', $line);
                    if (isset($line_ex) && is_array($line_ex) && count($line_ex)) {
                        if (intval($line_ex[0]) != CO_Config::god_mode_id()) {
                            $test_data[] = $line;
                        }
                    }
                }
                $control_sha = 'e949e1bec8c6744b540c48e83419db7213d4df9f';
                $variable_sha = sha1(serialize($test_data));
                echo('<p><strong>SHA:</strong> <big><code>'.$variable_sha.'</code></big></p>');
                if ($variable_sha != $control_sha) {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('BACKUP FETCH VALIDITY CHECK', $test_count++, false);
                    echo('<h4 style="color:red">BACKUP DATA NOT VALID!</h4>');
                } else {
                    fwrite($handle, $data);
                    echo('<h4 style="color:green">BACKUP SUCCESSFUL!</h4>');
                    $test_harness_instance->write_log_entry('BACKUP FETCH', $test_count++, true);
                }
                fclose($handle);
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