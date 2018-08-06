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
define('LGV_CONFIG_CATCHER', true);
require_once (dirname(__FILE__).'/config/s_config.class.php');
set_time_limit(3600);
define('__SECURITY_START__', 3);
define('__DATA_START__', 2);

/****************************************************************************************************************************/
/*####################################################### PHP HANDLERS #####################################################*/
/****************************************************************************************************************************/
/***********************/
/**
 */
function process_bmlt_data( $in_output_file_handle,
                            $in_account_file_handle,
                            $in_meetings_object,
                            $in_formats_object,
                            $in_service_bodies_object,
                            $in_security_db_offset = __SECURITY_START__,
                            $in_data_db_offset = __DATA_START__
                            ) {
    sort_bmlt_data($in_meetings_object, $in_formats_object, $in_service_bodies_object);
    $service_body_hierarchy = find_my_children($in_service_bodies_object);
    echo('<h3>Creating Logins.</h3>');
    generate_user_logins($service_body_hierarchy, $in_security_db_offset, $in_output_file_handle, $in_account_file_handle);
    echo('<h3>Creating Service Body Objects.</h3>');
    generate_service_body_objects($in_meetings_object, $service_body_hierarchy, $in_data_db_offset, $in_output_file_handle);
}

/***********************/
/**
 */
function find_my_children(  $in_service_bodies_object,
                            $current_object = NULL,
                            $in_id = 0
                        ) {
    $ret = ['object' => $current_object, 'children' => []];
    
    foreach ($in_service_bodies_object as $sb) {
        if ($sb->parent_id == $in_id) {
            $ret['children'][] = find_my_children($in_service_bodies_object, $sb, $sb->id);
        }
    }
    
    return $ret;
}

/***********************/
/**
 */
function generate_user_logins(  &$in_service_body_hierarchy,
                                &$current_id,
                                $in_output_file_handle,
                                $in_account_file_handle
                            ) {
    $ret = [];
    
    if (isset($in_service_body_hierarchy['children']) && is_array($in_service_body_hierarchy['children']) && count($in_service_body_hierarchy['children'])) {
        foreach ($in_service_body_hierarchy['children'] as $child) {
            if (isset($child)) {
                $tokens = generate_user_logins($child, $current_id, $in_output_file_handle, $in_account_file_handle);
                $manager = (isset($tokens) && is_array($tokens) && count($tokens));
                $tokens = generate_user_login($child['object'], $current_id++, $tokens, $in_output_file_handle, $in_account_file_handle, $manager);
                $ret = array_merge($ret, $tokens);
            }
        }
    }
    
    return $ret;
}

/***********************/
/**
 */
function generate_user_login(   &$in_sb_object,
                                $in_id,
                                $in_tokens,
                                $in_output_file_handle,
                                $in_account_file_handle,
                                $is_manager
                            ) {
    $line = [];
    $in_tokens = array_map('intval', $in_tokens);
    $in_tokens = array_filter($in_tokens, function($a) { return 0 < $a; });
    $ret = $in_tokens;
    
    if (NULL != $in_sb_object) {
        $ret[] = intval($in_id);
        $context = ['lang' => 'en'];
        $password = generate_random_password();
        $context['hashed_password'] = password_hash($password, PASSWORD_DEFAULT);
        $in_sb_object->admin_login_id = $in_id;

        $line['id'] = strval($in_id++);
        $line['api_key'] = 'NULL';
        $line['login_id'] = 'login-'.$line['id'];
        $line['access_class'] = $is_manager ? 'CO_Login_Manager' : 'CO_Cobra_Login';
        $line['last_access'] = date('Y-m-d H:i:s');
        $line['read_security_id'] = strval($line['id']);
        $line['write_security_id'] = strval($line['id']);
        $line['object_name'] = $in_sb_object->name.' Administrator';
        $line['access_class_context'] = serialize($context);
        $line['owner'] = 'NULL';
        $line['longitude'] = 'NULL';
        $line['latitude'] = 'NULL';
        $line['tag0'] = 'NULL';
        $line['tag1'] = 'NULL';
        $line['tag2'] = 'NULL';
        $line['tag3'] = 'NULL';
        $line['tag4'] = 'NULL';
        $line['tag5'] = 'NULL';
        $line['tag6'] = 'NULL';
        $line['tag7'] = 'NULL';
        $line['tag8'] = 'NULL';
        $line['tag9'] = 'NULL';
        $line['ids'] = (isset($in_tokens) && is_array($in_tokens) && count($in_tokens)) ? implode(',', array_map('strval', $in_tokens)) : 'NULL';
        $line['payload'] = 'NULL';
        
        write_csv_line($in_output_file_handle, $line);
        write_csv_line($in_account_file_handle, [$line['object_name'], $line['login_id'], $password]);
    }
    
    return $ret;
}

/***********************/
/**
 */
function generate_random_password() {
//     return substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, CO_Config::$min_pw_len + 5);
    return 'CoreysGoryStory';
}

/***********************/
/**
 */
function generate_service_body_objects( $in_meetings_object,
                                        $in_service_body_hierarchy,
                                        &$current_id,
                                        $in_output_file_handle
                                    ) {
    if (isset($in_service_body_hierarchy['children']) && is_array($in_service_body_hierarchy['children']) && count($in_service_body_hierarchy['children'])) {
        foreach ($in_service_body_hierarchy['children'] as $child) {
            if (isset($child)) {
                generate_service_body_object($in_meetings_object, $child, $current_id, $in_output_file_handle);
            }
        }
    }
}

/***********************/
/**
 */
function generate_service_body_object(  $in_meetings_object,
                                        $in_object,
                                        &$in_id,
                                        $in_output_file_handle
                                    ) {
    $object = $in_object['object'];
    $owner_id = intval($object->admin_login_id);
    
    if (0 == $owner_id) {
        $owner_id = CO_Config::god_mode_id();
    }
    
    $context = ['lang' => 'en'];
    
    $children = [];
    
    if (isset($in_object['children']) && is_array($in_object['children']) && count($in_object['children'])) {
        foreach ($in_object['children'] as $child) {
            $in_id = generate_service_body_object($in_meetings_object, $child, $in_id, $in_output_file_handle);
            $children[] = $in_id;
        }
    }
    
    if (isset($children) && is_array($children) && count($children)) {
        $context['children'] = implode(',', $children);
    }
    
    $line['id'] = strval($in_id++);
    $line['api_key'] = 'NULL';
    $line['login_id'] = 'NULL';
    $line['access_class'] = 'CO_KeyValue_CO_Collection';
    $line['last_access'] = date('Y-m-d H:i:s');
    $line['read_security_id'] = 0;
    $line['write_security_id'] = $owner_id;
    $line['object_name'] = isset($object-> name) ? $object->name : 'No Name';
    $line['access_class_context'] = serialize($context);
    $line['owner'] = 'NULL';
    $line['longitude'] = 'NULL';
    $line['latitude'] = 'NULL';
    $line['tag0'] = 'bmlt-service-body-'.$line['id'];
    $line['tag1'] = 'NULL';
    $line['tag2'] = 'NULL';
    $line['tag3'] = 'NULL';
    $line['tag4'] = 'NULL';
    $line['tag5'] = 'NULL';
    $line['tag6'] = 'NULL';
    $line['tag7'] = 'NULL';
    $line['tag8'] = 'NULL';
    $line['tag9'] = 'NULL';
    $line['ids'] = 'NULL';
    $line['payload'] = 'NULL';
        
    write_csv_line($in_output_file_handle, $line);
    
    return $in_id;
}

/***********************/
/**
 */
function process_bmlt_files() {
    $meetings_file = dirname(__FILE__).'/BMLT/Meetings.json';
    $formats_file = dirname(__FILE__).'/BMLT/Formats.json';
    $service_bodies_file = dirname(__FILE__).'/BMLT/ServiceBodies.json';
    $output_file = dirname(__FILE__).'/BMLT/bmlt.csv';
    $account_file = dirname(__FILE__).'/BMLT/bmlt-accounts.csv';
    
    if (file_exists($output_file)) {
        unlink($output_file);
    }
    
    if (file_exists($account_file)) {
        unlink($account_file);
    }
    
    if (file_exists($meetings_file) && file_exists($formats_file) && file_exists($service_bodies_file)) {
        $meetings_object = (array)(json_decode(file_get_contents($meetings_file))->meetings);
        $formats_object = (array)json_decode(file_get_contents($formats_file));
        $service_bodies_object = (array)json_decode(file_get_contents($service_bodies_file));
        
        if (isset($meetings_object) && isset($formats_object) && isset($service_bodies_object)) {
            echo('<h2>BMLT DATA READ AND INSTANTIATED</h2>');
            $output_file_handle = fopen($output_file, 'w');
            
            if ($output_file_handle) {
                $line = ['id','api_key','login_id','access_class','last_access','read_security_id','write_security_id','object_name','access_class_context','owner','longitude','latitude','tag0','tag1','tag2','tag3','tag4','tag5','tag6','tag7','tag8','tag9','ids','payload'];
                write_csv_line($output_file_handle, $line);

                $account_file_handle = fopen($account_file, 'w');
            
                if ($account_file_handle) {
                    $line = ['object_name','login_id','password'];
                    write_csv_line($account_file_handle, $line);
                    
                    echo('<h3>Output Files Created. Starting Processing</h3>');
                    process_bmlt_data($output_file_handle, $account_file_handle, $meetings_object, $formats_object, $service_bodies_object);
                    fclose($account_file_handle);
                    echo('<h3>Completed Processing. Account File Closed</h3>');
                } else {
                    echo('<h2>UNABLE TO CREATE ACCOUNT FILE</h2>');
                }
                
                fclose($output_file_handle);
                echo('<h3>Output File Closed</h3>');
            } else {
                echo('<h2>UNABLE TO CREATE OUTPUT FILE</h2>');
            }
        } else {
            echo('<h2>UNABLE TO INSTANTIATE OBJECTS</h2>');
        }
    } else {
        echo('<h2>PROPER FILES DO NOT EXIST</h2>');
    }
}

/***********************/
/**
 */
function sort_bmlt_data(    &$in_meetings_object,
                            &$in_formats_object,
                            &$in_service_bodies_object
                            ) {
    function sort_callback($a, $b) {
        if (isset($a->root_server_id) && isset($b->root_server_id)) {
            $rs_id_a = intval($a->root_server_id);
            $service_body_a = isset($a->service_body_bigint) ? intval($a->service_body_bigint) : 0;
            $id_a = isset($a->id_bigint) ? intval($a->id_bigint) : (isset($a->id) ? intval($a->id) : 0);
        
            $rs_id_b = intval($b->root_server_id);
            $service_body_b = isset($b->service_body_bigint) ? intval($b->service_body_bigint) : 0;
            $id_b = isset($b->id_bigint) ? intval($b->id_bigint) : (isset($b->id) ? intval($b->id) : 0);

            if ($rs_id_a < $rs_id_b) {
                return -1;
            } elseif ($rs_id_a > $rs_id_b) {
                return 1;
            } else {
                if ($service_body_a < $service_body_b) {
                    return -1;
                } elseif ($service_body_a > $service_body_b) {
                    return 1;
                } else {
                    if ($id_a < $id_b) {
                        return -1;
                    } elseif ($id_a > $id_b) {
                        return 1;
                    }
                }
            }
        }
        
        return 0;
    }
                                
    usort($in_meetings_object, 'sort_callback');
    usort($in_formats_object, 'sort_callback');
    usort($in_service_bodies_object, 'sort_callback');
}

/***********************/
/**
 */
function write_csv_line(    $in_file_handle,
                            $in_line
                        ) {
    fputcsv($in_file_handle, $in_line);
}

/****************************************************************************************************************************/
/*##################################################### MAIN HTML PAGE #####################################################*/
/****************************************************************************************************************************/
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>PROCESS BMLT</title>
        <link rel="shortcut icon" href="../images/BAOBAB.png" type="image/png" />
    </head>
    <body>
        <h1 style="text-align:center">BLUE DRAGON TEST BMLT PROCESSOR</h1>
        <div style="text-align:center;padding:1em;">
            <img src="../images/BAOBAB.png" style="display:block;margin:auto;width:80px" alt="BAOBAB Logo" />
            <div style="display:table;margin:auto;text-align:left"><?php process_bmlt_files(); ?></div>
        </div>
        <h3 style="text-align:center"><a href="./">RETURN TO MAIN ENVIRONMENT SETUP</a></h3>
    </body>
</html>