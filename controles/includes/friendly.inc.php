<?php

    function friendly($value) {
		
        $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
		$repl = array('a', 'e', 'i', 'o', 'u', 'n');
		$value = str_replace ($find, $repl, $value);
		
        $find = array('Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ');
		$repl = array('A', 'E', 'I', 'O', 'U', 'n');
		$value = str_replace ($find, $repl, $value);
		
        $find = array(' ', '&', '\r\n', '\n', '+');
		$value = str_replace ($find, '-', $value);
		
        $find = array('/[^a-z0-9\.\^A-Z\-<>]/', '/[\-]+/', '/<[^>]*>/');
		$repl = array('', '-', '');
		$value= preg_replace ($find, $repl, $value);
		
        return strtolower($value);
        
	}

?>