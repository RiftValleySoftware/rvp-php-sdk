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
define('__LOGIN_NAME__', 'Main Admin Login');
define('__LOGIN_ID__', 12);
define('__LOGIN_READ_TOKEN__', 12);
define('__LOGIN_WRITE_TOKEN__', 12);
define('__LOGIN_TIME_DIFF__', 10);
define('__LOGIN_LANG__', 'en');
define('__LOGIN_WRITEABLE__', true);
define('__LOGIN_MANAGER__', true);
define('__LOGIN_MAIN_ADMIN__', false);
define('__LOGIN_LOGGED_IN__', true);
define('__LOGIN_TOKENS__', [1, 7, 8, 9, 10, 11, 12]);
define('__LOGIN_USER_ID__', 1730);

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
    $test_count = $test_harness_instance->test_count + 1;
    
    if ($test_harness_instance->sdk_instance->is_logged_in()) {
        $all_pass = true;
        $info = $test_harness_instance->sdk_instance->my_info();
        $pass = true;
        if (isset($info['login'])) {
            $pass = (__LOGIN_NAME__ == $info['login']->name());
        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('LOGIN INFO NAME CHECK', $test_count++, $pass);

            $pass = (__LOGIN_ID__ == $info['login']->id());
        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('LOGIN INFO ID CHECK', $test_count++, $pass);

            $tokens = $info['login']->security_tokens();
            
            if (isset($tokens) && is_array($tokens) && (2 == count($tokens))) {
                $pass &= (__LOGIN_READ_TOKEN__ == $tokens['read']);
                $pass &= (__LOGIN_WRITE_TOKEN__ == $tokens['write']);
        
                $all_pass &= $pass;
                $test_harness_instance->write_log_entry('LOGIN TOKENS CHECK', $test_count++, $pass);
            }
            
            $last_access = $info['login']->last_access();
            $start_time = round($test_harness_instance->test_start_time);
            $now = time();
            $diff = abs($now - $last_access);
            
            $pass = $diff < __LOGIN_TIME_DIFF__;
        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('LOGIN LAST ACCESS CHECK ('.$diff.')', $test_count++, $pass);
            
            $pass = (__LOGIN_LANG__ == $info['login']->lang());
        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('LOGIN INFO LANG CHECK', $test_count++, $pass);
            
            $pass = (__LOGIN_WRITEABLE__ == $info['login']->writeable());
        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('LOGIN INFO WRITEABLE CHECK', $test_count++, $pass);
            
            $pass = (__LOGIN_MANAGER__ == $info['login']->is_manager());
        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('LOGIN INFO MANAGER CHECK', $test_count++, $pass);
            
            $pass = (__LOGIN_MAIN_ADMIN__ == $info['login']->is_main_admin());
        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('LOGIN INFO (NOT) MAIN ADMIN CHECK', $test_count++, $pass);
            
            $pass = (__LOGIN_LOGGED_IN__ == $info['login']->is_logged_in());
        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('LOGIN INFO IS LOGGED IN CHECK', $test_count++, $pass);
            
            $pass = (__LOGIN_TOKENS__ == $info['login']->security_tokens());
        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('LOGIN INFO TOKENS CHECK', $test_count++, $pass);
            
            $pass = (__LOGIN_USER_ID__ == $info['login']->user_object_id());
        
            $all_pass &= $pass;
            $test_harness_instance->write_log_entry('LOGIN INFO USER ID CHECK', $test_count++, $pass);
        } else {
            $pass = false;
            $all_pass= false;
            $test_harness_instance->write_log_entry('LOGIN INFO NAME CHECK', $test_count++, false);
        }
        
        if ($all_pass) {
            echo('<h4 style="color:green">LOGIN INFO TESTS PASS</h4>');
        } else {
            echo('<h4 style="color:red">LOGIN INFO TESTS FAILED!</h4>');
        }
        
        $user_pass = true;
        if (isset($info['user'])) {
            $pass = (__USER_NAME__ == $info['user']->name());
        
            $user_pass &= $pass;
            $test_harness_instance->write_log_entry('USER INFO NAME CHECK', $test_count++, $pass);

            $pass = (__USER_ID__ == $info['user']->id());
        
            $user_pass &= $pass;
            $test_harness_instance->write_log_entry('USER INFO ID CHECK', $test_count++, $pass);

            $coords = $info['user']->coords();
            
            if (isset($coords) && is_array($coords) && (2 == count($coords))) {
                $pass &= (__USER_COORDS_LAT__ == $coords['latitude']);
                $pass &= (__USER_COORDS_LNG__ == $coords['longitude']);
        
                $user_pass &= $pass;
                $test_harness_instance->write_log_entry('USER COORDINATES CHECK', $test_count++, $pass);
            }

            $tokens = $info['user']->security_tokens();
            
            if (isset($tokens) && is_array($tokens) && (2 == count($tokens))) {
                $pass &= (__USER_READ_TOKEN__ == $tokens['read']);
                $pass &= (__USER_WRITE_TOKEN__ == $tokens['write']);
        
                $user_pass &= $pass;
                $test_harness_instance->write_log_entry('USER TOKENS CHECK', $test_count++, $pass);
            }
            
            $last_access = $info['user']->last_access();
            $pass = $last_access == strtotime('1970-01-02 00:00:00');        
            $user_pass &= $pass;
            $test_harness_instance->write_log_entry('USER LAST ACCESS CHECK', $test_count++, $pass);
            
            $pass = (__USER_LANG__ == $info['user']->lang());
        
            $user_pass &= $pass;
            $test_harness_instance->write_log_entry('USER INFO LANG CHECK', $test_count++, $pass);
            
            $pass = (__USER_WRITEABLE__ == $info['user']->writeable());
        
            $user_pass &= $pass;
            $test_harness_instance->write_log_entry('USER INFO WRITEABLE CHECK', $test_count++, $pass);

            $payload = $info['user']->payload();
            
            if (isset($payload) && is_array($payload)) {
                $pass = false;
            }
           
            $user_pass &= $pass;
            $test_harness_instance->write_log_entry('USER (LACK OF) PAYLOAD CHECK', $test_count++, $pass);
            
            $sha = sha1(serialize($info['user']->children_ids()));
            $test_harness_instance->echo_sha_data($sha);
            $pass = ('a1238d8016abdc795b83f9740eb24d1c6191d39f' == $sha);
            $user_pass &= $pass;
            $test_harness_instance->write_log_entry('LOGIN INFO CHILD IDS CHECK', $test_count++, $pass);
            
            $pass = (NULL == $info['user']->parent_ids());
            
            $user_pass &= $pass;
            $test_harness_instance->write_log_entry('LOGIN INFO PARENT IDS CHECK', $test_count++, $pass);
        } else {
            $pass = false;
            $user_pass &= $pass;
            $test_harness_instance->write_log_entry('USER INFO NAME CHECK', $test_count++, $pass);
        }
        
        if ($user_pass) {
            echo('<h4 style="color:green">USER INFO TESTS PASS</h4>');
        } else {
            echo('<h4 style="color:red">USER INFO TESTS FAILED!</h4>');
        }
        
        $all_pass &= $user_pass;
    } else {
        $test_harness_instance->write_log_entry('LOGIN CHECK', false);
        echo('<h4 style="color:red">NOT LOGGED IN!</h4>');
    }
    
    $test_harness_instance->test_count = $test_count;
    
    return $all_pass;     
}
?>