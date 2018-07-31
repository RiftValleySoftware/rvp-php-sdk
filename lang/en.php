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
require_once(dirname(__FILE__).'/common.php');

class RVP_Locale_en extends A_RVP_Locale {
    /***********************/
    /**
     */
    protected static function _get_default_error_message() {
        return 'Unknown Error';
    }
    
    /***********************/
    /**
     */
    protected static function _get_error_table() {
        return  [
                   'message_'._ERR_INVALID_SERVER_URI__         =>  'Invalid Server URL',
                   'message_'._ERR_INVALID_LOGIN__              =>  'Invalid Login Credentials',
                   'message_'._ERR_INVALID_AUTHENTICATION__     =>  'Invalid Authentication',
                   'message_'._ERR_NOT_AUTHORIZED__             =>  'Not Authorized to Perform This Operation',
                   'message_'._ERR_NO_RESULTS__                 =>  'No Results',
                   'message_'._ERR_PREV_LOGIN__                 =>  'There Is Already A Login',
                   'message_'._ERR_COMM_ERR__                   =>  'There Was A Communication Error With the Server',
                   'message_'._ERR_INTERNAL_ERR__               =>  'There Was An Internal Program Error',
                   'message_'._ERR_NOT_LOGGED_IN__              =>  'Attempted to Log Out When No Login present',
                ];
    }
    
    /***********************/
    /**
     */
    protected static function _get_string_match_table() {
        return  [
                    'name'                                      =>  'search_name',
                    
                ];
    }
}
?>