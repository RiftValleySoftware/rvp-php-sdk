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
define('__TEST_31_PASSWORD__', 'CoreysGoryStory');
define('__TEST_31_LOGIN_1__', 'MDAdmin');
define('__TEST_31_LOGIN_1_CHILD_COUNT__', 852);
define('__TEST_31_LOGIN_1_CHILD_COUNT_2__', 847);

function run_test_31_child_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            $login_id = __TEST_31_LOGIN_1__;
            $timeout = CO_Config::$session_timeout_in_seconds;
            echo('<h6>Logging In '.$login_id.':</h6>');
            if ($test_harness_instance->sdk_instance->login($login_id, __TEST_31_PASSWORD__, $timeout)) {
                if ($test_harness_instance->sdk_instance->is_logged_in()) {
                    $test_harness_instance->write_log_entry('Log In "'.$login_id.'"', $test_count++, true);
                    $info = $test_harness_instance->sdk_instance->my_info();
                    if (isset($info) && is_array($info) && count($info) && isset($info['login'])) {
                        $test_harness_instance->write_log_entry('Verify Log In "'.$login_id.'"', $test_count++, true);
                        
                        $login_object = $test_harness_instance->sdk_instance->current_login_object();
                        
                        if (isset($login_object) && ($login_object instanceof RVP_PHP_SDK_Login)) {
                            $test_harness_instance->write_log_entry('Verify Login Object for "'.$login_id.'"', $test_count++, true);
                            
                            $user_object = $login_object->user_object();
                            if (isset($user_object) && ($user_object instanceof RVP_PHP_SDK_User)) {
                                $test_harness_instance->write_log_entry('Verify User Object for "'.$login_id.'"', $test_count++, true);
                                echo('<h5 style="color:green">'.$user_object->name().' has been successfully logged in!</h5>');
                                $children_ids = $user_object->children_ids();
                                if (isset($children_ids) && is_array($children_ids) && (1 == count($children_ids)) && isset($children_ids['places'])) {
                                    $children_ids = $children_ids['places'];
                                    $test_harness_instance->write_log_entry('Verify Children for "'.$login_id.'"', $test_count++, true);
                                    if (isset($children_ids) && is_array($children_ids) && (__TEST_31_LOGIN_1_CHILD_COUNT__ == count($children_ids))) {
                                        $test_harness_instance->write_log_entry('Verify Child Count for "'.$login_id.'"', $test_count++, true);
                                        echo('<h5 style="color:green">'.$user_object->name().' has '.count($children_ids).' place child objects.</h5>');
                                        
                                        if ($user_object->set_new_children_ids([-470, -471, -472, -473, -474])) {
                                            $test_harness_instance->write_log_entry('Remove children from "'.$login_id.'"', $test_count++, false);
                                            $children_ids = $user_object->children_ids();
                                            if (isset($children_ids) && is_array($children_ids) && (1 == count($children_ids)) && isset($children_ids['places'])) {
                                                $test_harness_instance->write_log_entry('Verify Places for "'.$login_id.'"', $test_count++, false);
                                                $children_ids = $children_ids['places'];
                                                if (isset($children_ids) && is_array($children_ids) && (__TEST_31_LOGIN_1_CHILD_COUNT_2__ == count($children_ids))) {
                                                    $test_harness_instance->write_log_entry('Verify New Child Count for "'.$login_id.'"', $test_count++, true);
                                                    echo('<h5 style="color:green">'.$user_object->name().' has '.count($children_ids).' place child objects.</h5>');
                                                } else {
                                                    $all_pass = false;
                                                    $test_harness_instance->write_log_entry('Verify New Child Count for "'.$login_id.'"', $test_count++, false);
                                                    echo('<h4 style="color:red">INVALID CHILD ID COUNT!</h4>');
                                                }
                                            } else {
                                                $all_pass = false;
                                                $test_harness_instance->write_log_entry('Verify Places for "'.$login_id.'"', $test_count++, false);
                                                echo('<h4 style="color:red">INVALID CHILD IDS!</h4>');
                                            }
                                        } else {
                                            $all_pass = false;
                                            $test_harness_instance->write_log_entry('Remove children from "'.$login_id.'"', $test_count++, false);
                                            echo('<h4 style="color:red">FAILED TO REMOVE CHILDREN!</h4>');
                                        }
                                    } else {
                                        $all_pass = false;
                                        $test_harness_instance->write_log_entry('Verify Child Count for "'.$login_id.'"', $test_count++, false);
                                        echo('<h4 style="color:red">INVALID CHILD ID COUNT!</h4>');
                                    }
                                } else {
                                    $all_pass = false;
                                    $test_harness_instance->write_log_entry('Verify Children for "'.$login_id.'"', $test_count++, false);
                                    echo('<h4 style="color:red">INVALID CHILD IDS!</h4>');
                                }
                            } else {
                                $all_pass = false;
                                $test_harness_instance->write_log_entry('Verify User Object for "'.$login_id.'"', $test_count++, false);
                                echo('<h4 style="color:red">USER OBJECT NOT VALID!</h4>');
                            }
                        } else {
                            $all_pass = false;
                            $test_harness_instance->write_log_entry('Verify Login Object for "'.$login_id.'"', $test_count++, false);
                            echo('<h4 style="color:red">LOGIN OBJECT NOT VALID!</h4>');
                        }
                    } else {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('Verify Log In "'.$login_id.'"', $test_count++, false);
                        echo('<h4 style="color:red">SERVER NOT VALID!</h4>');
                    }
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('Verify Log In "'.$login_id.'"', $test_count++, false);
                    echo('<h4 style="color:red">LOGIN DIDN\'T TAKE!</h4>');
                }
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('Log In "'.$login_id.'"', $test_count++, false);
                echo('<h4 style="color:red">LOGIN FAILED!</h4>');
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