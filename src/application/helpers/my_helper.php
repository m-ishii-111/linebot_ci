<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (! function_exists('debug_log'))
{
    /**
     * @access public
     * @param mixed
     */
    function debug_log($value)
    {
        if (gettype($value) == 'array') {
            $value = print_r($value, true);
        }
    
        log_message('error', $value);
    }
}

if (! function_exists('timezone'))
{
    /**
     * @access public
     * @return string
     */
    function timezone()
    {
        $hour = date("H");
        if (5 < $hour && $hour <= 10)
        {
            $time = 'morning';
        }
        elseif (10 < $hour && $hour <= 15)
        {
            $time = 'noon';
        }
        elseif (15 < $hour && $hour <= 21)
        {
            $time = 'night';
        }
        else
        {
            $time = 'midnight';
        }
        return $time;
    }
}