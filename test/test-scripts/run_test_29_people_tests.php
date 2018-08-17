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
defined('__TEST_29_USER_ID__') or define('__TEST_29_USER_ID__', 17);
defined('__TEST_29_OLD_PASSWORD__') or define('__TEST_29_OLD_PASSWORD__', 'CoreysGoryStory');
defined('__TEST_29_NEW_PASSWORD__') or define('__TEST_29_NEW_PASSWORD__', 'ThisIsANewPassword');

function run_test_29_people_tests($test_harness_instance) {
    $all_pass = false;
    $test_count = $test_harness_instance->test_count;
    
    if (isset($test_harness_instance->sdk_instance)) {
        $all_pass = true;
        if ($test_harness_instance->sdk_instance->valid()) {
            $god_mode_sdk_instance = $test_harness_instance->sdk_instance;
            $manager_sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__, 'PHB', __TEST_29_OLD_PASSWORD__, CO_Config::$session_timeout_in_seconds);
            
            if (($manager_sdk_instance instanceof RVP_PHP_SDK) && $manager_sdk_instance->is_manager()) {
                $test_harness_instance->write_log_entry('MANAGER LOGIN CHECK', $test_count++, true);
                $normal_sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__, 'Tina', __TEST_29_OLD_PASSWORD__, CO_Config::$session_timeout_in_seconds);
            
                if (($normal_sdk_instance instanceof RVP_PHP_SDK) && $normal_sdk_instance->is_logged_in()) {
                    $test_harness_instance->write_log_entry('NORMAL LOGIN CHECK', $test_count++, true);
                    $other_normal_sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__, 'Dilbert', __TEST_29_OLD_PASSWORD__, CO_Config::$session_timeout_in_seconds);
            
                    if (($other_normal_sdk_instance instanceof RVP_PHP_SDK) && $other_normal_sdk_instance->is_logged_in()) {
                        $test_harness_instance->write_log_entry('SECOND NORMAL LOGIN CHECK', $test_count++, true);
                        
                        $all_pass = test_29_run_login_tests($test_harness_instance, $god_mode_sdk_instance, $manager_sdk_instance, $normal_sdk_instance, $other_normal_sdk_instance, $test_count);
                    } else {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('SECOND NORMAL LOGIN CHECK', $test_count++, false);
                        echo('<h4 style="color:red">FAILED TO LOG IN AS DILBERT!</h4>');
                    }
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('NORMAL LOGIN CHECK', $test_count++, false);
                    echo('<h4 style="color:red">FAILED TO LOG IN AS TINA!</h4>');
                }
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('MANAGER LOGIN CHECK', $test_count++, false);
                echo('<h4 style="color:red">FAILED TO LOG IN AS MANAGER!</h4>');
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

function test_29_run_login_tests($test_harness_instance, $god_mode_sdk_instance, $manager_sdk_instance, $normal_sdk_instance, $other_normal_sdk_instance, &$test_count) {
    $all_pass = true;
    
    $dilbert_record = $other_normal_sdk_instance->get_login_info(__TEST_29_USER_ID__);
    
    if (!($dilbert_record instanceof RVP_PHP_SDK_Login)) {
        $test_harness_instance->write_log_entry('ENSURE DILBERT CANNOT SEE TINA\'S INFO LOGIN RECORD CHECK', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('ENSURE DILBERT CANNOT SEE TINA\'S INFO LOGIN RECORD CHECK', $test_count++, false);
        echo('<h4 style="color:red">DILBERT CAN SEE TINA\'S INFO! THAT\'S NOT GOOD!</h4>');
    }
    
    $normal_record = $normal_sdk_instance->get_login_info(__TEST_29_USER_ID__);
    
    if ($normal_record instanceof RVP_PHP_SDK_Login) {
        $test_harness_instance->write_log_entry('ENSURE NORMAL CAN SEE THEIR OWN INFO CHECK', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('ENSURE NORMAL CAN SEE THEIR OWN INFO CHECK', $test_count++, false);
        echo('<h4 style="color:red">TINA CAN\'T SEE HER OWN INFO!</h4>');
    }
    
    $manager_record = $manager_sdk_instance->get_login_info(__TEST_29_USER_ID__);
    
    if ($manager_record instanceof RVP_PHP_SDK_Login) {
        $test_harness_instance->write_log_entry('ENSURE MANAGER CAN SEE RECORD CHECK', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('ENSURE MANAGER CAN SEE RECORD CHECK', $test_count++, false);
        echo('<h4 style="color:red">MANAGER CANNOT SEE THE LOGIN!</h4>');
    }
    
    $god_record = $god_mode_sdk_instance->get_login_info(__TEST_29_USER_ID__);
    
    if ($god_record instanceof RVP_PHP_SDK_Login) {
        $test_harness_instance->write_log_entry('ENSURE GOD CAN SEE RECORD CHECK', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('ENSURE GOD CAN SEE RECORD CHECK', $test_count++, false);
        echo('<h4 style="color:red">GOD CANNOT SEE THE LOGIN!</h4>');
    }
    
    $dilbert_id = $other_normal_sdk_instance->current_login_id();
    $manager_dilbert_record = $manager_sdk_instance->get_login_info($dilbert_id);
    
    if (($manager_dilbert_record instanceof RVP_PHP_SDK_Login) && ($manager_dilbert_record->id() == $other_normal_sdk_instance->current_login_id())) {
        $test_harness_instance->write_log_entry('MANAGER ACCESS TO DILBERT LOGIN CHECK', $test_count++, true);
        if ($other_normal_sdk_instance->is_logged_in()) {
            $test_harness_instance->write_log_entry('DILBERT LOGGED IN BEFORE PASSWORD CHANGE', $test_count++, true);
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('DILBERT LOGGED IN BEFORE PASSWORD CHANGE', $test_count++, false);
            echo('<h4 style="color:red">DILBERT IS NOT LOGGED IN!</h4>');
        }
        
        if ($manager_dilbert_record->set_password(__TEST_29_NEW_PASSWORD__)) {
            $test_harness_instance->write_log_entry('MANAGER CHANGE DILBERT LOGIN PASSWORD', $test_count++, true);
            
            if ($other_normal_sdk_instance->force_reload()) {
                $all_pass = false;
                $test_harness_instance->write_log_entry('DILBERT NOT LOGGED IN AFTER PASSWORD CHANGE', $test_count++, false);
                echo('<h4 style="color:red">DILBERT IS STILL LOGGED IN AFTER PASSWORD CHANGE!</h4>');
            } else {
                $test_harness_instance->write_log_entry('DILBERT NOT LOGGED IN AFTER PASSWORD CHANGE', $test_count++, true);
                $other_normal_sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__, 'Dilbert', __TEST_29_NEW_PASSWORD__, CO_Config::$session_timeout_in_seconds);
        
                if (($other_normal_sdk_instance instanceof RVP_PHP_SDK) && $other_normal_sdk_instance->is_logged_in()) {
                    $test_harness_instance->write_log_entry('TRY NEW DILBERT LOGIN', $test_count++, true);
                    if ($other_normal_sdk_instance->change_my_password_to(__TEST_29_OLD_PASSWORD__)) {
                        $test_harness_instance->write_log_entry('DILBERT CHANGES HIS OWN PASSWORD', $test_count++, true);
                        
                        if ($other_normal_sdk_instance->is_logged_in()) {
                            $test_harness_instance->write_log_entry('CHECK DILBERT LOGIN', $test_count++, true);
                        } else {
                            $all_pass = false;
                            $test_harness_instance->write_log_entry('CHECK DILBERT LOGIN', $test_count++, false);
                            echo('<h4 style="color:red">DILBERT WAS NOT PROPERLY LOGGED BACK IN!</h4>');
                        }
                    } else {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('DILBERT CHANGES HIS OWN PASSWORD', $test_count++, false);
                        echo('<h4 style="color:red">DILBERT COULD NOT CHANGE HIS OWN PASSWORD!</h4>');
                    }
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('TRY NEW DILBERT LOGIN', $test_count++, false);
                    echo('<h4 style="color:red">DILBERT COULD NOT LOG IN WITH THE NEW PASSWORD!</h4>');
                }
            }
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('MANAGER CHANGE DILBERT LOGIN PASSWORD', $test_count++, false);
            echo('<h4 style="color:red">PHB FAILED TO CHANGE DILBERT\'S PASSWORD!</h4>');
        }
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('MANAGER ACCESS TO DILBERT LOGIN CHECK', $test_count++, false);
        echo('<h4 style="color:red">PHB FAILED TO ACCESS DILBERT!</h4>');
    }

    $record_myself = $normal_sdk_instance->get_login_info(__TEST_29_USER_ID__);
    $record_manager = $manager_sdk_instance->get_login_info(__TEST_29_USER_ID__);
    $record_god = $god_mode_sdk_instance->get_login_info(__TEST_29_USER_ID__);

    $normal_tokens = $normal_sdk_instance->my_tokens();
    $manager_tokens = $manager_sdk_instance->my_tokens();
    $all_tokens = $god_mode_sdk_instance->my_tokens();

    if ($record_god->set_security_tokens($all_tokens)) {
        $test_harness_instance->write_log_entry('SETTING SECURITY TOKENS TO ITEM WITH UNREACHABLE TOKENS -GOD LOGIN', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('SETTING SECURITY TOKENS TO ITEM WITH UNREACHABLE TOKENS -GOD LOGIN', $test_count++, false);
        echo('<h4 style="color:red">GOD WAS NOT ABLE TO MODIFY THE TOKENS!</h4>');
    }
    
    $record_myself->force_reload();
    $record_manager->force_reload();
    $record_god->force_reload();

    $from_myself = $record_myself->security_tokens();
    $from_manager = $record_manager->security_tokens();
    $from_god = $record_god->security_tokens();
    
    if ($test_harness_instance->are_arrays_equal($from_myself, $all_tokens)) {
        $test_harness_instance->write_log_entry('VERIFY NEW TOKENS (MYSELF)', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('VERIFY NEW TOKENS (MYSELF)', $test_count++, false);
        echo('<h4 style="color:red">THE NEW TOKEN SET DOES NOT MATCH WHAT IS EXPECTED (MYSELF)!</h4>');
    }
    
    if ($test_harness_instance->are_arrays_equal($from_manager, $manager_tokens)) {
        $test_harness_instance->write_log_entry('VERIFY NEW TOKENS (MANAGER)', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('VERIFY NEW TOKENS (MANAGER)', $test_count++, false);
        echo('<h4 style="color:red">THE NEW TOKEN SET DOES NOT MATCH WHAT IS EXPECTED (MANAGER)!</h4>');
    }
    
    if ($test_harness_instance->are_arrays_equal($from_god, $all_tokens)) {
        $test_harness_instance->write_log_entry('VERIFY NEW TOKENS (MANAGER)', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('VERIFY NEW TOKENS (MANAGER)', $test_count++, false);
        echo('<h4 style="color:red">THE NEW TOKEN SET DOES NOT MATCH WHAT IS EXPECTED (MANAGER)!</h4>');
    }

    if ($record_manager->set_security_tokens($manager_tokens)) {
        $test_harness_instance->write_log_entry('ATTEMPT TO SET TOKENS WHEN WE DON\'T HAVE PERMISSION -MANAGER LOGIN', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('ATTEMPT TO SET TOKENS WHEN WE DON\'T HAVE PERMISSION -MANAGER LOGIN', $test_count++, false);
        echo('<h4 style="color:red">THIS SHOULD NOT HAVE FAILED!</h4>');
    }
    
    $record_myself->force_reload();
    $record_manager->force_reload();
    $record_god->force_reload();

    $from_myself = $record_myself->security_tokens();
    $from_manager = $record_manager->security_tokens();
    $from_god = $record_god->security_tokens();

    if ($test_harness_instance->are_arrays_equal($from_god, $all_tokens)) {
        $test_harness_instance->write_log_entry('VERIFY NEW TOKENS WERE NOT CHANGED (MANAGER)', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('VERIFY NEW TOKENS WERE NOT CHANGED (MANAGER)', $test_count++, false);
        echo('<h4 style="color:red">THE NEW TOKEN SET DOES NOT MATCH WHAT IS EXPECTED (MANAGER)!</h4>');
    }

    if ($record_myself->set_security_tokens($manager_tokens)) {
        $all_pass = false;
        $test_harness_instance->write_log_entry('ATTEMPT TO SET TOKENS WHEN WE DON\'T HAVE PERMISSION -MYSELF LOGIN', $test_count++, false);
        echo('<h4 style="color:red">THIS SHOULD HAVE FAILED!</h4>');
    } else {
        $test_harness_instance->write_log_entry('ATTEMPT TO SET TOKENS WHEN WE DON\'T HAVE PERMISSION -MYSELF LOGIN', $test_count++, true);
    }
    
    $record_myself->force_reload();
    $record_manager->force_reload();
    $record_god->force_reload();

    $from_myself = $record_myself->security_tokens();
    $from_manager = $record_manager->security_tokens();
    $from_god = $record_god->security_tokens();

    if ($test_harness_instance->are_arrays_equal($from_god, $all_tokens)) {
        $test_harness_instance->write_log_entry('VERIFY NEW TOKENS WERE NOT CHANGED (MYSELF)', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('VERIFY NEW TOKENS WERE NOT CHANGED (MYSELF)', $test_count++, false);
        echo('<h4 style="color:red">THE NEW TOKEN SET DOES NOT MATCH WHAT IS EXPECTED (MYSELF)!</h4>');
    }

    if ($record_god->set_security_tokens($manager_tokens)) {
        $test_harness_instance->write_log_entry('SET MANAGER TOKENS -GOD LOGIN', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('SET MANAGER TOKENS -GOD LOGIN', $test_count++, false);
        echo('<h4 style="color:red">THIS SHOULD NOT HAVE FAILED!</h4>');
    }
    
    $record_myself->force_reload();
    $record_manager->force_reload();
    $record_god->force_reload();

    $from_myself = $record_myself->security_tokens();
    $from_manager = $record_manager->security_tokens();
    $from_god = $record_god->security_tokens();

    if ($test_harness_instance->are_arrays_equal($from_god, $manager_tokens)) {
        $test_harness_instance->write_log_entry('VERIFY NEW TOKENS WERE CHANGED (GOD)', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('VERIFY NEW TOKENS WERE CHANGED (GOD)', $test_count++, false);
        echo('<h4 style="color:red">THE NEW TOKEN SET DOES NOT MATCH WHAT IS EXPECTED (GOD)!</h4>');
    }

    if ($record_manager->set_security_tokens([])) {
        $test_harness_instance->write_log_entry('CLEAR TOKENS -MANAGER LOGIN', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('CLEAR TOKENS -MANAGER LOGIN', $test_count++, false);
        echo('<h4 style="color:red">THIS SHOULD NOT HAVE FAILED!</h4>');
    }
    
    $record_myself->force_reload();
    $record_manager->force_reload();
    $record_god->force_reload();

    $from_myself = $record_myself->security_tokens();
    $from_manager = $record_manager->security_tokens();
    $from_god = $record_god->security_tokens();

    if ($test_harness_instance->are_arrays_equal($from_god, $normal_tokens)) {
        $test_harness_instance->write_log_entry('VERIFY NEW TOKENS WERE CHANGED (GOD)', $test_count++, true);
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('VERIFY NEW TOKENS WERE CHANGED (GOD)', $test_count++, false);
        echo('<h4 style="color:red">THE NEW TOKEN SET DOES NOT MATCH WHAT IS EXPECTED (GOD)!</h4>');
    }

    return $all_pass;
}
?>