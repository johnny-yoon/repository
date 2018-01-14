<?php
// application library 1
namespace App\MyLib1;

const MYCONST = 'Yohan';

function MyFunction() {
	return __FUNCTION__;
}

class MyClass {
	static function WhoAmI() {
		return __METHOD__;
	}
}
?>