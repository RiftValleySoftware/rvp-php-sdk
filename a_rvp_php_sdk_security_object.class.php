<?php
/***************************************************************************************************************************/
/**
    BLUE DRAGON PHP SDK
    
    © Copyright 2018, Little Green Viper Software Development LLC.
    
    MIT License
    
    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation
    files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy,
    modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the
    Software is furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
    OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
    IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
    CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
    
    Little Green Viper Software Development: https://littlegreenviper.com
*/
defined( 'RVP_PHP_SDK_ACCESS' ) or die ( 'Cannot Execute Directly' );	// Makes sure that this file is in the correct context.

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
        $tokens = $this->_object_data->security_tokens;
        
        $to_set = [
            'tokens' => ((isset($tokens) && is_array($tokens) && count($tokens)) ? implode(',', $tokens) : NULL)
            ];
        $put_args = '';
        
        foreach ($to_set as $key => $value) {
            if (isset($key) && isset($value)) {
                $put_args .= '&'.$key.'='.urlencode(trim(strval($value)));
            }
        }

        return parent::_save_data($put_args.$in_args);
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
    This requires a "detailed" load.
    
    \returns an array of integer (security tokens) that comprise the "pool" for this login.
     */
    function security_tokens() {
        $ret = [];
        
        $this->_load_data(false, true);
        
        if (isset($this->_object_data) && isset($this->_object_data->security_tokens)) {
            $ret = $this->_object_data->security_tokens;
        }
        
        return $ret;
    }
    
    /***********************/
    /**
    Set the tokens for this ID. NOTE: For security reasons, a user is not allowed to change their own tokens. In order to set the tokens for another user, the current user must be a manager.
    The manager must "own" all the tokens they specify. If they specify tokens they don't "own," then those tokens will be ignored.
    
    AN IMPORTANT NOTE ABOUT SECURITY TOKENS
    =======================================
    
    There are a few rules with setting security tokens:
    
    - You cannot set your own security tokens. It must be done by a manager object with edit rights to your login (not user).
    
    - You must be a manager to edit security tokens.
    
    - Any security tokens that you wish to edit must be ones that your login "owns."
    
    - You add security tokens by setting them as positive integers.
    
    - You remove security tokens by setting them as negative integers. HOWEVER, in order to remove security tokens, you must be an owner of EVERY SECURITY TOKEN IN THE TARGET LOGIN. If the login has tokens you can't see, the deletion will fail.
    
    The object will force-reload its data after this operation, in order to reflect the new security tokens.
    
    \returns true, if the operation succeeded
     */
    function set_security_tokens(    $in_token_array ///< REQUIRED: An array of int. Positive ints will be added, negative ones will be deleted.
                        ) {
        $ret = false;
        
        $this->_load_data(false, true);
        
        if (isset($this->_object_data) && $this->_sdk_object->is_manager() && ($this->_sdk_object->current_login_id() != $this->id())) {
            $in_vals = array_map('intval', $in_token_array);
            $this->_object_data->security_tokens = $in_vals;
            $ret = $this->save_data();
            if ($ret) {
                $ret = $this->_load_data(true, true);
            } else {
                $this->_load_data(true, true);  // We reload the data in any case, but we don't record the result if the operation failed.
            }
        }
        
        return $ret;
    }
};
