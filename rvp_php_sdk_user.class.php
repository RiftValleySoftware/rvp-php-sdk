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

require_once(dirname(__FILE__).'/a_rvp_php_sdk_data_object.class.php');   // Make sure that we have the base class in place.

/****************************************************************************************************************************/
/**
 */
class RVP_PHP_SDK_User extends A_RVP_PHP_SDK_Data_Object {
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
    This is called after a successful save. It has the change record[s], and we will parse them to save the "before" object.
    
    \returns true, if the save was successful.
     */
    protected function _save_change_record( $in_change_record_object    ///< REQUIRED: The change response, as a parsed object.
                                            ) {
        $ret = false;
        
        return $ret;
    }
    
    /***********************/
    /**
    This is the specific "load some data" method. It will send a GET REST request to the API in order to fetch information about this object.
    
    \returns true, if it loaded the data.
     */
    protected function _load_data(  $in_force = false,      ///< OPTIONAL: If true (default is false), then the load will happen, even if we already have the data.
                                    $in_details = false,    ///< OPTIONAL: Default is false. If true, then the load will be a "show details" load, which could bring in a great deal more data.
                                    $in_parents = false     ///< OPTIONAL: Default is false. If true, then the load will be a "show details" load, AND it will get the "parents," which can be a time-consuming operation. This will also "force" a load.
                                ) {
        $ret = parent::_load_data($in_force, $in_details, $in_parents);
        
        if ($ret) {
            if (isset($this->_object_data) && isset($this->_object_data->people) && isset($this->_object_data->people->people) && is_array($this->_object_data->people->people) && (1 == count($this->_object_data->people->people))) {
                $this->_object_data = $this->_object_data->people->people[0];
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
    function __construct(   $in_sdk_object,             ///< REQUIRED: The "owning" SDK object.
                            $in_id,                     ///< REQUIRED: The server ID of the object. An integer.
                            $in_data = NULL,            ///< OPTIONAL: Parsed JSON Data for the object. Default is NULL.
                            $in_detailed_data = false   ///< OPTIONAL: Ignored if $in_data is NULL. Default is false. If true, then the data sent in was in "detailed" format.
                        ) {
        parent::__construct($in_sdk_object, $in_id, $in_data, $in_detailed_data, 'people/people');
    }
    
    /***********************/
    /**
    This requires a "detailed" load.
    
    \returns an array of integer (security tokens) that comprise the "pool" for this user (assuming it has a login).
     */
    function tokens() {
        $ret = [];
        
        $this->_load_data(false, true);
        
        if (isset($this->_object_data) && isset($this->_object_data->associated_login) && isset($this->_object_data->associated_login->security_tokens)) {
            $ret = $this->_object_data->associated_login->security_tokens;
        }
        
        return $ret;
    }
};
