<?php

class x_BOT{

	function noHints1($x) { //============ error

	}

	function noHints2($x, $x) { //============ error

	}

	function noHints3($x, $x, $x) { //============ error

	}

	function noHints1($x = null) { //============ error

	}

	function noHints1($x = 'x') { //============ error

	}

	function noHints1(DateTime $x, $u) {

	}

	function noHints1(DateTime $x = null) {

	}

	function noHints1(DateTime $x) {

	}

	function noHints2(DateTime $x, DateTime $x) {

	}

	function noHints3(
		DateTime $x, 
		DateTime $x, 
		DateTime $x) {

	}

}


