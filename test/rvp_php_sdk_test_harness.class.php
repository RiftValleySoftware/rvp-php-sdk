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

class RVP_PHP_SDK_Test_Harness {
    var $sdk_instance = NULL;
    
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
                $ret = '<h1 style="color:red">ERROR WHILE TRYING TO ACCESS DATABASES!</h1>';
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
                                                
                $ret = '<h1 style="color:red">ERROR WHILE TRYING TO OPEN DATABASES!</h1>';
                $ret .= '<pre>'.htmlspecialchars(print_r($error, true)).'</pre>';
                }
            }
        } else {
            $ret = '<h1 style="color:red">UNABLE TO OPEN DATABASE!</h1>';
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
    
    function __construct(   $in_function_list   ///< REQUIRED: A List of all the functions we need to call with this test.
                        ) {
        $server_uri = 'http://localhost/'.dirname($_SERVER['PHP_SELF']).'/baobab.php';
        $server_secret = 'Supercalifragilisticexpialidocious';
        
        foreach ($in_function_list as $test) {
            $blurb = $test['blurb'];
            
            if (isset($blurb)) {
                echo('<h1>'.htmlspecialchars($blurb).'</h1>');
            }
            
            $db_prefix = $test['db'];
            
            if (isset($db_prefix) && $db_prefix) {
                echo('<h2>Preparing the "'.htmlspecialchars($db_prefix).'" Databases.</h2>');
                $result = self::prepare_databases($db_prefix);
                if (!$result) {
                    echo('<h2>Databases Ready.</h2>');
                } else {
                    echo($result);
                }
            }
            
            $login = $test['login'];
            
            if (isset($login) && is_array($login) && (3 == count($login))) {
                echo('<h2>Preparing the SDK.</h2>');
                $this->sdk_instance = new RVP_PHP_SDK($server_uri, $server_secret, $login[0], $login[1], $login[2]);
                if ($this->sdk_instance->is_logged_in()) {
                    echo('<h2>SDK Ready.</h2>');
                } else {
                    echo('<h2 style="color:red">SDK NOT READY!</h2>');
                }
            }
            
            $function = $test['closure'];
            if (is_array($function)) {
                $function[0]->$function[1]($this);
            } else {
                $function($this);
            }
        }
    }
};
?>