<?php

class x_BOT{
}

function MXample()
{
	var_dump('this is an illegal method');
	dd('dd');

	var_export('this is illegal');
	print_r('this is illegal');
	var_export('this is still illegal', false);
	print_r('this is till ilelgal', false);
	var_export('this is safe', true);
	print_r('this is safe', true);

	eval('xx');
	shell_exec('xx');

	//die
	die('asds');
}

}


