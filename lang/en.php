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
                ];
    }
}
?>