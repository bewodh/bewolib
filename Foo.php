<?php
/**
 * bewolib
 *
 * @copyright Copyright (c) 2012-2014 Bewotec GmbH (http://www.bewotec.de)
 * 
 */

namespace Bewo;

use Zend\Debug\Debug;

class Foo
{
    public static function test($var="") {
        //echo "success !";
        $test = 7.1;
        
        if($var != "") {
        	$returnValue = $var." success!";
        } else {
        	$returnValue = "success!";
        }
        
        return $returnValue;
    }
    
}
