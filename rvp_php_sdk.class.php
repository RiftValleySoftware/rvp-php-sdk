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
defined( 'RVP_PHP_SDK' ) or die ( 'Cannot Execute Directly' );	// Makes sure that this file is in the correct context.

define('__SDK_VERSION__', '1.0.0.0000');

/****************************************************************************************************************************/
/**
 */
class RVP_PHP_SDK {
    protected   $_server_uri;       ///< This is the URI of the BAOBAB server.
    protected   $_server_secret;    ///< This is the "server secret" that is specified by the admin of the BAOBAB server.
    protected   $_api_key;          ///< This is the current session API key.
    protected   $_login_time;       ///< The microtime for the last successful login. Meaningless if _api_key is NULL.
    protected   $_login_time_limit; ///< If >0, then this is the maximum time at which the current login is valid..
    
    /************************************************************************************************************************/    
    /*################################################ INTERNAL STATIC METHODS #############################################*/
    /************************************************************************************************************************/
    /************************************************************************************************************************/
    /**
    This is the function that is used by the SDK to make REST calls to the BAOBAB server.

    \returns the resulting transfer from the server, as a string of bytes.
     */
    protected function _call_REST_API(  $method,                /**< REQUIRED:  This is the method to call. It should be one of:
                                                                    - 'GET'     This is considered the default, but should be provided anyway, in order to ensure that the intent is clear.
                                                                    - 'POST'    This means that the resource needs to be created.
                                                                    - 'PUT'     This means that the resource is to be modified.
                                                                    - 'DELETE'  This means that the resource is to be deleted.
                                                                */
                                        $url_extension,         ///< REQIRED:   This is the query section of the URL for the call.
                                        $data_input = NULL,     ///< OPTIONAL:  Default is NULL. This is an associative array, containing a collection of data, and a MIME type ("data" and "type") to data to be uploaded to the server, along with the URL. This will be Base64-encoded, so it is not necessary for it to be already encoded.
                                        &$httpCode = NULL,      ///< OPTIONAL:  Default is NULL. If provided, this has a reference to an integer data item that will be set to any HTTP response code.
                                        $display_log = false    ///< OPTIONAL:  Default is false. If true, then the function will echo detailed debug information.
                                        ) {
    
        $method = strtoupper(trim($method));            // Make sure the method is always uppercase.
    
        global $g_server_secret;

        // Initialize function local variables.
        $file = NULL;               // This will be a file handle, for uploads.
        $content_type = NULL;       // This is used to signal the content-type for uploaded files.
        $file_size = 0;             // This is the size, in bytes, of uploaded files.
        $temp_file_name = NULL;     // This is a temporary file that is used to hold files before they are sent to the server.
    
        // If data is provided by the caller, we read it into a temporary location, and Base64-encode it.
        if ($data_input) {
            $file_data = base64_encode($data_input['data']);
        
            $temp_file_name = tempnam(sys_get_temp_dir(), 'RVP');
    
            $file = fopen($temp_file_name, 'w');
    
            fwrite($file, $file_data, strlen($file_data));
    
            fclose($file);
    
            $content_type = $data_input['type'].':base64';
            $file_size = filesize($temp_file_name);
    
            $file = fopen($temp_file_name, 'rb');
        }

        $curl = curl_init();                    // Initialize the cURL handle.
        $url = $this->_server_uri.'/'.$url_extension;
        curl_setopt($curl, CURLOPT_URL, $url);  // This is the URL we are calling.
        
        // Different methods require different ways of dealing with any file that has been passed in.
        // The file is ignored for GET and DELETE.
        // We ask the server not to send us EXPECT (HTTP 100) calls for POST and PUT.
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, true);
            
                // POST sends the file as a standard multipart/form-data item.
                if ($file) {
                    curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Expect:', 'Content-type: multipart/form-data']);
                    $post = Array('payload'=> curl_file_create($temp_file_name, $content_type));
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                } else {
                    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Expect:']);
                }
                break;
            
            case "PUT":
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['Expect:']);
                curl_setopt($curl, CURLOPT_PUT, true);
            
                // PUT requires a direct inline file transfer.
                if ($file) {
                    curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
                    curl_setopt($curl, CURLOPT_INFILE, $file);
                    curl_setopt($curl, CURLOPT_INFILESIZE, $file_size);
                }
                break;
            
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        }

        $server_secret = CO_Config::server_secret();    // Get the server secret.
    
        // Authentication. We provide the Server Secret and the API key here.
        if (isset($this->_server_secret) && isset($this->_api_key)) {
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, $this->_server_secret.':'.$this->_api_key);
        }

        curl_setopt($curl, CURLOPT_HEADER, false);          // Do not return any headers, please.
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);   // Please return to sender as a function response.
        curl_setopt($curl, CURLOPT_VERBOSE, false);         // Let's keep this thing simple.
    
        // This is if we want to see a display log (echoed directly).
        if (isset($display_log) && $display_log) {
            curl_setopt($curl, CURLOPT_HEADERFUNCTION, function ( $curl, $header_line ) {
                                                                                            echo "<pre>$header_line</pre>";
                                                                                            return strlen($header_line);
                                                                                        });
            echo('<div style="margin:1em">');
            echo("<h4>Sending REST $method CALL:</h4>");
            echo('<div>URL: <code>'.htmlspecialchars($url).'</code></div>');

            if ($this->_api_key) {
                echo('<div>API KEY:<pre>'.htmlspecialchars($this->_api_key).'</pre></div>');
            }
        }
    
        $result = curl_exec($curl); // Do it to it.
    
        // If they want a report, we send it.
        if (isset($httpCode)) {
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        }

        curl_close($curl);  // Bye, now.
    
        // If we had a file open for transfer, we close it now.
        if ($file) {
            fclose($file);
        }
    
        // More reportage.
        if (isset($display_log) && $display_log) {
            if (isset($data_file)) {
                echo('<div>ADDITIONAL DATA:<pre>'.htmlspecialchars(print_r($data_file, true)).'</pre></div>');
            }
            if (isset($httpCode) && $httpCode) {
                echo('<div>HTTP CODE:<code>'.htmlspecialchars($httpCode, true).'</code></div>');
            }
        
            if ((1024 * 1024 * 10) <= strlen($result)) {
                $integer = 1;
                $original_file_name = dirname(dirname(__FILE__)).'/text-dump-result';
                $file_name = $original_file_name.'.txt';
                while(file_exists($file_name)) {
                    $file_name = $original_file_name.'-'.$integer.'.txt';
                    $integer++;
                }
                $file_handle = fopen($file_name, 'w');
                fwrite($file_handle, $result);
                fclose($file_handle);
                echo('<div>RESULT SAVED TO FILE" '.$file_name.'.</div>');
            } else {
                echo('<div>RESULT:<pre>'.htmlspecialchars(print_r(chunk_split($result, 2048), true)).'</pre></div>');
            }
            echo("</div>");
        }

        return $result;
    }

    /************************************************************************************************************************/    
    /*#################################################### INTERNAL METHODS ################################################*/
    /************************************************************************************************************************/
    
    /***********************/
    /**
     */

    /************************************************************************************************************************/    
    /*#################################################### PUBLIC METHODS ##################################################*/
    /************************************************************************************************************************/
    
    /***********************/
    /**
     */
    function __construct(   $in_server_uri,         ///< REQUIRED: The URI of the BAOBAB Server
                            $in_server_secret,      ///< REQUIRED: The "server secret" for the BAOBAB Server.
                            $in_username = NULL,    ///< OPTIONAL: The Login Username, if we are doing an immediate login.
                            $in_password = NULL,    ///< OPTIONAL: The password, if we are doing an immediate login.
                            $in_login_timeout = -1  ///< OPTIONAL: If we have a known login timeout, we provide it here.
                        ) {
        $this->_server_uri = $in_server_uri;        // This is the server's base URI.
        $this->_server_secret  = $in_server_secret; // This is the secret that we need to provide with authentication.
        $this->_api_key = NULL;                     // If we log in, this will be non-NULL, and will contain the active API key for this instance.
        $this->_login_time = 0;                     // This is the microtime that we had a successful login. This plus the time limit are the maximum login age.
        $this->_login_time_limit = -1;              // No timeout to start.
        
        if ($in_username && $in_password) {
            $this->login($in_username, $in_password, $in_login_timeout);
        }
    }
    
    /***********************/
    /**
     */
    function login( $in_username,           ///< REQUIRED: The Login Username
                    $in_password,           ///< REQUIRED: The password.
                    $in_login_timeout = -1  ///< OPTIONAL: If we have a known login timeout, we provide it here.
                    ) {
        if (!$this->_api_key) {
            $response_code = '';
            $api_key = self::_call_REST_API('GET', 'login?login_id='.urlencode($in_username).'&password='.urlencode($in_password), NULL, $response_code);

            if (200 == intval($response_code) && isset($api_key) && $api_key) {
                $this->_login_time = microtime(true);
                $this->_api_key = $api_key;
                $this->_login_time_limit = (0 < $in_login_timeout) ? floatval($in_login_timeout) + $this->_login_time : -1;
                return true;
            }
        }
        
        return false;
    }
    
    /***********************/
    /**
     */
    function logout() {
        if ($this->_api_key && (0 >= $this->_login_time_limit) || (microtime(true) < $this->_login_time_limit)) {
            $response_code = '';
            $this->_call_REST_API('GET', 'logout', NULL, $response_code);
            if (205 == intval($response_code)) {
                $this->_login_time = 0;
                $this->_login_time_limit = -1;
                $this->_api_key = NULL;
                return true;
            }
        } else {
            $this->_login_time = 0;
            $this->_login_time_limit = -1;
            $this->_api_key = NULL;
        }
        
        return false;
    }
    
    /***********************/
    /**
     */
    function is_logged_in() {
        if ($this->_api_key && ((0 >= $this->_login_time_limit) || ($this->_login_time_limit > floatval($this->_login_time)))) {
            return true;
        }
        
        return false;
    }
    
    /***********************/
    /**
     */
    function login_time_left() {
        if ($this->_api_key && (0 < $this->_login_time_limit) && ($this->_login_time_limit > floatval($this->_login_time))) {
            return $this->_login_time_limit - floatval($this->_login_time);
        }
        
        return 0;
    }
    
    /***********************/
    /**
     */
    function get_my_info() {
        $ret = NULL;
        
        if ($this->is_logged_in()) {
            $response_code = '';
            $info = $this->_call_REST_API('GET', 'json/people/logins/my_info', NULL, $response_code);
            if ((200 == intval($response_code)) && $info) {
                $temp = json_decode($info);
                if (isset($temp) && isset($temp->people) && isset($temp->people->logins) && isset($temp->people->logins->my_info)) {
                    $login_info = $temp->people->logins->my_info;
                    
                    if (isset($login_info->user_object_id) && (1 < intval($login_info->user_object_id))) {
                        $ret = ['login' => $login_info];
                        $info = $this->_call_REST_API('GET', 'json/people/people/my_info', NULL, $response_code);
                        $temp = json_decode($info);
                        if (isset($temp) && isset($temp->people) && isset($temp->people->people) && isset($temp->people->people->my_info)) {
                            $user_info = $temp->people->people->my_info;
                            $ret['user'] = $user_info;
                        }
                    } else {
                        $ret = ['login' => $login_info];
                    }
                }
            }
        }
        
        return $ret;
    }
    
    /***********************/
    /**
     */
    function get_user_info( $in_user_id ///< REQUIRED: The integer ID of the user we want to examine. If we don't have rights to the user, or the user does not exist, we get nothing.
                            ) {
        $ret = NULL;
        
        if ($this->is_logged_in()) {
            $response_code = '';
            $info = $this->_call_REST_API('GET', 'json/people/people/'.intval($in_user_id).'?login_user', NULL, $response_code);
            if ((200 == intval($response_code)) && $info) {
                $temp = json_decode($info);
                if (isset($temp) && isset($temp->people) && isset($temp->people->people) && isset($temp->people->people[0])) {
                    $ret = $temp->people->people[0];
                }
            }
        }
        
        return $ret;
    }
    
    /***********************/
    /**
     */
    function get_login_info(    $in_login_id    ///< REQUIRED: The integer ID of the login we want to examine. If we don't have rights to the login, or the login does not exist, we get nothing.
                            ) {
        $ret = NULL;
        
        if ($this->is_logged_in()) {
            $response_code = '';
            $info = $this->_call_REST_API('GET', 'json/people/logins/'.intval($in_login_id).'?show_details', NULL, $response_code);
            if ((200 == intval($response_code)) && $info) {
                $temp = json_decode($info);
                if (isset($temp) && isset($temp->people) && isset($temp->people->logins) && isset($temp->people->logins[0])) {
                    $ret = $temp->people->logins[0];
                }
            }
        }
        
        return $ret;
    }
};
