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
define('RVP_PHP_SDK', true);
require_once (dirname(dirname(__FILE__)).'/rvp_php_sdk.class.php');

define('LGV_CONFIG_CATCHER', true);
require_once (dirname(__FILE__).'/config/s_config.class.php');
define('__SERVER_URI__', 'http://localhost'.dirname($_SERVER['PHP_SELF']).'/baobab.php');
define('__SERVER_SECRET__', 'Supercalifragilisticexpialidocious');
define('__LOG_FILE__', dirname(__FILE__).'/tmp/test_log_file.csv');

class RVP_PHP_SDK_Test_Harness {
    var $sdk_instance = NULL;
    var $log_file = NULL;
    var $main_start_time = NULL;
    var $prep_time = NULL;
    var $test_time = NULL;
    var $prep_start_time = NULL;
    var $test_start_time = NULL;
    var $current_test_name = NULL;
    var $test_count = 0;
    
	/*******************************************************************/
	/**
		\brief Uses the Vincenty calculation to determine the distance (in Kilometers) between the two given lat/long pairs (in Degrees).
		
		The Vincenty calculation is more accurate than the Haversine calculation, as it takes into account the "un-spherical" shape of the Earth, but is more computationally intense.
		We use this calculation to refine the Haversine "triage" in SQL.
		
		\returns a Float with the distance, in Kilometers.
	*/
	static function get_accurate_distance (	$lat1,  ///< This is the first point latitude (degrees).
                                            $lon1,  ///< This is the first point longitude (degrees).
                                            $lat2,  ///< This is the second point latitude (degrees).
                                            $lon2   ///< This is the second point longitude (degrees).
                                        )
	{
	    if (($lat1 == $lat2) && ($lon1 == $lon2)) { // Just a quick shortcut.
	        return 0;
	    }
	    
		$a = 6378137;
		$b = 6356752.3142;
		$f = 1/298.257223563;  // WGS-84 ellipsiod
		$L = ($lon2-$lon1)/57.2957795131;
		$U1 = atan((1.0-$f) * tan($lat1/57.2957795131));
		$U2 = atan((1.0-$f) * tan($lat2/57.2957795131));
		$sinU1 = sin($U1);
		$cosU1 = cos($U1);
		$sinU2 = sin($U2);
		$cosU2 = cos($U2);
		  
		$lambda = $L;
		$lambdaP = $L;
		$iterLimit = 100;
		
		do {
			$sinLambda = sin($lambda);
			$cosLambda = cos($lambda);
			$sinSigma = sqrt(($cosU2*$sinLambda) * ($cosU2*$sinLambda) + ($cosU1*$sinU2-$sinU1*$cosU2*$cosLambda) * ($cosU1*$sinU2-$sinU1*$cosU2*$cosLambda));
    		if ($sinSigma==0)
    			{
    			return true;  // co-incident points
    			}
			$cosSigma = $sinU1*$sinU2 + ($cosU1*$cosU2*$cosLambda);
			$sigma = atan2($sinSigma, $cosSigma);
			$sinAlpha = ($cosU1 * $cosU2 * $sinLambda) / $sinSigma;
			$cosSqAlpha = 1.0 - $sinAlpha*$sinAlpha;
			$cos2SigmaM = $cosSigma - 2.0*$sinU1*$sinU2/$cosSqAlpha;
			$C = $f/(16.0*$cosSqAlpha*(4.0+$f*(4.0-3.0*$cosSqAlpha)));
			$lambdaP = $lambda;
			$lambda = $L + (1.0-$C) * $f * $sinAlpha * ($sigma + $C*$sinSigma*($cos2SigmaM+$C*$cosSigma*(-1.0+2.0*$cos2SigmaM*$cos2SigmaM)));
			} while (abs($lambda-$lambdaP) > 1e-12 && --$iterLimit>0);

		$uSq = $cosSqAlpha * ($a*$a - $b*$b) / ($b*$b);
		$A = 1.0 + $uSq/16384.0*(4096.0+$uSq*(-768.0+$uSq*(320.0-175.0*$uSq)));
		$B = $uSq/1024.0 * (256.0+$uSq*(-128.0+$uSq*(74.0-47.0*$uSq)));
		$deltaSigma = $B*$sinSigma*($cos2SigmaM+$B/4.0*($cosSigma*(-1.0+2.0*$cos2SigmaM*$cos2SigmaM)-$B/6.0*$cos2SigmaM*(-3.0+4.0*$sinSigma*$sinSigma)*(-3.0+4.0*$cos2SigmaM*$cos2SigmaM)));
		$s = $b*$A*($sigma-$deltaSigma);
  		
		return ( abs ( round ( $s ) / 1000.0 ) ); 
	}
	
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

    static function in_array_r($needle, $haystack, $strict = false) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && RVP_PHP_SDK_Test_Harness::in_array_r($needle, $item, $strict))) {
                return true;
            }
        }

        return false;
    }

    static function are_arrays_equal(   $in_array_1,
                                        $in_array_2
                                    ) {
                                     
        $diff = [];
        
        foreach ($in_array_1 as $element) {
            if (!RVP_PHP_SDK_Test_Harness::in_array_r($element, $in_array_2)) {
                $diff[] = $element;
            }
        }
        
        foreach ($in_array_2 as $element) {
            if (!RVP_PHP_SDK_Test_Harness::in_array_r($element, $in_array_1)) {
                $diff[] = $element;
            }
        }

        return 0 == count($diff);
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
            $line = "test_suite_number,test_number,test_name,data_db_type,security_db_type,log_message,test_passed,actual_time,current_test_time,total_prep_time,total_test_time\n";
            
            fwrite($this->log_file, $line);
        }
    }
    
    function echo_sha_data($in_sha) {
        echo('<p><strong>SHA:</strong> <big><code>'.$in_sha.'</code></big></p>');
    }
    
    function close_log_file() {
        if ($this->log_file) {
            fclose($this->log_file);
            $this->log_file = NULL;
        }
    }
    
    function write_log_entry(   $in_message = '',
                                $in_num = 0,
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
            $num = (0 == $in_num) ? '*' : intval($in_num);
            $line = "$index,$num,'$name','".CO_Config::$data_db_type."','".CO_Config::$sec_db_type."','$message','$pass_fail',$timestamp,$main_timestamp,$prep_timestamp,$test_timestamp\n";
            
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
        $this->test_count = 0;
        
        foreach ($in_function_manifest as $test) {
            $this_pass = true;
            
            $this->prep_time = 0;
            $this->test_time = 0;
            $this->test_start_time = 0;
            $this->prep_start_time = microtime(true);
            
            $blurb = (isset($test['blurb']) && trim($test['blurb'])) ? trim($test['blurb']) : 'Unnamed Test';
            $explain = isset($test['explain']) ? trim($test['explain']) : NULL;
            $db_prefix = isset($test['db']) ? $test['db'] : NULL;
            $login_setup = isset($test['login']) ? $test['login'] : NULL;
            
            $this->test_index++;
            $this->current_test_name = $blurb;
            $this->write_log_entry('START PREP');
            $main_id = 'test_wrapper_'.$this->test_index.'_'.uniqid();
            
            echo('<div id="'.$main_id.'" class="closed">');
            echo('<h2 class="header"><a href="javascript:toggle_main_state(\''.$main_id.'\')">TEST SET '.$this->test_index.': '.htmlspecialchars($blurb).'</a></h2>');
            
            echo('<div class="container">');
            
            if ($explain) {
                echo('<div class="explain">'.htmlspecialchars($explain).'</div>');
            }
            
            if (isset($db_prefix) && $db_prefix) {
                echo('<h3>Preparing the "'.htmlspecialchars($db_prefix).'" Databases.</h3>');
                $result = self::prepare_databases($db_prefix);
                if (!$result) {
                    echo('<h3>Databases Ready.</h3>');
                } else {
                    $this->write_log_entry('FAILED DATABASE SETUP', 0, false);
                    echo($result);
                    echo('</div>');
                    echo('</div>');
                    continue;
                }
            }
            
            $logout = false;
            
            $this->write_log_entry('END PREP - START TEST');
            $this->test_start_time = microtime(true);
            $this->prep_time = $this->test_start_time - $this->prep_start_time;

            if (isset($login_setup)) {
                if (isset($login_setup['logout']) && $login_setup['logout']) {
                    $logout = true;
                }
                
                echo('<h3>Preparing the SDK.</h3>');
                $this->sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__, $login_setup['login_id'], $login_setup['password'], $login_setup['timeout']);
                
                if ($this->sdk_instance->is_logged_in()) {
                    echo('<h3>SDK Ready And Logged In. There Are '.$this->sdk_instance->login_time_left().' Seconds Left.</h3>');
                } else {
                    $this->write_log_entry('FAILED SDK LOGIN', 0, false);
                    echo('<h3 style="color:red">SDK NOT LOGGED IN!</h3>');
                }
            } else {
                $this->sdk_instance = new RVP_PHP_SDK(__SERVER_URI__, __SERVER_SECRET__);
                echo('<h3>SDK Ready, But Not Logged In.</h3>');
            }
            
            $function = $test['closure']['function'];
            $function_file = $test['closure']['file'];
            include_once($function_file);
            
            if (is_array($function)) {
                $thispass = $function[0]->$function[1]($this);
            } else {
                $thispass = $function($this);
            }
            
            $allpass &= $thispass;
            if ($logout && $this->sdk_instance && $this->sdk_instance->is_logged_in()) {
                echo('<h3>SDK Logging Out. There Were '.$this->sdk_instance->login_time_left().' Seconds Left In the Login.</h3>');
                $this->sdk_instance->logout();
            }
            
            $this->test_time = microtime(true) - $this->test_start_time;
            
            $this->write_log_entry('END TEST', 0, $thispass);
            
            echo('</div>');
            echo('</div>');
        }
        
        $this->prep_time = 0;
        $this->test_time = 0;
        $this->test_index = 0;
        $this->current_test_name = '****';
        $this->write_log_entry('MAIN TEST END', 0, $allpass);
        
        $this->close_log_file();
    }

    function hybrid_search_test(&$test_count, $in_search_type, $in_sha, $in_text_search, $in_location = NULL, $in_logins_only = false, $in_debug_display = false) {
        $all_pass = true;
    
        if (isset($in_text_search) && is_array($in_text_search) && count($in_text_search)) {
            foreach ($in_text_search as $key => $value) {
                echo('<h4>Searching '.htmlspecialchars($key).' for "'.htmlspecialchars($value).'".</h4>');
            }
        } else {
            $in_text_search = NULL;
        }
    
        if (isset($in_location) && is_array($in_location) && (3 == count($in_location))) {
            echo('<h4>Searching within '.htmlspecialchars($in_location['radius']).' kilometers of ('.$in_location['latitude'].', '.$in_location['longitude'].').</h4>');
        } else {
            $in_location = NULL;
        }
    
        $results = NULL;
    
        switch (strtolower(trim($in_search_type))) {
            case    'people':
                echo('<h4>Searching for '.($in_logins_only ? 'logins' : 'users').'.</h4>');
                $results = $this->sdk_instance->people_search($in_text_search, $in_location, $in_logins_only);
                break;
            
            case    'places':
                echo('<h4>Searching for places.</h4>');
                $results = $this->sdk_instance->places_search($in_text_search, $in_location);
                break;
            
            case    'things':
                echo('<h4>Searching for things.</h4>');
                $results = $this->sdk_instance->things_search($in_text_search, $in_location);
                break;
            
            default:
                echo('<h4>Searching for anything.</h4>');
                $results = $this->sdk_instance->general_search($in_text_search, $in_location);
                break;
        }
    
        $dump = [];
        if (isset($results) && is_array($results) && count($results)) {
            foreach ($results as $result) {
                $dump[] = ['id' => $result->id(), 'type' => get_class($result), 'name' => $result->name()];
            }
        }
    
        echo('<p><strong>SHA:</strong> <big><code>'.htmlspecialchars(print_r(sha1(serialize($dump)), true)).'</code></big></p>');
    
        if ($in_debug_display) {
            echo('<p><strong>RESPONSE DATA:</strong> <pre>'.htmlspecialchars(print_r($dump, true)).'</pre></p>');
        }

        if ($in_sha == sha1(serialize($dump))) {
            echo('<h4 style="color:green">Success!</h4>');
            $this->write_log_entry('Hybrid Text/Location Search.', $test_count++, true);
        } else {
            $all_pass = false;
            $this->write_log_entry('Hybrid Text/Location Search.', $test_count++, false);
            echo('<h4 style="color:red">SEARCH RESULTS NOT VALID!</h4>');
        }
    
        return $all_pass;     
    }
};
?>