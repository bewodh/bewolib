<?php
/**
 * CMS
 *
 * @copyright Copyright (c) 2012-2014 DHE, Daniel Henninger (http://www.dhe.de)
 */

namespace Cms;

use Zend\Debug\Debug;

class Foo
{
    public static function test($var="") {
        //echo "success !";
        
        if($var != "") {
        	$returnValue = $var." success!";
        } else {
        	$returnValue = "success!";
        }
        
        return $returnValue;
    }
    
}
