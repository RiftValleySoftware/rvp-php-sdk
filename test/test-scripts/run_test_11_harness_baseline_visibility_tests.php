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
define('__CREATE_FILE__', false);

function run_test_11_harness_baseline_visibility_tests($test_harness_instance) {
    function run_test_11_harness_baseline_visibility_tests_test_visibility($in_sdk_instance, $test_harness_instance, $in_login_id, $in_element_1, $in_element_2, $in_test_count) {        
        $index = 0;
        
        foreach (__TEST_11_OBJECT_IDS__ as $id) {
            $test_compare = $in_element_1[$index++];
            $test_result = $in_sdk_instance->test_visibility($id);
            if (isset($test_result) && is_array($test_result) && count($test_result)) {
                if (    $test_result['id'] == $test_compare['id']
                    &&  (!isset($test_result['writeable']) && !isset($test_compare['writeable'])) || (isset($test_result['writeable']) && ($test_result['writeable'] == $test_compare['writeable']))
                    &&  (!isset($test_result['read_login_ids']) && !isset($test_compare['read_login_ids'])) || (isset($test_result['read_login_ids']) && ($test_result['read_login_ids'] == $test_compare['read_login_ids']))
                    &&  (!isset($test_result['write_login_ids']) && !isset($test_compare['write_login_ids'])) || (isset($test_result['write_login_ids']) && ($test_result['write_login_ids'] == $test_compare['write_login_ids']))
                ) {
                    $test_harness_instance->write_log_entry("$in_login_id ID ($id) Test", $in_test_count++, true);
                } else {
                    $test_harness_instance->write_log_entry("$in_login_id ID ($id) Test", $in_test_count++, false);
                    echo('<h4 style="color:red">RESPONSE DATA INVALID!</h4>');
                }
            }
        }
        $index = 0;
        foreach (__TEST_11_TOKEN_IDS__ as $id) {
            $test_result = $in_sdk_instance->test_visibility($id, true);
            if (isset($test_result) && is_array($test_result) && count($test_result)) {
                $test_compare = $in_element_2[$index++];
                if ($test_result == $test_compare) {
                    $test_harness_instance->write_log_entry("$in_login_id Token ($id) Test", $in_test_count++, true);
                } else {
                    $test_harness_instance->write_log_entry("$in_login_id Token ($id) Test", $in_test_count++, false);
                    echo('<h4 style="color:red">RESPONSE DATA INVALID!</h4>');
                }
            }
        }
    
        return $in_test_count;
    }

    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            echo('<h4>Go through each login, and test visibility on all the assets:</h4>');
            if (__CREATE_FILE__) {
                $file_handle = fopen(dirname(__FILE__).'/run_test_11_harness_baseline_visibility_tests_results.php', 'w');
                fwrite($file_handle, "<?php");
            } else {
                include(dirname(__FILE__).'/run_test_11_harness_baseline_visibility_tests_results.php');
            }
            
            foreach (__TEST_LOGINS__ as $category => $list) {
                echo('<h5>'.$category.':</h5>');
                $timeout = ('God User' != $category) ? CO_Config::$session_timeout_in_seconds : CO_Config::$god_session_timeout_in_seconds;
                foreach ($list as $login_id) {
                    echo('<h6>Logging In '.$login_id.':</h6>');
                    $temp_sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__, $login_id, __PASSWORD__, $timeout);
                    if (__CREATE_FILE__) {
                        $test_count = create_test_11_harness_baseline_visibility_tests_test_visibility_file($temp_sdk_instance, $login_id, $test_count, $file_handle);
                    } else {
                        $variable_name_1 = "visibility_id_result_array_$login_id";
                        $variable_name_2 = "visibility_token_result_array_$login_id";
                        $test_count = run_test_11_harness_baseline_visibility_tests_test_visibility($temp_sdk_instance, $test_harness_instance, $login_id, $$variable_name_1, $$variable_name_2, $test_count);
                    }
                    $temp_sdk_instance = NULL;
                }
            }
            if (__CREATE_FILE__) {
                fwrite($file_handle, "\n?>\n");
                fclose($file_handle);
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

function create_test_11_harness_baseline_visibility_tests_test_visibility_file($in_sdk_instance, $in_login_id, $in_test_count, $file_handle) {
    fwrite($file_handle, "\n\n\$visibility_id_result_array_$in_login_id = [");
    
    foreach (__TEST_11_OBJECT_IDS__ as $id) {
        fwrite($file_handle, "\n\t\t[");
        fwrite($file_handle, "\n\t\t'id' => $id,");
        $test_result = $in_sdk_instance->test_visibility($id);
        if (isset($test_result['writeable'])) {
            fwrite($file_handle, "\n\t\t'writeable' => ".(isset($test_result['writeable']) && $test_result['writeable'] ? 'true' : 'false').",");
        }
        if (isset($test_result['read_login_ids']) && is_array($test_result['read_login_ids']) && count($test_result['read_login_ids'])) {
            fwrite($file_handle, "\n\t\t'read_login_ids' => [");
            $ids = implode(',', $test_result['read_login_ids']);
            fwrite($file_handle, $ids);
            fwrite($file_handle, "],");
        }
        
        if (isset($test_result['write_login_ids']) && is_array($test_result['write_login_ids']) && count($test_result['write_login_ids'])) {
            fwrite($file_handle, "\n\t\t'write_login_ids' => [");
            $ids = implode(',', $test_result['write_login_ids']);
            fwrite($file_handle, $ids);
            fwrite($file_handle, "],");
        }
        fwrite($file_handle, "\n\t\t],");
    }
    fwrite($file_handle, "\n\t];");
    
    fwrite($file_handle, "\n\n\$visibility_token_result_array_$in_login_id = [");
    foreach (__TEST_11_TOKEN_IDS__ as $id) {
        $test_result = $in_sdk_instance->test_visibility($id, true);
        if (isset($test_result)) {
            fwrite($file_handle, "\n\t\t[");
            $ids = implode(',', $test_result);
            fwrite($file_handle, $ids);
            fwrite($file_handle, "],");
        }
    }
    fwrite($file_handle, "\n\t];");
    
    return $in_test_count;
}
?>