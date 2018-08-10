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
defined('__TEST_26_ID__') or define('__TEST_26_ID__', 7400);

function run_test_26_place_record_put_tests($test_harness_instance) {
    $all_pass = false;
    $test_count = $test_harness_instance->test_count;
    
    if (isset($test_harness_instance->sdk_instance)) {
        $all_pass = true;
        if ($test_harness_instance->sdk_instance->valid()) {
            $record = $test_harness_instance->sdk_instance->get_place_info(__TEST_26_ID__);
            if (isset($record) && ($record instanceof RVP_PHP_SDK_Place)) {
                echo('<h5>Modifying record ID '.__TEST_26_ID__.' ('.$record->name().').</h5>');
                echo('<h6>Basic Address: '.htmlspecialchars($record->basic_address()).'</h6>');
                $elements = [];
                $elements['venue'] = 'The Mick E. Mauwse Club';
                $elements['street_address'] = '1313 Disneyland Dr';
                $elements['extra_information'] = '';
                $elements['town'] = 'Anaheim';
                $elements['state'] = 'CA';
                $elements['county'] = 'Orange';
                $elements['postal_code'] = '92802';
                $elements['nation'] = 'USA';
                if ($record->set_address_elements($elements)) {
                    $second_sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__);
                    if ($second_sdk_instance->valid()) {
                        $record = $second_sdk_instance->get_place_info(__TEST_26_ID__);
                        echo('<h6>New Basic Address: '.htmlspecialchars($record->basic_address()).'</h6>');
                        $new_elements = $record->address_elements();
                        unset($elements['extra_information']);
                        if ($new_elements == $elements) {
                            $test_harness_instance->write_log_entry('SET NEW ADDRESS ELEMENTS TEST', $test_count++, true);
                        } else {
                            $all_pass = false;
                            $test_harness_instance->write_log_entry('NEW ADDRESS ELEMENTS DON\'T MATCH', $test_count++, false);
                            echo('<h4 style="color:red">NEW ADDRESS ELEMENTS DON\'T MATCH!</h4>');
                        }
                    } else {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('VALIDITY CHECK (SECOND SDK INSTANCE)', $test_count++, false);
                        echo('<h4 style="color:red">SECOND SDK INSTANCE NOT VALID!</h4>');
                    }
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('SET ELEMENTS FOR RECORD '.__TEST_26_ID__.' FAILED', $test_count++, false);
                    echo('<h4 style="color:red">SET ELEMENTS FOR RECORD '.__TEST_26_ID__.' FAILED!</h4>');
                }
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('FAILED TO GET RECORD '.__TEST_26_ID__, $test_count++, false);
                echo('<h4 style="color:red">FAILED TO GET RECORD '.__TEST_26_ID__.'!</h4>');
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