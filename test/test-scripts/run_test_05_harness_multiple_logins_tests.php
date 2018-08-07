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
function run_test_05_harness_multiple_logins_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            echo('<h4>First, Log In And Out Several Logins:</h4>');
            
            foreach (__TEST_LOGINS__ as $category => $list) {
                echo('<h5>'.$category.':</h5>');
                $timeout = ('God User' != $category) ? CO_Config::$session_timeout_in_seconds : CO_Config::$god_session_timeout_in_seconds;
                foreach ($list as $login_id) {
                    echo('<h6>Logging In '.$login_id.':</h6>');
                    if ($test_harness_instance->sdk_instance->login($login_id, __PASSWORD__, $timeout)) {
                        echo('<h6 style="color:green">SUCCESS!</h6>');
                        $test_harness_instance->write_log_entry('Log In "'.$login_id.'"', $test_count++, true);
                        $info = $test_harness_instance->sdk_instance->my_info();
                        
                        if (isset($info) && is_array($info) && count($info) && isset($info['login'])) {
                            if ($login_id == $info['login']->login_id()) {
                                echo('<h6 style="color:green">LOGIN ID CHECK FOR "'.$login_id.'" SUCCESS!</h6>');
                                $test_harness_instance->write_log_entry('LOGIN ID CHECK FOR "'.$login_id.'"', $test_count++, true);
                            } else {
                                $all_pass = false;
                                echo('<h6 style="color:red">LOGIN ID CHECK FOR "'.$login_id.'" FAILED!</h6>');
                                $test_harness_instance->write_log_entry('LOGIN ID CHECK FOR "'.$login_id.'"', $test_count++, false);
                            }
                            
                            if (('God User' == $category) || ('Manager Login' == $category)) {
                                if ($info['login']->is_manager()) {
                                    echo('<h6 style="color:green">MANAGER CHECK FOR "'.$login_id.'" SUCCESS!</h6>');
                                    $test_harness_instance->write_log_entry('MANAGER CHECK FOR "'.$login_id.'"', $test_count++, true);
                                } else {
                                    $all_pass = false;
                                    echo('<h6 style="color:red">MANAGER CHECK FOR "'.$login_id.'" FAILED!</h6>');
                                    $test_harness_instance->write_log_entry('MANAGER CHECK FOR "'.$login_id.'"', $test_count++, false);
                                }
                            
                                if ('God User' == $category) {
                                    if ($info['login']->is_main_admin()) {
                                        echo('<h6 style="color:green">MAIN ADMIN CHECK FOR "'.$login_id.'" SUCCESS!</h6>');
                                        $test_harness_instance->write_log_entry('MAIN ADMIN CHECK FOR "'.$login_id.'"', $test_count++, true);
                                    } else {
                                        $all_pass = false;
                                        echo('<h6 style="color:red">MAIN ADMIN CHECK FOR "'.$login_id.'" FAILED!</h6>');
                                        $test_harness_instance->write_log_entry('MAIN ADMIN CHECK FOR "'.$login_id.'"', $test_count++, false);
                                    }
                                } elseif (!$info['login']->is_main_admin()) {
                                    echo('<h6 style="color:green">(NOT) MAIN ADMIN CHECK FOR "'.$login_id.'" SUCCESS!</h6>');
                                    $test_harness_instance->write_log_entry('(NOT) MAIN ADMIN CHECK FOR "'.$login_id.'"', $test_count++, true);
                                } else {
                                    $all_pass = false;
                                    echo('<h6 style="color:red">(NOT) MAIN ADMIN CHECK FOR "'.$login_id.'" FAILED!</h6>');
                                    $test_harness_instance->write_log_entry('(NOT) MAIN ADMIN CHECK FOR "'.$login_id.'"', $test_count++, false);
                                }
                            } else {
                                if ('Login Only' == $category) {
                                    if (0 == $info['login']->user_object_id()) {
                                        echo('<h6 style="color:green">(NO) USER ID CHECK CHECK FOR "'.$login_id.'" SUCCESS!</h6>');
                                        $test_harness_instance->write_log_entry('(NO) USER ID CHECK CHECK FOR "'.$login_id.'"', $test_count++, true);
                                    } else {
                                        $all_pass = false;
                                        echo('<h6 style="color:red">(NO) USER ID CHECK CHECK FOR "'.$login_id.'" FAILED!</h6>');
                                        $test_harness_instance->write_log_entry('(NO) USER ID CHECK CHECK FOR "'.$login_id.'"', $test_count++, false);
                                    }
                                } else {
                                    if (1 < $info['login']->user_object_id()) {
                                        echo('<h6 style="color:green">USER ID CHECK CHECK FOR "'.$login_id.'" SUCCESS!</h6>');
                                        $test_harness_instance->write_log_entry('USER ID CHECK CHECK FOR "'.$login_id.'"', $test_count++, true);
                                    } else {
                                        $all_pass = false;
                                        echo('<h6 style="color:red">USER ID CHECK CHECK FOR "'.$login_id.'" FAILED!</h6>');
                                        $test_harness_instance->write_log_entry('USER ID CHECK CHECK FOR "'.$login_id.'"', $test_count++, false);
                                    }
                                }
                            
                                if (!$info['login']->is_manager()) {
                                    echo('<h6 style="color:green">(NOT) MANAGER CHECK FOR "'.$login_id.'" SUCCESS!</h6>');
                                    $test_harness_instance->write_log_entry('(NOT) MANAGER CHECK FOR "'.$login_id.'"', $test_count++, true);
                                } else {
                                    $all_pass = false;
                                    echo('<h6 style="color:red">(NOT) MANAGER CHECK FOR "'.$login_id.'" FAILED!</h6>');
                                    $test_harness_instance->write_log_entry('(NOT) MANAGER CHECK FOR "'.$login_id.'"', $test_count++, false);
                                }
                                
                                if (!$info['login']->is_main_admin()) {
                                    echo('<h6 style="color:green">(NOT) MAIN ADMIN CHECK FOR "'.$login_id.'" SUCCESS!</h6>');
                                    $test_harness_instance->write_log_entry('(NOT) MAIN ADMIN CHECK FOR "'.$login_id.'"', $test_count++, true);
                                } else {
                                    $all_pass = false;
                                    echo('<h6 style="color:red">(NOT) MAIN ADMIN CHECK FOR "'.$login_id.'" FAILED!</h6>');
                                    $test_harness_instance->write_log_entry('(NOT) MAIN ADMIN CHECK FOR "'.$login_id.'"', $test_count++, false);
                                }
                            }
                            
                        } else {
                            $all_pass = false;
                            echo('<h6 style="color:red">LOGIN INFO LOAD FOR FOR "'.$login_id.'" FAILED!</h6>');
                            $test_harness_instance->write_log_entry('LOGIN INFO LOAD FOR "'.$login_id.'"', $test_count++, false);
                        }
                        
                        echo('<h6>Logging Out '.$login_id.':</h6>');
                        if ($test_harness_instance->sdk_instance->logout()) {
                            echo('<h6 style="color:green">LOGOUT "'.$login_id.'" SUCCESS!</h6>');
                            $test_harness_instance->write_log_entry('Log Out "'.$login_id.'"', $test_count++, true);
                        } else {
                            $all_pass = false;
                            echo('<h6 style="color:red">LOGOUT "'.$login_id.'" FAILED!</h6>');
                            $err = $test_harness_instance->sdk_instance->get_error();
                            if ($err) {
                                echo('<p class="explain">'.$err['message'].'</p>');
                            }
                            $test_harness_instance->write_log_entry('Log Out "'.$login_id.'"', $test_count++, false);
                        }
                    } else {
                        $all_pass = false;
                        echo('<h6 style="color:red">LOG IN "'.$login_id.'" FAILED!</h6>');
                        $err = $test_harness_instance->sdk_instance->get_error();
                        if ($err) {
                            echo('<p class="explain">'.$err['message'].'</p>');
                        }
                        $test_harness_instance->write_log_entry('Log In "'.$login_id.'"', $test_count++, false);
                    }
                }
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