<?php
/***************************************************************************************************************************/
/**
    BASALT Extension Layer
    
    © Copyright 2018, Little Green Viper Software Development LLC.
    
    This code is proprietary and confidential code, 
    It is NOT to be reused or combined into any application,
    unless done so, specifically under written license from Little Green Viper Software Development LLC.

    Little Green Viper Software Development: https://littlegreenviper.com
*/
define('__LOGIN_NAME__', 'Main Admin Login');
define('__LOGIN_ID__', 12);
define('__LOGIN_READ_TOKEN__', 12);
define('__LOGIN_WRITE_TOKEN__', 12);
define('__LOGIN_TIME_DIFF__', 7);
define('__LOGIN_LANG__', 'en');
define('__LOGIN_WRITEABLE__', true);

define('__USER_NAME__', 'Main Admin');
define('__USER_ID__', 1730);
define('__USER_COORDS_LAT__', 38.9897);
define('__USER_COORDS_LNG__', -76.9378);
define('__USER_READ_TOKEN__', 1);
define('__USER_WRITE_TOKEN__', 12);
define('__USER_LANG__', 'en');
define('__USER_WRITEABLE__', true);

function run_test_02_harness_basic_login_tests($test_harness_instance) {
    $all_pass = false;
    
    if ($test_harness_instance->sdk_instance->is_logged_in()) {
        $all_pass = true;
        $info = $test_harness_instance->sdk_instance->my_info();
        $pass = true;
        if (isset($info['login'])) {
            $pass = (__LOGIN_NAME__ == $info['login']->name());
        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('1 -LOGIN INFO NAME CHECK', $pass);

            $pass = (__LOGIN_ID__ == $info['login']->id());
        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('2 -LOGIN INFO ID CHECK', $pass);

            $tokens = $info['login']->tokens();
            
            if (isset($tokens) && is_array($tokens) && (2 == count($tokens))) {
                $pass &= (__LOGIN_READ_TOKEN__ == $tokens['read']);
                $pass &= (__LOGIN_WRITE_TOKEN__ == $tokens['write']);
        
                $all_pass &= $pass;
                $test_harness_instance->write_log_entry('3 -LOGIN TOKENS CHECK', $pass);
            }
            
            $last_access = $info['login']->last_access();
            $start_time = round($test_harness_instance->test_start_time);
            $now = time();
            $diff = abs($now - $last_access);
            
            $pass = $diff < __LOGIN_TIME_DIFF__;
        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('4 -LOGIN LAST ACCESS CHECK ('.$diff.')', $pass);
            
            $pass = (__LOGIN_LANG__ == $info['login']->lang());
        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('5 -LOGIN INFO LANG CHECK', $pass);
            
            $pass = (__LOGIN_WRITEABLE__ == $info['login']->writeable());
        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('6 -LOGIN INFO WRITEABLE CHECK', $pass);
        } else {
            $pass = false;
            $all_pass= false;
            $test_harness_instance->write_log_entry('1 -LOGIN INFO NAME CHECK', $pass);
        }
        
        if ($all_pass) {
            echo('<h4 style="color:green">LOGIN INFO TESTS PASS</h4>');
        } else {
            echo('<h4 style="color:red">LOGIN INFO TESTS FAILED!</h4>');
        }
        
        if (isset($info['user'])) {
            $pass = (__USER_NAME__ == $info['user']->name());
        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('7 -USER INFO NAME CHECK', $pass);

            $pass = (__USER_ID__ == $info['user']->id());
        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('8 -USER INFO ID CHECK', $pass);

            $coords = $info['user']->coords();
            
            if (isset($coords) && is_array($coords) && (2 == count($coords))) {
                $pass &= (__USER_COORDS_LAT__ == $coords['latitude']);
                $pass &= (__USER_COORDS_LNG__ == $coords['longitude']);
        
                $all_pass &= $pass;
                $test_harness_instance->write_log_entry('9 -USER COORDINATES CHECK', $pass);
            }

            $tokens = $info['user']->tokens();
            
            if (isset($tokens) && is_array($tokens) && (2 == count($tokens))) {
                $pass &= (__USER_READ_TOKEN__ == $tokens['read']);
                $pass &= (__USER_WRITE_TOKEN__ == $tokens['write']);
        
                $all_pass &= $pass;
                $test_harness_instance->write_log_entry('10 -USER TOKENS CHECK', $pass);
            }
            
            $last_access = $info['user']->last_access();
            $pass = $last_access == strtotime('1970-01-02 00:00:00');        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('11 -USER LAST ACCESS CHECK', $pass);
            
            $pass = (__USER_LANG__ == $info['user']->lang());
        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('12 -USER INFO LANG CHECK', $pass);
            
            $pass = (__USER_WRITEABLE__ == $info['user']->writeable());
        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('13 -USER INFO WRITEABLE CHECK', $pass);

            $payload = $info['user']->payload();
            
            if (isset($payload) && is_array($payload)) {
                $pass = false;
            }
           
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('14 -USER PAYLOAD CHECK', $pass);
        } else {
            $pass = false;
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('7 -USER INFO NAME CHECK', $pass);
        }
        
        if ($all_pass) {
            echo('<h4 style="color:green">USER INFO TESTS PASS</h4>');
        } else {
            echo('<h4 style="color:red">USER INFO TESTS FAILED!</h4>');
        }
    } else {
        $test_harness_instance->write_log_entry('LOGIN CHECK', false);
        echo('<h4 style="color:red">NOT LOGGED IN!</h4>');
    }
    
    return $all_pass;     
}
?>