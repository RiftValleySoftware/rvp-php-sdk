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
define('__TEST_32_PASSWORD__', 'CoreysGoryStory');
define('__TEST_32_GOD_LOGIN__', 'admin');
define('__TEST_32_LOGIN_1__', 'MDAdmin');
define('__TEST_32_LOGIN_1_ID__', 1725);
define('__TEST_32_LOGIN_2_ID__', 1726);
define('__TEST_32_LOGIN_3_ID__', 1727);

function run_test_32_child_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance) && $test_harness_instance->sdk_instance->valid()) {
        $test_harness_instance->write_log_entry('INSTANTIATION CHECK', $test_count++, true);
        $all_pass = combine_users($test_harness_instance, $test_count);
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

function combine_users($test_harness_instance, &$test_count) {
    $all_pass = true;
    
    $god_admin_login = $test_harness_instance->new_login_instance(__TEST_32_GOD_LOGIN__, __TEST_32_PASSWORD__, false);
    $test_count = $test_harness_instance->test_count;
    
    if (!$god_admin_login) {
        $all_pass = false;
    } else {
        $dc_admin_user = $god_admin_login->get_objects(__TEST_32_LOGIN_3_ID__);
        if (is_array($dc_admin_user) && (1 == count($dc_admin_user))) {
            $dc_admin_user = $dc_admin_user[0];
            $test_harness_instance->write_log_entry('Get the DCAdmin object.', $test_count++, true);
        } else {
            $dc_admin_user = NULL;
            $all_pass = false;
            $test_harness_instance->write_log_entry('Get the DCAdmin object.', $test_count++, false);
            echo('<h4 style="color:red">Could not Get the DCAdmin Object!</h4>');
        }
        
        $va_admin_user = $god_admin_login->get_objects(__TEST_32_LOGIN_2_ID__);
        if (is_array($va_admin_user) && (1 == count($va_admin_user))) {
            $va_admin_user = $va_admin_user[0];
            $test_harness_instance->write_log_entry('Get the VAAdmin object.', $test_count++, true);
        } else {
            $va_admin_user = NULL;
            $all_pass = false;
            $test_harness_instance->write_log_entry('Get the VAAdmin object.', $test_count++, false);
            echo('<h4 style="color:red">Could not Get the DCAdmin Object!</h4>');
        }
        
        $md_admin_user = $god_admin_login->get_objects(__TEST_32_LOGIN_1_ID__);
        if (is_array($md_admin_user) && (1 == count($md_admin_user))) {
            $md_admin_user = $md_admin_user[0];
            $test_harness_instance->write_log_entry('Get the MDAdmin object.', $test_count++, true);
        } else {
            $md_admin_user = NULL;
            $all_pass = false;
            $test_harness_instance->write_log_entry('Get the MDAdmin object.', $test_count++, false);
            echo('<h4 style="color:red">Could not Get the MDAdmin Object!</h4>');
        }
        
        if ($md_admin_user && $va_admin_user && $dc_admin_user) {
            if ($va_admin_user->set_new_children_ids([$dc_admin_user->id()])) {
                $test_harness_instance->write_log_entry('Add the DC Admin to the VA Admin.', $test_count++, true);
            
                if ($md_admin_user->set_new_children_ids([$va_admin_user->id()])) {
                    $test_harness_instance->write_log_entry('Add the VA Admin to the MD Admin.', $test_count++, true);
                    $hierarchy = $md_admin_user->get_hierarchy();
                    $all_pass = display_hierarchy($test_harness_instance, $hierarchy);
                    if ($all_pass) {
                        $test_harness_instance->write_log_entry('Displaying the hierarchy.', $test_count++, true);
                    } else {
                        $test_harness_instance->write_log_entry('Displaying the hierarchy.', $test_count++, false);
                        echo('<h4 style="color:red">Hieararchy Crawl Failed!</h4>');
                    }
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('Add the VA Admin to the MD Admin.', $test_count++, false);
                    echo('<h4 style="color:red">Could not Add the VA Admin to the MD Admin!</h4>');
                }
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('Add the DC Admin to the VA Admin.', $test_count++, false);
                echo('<h4 style="color:red">Could not Add the DC Admin to the VA Admin!</h4>');
            }
        }
    }
    
    return $all_pass;
}

function display_hierarchy($test_harness_instance, $in_hierarchy) {
    $all_pass = true;
    if (isset($in_hierarchy) && is_array($in_hierarchy) && (0 < count($in_hierarchy)) && isset($in_hierarchy['object']) && ($in_hierarchy['object'] instanceof A_RVP_PHP_SDK_Data_Object)) {
        if (isset($in_hierarchy['children']) && count($in_hierarchy['children'])) {
            $display_id = uniqid();
            echo('<div id="'.$display_id.'" class="inner_closed">');
            echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\''.$display_id.'\')">');
        } else {
            echo('<div>');
            echo('<code>');
        }
        echo(htmlspecialchars($in_hierarchy['object']->name()));
        if (isset($in_hierarchy['children']) && count($in_hierarchy['children'])) {
            echo(' ('.count($in_hierarchy['children']).' children)</a></h3>');
        } else {
            echo('</code>');
        }
        if (isset($in_hierarchy['children']) && count($in_hierarchy['children'])) {
            echo('<div class="main_div inner_container">');
            foreach($in_hierarchy['children'] as $child) {
                $all_pass = display_hierarchy($test_harness_instance, $child);
                if (!$all_pass) {
                    break;
                }
            }
            echo('</div>');
        }
        echo('</div>');
    } else {
        $all_pass = false;
        echo('<h4 style="color:red">Invalid Admin Object!</h4>');
    }
    
    return $all_pass;
}
?>