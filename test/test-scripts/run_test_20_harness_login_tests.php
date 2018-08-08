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
defined('__CSV_TEST_ACCOUNTS_FILE__') or define('__CSV_TEST_ACCOUNTS_FILE__','BMLT/bmlt-accounts-1');

function run_test_20_harness_login_tests($test_harness_instance) {
    $all_pass = false;
    $test_count = $test_harness_instance->test_count;
    
    if (isset($test_harness_instance->sdk_instance)) {
        $all_pass = true;
        if ($test_harness_instance->sdk_instance->valid()) {
            $test_file_loc = dirname(dirname(__FILE__)).'/'.__CSV_TEST_ACCOUNTS_FILE__.'.csv';
            if (file_exists($test_file_loc)) {
                $csv_array = [];
                $get_file = file_get_contents($test_file_loc);
                if (isset($get_file) && $get_file) {
                    $in_text_data = explode("\n", $get_file);
                    if (isset($in_text_data) && is_array($in_text_data) && (1 < count($in_text_data))) {
                        $keys = str_getcsv(array_shift($in_text_data));
                        foreach ($in_text_data as $row) {
                            $row_temp = str_getcsv($row);
                            $row = [];
                            
                            foreach ($row_temp as $element) {
                                if (('"NULL"' == $element) || ('NULL' == $element) || ("'NULL'" == $element) || !trim($element)) {
                                    $element = NULL;
                                }
                    
                                $row[] = $element;
                            }
                            if (count($row) == count($keys)) {
                                $row = array_combine($keys, $row);
                                $csv_array[] = $row;
                            }
                        }
                        
                        foreach ($csv_array as $row) {
                            $all_pass &= run_test_20_harness_login_tests_test_login($test_harness_instance, $row, $test_count);
                            if (!$all_pass) {
                                break;
                            }
                        }
                        
                        if ($all_pass) {
                            $test_harness_instance->write_log_entry('Log In '.count($csv_array).' logins: Success!', $test_count++, true);
                        }
                    } else {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('TEST FILE PARSE', $test_count++, false);
                        echo('<h4 style="color:red">TEST FILE "'.$test_file_loc.'" DOES NOT CONTAIN VALID DATA!</h4>');
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

function run_test_20_harness_login_tests_test_login($in_test_harness_instance, $in_csv_row, &$test_count) {
    $all_pass = true;
    if ($in_test_harness_instance->sdk_instance->login($in_csv_row['login_id'], $in_csv_row['password'], CO_Config::$session_timeout_in_seconds)) {
        $info = $in_test_harness_instance->sdk_instance->my_info();
        if (isset($info) && is_array($info) && count($info) && isset($info['login'])) {
            if ($info['login']->login_id() != $in_csv_row['login_id']) {
                $all_pass = false;
                $in_test_harness_instance->write_log_entry('Log In "'.$in_csv_row['login_id'].'" Failure!', $test_count++, false);
                echo('<h3 style="color:red">Info for "'.$in_csv_row['login_id'].'" Failed!</h3>');
            } elseif ($info['user']->name() != $in_csv_row['name']) {
                $all_pass = false;
                $in_test_harness_instance->write_log_entry('Name Match "'.$in_csv_row['name'].'" Failure!', $test_count++, false);
                echo('<h3 style="color:red">Info for "'.$in_csv_row['login_id'].'" Failed!</h3>');
            } else {
                $editable_places = $in_test_harness_instance->sdk_instance->places_search(NULL, NULL, true);
                if (count($editable_places) != intval($in_csv_row['editable_record_count'])) {
                    $all_pass = false;
                    $in_test_harness_instance->write_log_entry('Log In "'.$in_csv_row['login_id'].'" Editable Record Count Failure!', $test_count++, false);
                    echo('<h3 style="color:red">Info for "'.$in_csv_row['login_id'].'" Failed (Count Mismatch)!</h3>');
                }
            }
        } else {
            $all_pass = false;
            $in_test_harness_instance->write_log_entry('Log In "'.$in_csv_row['login_id'].'" Failure!', $test_count++, false);
            echo('<h3 style="color:red">Info for "'.$in_csv_row['login_id'].'" Failed!</h3>');
        }
        $in_test_harness_instance->sdk_instance->logout();
    } else {
        $all_pass = false;
        $in_test_harness_instance->write_log_entry('Log In "'.$in_csv_row['login_id'].'" Failure!', $test_count++, false);
        echo('<h3 style="color:red">Log In "'.$in_csv_row['login_id'].'" Failed!</h3>');
    }
    
    if (isset($handle)) {
        fclose($handle);
    }
    
    return $all_pass;
}
?>