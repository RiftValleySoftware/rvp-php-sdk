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
define('RVP_PHP_SDK', true);
require_once (dirname(dirname(__FILE__)).'/rvp_php_sdk.class.php');

define('LGV_CONFIG_CATCHER', true);
require_once (dirname(__FILE__).'/config/s_config.class.php');
define('__SERVER_URI__', 'http://localhost'.dirname($_SERVER['PHP_SELF']).'/baobab.php');
define('__SERVER_SECRET__', 'Supercalifragilisticexpialidocious');
define('__LOG_FILE__', dirname(__FILE__).'/test_log_file.csv');

class RVP_PHP_SDK_Test_Harness {
    var $sdk_instance = NULL;
    var $log_file = NULL;
    var $main_start_time = NULL;
    var $prep_time = NULL;
    var $test_time = NULL;
    var $prep_start_time = NULL;
    var $test_start_time = NULL;
    var $current_test_name = NULL;
    
    static function prepare_databases($in_file_prefix) {
        $ret = '';
        
        if ( !defined('LGV_DB_CATCHER') ) {
            define('LGV_DB_CATCHER', 1);
        }

        require_once(CO_Config::db_class_dir().'/co_pdo.class.php');

        if ( !defined('LGV_ERROR_CATCHER') ) {
            define('LGV_ERROR_CATCHER', 1);
        }

        require_once(CO_Config::badger_shared_class_dir().'/error.class.php');
    
        $pdo_data_db = NULL;
        
        try {
            $pdo_data_db = new CO_PDO(CO_Config::$data_db_type, CO_Config::$data_db_host, CO_Config::$data_db_name, CO_Config::$data_db_login, CO_Config::$data_db_password);
        } catch (Exception $exception) {
// die('<pre style="text-align:left">'.htmlspecialchars(print_r($exception, true)).'</pre>');
                    $error = new LGV_Error( 1,
                                            'INITIAL DATABASE SETUP FAILURE',
                                            'FAILED TO INITIALIZE A DATABASE!',
                                            $exception->getFile(),
                                            $exception->getLine(),
                                            $exception->getMessage());
                $ret = '<h2 style="color:red">ERROR WHILE TRYING TO ACCESS DATABASES!</h2>';
                $ret .= '<pre>'.htmlspecialchars(print_r($error, true)).'</pre>';
        }
    
        if ($pdo_data_db) {
            $pdo_security_db = new CO_PDO(CO_Config::$sec_db_type, CO_Config::$sec_db_host, CO_Config::$sec_db_name, CO_Config::$sec_db_login, CO_Config::$sec_db_password);
        
            if ($pdo_security_db) {
                $data_db_sql = file_get_contents(dirname(__FILE__).'/sql/'.$in_file_prefix.'_data_'.CO_Config::$data_db_type.'.sql');
                $security_db_sql = file_get_contents(dirname(__FILE__).'/sql/'.$in_file_prefix.'_security_'.CO_Config::$sec_db_type.'.sql');
            
                $error = NULL;
    
                try {
                    $pdo_data_db->preparedExec($data_db_sql);
                    $pdo_security_db->preparedExec($security_db_sql);
                } catch (Exception $exception) {
// die('<pre style="text-align:left">'.htmlspecialchars(print_r($exception, true)).'</pre>');
                    $error = new LGV_Error( 1,
                                            'INITIAL DATABASE SETUP FAILURE',
                                            'FAILED TO INITIALIZE A DATABASE!',
                                            $exception->getFile(),
                                            $exception->getLine(),
                                            $exception->getMessage());
                                                
                $ret = '<h2 style="color:red">ERROR WHILE TRYING TO OPEN DATABASES!</h2>';
                $ret .= '<pre>'.htmlspecialchars(print_r($error, true)).'</pre>';
                }
            }
        } else {
            $ret = '<h2 style="color:red">UNABLE TO OPEN DATABASE!</h2>';
        }
        
        return $ret;
    }

    static function prettify_json($json) {
        $json .= "\n";
        $result = '';
        $level = 0;
        $in_quotes = false;
        $in_escape = false;
        $ends_line_level = NULL;
        $json_length = strlen( $json );

        for( $i = 0; $i < $json_length; $i++ ) {
            $char = $json[$i];
            $new_line_level = NULL;
            $post = "";
            if( $ends_line_level !== NULL ) {
                $new_line_level = $ends_line_level;
                $ends_line_level = NULL;
            }
            if ( $in_escape ) {
                $in_escape = false;
            } else if( $char === '"' ) {
                $in_quotes = !$in_quotes;
            } else if( ! $in_quotes ) {
                switch( $char ) {
                    case '}': case ']':
                        $level--;
                        $ends_line_level = NULL;
                        $new_line_level = $level;
                        break;

                    case '{': case '[':
                        $level++;
                    case ',':
                        $ends_line_level = $level;
                        break;

                    case ':':
                        $post = " ";
                        break;

                    case " ": case "\t": case "\n": case "\r":
                        $char = "";
                        $ends_line_level = $new_line_level;
                        $new_line_level = NULL;
                        break;
                }
            } else if ( $char === '\\' ) {
                $in_escape = true;
            }
            if( ($new_line_level !== NULL) && (0 < $new_line_level) ) {
                $result .= "\n".str_repeat( "  ", $new_line_level );
            }
            $result .= $char.$post;
        }
    
        $result = trim(stripslashes(preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');}, $result)));
    
        return $result;
    }
    
    function open_log_file( $in_clear_file = false
                            ) {
        $this->log_file = fopen(__LOG_FILE__, ($in_clear_file ? 'w' : 'a'));
        
        if ($this->log_file && $in_clear_file) {
            $line = "test_number,test_name,log_message,test_passed,actual_time,current_test_time,total_prep_time,total_test_time\n";
            
            fwrite($this->log_file, $line);
        }
    }
    
    function close_log_file() {
        if ($this->log_file) {
            fclose($this->log_file);
            $this->log_file = NULL;
        }
    }
    
    function write_log_entry(   $in_message = '',
                                $in_pass_fail = true
                            ) {
        if ($this->log_file) {
            $test_timestamp = sprintf('%f', $this->test_time);
            $prep_timestamp = sprintf('%f', $this->prep_time);
            
            $timestamp = microtime(true);
            $main_timestamp = $this->main_start_time ? sprintf('%f', ($timestamp - $this->main_start_time)) : '0';
            $timestamp = sprintf('%f', $timestamp);
            
            $name = str_replace('"', '\\"', $this->current_test_name);
            $message = str_replace('"', '\\"', $in_message);
            $pass_fail = $in_pass_fail ? 'PASS' : 'FAIL';
            $index = strval($this->test_index);
            
            $line = "$index,'$name','$message','$pass_fail',$timestamp,$main_timestamp,$prep_timestamp,$test_timestamp\n";
            
            fwrite($this->log_file, $line);
        }
    }
    
    function __construct(   $in_function_manifest   ///< REQUIRED: A List of all the functions we need to call with this test.
                        ) {
        $this->log_file = NULL;
        $this->main_start_time = microtime(true);
        $this->prep_start_time = 0;
        $this->test_start_time = 0;
        $this->test_index = 0;
        
        $this->open_log_file(true);
        
        $this->current_test_name = '****';
        $this->write_log_entry('MAIN TEST START');
        
        $allpass = true;
        
        foreach ($in_function_manifest as $test) {
            $this_pass = true;
            
            $this->prep_time = 0;
            $this->test_time = 0;
            $this->test_start_time = 0;
            $this->prep_start_time = microtime(true);
            
            $blurb = (isset($test['blurb']) && trim($test['blurb'])) ? trim($test['blurb']) : 'Unnamed Test';
            $explain = isset($test['explain']) ? $test['explain'] : NULL;
            $db_prefix = isset($test['db']) ? $test['db'] : NULL;
            $login_setup = isset($test['login']) ? $test['login'] : NULL;
            
            $this->test_index++;
            $this->current_test_name = $blurb;
            $this->write_log_entry('START PREP');
            $main_id = 'test_wrapper_'.$this->test_index.'_'.uniqid();
            
            echo('<div id="'.$main_id.'" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\''.$main_id.'\')">TEST SET '.$this->test_index.': '.htmlspecialchars($blurb).'</a></h2>');
            echo('<div class="container">');
            
            if (isset($db_prefix) && $db_prefix) {
                echo('<h3>Preparing the "'.htmlspecialchars($db_prefix).'" Databases.</h3>');
                $result = self::prepare_databases($db_prefix);
                if (!$result) {
                    echo('<h3>Databases Ready.</h3>');
                } else {
                    $this->write_log_entry('FAILED DATABASE SETUP', false);
                    echo($result);
                }
            }
            
            $logout = false;
            
            if (isset($login_setup)) {
                if (isset($login_setup['logout']) && $login_setup['logout']) {
                    $logout = true;
                }
                
                echo('<h3>Preparing the SDK.</h3>');
                $this->sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__, $login_setup['login_id'], $login_setup['password'], $login_setup['timeout']);
                
                if ($this->sdk_instance->is_logged_in()) {
                    echo('<h3>SDK Ready And Logged In. There are '.$this->sdk_instance->login_time_left().' Seconds Left.</h3>');
                } else {
                    $this->write_log_entry('FAILED SDK LOGIN', false);
                    echo('<h3 style="color:red">SDK NOT LOGGED IN!</h3>');
                }
            } else {
                $this->sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__);
                echo('<h3>SDK Ready, But Not Logged In.</h3>');
            }
            
            $function = $test['closure']['function'];
            $function_file = $test['closure']['file'];
            include_once($function_file);
            $this->test_start_time = microtime(true);
            $this->prep_time = $this->test_start_time - $this->prep_start_time;
            $this->write_log_entry('END PREP - START TEST');
            
            if (is_array($function)) {
                $thispass = $function[0]->$function[1]($this);
            } else {
                $thispass = $function($this);
            }
            
            $allpass &= $thispass;
            
            if ($logout && $this->sdk_instance && $this->sdk_instance->is_logged_in()) {
                echo('<h3>SDK Logged Out.</h3>');
                $this->sdk_instance->logout();
            }
            
            $this->test_time = microtime(true) - $this->test_start_time;
            
            $this->write_log_entry('END TEST', $thispass);
            
            echo('</div>');
            echo('</div>');
        }
        
        $this->prep_time = 0;
        $this->test_time = 0;
        $this->test_index = 0;
        $this->current_test_name = '****';
        $this->write_log_entry('MAIN TEST END', $allpass);
        
        $this->close_log_file();
    }
};
?>