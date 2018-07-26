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

require_once(dirname(__FILE__).'/rvp_php_sdk.class.php');   // Make sure that we have the main SDK class in place.

/****************************************************************************************************************************/
/**
 This is an abstract base class for various data objects provided by the SDK.
 */
abstract class A_RVP_PHP_SDK_Object {
    protected   $_sdk_object;   ///< This is the RVP_PHP_SDK object that "owns" this object.
    protected   $_object_id;    ///< This is the server unique ID of this object.
    protected   $_object_data;  ///< This is any data that was associated with this object (parsed JSON).
    protected   $_details;      ///< If true, then the last load was a "show details" load..
    protected   $_plugin_path;  ///< This is a string that is applied to fetches to get the object.

    /************************************************************************************************************************/    
    /*#################################################### INTERNAL METHODS ################################################*/
    /************************************************************************************************************************/
    
    /***********************/
    /**
    This is the base "load some data" method. It will send a JSON GET REST request to the API in order to fetch information about this object.
    
    Once it receives the object, it JSON-decodes it, and stores it in the _object_data internal field.
    
    IT IS PROBABLT NOT YET READY! Subclasses should overload this, then apply their own filtering to the data after calling this parent method.
    
    \returns true, if it loaded the data.
     */
    protected function _load_data(  $in_force = false,  ///< OPTIONAL: If true (default is false), then the load will happen, even if we already have the data.
                                    $in_details = false ///< OPTIONAL: If true, then the load will be a "show details" load, which could bring in a great deal more data.
                                ) {
        $ret = false;
        
        if ($in_force || (NULL == $this->_object_data) || ($in_details && !$this->_details)) {
            $this->_details = $in_details;
            $this->_object_data = json_decode($this->_sdk_object->fetch_data('json/'.$this->_plugin_path.'/'.$this->_object_id, $in_details ? 'show_details' : NULL));
            $ret = true;
        }
        
        return $ret;
    }

    /************************************************************************************************************************/    
    /*#################################################### PUBLIC METHODS ##################################################*/
    /************************************************************************************************************************/
    
    /***********************/
    /**
    The basic constructor for the class. You have the option of "priming" the object with information.
     */
    function __construct(   $in_sdk_object,                 ///< REQUIRED: The "owning" SDK object.
                            $in_id,                         ///< REQUIRED: The server ID of the object. An integer.
                            $in_data = NULL,                ///< OPTIONAL: Parsed JSON Data for the object. Default is NULL.
                            $in_detailed_data = false,      ///< OPTIONAL: Ignored if $in_data is NULL. Default is false. If true, then the data sent in was in "detailed" format.
                            $in_plugin_path = 'baseline'    ///< OPTIONAL: This is a path that is added to the server, to fetch data. Default is "baseline."
                        ) {
        $this->_sdk_object = $in_sdk_object;    // This is the RVP_PHP_SDK object that "owns" this user.
        $this->_object_id = $in_id;
        $this->_object_data = $in_data;
        $this->_details = (NULL != $in_data) ? $in_detailed_data : false;
        $this->_plugin_path = $in_plugin_path;
    }
    
    /***********************/
    /**
    \returns the integer ID of the object; unique in the object's database.
     */
    function id() {
        return $this->_object_id;
    }
    
    /***********************/
    /**
    This requires a load, but not a "detailed" load.
    
    \returns the string name of the object. This is the generic "object_name" column that all records have.
     */
    function name() {
        $ret = NULL;
        
        $this->_load_data();

        if (isset($this->_object_data) && isset($this->_object_data->name)) {
            $ret = $this->_object_data->name;
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    This requires a load, but not a "detailed" load.
    
    \returns the string for the object's "lang" (language code) field.
     */
    function lang() {
        $ret = NULL;
        
        $this->_load_data();
        
        if (isset($this->_object_data) && isset($this->_object_data->lang)) {
            $ret = $this->_object_data->lang;
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    This requires a load, but not a "detailed" load.
    
    \returns true, if the current login can write/modify this object.
     */
    function writeable() {
        $ret = false;
        
        $this->_load_data();
        
        if (isset($this->_object_data) && isset($this->_object_data->writeable) && $this->_object_data->writeable) {
            $ret = true;
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    This requires a load, but not a "detailed" load.
    
    \returns the last access date, as a timedate integer.
     */
    function last_access() {
        $ret = NULL;
        
        $this->_load_data();
        
        if (isset($this->_object_data) && isset($this->_object_data->last_access)) {
            $ret = strtotime($this->_object_data->last_access);
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    This requires a load, but not a "detailed" load.
    
    \returns an associative array ('read' => integer, 'write' => integer), with the tokens for the object. The tokens will only be available if they are visible to the current user, or NULL, if there are no tokens (should never happen).
     */
    function tokens() {
        $ret = NULL;
        $read_token = NULL;
        $write_token = NULL;
        
        $this->_load_data();
        
        if (isset($this->_object_data) && isset($this->_object_data->read_token)) {
            $read_token = intval($this->_object_data->read_token);
        }
        
        if (isset($this->_object_data) && isset($this->_object_data->write_token)) {
            $write_token = intval($this->_object_data->write_token);
        }
        
        if ($read_token || $write_token) {
            $ret = [];
            
            if ($read_token) {
                $ret['read'] = $read_token;
            }
            
            if ($write_token) {
                $ret['write'] = $write_token;
            }
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    This requires a load, but not a "detailed" load.
    
    \returns an associative array ('latitude' => float, 'longitude' => float), with the long/lat coordinates of the object, or NULL, if there are no long/lat coordinates.
     */
    function coords() {
        $ret = NULL;
        
        $this->_load_data();
        
        if (isset($this->_object_data) && isset($this->_object_data->coords)) {
            $temp = explode(',', $this->_object_data->coords);
            if (isset($temp) && is_array($temp) && (1 < count($temp))) {
                $ret = [];
                $ret['latitude'] = floatval($temp[0]);
                $ret['longitude'] = floatval($temp[1]);
            }
        }
                
        return $ret;
    }
    
    /***********************/
    /**
    This requires a "detailed" load.
    
    \returns an associative array ('data' => binary data string, 'type' => string MIME type), containing the data in the payload, and its type. The data is not Base64-encoded.
     */
    function payload() {
        $ret = NULL;
        
        $this->_load_data(false, true);
        
        if (isset($this->_object_data) && isset($this->_object_data->payload)) {
            $payload_data = base64_decode($this->_object_data->payload);
            
            $ret = ['data' => $payload_data];
            
            if (isset($this->_object_data->payload_type)) {
                $ret['type'] = str_replace(';base64', '', $this->_object_data->payload_type);
            }
        }
        
        return $ret;
    }
};
