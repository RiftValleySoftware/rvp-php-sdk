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
    protected function _load_data(  $in_force = false,      ///< OPTIONAL: Default is false. If true, then the load will happen, even if we already have the data.
                                    $in_details = false,    ///< OPTIONAL: Default is false. If true, then the load will be a "show details" load, which could bring in a great deal more data.
                                    $in_parents = false     ///< OPTIONAL: Default is false. If true, then the load will be a "show details" load, AND it will get the "parents," which can be a time-consuming operation. This will also "force" a load.
                                ) {
        $ret = false;
        
        if ($in_force || $in_parents || (NULL == $this->_object_data) || ($in_details && !$this->_details)) {
            $this->_details = $in_details;
            $this->_object_data = json_decode($this->_sdk_object->fetch_data('json/'.$this->_plugin_path.'/'.$this->_object_id, $in_details ? 'show_details'.($in_parents ? '&show_parents' : '') : NULL));
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
    
    \returns true, if the object declares that it is "fuzzy" (has location obfuscation).
     */
    function is_fuzzy() {
        $ret = false;
        
        $this->_load_data(false, true);
        
        if (isset($this->_object_data) && isset($this->_object_data->fuzzy)) {
            $ret = true;
        }
                
        return $ret;
    }
    
    /***********************/
    /**
    This requires a "detailed" load.
    
    \returns the "raw" coordinates for a "fuzzy" location, assuming the current login has rights to them. If not, it returns NULL.
     */
    function raw_coords() {
        $ret = NULL;
        
        $this->_load_data(false, true);
        
        if (isset($this->_object_data) && isset($this->_object_data->raw_latitude) && isset($this->_object_data->raw_longitude)) {
            $ret = [];
            $ret['latitude'] = floatval($this->_object_data->raw_latitude);
            $ret['longitude'] = floatval($this->_object_data->raw_longitude);
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
    
    /***********************/
    /**
    This requires a "detailed" load.
    
    \returns an associative array ('people' => integer array of IDs, 'places' => integer array of IDs, and 'things' => integer array of IDs), containing the IDs of any "child" objects for this object.
     */
    function children_ids() {
        $ret = NULL;
        
        $this->_load_data(false, true);
        
        if (isset($this->_object_data) && isset($this->_object_data->children)) {
            $child_data = (array)$this->_object_data->children;

            if (count($child_data)) {
                $ret = $child_data;
            }
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    This requires a "detailed and parents" load.
    
    **NOTE:** Calling this can incur a fairly significant performance penalty!
    
    \returns an associative array ('people' => integer array of IDs, 'places' => integer array of IDs, and 'things' => integer array of IDs), containing the IDs of any "parent" objects for this object.
     */
    function parent_ids() {
        $ret = NULL;
        
        $this->_load_data(false, true, true);
        
        if (isset($this->_object_data) && isset($this->_object_data->parents)) {
            $parent_data = $this->_object_data->parents;

            if (isset($parent_data) && is_array($parent_data) && count($parent_data)) {
                $ret = $parent_data;
            }
        }
        
        return $ret;
    }
};
