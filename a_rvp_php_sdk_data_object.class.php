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
defined( 'RVP_PHP_SDK' ) or die ( 'Cannot Execute Directly' );	// Makes sure that this file is in the correct context.

require_once(dirname(__FILE__).'/a_rvp_php_sdk_object.class.php');   // Get our base class.

/****************************************************************************************************************************/
/**
 This is an abstract base class for various data objects provided by the SDK.
 */
abstract class A_RVP_PHP_SDK_Data_Object extends A_RVP_PHP_SDK_Object {
    /************************************************************************************************************************/    
    /*#################################################### INTERNAL METHODS ################################################*/
    /************************************************************************************************************************/
    /***********************/
    /**
    \returns true, if the save was successful.
     */
    protected function _save_data(  $in_args = ''   ///< OPTIONAL: Default is an empty string. This is any previous arguments. This will be appeneded to the end of the list, so it should begin with an ampersand (&), and be url-encoded.
                                ) {
        $owner_id = isset($this->_object_data->owner_id) ? intval($this->_object_data->owner_id) : 0;
        $latitude = isset($this->_object_data->raw_latitude) ? floatval($this->_object_data->raw_latitude) : floatval($this->_object_data->latitude);
        $longitude = isset($this->_object_data->raw_longitude) ? floatval($this->_object_data->raw_longitude) : floatval($this->_object_data->longitude);
        
        $put_args = '&owner_id='.$owner_id.'&latitude='.$latitude.'&longitude='.$longitude;
        
        $ret = parent::_save_data($put_args.$in_args);
        
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
        parent::__construct($in_sdk_object, $in_id, $in_data, $in_detailed_data, $in_plugin_path);
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
    This requires a a "detailed" load.
    
    \returns the distance, if provided. Otherwise, it returns 0.
     */
    function distance() {
        $ret = 0;
        
        $this->_load_data(false, true);
        
        if (isset($this->_object_data) && isset($this->_object_data->distance_in_km)) {
            $ret = floatval($this->_object_data->distance_in_km);
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
