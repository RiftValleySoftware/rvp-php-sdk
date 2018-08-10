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
defined('__TEST_24_ID__') or define('__TEST_24_ID__', 5403);
defined('__TEST_24_NEW_COORDS_LAT_') or define('__TEST_24_NEW_COORDS_LAT_', 34.235951);
defined('__TEST_24_NEW_COORDS_LNG_') or define('__TEST_24_NEW_COORDS_LNG_', -118.563670);
defined('__TEST_24_FUZZ_FACTOR__') or define('__TEST_24_FUZZ_FACTOR__', 10);

function run_test_24_basic_data_record_put_tests($test_harness_instance) {
    $all_pass = false;
    $test_count = $test_harness_instance->test_count;
    
    if (isset($test_harness_instance->sdk_instance)) {
        $all_pass = true;
        if ($test_harness_instance->sdk_instance->valid()) {
            $record = $test_harness_instance->sdk_instance->get_place_info(__TEST_24_ID__);
            if (isset($record) && ($record instanceof RVP_PHP_SDK_Place)) {
                echo('<h5>Modifying record ID '.__TEST_24_ID__.' ('.$record->name().').</h5>');
                
                echo('<h6>Modify Coords.</h6>');
                $original_coords = $record->coords();
                $original_fuzz_factor = $record->fuzz_factor();
                if ($record->set_coords(__TEST_24_NEW_COORDS_LAT_, __TEST_24_NEW_COORDS_LNG_)) {
                    $second_sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__);
                    if (isset($second_sdk_instance) && ($second_sdk_instance instanceof RVP_PHP_SDK)) {
                        $second_records = $second_sdk_instance->get_objects(__TEST_24_ID__);
                        if (isset($second_records) && is_array($second_records) && (1 == count($second_records))) {
                            $new_coords = $second_records[0]->coords();
                            if (($new_coords['latitude'] == __TEST_24_NEW_COORDS_LAT_) && ($new_coords['longitude'] == __TEST_24_NEW_COORDS_LNG_)) {
                                $test_harness_instance->write_log_entry('NEW COORDS MATCH', $test_count++, true);
                                $changes = $second_records[0]->changes();
                                if (isset($changes) && is_array($changes) && (0 == count($changes))) {
                                    $test_harness_instance->write_log_entry('VERIFY NO CHANGES IN SECOND SDK INSTANCE RECORD', $test_count++, true);
                                } else {
                                    $all_pass = false;
                                    $test_harness_instance->write_log_entry('WE SHOULD HAVE GOTTEN AN EMPTY CHANGES ARRAY', $test_count++, false);
                                    echo('<h4 style="color:red">WE SHOULD HAVE GOTTEN AN EMPTY CHANGES ARRAY!</h4>');
                                }
                                
                                $changes = $record->changes();
                                if (isset($changes) && is_array($changes) && (1 == count($changes)) && ($original_coords == $changes[0]->coords())) {
                                    $test_harness_instance->write_log_entry('VERIFY ROLLBACK VALUE', $test_count++, true);
                                } else {
                                    $all_pass = false;
                                    $test_harness_instance->write_log_entry('UNEXPECTED CHANGES ARRAY', $test_count++, false);
                                    echo('<h4 style="color:red">UNEXPECTED CHANGES ARRAY!</h4>');
                                }
                            } else {
                                $all_pass = false;
                                $test_harness_instance->write_log_entry('NEW COORDS DON\'T MATCH', $test_count++, false);
                                echo('<h4 style="color:red">NEW COORDS DON\'T MATCH!</h4>');
                            }
                        } else {
                            $all_pass = false;
                            $test_harness_instance->write_log_entry('FAILED TO FETCH RECORD '.__TEST_24_ID__, $test_count++, false);
                            echo('<h4 style="color:red">FAILED TO FETCH RECORD '.__TEST_24_ID__.'!</h4>');
                        }
                        
                        $second_sdk_instance = NULL;
                    } else {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('FAILED TO CREATE SECOND SDK INSTANCE', $test_count++, false);
                        echo('<h4 style="color:red">FAILED TO CREATE SECOND SDK INSTANCE!</h4>');
                    }
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('FAILED TO MODIFY COORDS FOR RECORD '.__TEST_24_ID__, $test_count++, false);
                    echo('<h4 style="color:red">FAILED TO MODIFY COORDS FOR RECORD '.__TEST_24_ID__.'!</h4>');
                }
                
                echo('<h6>Modify Fuzz Factor.</h6>');
                $original_2_coords = $record->coords();
                if ($record->set_fuzz_factor(__TEST_24_FUZZ_FACTOR__)) {
                    if ($record->is_fuzzy()) {
                        $test_harness_instance->write_log_entry('RECORD '.__TEST_24_ID__.' IS FUZZY', $test_count++, true);
                    } else {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('RECORD '.__TEST_24_ID__.' IS NOT FUZZY', $test_count++, false);
                        echo('<h4 style="color:red">THE FUZZ FACTOR MADE NO DIFFERENCE!!</h4>');
                    }
                    $second_sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__);
                    if (isset($second_sdk_instance) && ($second_sdk_instance instanceof RVP_PHP_SDK)) {
                        $second_records = $second_sdk_instance->get_objects(__TEST_24_ID__);
                        if (isset($second_records) && is_array($second_records) && (1 == count($second_records))) {
                            $new_coords = $second_records[0]->coords();
                            if (($new_coords['latitude'] != __TEST_24_NEW_COORDS_LAT_) || ($new_coords['longitude'] != __TEST_24_NEW_COORDS_LNG_)) {
                                $test_harness_instance->write_log_entry('NEW COORDS DON\'T MATCH. THAT\'S GOOD.', $test_count++, true);
                                $changes = $second_records[0]->changes();
                                if (isset($changes) && is_array($changes) && (0 == count($changes))) {
                                    $test_harness_instance->write_log_entry('VERIFY NO CHANGES IN SECOND SDK INSTANCE RECORD', $test_count++, true);
                                } else {
                                    $all_pass = false;
                                    $test_harness_instance->write_log_entry('WE SHOULD HAVE GOTTEN AN EMPTY CHANGES ARRAY', $test_count++, false);
                                    echo('<h4 style="color:red">WE SHOULD HAVE GOTTEN AN EMPTY CHANGES ARRAY!</h4>');
                                }
                                
                                $changes = $record->changes();
                                if (isset($changes) && is_array($changes) && (2 == count($changes))) {
                                    if ($original_coords == $changes[0]->coords()) {
                                        $test_harness_instance->write_log_entry('VERIFY ROLLBACK VALUE: FIRST COORDS OK', $test_count++, true);
                                    } else {
                                        $all_pass = false;
                                        $test_harness_instance->write_log_entry('UNEXPECTED CHANGES ARRAY (FIRST COORDS DON\'T MATCH)', $test_count++, false);
                                        echo('<h4 style="color:red">UNEXPECTED CHANGES ARRAY! (FIRST COORDS DON\'T MATCH)</h4>');
                                    }
                                    
                                    if ($original_2_coords != $changes[1]->coords()) {
                                        $test_harness_instance->write_log_entry('VERIFY ROLLBACK VALUE: SECOND COORDS OK', $test_count++, true);
                                    } else {
                                        $all_pass = false;
                                        $test_harness_instance->write_log_entry('UNEXPECTED CHANGES ARRAY (SECOND COORDS MATCH)', $test_count++, false);
                                        echo('<h4 style="color:red">UNEXPECTED CHANGES ARRAY! (SECOND COORDS DON\'T MATCH)</h4>');
                                    }
                                    
                                    if ($original_fuzz_factor == $changes[0]->fuzz_factor()) {
                                        $test_harness_instance->write_log_entry('VERIFY ROLLBACK VALUE: FIRST FUZZ FACTOR OK', $test_count++, true);
                                    } else {
                                        $all_pass = false;
                                        $test_harness_instance->write_log_entry('UNEXPECTED CHANGES ARRAY (FIRST FUZZ FACTOR DOESN\'T MATCH)', $test_count++, false);
                                        echo('<h4 style="color:red">UNEXPECTED CHANGES ARRAY! (FIRST FUZZ FACTOR DOESN\'T MATCH)</h4>');
                                    }
                                    
                                    if ($original_fuzz_factor == $changes[1]->fuzz_factor()) {
                                        $test_harness_instance->write_log_entry('VERIFY ROLLBACK VALUE: SECOND FUZZ FACTOR OK', $test_count++, true);
                                    } else {
                                        $all_pass = false;
                                        $test_harness_instance->write_log_entry('UNEXPECTED CHANGES ARRAY (SECOND FUZZ FACTOR DOESN\'T MATCH)', $test_count++, false);
                                        echo('<h4 style="color:red">UNEXPECTED CHANGES ARRAY! (SECOND FUZZ FACTOR DOESN\'T MATCH)</h4>');
                                    }
                                } else {
                                    $all_pass = false;
                                    $test_harness_instance->write_log_entry('UNEXPECTED CHANGES ARRAY', $test_count++, false);
                                    echo('<h4 style="color:red">UNEXPECTED CHANGES ARRAY! (COUNT -'.count($changes).'- OFF).</h4>');
                                }
                            } else {
                                $all_pass = false;
                                $test_harness_instance->write_log_entry('NEW COORDS MATCH. THEY SHOULDN\'T.', $test_count++, false);
                                echo('<h4 style="color:red">NEW COORDS MATCH. THEY SHOULDN\'T.!</h4>');
                            }
                        } else {
                            $all_pass = false;
                            $test_harness_instance->write_log_entry('FAILED TO FETCH RECORD '.__TEST_24_ID__, $test_count++, false);
                            echo('<h4 style="color:red">FAILED TO FETCH RECORD '.__TEST_24_ID__.'!</h4>');
                        }
                        
                        $second_sdk_instance = NULL;
                    } else {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('FAILED TO CREATE SECOND SDK INSTANCE', $test_count++, false);
                        echo('<h4 style="color:red">FAILED TO CREATE SECOND SDK INSTANCE!</h4>');
                    }
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('FAILED TO SET FUZZ FACTOR FOR RECORD '.__TEST_24_ID__, $test_count++, false);
                    echo('<h4 style="color:red">FAILED TO SET FUZZ FACTOR FOR RECORD '.__TEST_24_ID__.'!</h4>');
                }
                
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('FAILED TO GET RECORD '.__TEST_24_ID__, $test_count++, false);
                echo('<h4 style="color:red">FAILED TO GET RECORD '.__TEST_24_ID__.'!</h4>');
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