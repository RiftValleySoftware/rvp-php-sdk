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
defined('__TEST_23_NAME__') or define('__TEST_23_NAME__', 'Sharing Clean, Living Dirty');
defined('__TEST_23_LANG__') or define('__TEST_23_LANG__', 'sv');
function run_test_23_basic_put_tests($test_harness_instance) {
    $all_pass = false;
    $test_count = $test_harness_instance->test_count;
    
    if (isset($test_harness_instance->sdk_instance)) {
        $all_pass = true;
        if ($test_harness_instance->sdk_instance->valid()) {
            $record = $test_harness_instance->sdk_instance->get_place_info(5403);
            if (isset($record) && ($record instanceof RVP_PHP_SDK_Place)) {
                echo('<h5>Modifying record ID 5403 ('.$record->name().').</h5>');
                $old_name = $record->name();
                $old_lang = $record->lang();
                
                $changes = $record->changes();
                if (!isset($changes) || !is_array($changes) || count($changes)) {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('INITIAL CHANGES OCCUPIED', $test_count++, false);
                    echo('<h4 style="color:red">INITIAL CHANGES OCCUPIED!</h4>');
                }
                
                $record->set_name(__TEST_23_NAME__);
                
                $changes = $record->changes();
                if (!isset($changes) || !is_array($changes) || (1 != count($changes))) {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('FIRST CHANGES NOT SET', $test_count++, false);
                    echo('<h4 style="color:red">FIRST CHANGES NOT SET!</h4>');
                } else {
                    if ($changes[0] instanceof RVP_PHP_SDK_Place) {
                        if ($changes[0]->name() != $old_name) {
                            $all_pass = false;
                            $test_harness_instance->write_log_entry('CHANGE RECORD NAME MATCH', $test_count++, false);
                            echo('<h4 style="color:red">CHANGE RECORD NAME DOES NOT MATCH ("'.$changes[0]->name().'" is not the same as "'.$old_name.'")!</h4>');
                        } else {
                            $test_harness_instance->write_log_entry('CHANGE RECORD NAME MATCH', $test_count++, true);
                        }
                    } else {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('CHANGE RECORD WRONG CLASS', $test_count++, false);
                        echo('<h4 style="color:red">CHANGE RECORD WRONG CLASS!</h4>');
                    }
                }
                
                $record->set_lang(__TEST_23_LANG__);
                
                $changes = $record->changes();
                
                if (!isset($changes) || !is_array($changes) || (2 != count($changes))) {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('SECOND CHANGES NOT SET', $test_count++, false);
                    echo('<h4 style="color:red">SECOND CHANGES NOT SET!</h4>');
                } else {
                    if ($changes[1] instanceof RVP_PHP_SDK_Place) {
                        if ($changes[1]->lang() != $old_lang) {
                            $all_pass = false;
                            $test_harness_instance->write_log_entry('CHANGE RECORD LANG MATCH', $test_count++, false);
                            echo('<h4 style="color:red">CHANGE RECORD LANG DOES NOT MATCH ("'.$changes[1]->lang().'" is not the same as "'.$old_lang.'")!</h4>');
                        } else {
                            $test_harness_instance->write_log_entry('CHANGE RECORD LANG MATCH', $test_count++, true);
                        }
                    } else {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('CHANGE RECORD WRONG CLASS', $test_count++, false);
                        echo('<h4 style="color:red">CHANGE RECORD WRONG CLASS!</h4>');
                    }
                }

                $second_sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__);
                $second_records = $second_sdk_instance->get_objects(5403);
                if (isset($second_records) && is_array($second_records) && (1 == count($second_records))) {
                    if ($second_records[0] instanceof RVP_PHP_SDK_Place) {
                        if ($record->name() != $second_records[0]->name()) {
                            $all_pass = false;
                            $test_harness_instance->write_log_entry('RECORD NAMES DON\'T MATCH', $test_count++, false);
                            echo('<h4 style="color:red">RECORD NAMES DON\'T MATCH!</h4>');
                        }
                        if ($record->lang() != $second_records[0]->lang()) {
                            $all_pass = false;
                            $test_harness_instance->write_log_entry('RECORD LANGUAGES DON\'T MATCH', $test_count++, false);
                            echo('<h4 style="color:red">RECORD LANGUAGES DON\'T MATCH!</h4>');
                        }
                    } else {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('FAILED TO GET SECOND INSTANCE OF RECORD 5403 (Not in Array)', $test_count++, false);
                        echo('<h4 style="color:red">FAILED TO GET SECOND INSTANCE OF RECORD 5403 (NOT IN ARRAY)!</h4>');
                    }
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('FAILED TO GET SECOND INSTANCE OF RECORD 5403', $test_count++, false);
                    echo('<h4 style="color:red">FAILED TO GET SECOND INSTANCE OF RECORD 5403!</h4>');
                }
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('FAILED TO GET RECORD 5403', $test_count++, false);
                echo('<h4 style="color:red">FAILED TO GET RECORD 5403!</h4>');
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