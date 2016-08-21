<?php

class ClassyClass {

	function noHints1($x) { //============ error

	}

	function noHints2($x, $x) : ClassyClass { //============ error

	}

	function noHints2(
		$x, 
		$x
	) : DateTime { //============ error

	}

	function noHints2(
		$x, 
		$x
	) : Array 
	{ //============ error

	}

	function noHints2(
		$x, 
		$x
	) 
	: boolean 
	{ //============ error

	}

}


