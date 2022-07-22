<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @access public
 * @param integer
 * @return string
 */
function hr($size = 2)
{
    if ($size == 2) {
        return '<hr />';
    } else {
        return '<hr size"' . $size . '" />';
    }
}