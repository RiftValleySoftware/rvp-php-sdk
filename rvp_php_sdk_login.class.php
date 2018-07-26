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

require_once(dirname(__FILE__).'/a_rvp_php_sdk_object.class.php');   // Make sure that we have the base class in place.

/****************************************************************************************************************************/
/**
 */
class RVP_PHP_SDK_Login extends A_RVP_PHP_SDK_Object {
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
    This is the specific "load some data" method. It will send a GET REST request to the API in order to fetch information about this object.
    
    \returns true, if it loaded the data.
     */
    protected function _load_data(  $in_force = false,  ///< OPTIONAL: If true (default is false), then the load will happen, even if we already have the data.
                                    $in_details = false ///< OPTIONAL: If true, then the load will be a "show details" load, which could bring in a great deal more data.
                                ) {
        $ret = parent::_load_data($in_force, $in_details);
        
        if ($ret) {
            if (isset($this->_object_data) && isset($this->_object_data->people) && isset($this->_object_data->people->logins) && is_array($this->_object_data->people->logins) && (1 == count($this->_object_data->people->logins))) {
                $this->_object_data = $this->_object_data->people->logins[0];
            } else {
                $this->_object_data = NULL;
                $this->_details = false;
            }
        }
        
        return $ret;
    }

    /************************************************************************************************************************/    
    /*#################################################### PUBLIC METHODS ##################################################*/
    /************************************************************************************************************************/
    
    /***********************/
    /**
     */
    function __construct(   $in_sdk_object,     ///< REQUIRED: The "owning" SDK object.
                            $in_id,             ///< REQUIRED: The server ID of the object. An integer.
                            $in_data = NULL     ///< OPTIONAL: Parsed JSON Data for the object. Default is NULL.
                        ) {
        parent::__construct($in_sdk_object, $in_id, $in_data, false, 'people/logins');
    }
    
    /***********************/
    /**
    This requires a "detailed" load.
    
    \returns true, if this is login is currently logged in.
     */
    function is_logged_in() {
        $ret = false;
        
        $this->_load_data(false, true);
        if (isset($this->_object_data) && isset($this->_object_data->current_login) && $this->_object_data->current_login) {
            $ret = true;
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    This requires a "detailed" load.
    
    \returns true, if this is a manager login.
     */
    function is_manager() {
        $ret = false;
        
        $this->_load_data(false, true);
        
        if (isset($this->_object_data) && isset($this->_object_data->is_manager) && $this->_object_data->is_manager) {
            $ret = true;
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    This requires a "detailed" load.
    
    \returns true, if this is a main admin login.
     */
    function is_main_admin() {
        $ret = false;
        
        $this->_load_data(false, true);
        
        if (isset($this->_object_data) && isset($this->_object_data->is_main_admin) && $this->_object_data->is_main_admin) {
            $ret = true;
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    This requires a "detailed" load.
    
    \returns an array of integer (security tokens) that comprise the "pool" for this login.
     */
    function tokens() {
        $ret = [];
        
        $this->_load_data(false, true);
        
        if (isset($this->_object_data) && isset($this->_object_data->security_tokens)) {
            $ret = $this->_object_data->security_tokens;
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    This requires a "detailed" load.
    
    \returns an integer. The ID of any associated user object. It returns 0 if there is no associated user object.
     */
    function user_object_id() {
        $ret = false;
        
        $this->_load_data(false, true);
        
        if (isset($this->_object_data) && isset($this->_object_data->user_object_id)) {
            $ret = intval($this->_object_data->user_object_id);
        }
        
        return $ret;
    }
};
