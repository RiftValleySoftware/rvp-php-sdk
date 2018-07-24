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

require_once(dirname(__FILE__).'/rvp_php_sdk.class.php');   // Make sure that we have the main SDK class in place.

/****************************************************************************************************************************/
/**
 */
class RVP_PHP_SDK_Object {
    protected   $_sdk_object;   ///< This is the RVP_PHP_SDK object that "owns" this object.
    protected   $_object_id;    ///< This is the server unique ID of this object.
    protected   $_object_data;  ///< This is any data that was associated with this object (parsed JSON).
    protected   $_plugin_path;  ///< This is a string that is applied to fetches to get the object.
    
    /************************************************************************************************************************/    
    /*################################################ INTERNAL STATIC METHODS #############################################*/
    /************************************************************************************************************************/
    
    /***********************/
    /**
     */

    /************************************************************************************************************************/    
    /*#################################################### INTERNAL METHODS ################################################*/
    /************************************************************************************************************************/
    
    /***********************/
    /**
     */
    protected function _load_data(  $in_force = false   ///< OPTIONAL: If true (default is false), then the load will happen, even if we already have the data.
                                ) {
        if ($in_force || (NULL == $this->_object_data)) {
            $this->_object_data = $this->_sdk_object->fetch_data($this->_plugin_path.'/', $this->_object_id); 
        }
    }

    /************************************************************************************************************************/    
    /*#################################################### PUBLIC METHODS ##################################################*/
    /************************************************************************************************************************/
    
    /***********************/
    /**
     */
    function __construct(   $in_sdk_object,                 ///< REQUIRED: The "owning" SDK object.
                            $in_id,                         ///< REQUIRED: The server ID of the object. An integer.
                            $in_data = NULL,                ///< OPTIONAL: Parsed JSON Data for the object. Default is NULL.
                            $in_plugin_path = 'baseline'    ///< OPTIONAL: This is a path that is added to the server, to fetch data. Default is "baseline."
                        ) {
        $this->_sdk_object = $in_sdk_object;    // This is the RVP_PHP_SDK object that "owns" this user.
        $this->_object_id = $in_id;
        $this->_object_data = $in_data;
        $this->_plugin_path = $in_plugin_path;
    }
    
    /***********************/
    /**
     */
    function id() {
        return $this->_object_id;
    }
    
    /***********************/
    /**
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
     */
    function coords() {
        $ret = NULL;
        
        $this->_load_data();
        
        if (isset($this->_object_data) && isset($this->_object_data->name)) {
            $ret = $this->_object_data->name;
        }
        
        return $ret;
    }
    
    /***********************/
    /**
     */
    function payload() {
        $ret = NULL;
        
        $this->_load_data();
        
        if (isset($this->_object_data) && isset($this->_object_data->payload)) {
            $ret = ['data' => $this->_object_data->payload];
            
            if (isset($this->_object_data->payload_type)) {
                $ret['type'] = $this->_object_data->payload_type;
            }
        }
        
        return $ret;
    }
};
