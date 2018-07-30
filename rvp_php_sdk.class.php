<?php
/***************************************************************************************************************************/
/**
    BAOBAB PHP SDK
    
    © Copyright 2018, Little Green Viper Software Development LLC.
    
    This code is proprietary and confidential code, 
    It is NOT to be reused or combined into any application,
    unless done so, specifically under written license from Little Green Viper Software Development LLC.

    Little Green Viper Software Development: https://littlegreenviper.com
*/
defined( 'RVP_PHP_SDK' ) or die ( 'Cannot Execute Directly' );	// Makes sure that this file is in the correct context.
require_once(dirname(__FILE__).'/rvp_php_sdk_login.class.php');
require_once(dirname(__FILE__).'/rvp_php_sdk_user.class.php');
require_once(dirname(__FILE__).'/rvp_php_sdk_place.class.php');
require_once(dirname(__FILE__).'/rvp_php_sdk_thing.class.php');
require_once(dirname(__FILE__).'/lang/common.php');

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
    protected   $_sdk_lang;             ///< The language specified for this SDK instance. Default is "en" (English).
    protected   $_server_secret;        ///< This is the "server secret" that is specified by the admin of the BAOBAB server.
    protected   $_api_key;              ///< This is the current session API key.
    protected   $_login_time_limit;     ///< If >0, then this is the maximum time at which the current login is valid.
    protected   $_error;                ///< This is supposed to be NULL. However, if we have an error, it will contain an integer code.
    protected   $_my_login_info;        ///< This will contain any login information for the current login (NULL if not logged in).
    protected   $_my_user_info;         ///< This will contain any login information for the current login (NULL if not logged in).
    protected   $_last_response_code;   ///< This will contain any response code from the last cURL call.
    protected   $_available_plugins;    ///< This will be an array of string, with available plugins on the server.
    protected   $_localizations;        ///< An array of string, with the available localizations. The first will always be the default localization.
    protected   $_localized_errors;     ///< This will be an associative array, with the loaded RVP_Local_Error_* instances for display of error messages.
    
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
            } else {
                echo('<div>NO API KEY</div>');
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
        
            if ((1024 * 1024 * 2) <= strlen($result)) { // Anything over 2MB gets spewed to a file.
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
                echo('<div>RESULT:<pre>'.htmlspecialchars(print_r(chunk_split($result, 1024), true)).'</pre></div>');
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
    \returns an array of unresolved objects (of any kind) that meet the ID requirements. NOTE: If the current user does not have permission to view resources, or the resources don't exist, they will not be returned.
     */
    protected function _decode_handlers (   $in_handlers    ///< An associative array ('people' => array of int, 'places' => array of int, 'things' => array of int), with lists of IDs for various resources.
                                        ) {
        $ret = NULL;
        $plugin_list = [];
        if (isset($in_handlers->people) && is_array($in_handlers->people) && count($in_handlers->people)) {
            $plugin_list['people'] = $in_handlers->people;
        }
        if (isset($in_handlers->places) && is_array($in_handlers->places) && count($in_handlers->places)) {
            $plugin_list['places'] = $in_handlers->places;
        }
        if (isset($in_handlers->things) && is_array($in_handlers->things) && count($in_handlers->things)) {
            $plugin_list['things'] = $in_handlers->things;
        }
        
        if (isset($plugin_list) && is_array($plugin_list) && count($plugin_list)) {
            $ret = [];
            foreach ($plugin_list as $plugin => $list) {
                sort($list);
                foreach ($list as $id) {
                    $id = intval($id);
                
                    if (1 < $id) {
                        switch ($plugin) {
                            case 'people':
                                $new_object = new RVP_PHP_SDK_User($this, $id);
                                if (isset($new_object) && ($new_object instanceof RVP_PHP_SDK_User)) {
                                    $ret[] = $new_object;
                                } else {
                                    $this->set_error(_ERR_INTERNAL_ERR__);
                                    return NULL;
                                }
                                break;
                            
                            case 'places':
                                $new_object = new RVP_PHP_SDK_Place($this, $id);
                                if (isset($new_object) && ($new_object instanceof RVP_PHP_SDK_Place)) {
                                    $ret[] = $new_object;
                                } else {
                                    $this->set_error(_ERR_INTERNAL_ERR__);
                                    return NULL;
                                }
                                break;
                            
                            case 'things':
                                $new_object = new RVP_PHP_SDK_Thing($this, $id);
                                if (isset($new_object) && ($new_object instanceof RVP_PHP_SDK_Thing)) {
                                    $ret[] = $new_object;
                                } else {
                                    $this->set_error(_ERR_INTERNAL_ERR__);
                                    return NULL;
                                }
                                break;  
                        }
                    }
                }
            }
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    This loads the localization objects for this instance.
    
    \returns an array of string, with each of the localizations loaded.
     */
    protected function _load_localizations() {
        $locale_dir = dirname(__FILE__).'/lang';
        $locale_name_array = [];
        foreach (new DirectoryIterator($locale_dir) as $fileInfo) {
            if (($fileInfo->getExtension() === 'php') && ('index.php' != $fileInfo->getBasename()) && ('common.php' != $fileInfo->getBasename())) {
                $locale_name_array[] = $fileInfo->getBasename('.php');
            }
        }
        
        $this->_localizations = [];
        
        // Read each available file, and add it to our list.
        foreach ($locale_name_array as $locale) {
            if ($locale != $this->_sdk_lang) {
                $this->_localizations[] = $locale;
            }
        }
        
        sort($this->_localizations); // Simple alpha-sort.
        array_unshift($this->_localizations, $this->_sdk_lang); // Make sure the first one is always our default.
    }
    
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
                            } else {
                                $this->set_error(_ERR_COMM_ERR__);
                            }
                        } else {
                            $this->set_error(_ERR_NO_RESULTS__);
                        }
                    } else {
                        $ret = ['login' => $login_info];
                    }
                }
            } else {
                $this->set_error(_ERR_NO_RESULTS__);
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
            } else {
                $this->set_error(_ERR_COMM_ERR__);
            }
        } else {
            $this->set_error(_ERR_NO_RESULTS__);
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
        
        $this->clear_error();                           // Start off clean.
        $this->set_lang('en');                          // Set to default (English). The implementor should call this after instantiation to change.
        
        $this->_available_plugins = $this->_get_plugins();
        
        if ($this->valid()) {
            if ($in_username && $in_password) {
                $this->login($in_username, $in_password, $in_login_timeout);
            }
        }
    }
    
    /***********************/
    /**
    The basic destructor. We make sure that we log out.
     */
    function __destruct() {
        $this->logout();    // Don't bother checking for current login. Just call logout().
    }
    
    /***********************/
    /**
    This simply sets the SDK language, and also reloads the localizations.
     */
    function set_lang(  $in_lang    ///< REQUIRED: The lang code to set as the default for the SDK instance.
                    ) {
        $this->_sdk_lang = $in_lang;
        
        $this->_load_localizations();
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
            
            if (isset($api_key) && $api_key) {  // If we logged in, then we get our info.
                $this->_api_key = $api_key;
                $this->_my_login_info = NULL;
                $this->_my_user_info = NULL;
                
                $info = $this->_get_my_info();
                
                if (!$this->get_error()) {
                    if (isset($info['login'])) {
                        $this->_my_login_info = new RVP_PHP_SDK_Login($this, $info['login']->id, $info['login'], true);
                    
                        if (!($this->_my_login_info instanceof RVP_PHP_SDK_Login)) {
                            $this->set_error(_ERR_INTERNAL_ERR__);
                            $this->logout();
                            $this->_api_key = NULL;
                            $this->_login_time_limit = -1;
                            $this->_my_login_info = NULL;
                            $this->_my_user_info = NULL;
                            return false;
                        }
                    }
                
                    if (isset($info['user'])) {
                        $this->_my_user_info = new RVP_PHP_SDK_User($this, $info['user']->id, $info['user'], true);
                    
                        if (!($this->_my_user_info instanceof RVP_PHP_SDK_User)) {
                            $this->set_error(_ERR_INTERNAL_ERR__);
                            $this->logout();
                            $this->_api_key = NULL;
                            $this->_login_time_limit = -1;
                            $this->_my_login_info = NULL;
                            $this->_my_user_info = NULL;
                            return false;
                        }
                    }
                
                    return true;
                }
            } else {
                $this->set_error(_ERR_INVALID_LOGIN__);
                $this->_api_key = NULL;
                $this->_login_time_limit = -1;
                $this->_my_login_info = NULL;
                $this->_my_user_info = NULL;
            }
        } else {
            $this->set_error(_ERR_PREV_LOGIN__);
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
            $this->_call_REST_API('GET', 'logout', NULL);
            if (205 == intval($this->_last_response_code)) {
                $this->_api_key = NULL;
                $this->_login_time_limit = -1;
                $this->_my_login_info = NULL;
                $this->_my_user_info = NULL;
                return true;
            } else {
                $this->set_error(_ERR_COMM_ERR__);
            }
        } else {
            $this->set_error(_ERR_NOT_LOGGED_IN__);
            $this->_api_key = NULL;
            $this->_login_time_limit = -1;
            $this->_my_login_info = NULL;
            $this->_my_user_info = NULL;
        }
        
        return false;
    }
    
    /***********************/
    /**
    \returns any error we may have, in an associative array ('code' => integer, 'message' => string). The localization will be dependent upon the SDK localization. NULL, if no error.
     */
    function get_error() {
        $ret = NULL;
        
        if ($this->_error) {
            $message_class = 'RVP_Locale_'.$this->_sdk_lang;
            require_once(dirname(__FILE__).'/lang/'.$this->_sdk_lang.'.php');
            $ret = ['code' => intval($this->_error)];
            $ret['message'] = $message_class::get_error_message($ret['code']);
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    Sets the internal error code.
     */
    function set_error( $in_code    ///< REQUIRED: This is an integer error code. It can be NULL or 0 to clear the error.
                        ) {
        $this->_error = isset($in_code) && (0 != intval($in_code)) ? intval($in_code) : NULL;
    }
    
    /***********************/
    /**
    Resets the internal error to NULL.
     */
    function clear_error() {
        $this->set_error(NULL);
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
    \returns true, if we are currently logged in as a manager.
     */
    function is_manager() {
        if (isset($this->_my_login_info)) {
            return $this->_my_login_info->is_manager();
        }
        
        return false;
    }
    
    /***********************/
    /**
    \returns true, if we are currently logged in as a main admin.
     */
    function is_main_admin() {
        if (isset($this->_my_login_info)) {
            return $this->_my_login_info->is_main_admin();
        }
        
        return false;
    }
    
    /***********************/
    /**
    \returns a string, with the current login ID. NULL, if not logged in.
     */
    function current_login_id() {
        if (isset($this->_my_login_info)) {
            return $this->_my_login_info->login_id();
        }
        
        return NULL;
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
    \returns new user, place and/or thing objects (or NULL) for the given integer ID[s]. These will be "unresolved" objects, sorted by ID.
     */
    function get_objects() {
        $args = array_map('intval', func_get_args());
        $ret = NULL;
        $plugin_list = [];
        $handlers = $this->fetch_data('json/baseline/handlers/'.implode(',', $args));
        if (isset($handlers)) {
            $handlers = json_decode($handlers);
            if (isset($handlers) && isset($handlers->baseline)) {
                $ret = $this->_decode_handlers($handlers->baseline);
            }
        } else {
            $this->set_error(_ERR_COMM_ERR__);
            return NULL;
        }
        
        if (isset($ret) && is_array($ret) && (1 < count($ret))) {
            usort($ret, function($a, $b) {
                            if ($a->id() == $b->id()) {
                                return 0;
                            }
                        
                            if ($a->id() < $b->id()) {
                                return -1;
                            }
                        
                            return 1;
                        }
            );
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    \returns a new user object (or NULL) for the given integer ID.
     */
    function get_user_info( $in_user_id ///< REQUIRED: The integer ID of the user we want to examine. If we don't have rights to the user, or the user does not exist, we get nothing.
                            ) {
        $ret = NULL;
        
        if ($this->is_logged_in()) {
            $info = $this->fetch_data('json/people/people/'.intval($in_user_id), 'show_details');
            if ($info) {
                $temp = json_decode($info);
                if (isset($temp) && isset($temp->people) && isset($temp->people->people) && isset($temp->people->people[0])) {
                    $ret = new RVP_PHP_SDK_User($this, $temp->people->people[0]->id, $temp->people->people[0], true);
                    if (!isset($ret) || !($ret instanceof RVP_PHP_SDK_User)) {
                        $this->set_error(_ERR_INTERNAL_ERR__);
                        $ret = NULL;
                    }
                }
            } else {
                $this->set_error(_ERR_COMM_ERR__);
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
                    $ret = new RVP_PHP_SDK_Login($this, $temp->people->logins[0]->id, $temp->people->logins[0], true);
                    if (!isset($ret) || !($ret instanceof RVP_PHP_SDK_Login)) {
                        $this->set_error(_ERR_INTERNAL_ERR__);
                        $ret = NULL;
                    }
                }
            } else {
                $this->set_error(_ERR_COMM_ERR__);
            }
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    \returns a new place object (or NULL) for the given integer ID.
     */
    function get_place_info(    $in_place_id    ///< REQUIRED: The integer ID of the place we want to examine. If we don't have rights to the place, or the place does not exist, we get nothing.
                            ) {
        $ret = NULL;
        
        $info = $this->fetch_data('json/places/'.intval($in_place_id), 'show_details');
        if ($info) {
            $temp = json_decode($info);
            if (isset($temp) && isset($temp->places) && isset($temp->places->results) && is_array($temp->places->results) && isset($temp->places->results[0])) {
                $ret = new RVP_PHP_SDK_Place($this, $temp->places->results[0]->id, $temp->places->results[0], true);
                if (!isset($ret) || !($ret instanceof RVP_PHP_SDK_Place)) {
                    $this->set_error(_ERR_INTERNAL_ERR__);
                    $ret = NULL;
                }
            }
        } else {
            $this->set_error(_ERR_COMM_ERR__);
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
        
        $info = $this->fetch_data('json/things/'.intval($in_thing_id), 'show_details');
        if ($info) {
            $temp = json_decode($info);
            if (isset($temp) && isset($temp->things) && isset($temp->things[0])) {
                $ret = new RVP_PHP_SDK_Thing($this, $temp->things[0]->id, $temp->things[0], true);
                if (!isset($ret) || !($ret instanceof RVP_PHP_SDK_Thing)) {
                    $this->set_error(_ERR_INTERNAL_ERR__);
                    $ret = NULL;
                }
            }
        } else {
            $this->set_error(_ERR_COMM_ERR__);
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    \returns an array of objects (of any kind) that fall within the search radius. NOTE: If the objects don't have an assigned long/lat, they will not be returned in this search.
     */
    function general_location_search(   $in_location    ///< An associative array ('latitude' => float, 'longitude' => float, 'radius' => float), with the long/lat (in degrees), and the radius of the location search (in Kilometers).
                                    ) {
        $ret = NULL;
        $plugin_list = [];
        $handlers = $this->fetch_data('json/baseline/search/?search_latitude='.floatval($in_location['latitude']).'&search_longitude='.floatval($in_location['longitude']).'&search_radius='.floatval($in_location['radius']));
        if (isset($handlers)) {
            $handlers = json_decode($handlers);
            if (isset($handlers) && isset($handlers->baseline)) {
                $ret = $this->_decode_handlers($handlers->baseline);
            }
        } else {
            $this->set_error(_ERR_COMM_ERR__);
            return NULL;
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    \returns an array of user (or login) objects that fall within the search radius. NOTE: If the objects don't have an assigned long/lat, they will not be returned in this search.
     */
    function people_location_search(    $in_location,               ///< An associative array ('latitude' => float, 'longitude' => float, 'radius' => float), with the long/lat (in degrees), and the radius of the location search (in Kilometers).
                                        $in_get_logins_only = false ///< If true (Default is false), then only login objects associated with the user objects that fall within the search will be returned.
                                    ) {
        $ret = NULL;
        $plugin_list = [];
        $url = 'json/people/people/?search_latitude='.floatval($in_location['latitude']).'&search_longitude='.floatval($in_location['longitude']).'&search_radius='.floatval($in_location['radius']);

        if ($in_get_logins_only) {
            $url .= '&login_user';
        }
        
        $results = json_decode($this->fetch_data($url));
            
        if (isset($results) && isset($results->people) && isset($results->people->people)) {
            $result_array = (array)$results->people->people;
            $ret = [];
            foreach ($result_array as $result) {
                if ($in_get_logins_only) {
                    if (isset($result->associated_login) && (1 < $result->associated_login->id)) {
                        $ret[] = new RVP_PHP_SDK_Login($this, $result->associated_login->id, $result->associated_login, true);
                        if (!isset($ret[count($ret) - 1]) || !($ret[count($ret) - 1] instanceof RVP_PHP_SDK_Login)) {
                            $this->set_error(_ERR_INTERNAL_ERR__);
                            $ret = NULL;
                            break;
                        }
                    }
                } elseif (isset($result) && isset($result->id) && (1 < $result->id)) {
                    $ret[] = new RVP_PHP_SDK_User($this, $result->id, $result);
                    if (!isset($ret[count($ret) - 1]) || !($ret[count($ret) - 1] instanceof RVP_PHP_SDK_User)) {
                        $this->set_error(_ERR_INTERNAL_ERR__);
                        $ret = NULL;
                        break;
                    }
                }
            }
        } else {
            $this->set_error(_ERR_COMM_ERR__);
            return NULL;
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    \returns an array of place objects that fall within the search radius. NOTE: If the objects don't have an assigned long/lat, they will not be returned in this search.
     */
    function place_location_search( $in_location    ///< An associative array ('latitude' => float, 'longitude' => float, 'radius' => float), with the long/lat (in degrees), and the radius of the location search (in Kilometers).
                                    ) {
        $ret = NULL;
        $plugin_list = [];
        $url = 'json/places/?search_latitude='.floatval($in_location['latitude']).'&search_longitude='.floatval($in_location['longitude']).'&search_radius='.floatval($in_location['radius']);
        
        $results = json_decode($this->fetch_data($url));
            
        if (isset($results) && isset($results->places) && isset($results->places->results)) {
            $result_array = (array)$results->places->results;
            $ret = [];
            foreach ($result_array as $result) {
                if (isset($result) && isset($result->id) && (1 < $result->id)) {
                    $ret[] = new RVP_PHP_SDK_Place($this, $result->id, $result);
                    if (!isset($ret[count($ret) - 1]) || !($ret[count($ret) - 1] instanceof RVP_PHP_SDK_Place)) {
                        $this->set_error(_ERR_INTERNAL_ERR__);
                        $ret = NULL;
                        break;
                    }
                }
            }
        } else {
            $this->set_error(_ERR_COMM_ERR__);
            return NULL;
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    \returns an array of thing objects that fall within the search radius. NOTE: If the objects don't have an assigned long/lat, they will not be returned in this search.
     */
    function thing_location_search( $in_location    ///< An associative array ('latitude' => float, 'longitude' => float, 'radius' => float), with the long/lat (in degrees), and the radius of the location search (in Kilometers).
                                    ) {
        $ret = NULL;
        $plugin_list = [];
        $url = 'json/things/?search_latitude='.floatval($in_location['latitude']).'&search_longitude='.floatval($in_location['longitude']).'&search_radius='.floatval($in_location['radius']);
        
        $results = json_decode($this->fetch_data($url));
            
        if (isset($results) && isset($results->things)) {
            $result_array = (array)$results->things;
            $ret = [];
            foreach ($result_array as $result) {
                if (isset($result) && isset($result->id) && (1 < $result->id)) {
                    $ret[] = new RVP_PHP_SDK_Thing($this, $result->id, $result);
                    if (!isset($ret[count($ret) - 1]) || !($ret[count($ret) - 1] instanceof RVP_PHP_SDK_Thing)) {
                        $this->set_error(_ERR_INTERNAL_ERR__);
                        $ret = NULL;
                        break;
                    }
                }
            }
        } else {
            $this->set_error(_ERR_COMM_ERR__);
            return NULL;
        }
        
        return $ret;
    }
};
