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
require_once(dirname(__FILE__).'/rvp_php_sdk_login.class.php');
require_once(dirname(__FILE__).'/rvp_php_sdk_user.class.php');
require_once(dirname(__FILE__).'/rvp_php_sdk_thing.class.php');

define('__SDK_VERSION__', '1.0.0.0000');

/****************************************************************************************************************************/
/**
This is the central SDK class for connecting a PHP application to a BAOBAB server.

Upon instantiation, a valid server base URI and secret need to be provided.

Optionally, you can also provide login credentials.

This object should be used to manage all connections to the server.
 */
class RVP_PHP_SDK {
    protected   $_server_uri;           ///< This is the URI of the BAOBAB server.
    protected   $_server_secret;        ///< This is the "server secret" that is specified by the admin of the BAOBAB server.
    protected   $_api_key;              ///< This is the current session API key.
    protected   $_login_time_limit;     ///< If >0, then this is the maximum time at which the current login is valid.
    protected   $_error;                ///< This is supposed to be NULL. However, if we have an error, it will contain a code.
    protected   $_my_login_info;        ///< This will contain any login information for the current login (NULL if not logged in).
    protected   $_my_user_info;         ///< This will contain any login information for the current login (NULL if not logged in).
    protected   $_last_response_code;   ///< This will contain any response code from the last cURL call.
    protected   $_available_plugins;    ///< This will be an array of string, with available plugins on the server.
    
    /************************************************************************************************************************/    
    /*################################################ INTERNAL STATIC METHODS #############################################*/
    /************************************************************************************************************************/
    
    /***********************/
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
                                        $url_extension,         ///< REQIRED:   This is the query section of the URL for the call. It can be empty, but you probably won't get much, if it is.
                                        $data_input = NULL,     ///< OPTIONAL:  Default is NULL. This is an associative array, containing a collection of data, and a MIME type ("data" and "type") to data to be uploaded to the server, along with the URL. This will be Base64-encoded, so it is not necessary for it to be already encoded.
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
    
        $this->_last_response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

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
    \returns an associative array ('login' => login JSON object, 'user' => user JSON object), with the current information for any valid login.
     */
    protected function _get_my_info() {
        $ret = NULL;
        
        if ($this->is_logged_in()) {
            $info = $this->fetch_data('json/people/logins/my_info');
            if ($info) {
                $temp = json_decode($info);
                if (isset($temp) && isset($temp->people) && isset($temp->people->logins) && isset($temp->people->logins->my_info)) {
                    $login_info = $temp->people->logins->my_info;
                    if (isset($login_info->user_object_id) && (1 < intval($login_info->user_object_id))) {
                        $ret = ['login' => $login_info];
                        $info = $this->fetch_data('json/people/people/my_info');
                        if ($info) {
                            $temp = json_decode($info);
                            if (isset($temp) && isset($temp->people) && isset($temp->people->people) && isset($temp->people->people->my_info)) {
                                $user_info = $temp->people->people->my_info;
                                $ret['user'] = $user_info;
                            }
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
    \returns an array of string, with all the available plugins on the server.
     */
    protected function _get_plugins() {
        $ret = NULL;
        
        $info = $this->fetch_data('json/baseline');
        if ($info) {
            $temp = json_decode($info);
            if (isset($temp) && isset($temp->baseline) && isset($temp->baseline->plugins) && is_array($temp->baseline->plugins)) {
                return $temp->baseline->plugins;
            }
        }
        
        return $ret;
    }

    /************************************************************************************************************************/    
    /*#################################################### PUBLIC METHODS ##################################################*/
    /************************************************************************************************************************/
    
    /***********************/
    /**
    The basic constructor, which includes a validity test and a possible login.
     */
    function __construct(   $in_server_uri,         ///< REQUIRED: The URI of the BAOBAB Server
                            $in_server_secret,      ///< REQUIRED: The "server secret" for the BAOBAB Server.
                            $in_username = NULL,    ///< OPTIONAL: The Login Username, if we are doing an immediate login.
                            $in_password = NULL,    ///< OPTIONAL: The password, if we are doing an immediate login.
                            $in_login_timeout = -1  ///< OPTIONAL: If we have a known login timeout, we provide it here.
                        ) {
        $this->_server_uri = trim($in_server_uri, '/'); // This is the server's base URI.
        $this->_server_secret  = $in_server_secret;     // This is the secret that we need to provide with authentication.
        $this->_api_key = NULL;                         // If we log in, this will be non-NULL, and will contain the active API key for this instance.
        $this->_login_time_limit = -1;                  // No timeout to start.
        $this->_my_login_info = NULL;                   // If we have logged in, we have the info for our login here.
        $this->_my_user_info = NULL;                    // If we have logged in, we have the info for our user (if available) here.
        
        $this->_available_plugins = $this->_get_plugins();
        
        if ($this->valid()) {
            if ($in_username && $in_password) {
                $this->login($in_username, $in_password, $in_login_timeout);
            }
        }
    }
    
    /***********************/
    /**
    This executes a login to the server.
    
    Upon successful login, the "my_info" queries are made to the login, and, if applicable, the user.
    
    \returns true, if the login was successful.
     */
    function login( $in_username,           ///< REQUIRED: The Login Username
                    $in_password,           ///< REQUIRED: The password.
                    $in_login_timeout = -1  ///< OPTIONAL: If we have a known login timeout, we provide it here.
                    ) {
        if (!$this->_api_key && $this->valid()) {
            $this->_login_time_limit = (0 < $in_login_timeout) ? (floatval($in_login_timeout) + microtime(true)) : -1;
            $api_key = $this->fetch_data('login', 'login_id='.urlencode($in_username).'&password='.urlencode($in_password));
            
            if (isset($api_key) && $api_key) {
                $this->_api_key = $api_key;
                $this->_my_login_info = NULL;
                $this->_my_user_info = NULL;
                $info = $this->_get_my_info();
                
                if (isset($info['login'])) {
                    $this->_my_login_info = new RVP_PHP_SDK_Login($this, $info['login']->id, $info['login'], true);
                }
                
                if (isset($info['user'])) {
                    $this->_my_user_info = new RVP_PHP_SDK_User($this, $info['user']->id, $info['user'], true);
                }
                
                return true;
            } else {
                $this->_api_key = NULL;
                $this->_login_time_limit = -1;
                $this->_my_login_info = NULL;
                $this->_my_user_info = NULL;
            }
        }
        
        return false;
    }
    
    /***********************/
    /**
    Logs the current user out, and resets the object to a "connection-only" state.
    
    \returns true, if the logout was successful.
     */
    function logout() {
        if ($this->is_logged_in()) {
            $response_code = '';
            $this->_call_REST_API('GET', 'logout', NULL, $response_code);
            if (205 == intval($response_code)) {
                $this->_api_key = NULL;
                $this->_login_time_limit = -1;
                $this->_my_login_info = NULL;
                $this->_my_user_info = NULL;
                return true;
            }
        } else {
            $this->_api_key = NULL;
            $this->_login_time_limit = -1;
            $this->_my_login_info = NULL;
            $this->_my_user_info = NULL;
        }
        
        return false;
    }
    
    /***********************/
    /**
    \returns true, if we are pointing at a valid server.
     */
    function valid() {
        return isset($this->_available_plugins) && is_array($this->_available_plugins) && (3 < count($this->_available_plugins));
    }
    
    /***********************/
    /**
    \returns true, if we are currently logged in.
     */
    function is_logged_in() {
        return 0 < $this->login_time_left();
    }
    
    /***********************/
    /**
    \returns true, if we are currently logged in.
     */
    function is_manager() {
        if ($this->is_logged_in()) {
            return true;
        }
        
        return false;
    }
    
    /***********************/
    /**
    If we logged in with a known time limit, we report how mucg time we have left.
    
    \returns the number of seconds (float) we have left in our current login.
     */
    function login_time_left() {
        if (0 < $this->_login_time_limit) {
            return floor(100 * max($this->_login_time_limit - microtime(true), 0)) / 100;
        }
        
        return 0;
    }
    
    /***********************/
    /**
    \returns an associative array ('login' => login object, 'user' => user object), with our information.
     */
    function my_info() {
        if (isset($this->_my_login_info)) {
            $ret = ['login' => $this->_my_login_info];
            
            if (isset($this->_my_user_info)) {
                $ret['user'] = $this->_my_user_info;
            }
            
            return $ret;
        } else {
            return NULL;
        }
    }
    
    /***********************/
    /**
    \returns an array of strings, with the available plugins.
     */
    function plugins() {
        $ret = [];
        
        if ($this->valid()) {
            $ret = $this->_available_plugins;
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    This method will initiate and complete a data GET connection to the server. It takes care of any authentication.
    
    \returns whatever data was returned. Usually JSON.
     */
    function fetch_data(    $in_plugin_path,            ///< REQUIRED: The plugin path to append to the base URI. This is a string.
                            $in_query_args = NULL       ///< OPTIONAL: Any query arguments to be attached after a question mark. This is a string.
                        ) {
        if (isset($in_query_args) && trim($in_query_args)) {
            $in_plugin_path .= '?'.$in_query_args;
        }
        
        $response = $this->_call_REST_API('GET', $in_plugin_path);
        
        return $response;
    }
    
    /***********************/
    /**
    \returns a new user object (or NULL) for the given integer ID.
     */
    function get_user_info( $in_user_id ///< REQUIRED: The integer ID of the user we want to examine. If we don't have rights to the user, or the user does not exist, we get nothing.
                            ) {
        $ret = NULL;
        
        if ($this->is_logged_in()) {
            $info = $this->fetch_data('json/people/people/'.intval($in_user_id), 'login_user');
            if ($info) {
                $temp = json_decode($info);
                if (isset($temp) && isset($temp->people) && isset($temp->people->people) && isset($temp->people->people[0])) {
                    $ret = new RVP_PHP_SDK_User($this, $temp->people->people[0]->id, $temp->people->people[0]);
                }
            }
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    \returns a new login object (or NULL) for the given integer ID.
     */
    function get_login_info(    $in_login_id    ///< REQUIRED: The integer ID of the login we want to examine. If we don't have rights to the login, or the login does not exist, we get nothing.
                            ) {
        $ret = NULL;
        
        if ($this->is_logged_in()) {
            $info = $this->fetch_data('json/people/logins/'.intval($in_login_id), 'show_details');
            if ($info) {
                $temp = json_decode($info);
                if (isset($temp) && isset($temp->people) && isset($temp->people->logins) && isset($temp->people->logins[0])) {
                    $ret = new RVP_PHP_SDK_Login($this, $temp->people->logins[0]->id, $temp->people->logins[0]);
                }
            }
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    \returns a new thing object (or NULL) for the given integer ID.
     */
    function get_thing_info(    $in_thing_id    ///< REQUIRED: The integer ID of the thing we want to examine. If we don't have rights to the thing, or the thing does not exist, we get nothing.
                            ) {
        $ret = NULL;
        
        if ($this->is_logged_in()) {
            $info = $this->fetch_data('json/things/'.intval($in_thing_id), 'show_details');
            if ($info) {
                $temp = json_decode($info);
                if (isset($temp) && isset($temp->things) && isset($temp->things[0])) {
                    $ret = new RVP_PHP_SDK_Thing($this, $temp->things[0]->id, $temp->things[0]);
                }
            }
        }
        
        return $ret;
    }
};
