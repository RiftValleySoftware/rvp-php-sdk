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
function run_test_06_harness_multiple_logins_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            echo('<h4>First, Log In And Out Several Logins:</h4>');
            
            $instances = [];
            foreach (__TEST_LOGINS__ as $category => $list) {
                echo('<h5>'.$category.':</h5>');
                $timeout = ('God User' != $category) ? CO_Config::$session_timeout_in_seconds : CO_Config::$god_session_timeout_in_seconds;
                foreach ($list as $login_id) {
                    echo('<h6>Logging In '.$login_id.':</h6>');
                    $temp_sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__, $login_id, __PASSWORD__, $timeout);
                    // Different rule for trying to log in again as the God login.
                    if ($login_id == $test_harness_instance->sdk_instance->current_login_id_string()) {
                        if (isset($temp_sdk_instance) && ($temp_sdk_instance instanceof RVP_PHP_SDK) && $temp_sdk_instance->valid()) {
                            $test_harness_instance->write_log_entry('SDK INSTANCE FOR "'.$login_id.'" VALIDITY CHECK', $test_count++, true);
                            echo('<h4 style="color:green">SDK FOR "'.$login_id.'" UP AND VALID!</h4>');
                            if (!$temp_sdk_instance->is_logged_in()) {
                                $test_harness_instance->write_log_entry('SDK INSTANCE FOR "'.$login_id.'" (NOT) LOGGED IN CHECK', $test_count++, true);
                                echo('<h4 style="color:green">SDK FOR "'.$login_id.'" NOT LOGGED IN, WHICH IS CORRECT!</h4>');
                                $err = $temp_sdk_instance->get_error();
                                if (isset($err) && is_array($err) && (2 == count($err) && (_ERR_INVALID_LOGIN__ == $err['code']))) {
                                    $test_harness_instance->write_log_entry('SDK INSTANCE ERROR CODE FOR "'.$login_id.'" CHECK', $test_count++, true);
                                } else {
                                    $all_pass = false;
                                    $test_harness_instance->write_log_entry('SDK INSTANCE ERROR CODE FOR "'.$login_id.'" CHECK', $test_count++, false);
                                    echo('<h4 style="color:red">"'.$login_id.'" HAS INCORRECT ERROR CODE!</h4>');
                                }
                            } else {
                                $all_pass = false;
                                $test_harness_instance->write_log_entry('SDK INSTANCE FOR "'.$login_id.'" (NOT) LOGGED IN CHECK', $test_count++, true);
                                echo('<h4 style="color:red">SDK FOR "'.$login_id.'" IS LOGGED IN, WHICH IS IS AN ERROR!</h4>');
                            }
                        } else {
                            $all_pass = false;
                            $test_harness_instance->write_log_entry('SDK INSTANCE FOR "'.$login_id.'" VALIDITY CHECK', $test_count++, false);
                            echo('<h4 style="color:red">SDK FOR "'.$login_id.'" NOT VALID!</h4>');
                            continue;
                        }
                    } else {
                        if (isset($temp_sdk_instance) && ($temp_sdk_instance instanceof RVP_PHP_SDK) && $temp_sdk_instance->valid() && $temp_sdk_instance->is_logged_in()) {
                            $instances[] = $temp_sdk_instance;
                            $test_harness_instance->write_log_entry('SDK INSTANCE FOR "'.$login_id.'" VALIDITY CHECK', $test_count++, true);
                            echo('<h4 style="color:green">SDK FOR "'.$login_id.'" UP AND VALID!</h4>');
                            $object = $temp_sdk_instance->get_objects(1744)[0];
                            if ($object instanceof RVP_PHP_SDK_Thing) {
                                if (1744 == $object->id()) {
                                    $test_harness_instance->write_log_entry('OBJECT ID CHECK', $test_count++, true);
                                } else {
                                    $all_pass = false;
                                    $test_harness_instance->write_log_entry('OBJECT ID CHECK', $test_count++, false);
                                    echo('<h4 style="color:red">INCORRECT ID FOR OBJECT!</h4>');
                                }

                                if (__DIVINE_COMEDY_SHA__ == sha1($object->payload()['data'])) {
                                    $test_harness_instance->write_log_entry('OBJECT PAYLOAD CHECK', $test_count++, true);
                                } else {
                                    $all_pass = false;
                                    $test_harness_instance->write_log_entry('OBJECT PAYLOAD CHECK', $test_count++, false);
                                    echo('<h4 style="color:red">INCORRECT PAYLOAD FOR OBJECT!</h4>');
                                }
                            } else {
                                $all_pass = false;
                                $test_harness_instance->write_log_entry('INCORRECT OBJECT TYPE WITH LOGIN "'.$login_id.'"!', $test_count++, false);
                                echo('<h4 style="color:red">INCORRECT OBJECT TYPE WITH LOGIN "'.$login_id.'"!</h4>');
                            }
                        } else {
                            $all_pass = false;
                            $test_harness_instance->write_log_entry('SDK INSTANCE FOR "'.$login_id.'" VALIDITY CHECK', $test_count++, false);
                            echo('<h4 style="color:red">SDK FOR "'.$login_id.'" NOT VALID!</h4>');
                        }
                    }
                }
            }
        
            foreach ($instances as $instance) {
                $login_id = $instance->current_login_id_string();
                echo('<h6>Logging Out '.$login_id.':</h6>');
                if ($instance->logout()) {
                    echo('<h6 style="color:green">LOGOUT "'.$login_id.'" SUCCESS!</h6>');
                    $test_harness_instance->write_log_entry('Log Out "'.$login_id.'"', $test_count++, true);
                } else {
                    $all_pass = false;
                    echo('<h6 style="color:red">LOGOUT "'.$login_id.'" FAILED!</h6>');
                    $err = $temp_sdk_instance->get_error();
                    if ($err) {
                        echo('<p class="explain">'.$err['message'].'</p>');
                    }
                    $test_harness_instance->write_log_entry('Log Out "'.$login_id.'"', $test_count++, false);
                }
            }
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('VALIDITY CHECK', $test_count++, false);
            echo('<h4 style="color:red">ORIGINAL SERVER NOT VALID!</h4>');
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