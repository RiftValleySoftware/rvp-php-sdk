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
defined('__TEST_28_USER_ID__') or define('__TEST_28_USER_ID__', 1752);
defined('__TEST_28_USER_NAME__') or define('__TEST_28_USER_NAME__', 'Ratbert');
defined('__TEST_28_USER_NEW_NAME__') or define('__TEST_28_USER_NEW_NAME__', 'Le Rat');
defined('__TEST_28_USER_LANG__') or define('__TEST_28_USER_LANG__', 'en');
defined('__TEST_28_USER_NEW_LANG__') or define('__TEST_28_USER_NEW_LANG__', 'sv');
defined('__TEST_28_USER_SURNAME__') or define('__TEST_28_USER_SURNAME__', 'Expendable');
defined('__TEST_28_USER_NEW_SURNAME__') or define('__TEST_28_USER_NEW_SURNAME__', 'Ramone');
defined('__TEST_28_USER_MIDDLE_NAME__') or define('__TEST_28_USER_MIDDLE_NAME__', 'Is');
defined('__TEST_28_USER_NEW_MIDDLE_NAME__') or define('__TEST_28_USER_NEW_MIDDLE_NAME__', 'P');
defined('__TEST_28_USER_FIRST_NAME__') or define('__TEST_28_USER_FIRST_NAME__', 'He');
defined('__TEST_28_USER_NEW_FIRST_NAME__') or define('__TEST_28_USER_NEW_FIRST_NAME__', 'Ratbert');
defined('__TEST_28_USER_NICKNAME__') or define('__TEST_28_USER_NICKNAME__', 'Petri');
defined('__TEST_28_USER_NEW_NICKNAME__') or define('__TEST_28_USER_NEW_NICKNAME__', 'Rat');
defined('__TEST_28_USER_PREFIX__') or define('__TEST_28_USER_PREFIX__', 'Test Subject');
defined('__TEST_28_USER_NEW_PREFIX__') or define('__TEST_28_USER_NEW_PREFIX__', 'A');
defined('__TEST_28_USER_SUFFIX__') or define('__TEST_28_USER_SUFFIX__', '12345');
defined('__TEST_28_USER_NEW_SUFFIX__') or define('__TEST_28_USER_NEW_SUFFIX__', '');
defined('__TEST_28_USER_TAG7__') or define('__TEST_28_USER_TAG7__', 'RAT');
defined('__TEST_28_USER_NEW_TAG7__') or define('__TEST_28_USER_NEW_TAG7__', 'tag 7');
defined('__TEST_28_USER_TAG8__') or define('__TEST_28_USER_TAG8__', 'Dilbert Co.');
defined('__TEST_28_USER_NEW_TAG8__') or define('__TEST_28_USER_NEW_TAG8__', 'tag 8');
defined('__TEST_28_USER_TAG9__') or define('__TEST_28_USER_TAG9__', 'Laboratory');
defined('__TEST_28_USER_NEW_TAG9__') or define('__TEST_28_USER_NEW_TAG9__', 'tag 9');
defined('__TEST_28_USER_LOGIN_ID__') or define('__TEST_28_USER_LOGIN_ID__', 0);
defined('__TEST_28_USER_NEW_BAD_LOGIN_ID__') or define('__TEST_28_USER_NEW_BAD_LOGIN_ID__', 18);
defined('__TEST_28_USER_NEW_LOGIN_ID__') or define('__TEST_28_USER_NEW_LOGIN_ID__', 19);

function run_test_28_people_tests($test_harness_instance) {
    $all_pass = false;
    $test_count = $test_harness_instance->test_count;
    
    if (isset($test_harness_instance->sdk_instance)) {
        $all_pass = true;
        if ($test_harness_instance->sdk_instance->valid()) {
            $person_record = $test_harness_instance->sdk_instance->get_user_info(__TEST_28_USER_ID__);
            
            if (isset($person_record) && ($person_record instanceof RVP_PHP_SDK_User) && (__TEST_28_USER_ID__ == $person_record->id())) {
                $test_harness_instance->write_log_entry('USER RECORD CHECK', $test_count++, true);
                $all_pass = test_user_record($test_harness_instance, $person_record, 'name', __TEST_28_USER_NAME__, __TEST_28_USER_NEW_NAME__, $test_count);
                $all_pass &= test_user_record($test_harness_instance, $person_record, 'lang', __TEST_28_USER_LANG__, __TEST_28_USER_NEW_LANG__, $test_count);
                $all_pass &= test_user_record($test_harness_instance, $person_record, 'surname', __TEST_28_USER_SURNAME__, __TEST_28_USER_NEW_SURNAME__, $test_count);
                $all_pass &= test_user_record($test_harness_instance, $person_record, 'middle_name', __TEST_28_USER_MIDDLE_NAME__, __TEST_28_USER_NEW_MIDDLE_NAME__, $test_count);
                $all_pass &= test_user_record($test_harness_instance, $person_record, 'given_name', __TEST_28_USER_FIRST_NAME__, __TEST_28_USER_NEW_FIRST_NAME__, $test_count);
                $all_pass &= test_user_record($test_harness_instance, $person_record, 'nickname', __TEST_28_USER_NICKNAME__, __TEST_28_USER_NEW_NICKNAME__, $test_count);
                $all_pass &= test_user_record($test_harness_instance, $person_record, 'prefix', __TEST_28_USER_PREFIX__, __TEST_28_USER_NEW_PREFIX__, $test_count);
                $all_pass &= test_user_record($test_harness_instance, $person_record, 'suffix', __TEST_28_USER_SUFFIX__, __TEST_28_USER_NEW_SUFFIX__, $test_count);
                $all_pass &= test_user_record($test_harness_instance, $person_record, 'tag7', __TEST_28_USER_TAG7__, __TEST_28_USER_NEW_TAG7__, $test_count);
                $all_pass &= test_user_record($test_harness_instance, $person_record, 'tag8', __TEST_28_USER_TAG8__, __TEST_28_USER_NEW_TAG8__, $test_count);
                $all_pass &= test_user_record($test_harness_instance, $person_record, 'tag9', __TEST_28_USER_TAG9__, __TEST_28_USER_NEW_TAG9__, $test_count);
                $all_pass &= test_associated_login_id($test_harness_instance, $person_record, $test_count);

            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('USER RECORD CHECK', $test_count++, false);
                echo('<h4 style="color:red">USER NOT VALID!</h4>');
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

function test_user_record($test_harness_instance, $in_user_record, $field_name, $original_value, $new_value, &$test_count) {
    $all_pass = true;
    
    if ($original_value == $in_user_record->$field_name()) {
        $test_harness_instance->write_log_entry('USER RECORD INITIAL '.strtoupper($field_name).' CHECK', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('USER RECORD INITIAL '.strtoupper($field_name).' CHECK', $test_count++, false);
        echo('<h4 style="color:red">INITIAL '.strtoupper($field_name).' NOT VALID!</h4>');
    }
    
    $setter = 'set_'.$field_name;
    
    if ($in_user_record->$setter($new_value)) {
        $test_harness_instance->write_log_entry('USER RECORD SET '.strtoupper($field_name), $test_count++, true);
        if ($new_value == $in_user_record->$field_name()) {
            $test_harness_instance->write_log_entry('USER RECORD NEW '.strtoupper($field_name).' CHECK 1', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('USER RECORD NEW '.strtoupper($field_name).' CHECK 1', $test_count++, false);
            echo('<h4 style="color:red">NEW '.strtoupper($field_name).' NOT VALID (FIRST CHECK)!</h4>');
        }
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('USER RECORD SET '.strtoupper($field_name), $test_count++, false);
        echo('<h4 style="color:red">SET SURNAME FAILED!</h4>');
    }
    
    $second_sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__);
    if ($second_sdk_instance->valid()) {
        $test_harness_instance->write_log_entry('GET SECOND SDK', $test_count++, true);
        if ($second_sdk_instance->login('admin', CO_Config::god_mode_password(), CO_Config::$god_session_timeout_in_seconds)) {
            $test_harness_instance->write_log_entry('LOGIN AS GOD', $test_count++, true);
            $record = $second_sdk_instance->get_user_info($in_user_record->id());
            if (isset($record) && ($record instanceof RVP_PHP_SDK_User)) {
                $test_harness_instance->write_log_entry('GET SECOND RECORD', $test_count++, true);
                if ($new_value == $record->$field_name()) {
                    $test_harness_instance->write_log_entry('USER RECORD NEW '.strtoupper($field_name).' CHECK 2', $test_count++, true);
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('USER RECORD NEW '.strtoupper($field_name).' CHECK 2', $test_count++, false);
                    echo('<h4 style="color:red">NEW '.strtoupper($field_name).' NOT VALID (SECOND CHECK)!</h4>');
                }
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('GET SECOND RECORD', $test_count++, false);
                echo('<h4 style="color:red">UNABLE TO GET SECOND RECORD!</h4>');
            }
            $second_sdk_instance->logout();
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('LOGIN AS GOD', $test_count++, false);
            echo('<h4 style="color:red">CANNOT LOG INTO SECOND SDK!</h4>');
        }
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('GET SECOND SDK', $test_count++, false);
        echo('<h4 style="color:red">SECOND SDK INSTANCE INVALID!</h4>');
    }
    return $all_pass;
}

function test_associated_login_id($test_harness_instance, $in_user_record, &$test_count) {
    $all_pass = true;
    $second_sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__);
    if ($second_sdk_instance->valid()) {
        $test_harness_instance->write_log_entry('GET SECOND SDK', $test_count++, true);
        if ($second_sdk_instance->login('admin', CO_Config::god_mode_password(), CO_Config::$god_session_timeout_in_seconds)) {
            $test_harness_instance->write_log_entry('LOGIN AS GOD', $test_count++, true);
            $record = $second_sdk_instance->get_user_info($in_user_record->id());
            if (isset($record) && ($record instanceof RVP_PHP_SDK_User)) {
                $test_harness_instance->write_log_entry('GET RECORD FROM GOD LOGIN', $test_count++, true);
                if ($record->set_associated_login_id(__TEST_28_USER_NEW_BAD_LOGIN_ID__)) {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('ATTEMPT TO SET BAD LOGIN ID', $test_count++, false);
                    echo('<h4 style="color:red">WE SHOULD NOT HAVE BEEN ABLE TO SET THIS LOGIN ID!</h4>');
                } else {
                    $test_harness_instance->write_log_entry('ATTEMPT TO SET BAD LOGIN ID', $test_count++, true);
                    if (_ERR_LOGIN_HAS_USER__ == $second_sdk_instance->get_error()['code']) {
                        $test_harness_instance->write_log_entry('CHECK ERROR CODE', $test_count++, true);
                    } else {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('CHECK ERROR CODE', $test_count++, false);
                        echo('<h4 style="color:red">INCORRECT ERROR CODE!</h4>');
                    }
                }
                
                if ($record->set_associated_login_id(__TEST_28_USER_NEW_LOGIN_ID__)) {
                    $test_harness_instance->write_log_entry('ATTEMPT TO SET NEW LOGIN ID', $test_count++, true);
                    $person_record = $test_harness_instance->sdk_instance->get_user_info(__TEST_28_USER_ID__);
            
                    if (isset($person_record) && ($person_record instanceof RVP_PHP_SDK_User) && (__TEST_28_USER_ID__ == $person_record->id())) {
                        $test_harness_instance->write_log_entry('GET SECOND PERSON RECORD', $test_count++, true);
                        
                        if (__TEST_28_USER_NEW_LOGIN_ID__ != $person_record->associated_login_id()) {
                            $test_harness_instance->write_log_entry('NEW LOGIN NOT VISIBLE TO ORIGINAL MANAGER', $test_count++, true);
                        } else {
                            $all_pass = false;
                            $test_harness_instance->write_log_entry('NEW LOGIN NOT VISIBLE TO ORIGINAL MANAGER', $test_count++, false);
                            echo('<h4 style="color:red">THE MANAGER SHOULD NOT BE ABLE TO KNOW THIS LOGIN EXISTS!</h4>');
                        }                        
                    } else {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('GET SECOND PERSON RECORD', $test_count++, false);
                        echo('<h4 style="color:red">CANNOT GET SECOND PERSON RECORD!</h4>');
                    }
                        
                    $yet_another_person_record = $second_sdk_instance->get_user_info(__TEST_28_USER_ID__);
            
                    if (isset($yet_another_person_record) && ($yet_another_person_record instanceof RVP_PHP_SDK_User) && (__TEST_28_USER_ID__ == $yet_another_person_record->id())) {
                        $test_harness_instance->write_log_entry('GET YET ANOTHER PERSON RECORD', $test_count++, true);
                        if (__TEST_28_USER_NEW_LOGIN_ID__ == $yet_another_person_record->associated_login_id()) {
                            $test_harness_instance->write_log_entry('NEW LOGIN IS VISIBLE TO GOD ADMIN', $test_count++, true);
                        } else {
                            $all_pass = false;
                            $test_harness_instance->write_log_entry('NEW LOGIN IS VISIBLE TO GOD ADMIN', $test_count++, false);
                            echo('<h4 style="color:red">THE GOD ADMIN SHOULD BE ABLE TO SEE THIS LOGIN!</h4>');
                        }                        
                    } else {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('GET YET ANOTHER PERSON RECORD', $test_count++, false);
                        echo('<h4 style="color:red">CANNOT GET YET ANOTHER PERSON RECORD!</h4>');
                    }
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('ATTEMPT TO SET NEW LOGIN ID', $test_count++, false);
                    echo('<h4 style="color:red">UNABLE TO ASSOCIATE VALID LOGIN!</h4>');
                }
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('GET RECORD FROM GOD LOGIN', $test_count++, false);
                echo('<h4 style="color:red">UNABLE TO GET RECORD!</h4>');
            }
            $second_sdk_instance->logout();
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('LOGIN AS GOD', $test_count++, false);
            echo('<h4 style="color:red">CANNOT LOG INTO SECOND SDK!</h4>');
        }
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('GET SECOND SDK', $test_count++, false);
        echo('<h4 style="color:red">SECOND SDK INSTANCE INVALID!</h4>');
    }
    return $all_pass;
}
?>