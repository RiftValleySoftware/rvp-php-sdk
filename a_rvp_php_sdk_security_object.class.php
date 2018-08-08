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
abstract class A_RVP_PHP_SDK_Security_Object extends A_RVP_PHP_SDK_Object {
    /************************************************************************************************************************/    
    /*#################################################### INTERNAL METHODS ################################################*/
    /************************************************************************************************************************/
    
    /***********************/
    /**
    \returns true, if the save was successful.
     */
    protected function _save_data(  $in_args = ''   ///< OPTIONAL: Default is an empty string. This is any previous arguments. This will be appeneded to the end of the list, so it should begin with an ampersand (&), and be url-encoded.
                                ) {
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
};
