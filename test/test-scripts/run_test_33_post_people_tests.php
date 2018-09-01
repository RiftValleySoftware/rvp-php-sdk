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
function run_test_33_post_people_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance) && $test_harness_instance->sdk_instance->valid()) {
        $test_harness_instance->write_log_entry('INSTANTIATION CHECK', $test_count++, true);
        $new_user = $test_harness_instance->sdk_instance->new_user('Dorkbert', [], 'VAAdmin');
        
        if ( isset($new_user)) {
            $all_pass = false;
            $test_harness_instance->write_log_entry('Not Allowed to Create New User', $test_count++, false);
            echo('<h4 style="color:red">ALLOWED TO CREATE NEW USER WITH DUPLICATE LOGIN ID!</h4>');
        } else {
            $test_harness_instance->write_log_entry('Not Allowed to Create New User', $test_count++, true);
        }
        
        $new_user = $test_harness_instance->sdk_instance->new_user('Dorkbert', [], 'Dorkbert');
        
        if ( isset($new_user)) {
            $test_harness_instance->write_log_entry('Allowed to Create New User', $test_count++, true);
            
            if ('Dorkbert' == $new_user['login_id']) {
                $test_harness_instance->write_log_entry('Validate Login ID', $test_count++, true);
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('Validate Login ID', $test_count++, false);
                cho('<h4 style="color:red">RETURNED LOGIN ID INVALID!</h4>');
            }
            
            if ((CO_Config::$min_pw_len + 2) == strlen($new_user['password'])) {
                $test_harness_instance->write_log_entry('Validate Password String Length', $test_count++, true);
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('Validate Password String Length', $test_count++, false);
                echo('<h4 style="color:red">RETURNED PASSWORD INVALID!</h4>');
            }
            
            if (isset($new_user['user']) && ($new_user['user'] instanceof RVP_PHP_SDK_User) && ('Dorkbert' == $new_user['user']->name()) && (20 == $new_user['user']->associated_login_id())) {
                $test_harness_instance->write_log_entry('Validate User Object', $test_count++, true);
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('Validate User Object', $test_count++, false);
                echo('<h4 style="color:red">USER OBJECT INVALID!</h4>');
            }
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('Allowed to Create New User', $test_count++, false);
            echo('<h4 style="color:red">UNABLE TO CREATE NEW USER!</h4>');
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