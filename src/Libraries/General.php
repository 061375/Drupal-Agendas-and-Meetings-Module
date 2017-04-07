<?php
namespace Drupal\msrc\Libraries;
/**
 *  
 *  General
 *  
 *  This represents a list of general methods I have gathered over the years that I find useful...some more than others
 *  some are outright depricated or just plain wrong...I remove stuff from time-to-time
 *  
 *  @author By Jeremy Heminger <j.heminger@061375.com>
 *  @copyright © 2013 to present 
 *
 * */
class General
{
	/**
	 * get a clean query string key
	 * @param string $key
	 * @param string
	 * @param bool
	 * @return string
	 * */
    public static function get_variable($key,$else='',$uri = false)
    {
        if(false == defined("GET_STRING")){
            parse_str(($_SERVER['QUERY_STRING'] ? $_SERVER['QUERY_STRING'] : ''), $_GET);
            define("GET_STRING",true);
        }
		$GET = self::cleanSuperGlobal($_GET,'clean_get');
        $return = isset($GET[$key]) ? $GET[$key] : $else;
		if(false !== $uri) {
		    if(false == is_string($return))return $else;
		    if(strpos($return,'/') === false)return array($return);
		    return explode('/',$return);
		}
		return $return;
    }
	/**
     * clean super globals
     * @param array $elem
     * @param string $globalkey
     * @return array
     *  */
    public static function cleanSuperGlobal($elem,$globalkey='') {
	    if(isset($GLOBALS[$globalkey]))return $GLOBALS[$globalkey];
	    if(false == is_array($elem)) 
		    $elem = htmlentities($elem,ENT_QUOTES,"UTF-8"); 
	    else 
		    foreach ($elem as $key => $value) 
			    $elem[$key] =self::cleanSuperGlobal($value); 
	    return $elem; 
    } 
    /**
     * gets a clean post string key
     * @param string $key
     * @param string $else
     * @param bool $bool if the expected result type is boolean
     * @param bool $die if the expected value is not mnet or empty should the program die
     * @param string $redirect if set the operation will redirect the user to $redirect
     * @param string $message a redirect message
     * @return mixed
     *  */
    public static function post_variable($key,$else='',$bool=false,$die=false,$redirect='',$message='')
    {
		$POST = self::cleanSuperGlobal($_POST,'clean_post');
        if($bool == false){
            $return = isset($POST[$key]) ? $POST[$key] : $else;
        }else{
            $return = isset($POST[$key]) ? 1 : 0;
        }
        if($return == '')
        {
            if($die == false){
                return $return;
            }else{
				if($redirect == ''){
					die($message);
				}else{
					self::Location($redirect);
				}
            }
        }
        return $return;
    }
    /**
     * gets a query and if false falls to POST
     * NOTE: *** this is bad practice and should be removed ***
     * @param string array key
     * @param string else
     * @return mixed
     *  */
    public static function get_query($var,$else='')
    {
        $return = self::get_variable($var,'');
        if($return == '')
        {
            return self::post_variable($var,$else);
        }
        return $return;
    }
    /**
     * this is the same as self::get_query but it uses the $_REQUEST super global
     * NOTE: *** this is bad practice and should be removed ***
     * @param string $key
     * @param string
     * @return mixed
     *  */
    public static function get_request($key,$else=''){
		$REQUEST = self::cleanSuperGlobal($_REQUEST,'clean_request');
        return isset($REQUEST[$key]) ? $REQUEST[$key] : $else;
    }
    /**
     * shortcut to ternary finding of an array key
     * @param array $array
     * @param string $key
     * @param string $default
     * @return mixed
     *  */
    public static function getFunctionParam($array,$key,$default=''){
        return isset($array[$key]) ? $array[$key] : $default;
    }
    /**
     * shortcut to ternary finding of an array key (same as getFunctionParam witha shorter name)
     * @param array $array
     * @param string $key
     * @param string $default
     * @return mixed
     *  */
    public static function is_set($array,$key,$default=''){
        return isset($array[$key]) ? $array[$key] : $default;
    }
    /**
     * shortcut to ternary catch for the PHP defined function
     * @param string the CONSTANT to check if defined
     * @param string $else
     * @return bool
     *  */
    public static function is_defined($variable,$else = ''){
        return defined($variable) ? $variable : $else;
    }
}