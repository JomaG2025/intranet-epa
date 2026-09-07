<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function redirect($uri = '', $alerta = NULL, $referrer = FALSE, $method = 'location', $http_response_code = 302)
{
	$CI =& get_instance();
	if($alerta) $CI->session->set_flashdata('alerta', $alerta);
    if($referrer)
    {
        if($CI->agent->is_referral()) redirect($CI->agent->referrer());
        else _redirect($uri, $method, $http_response_code);    
    }
    else
    {
        _redirect($uri, $method, $http_response_code);    
    }
}

function _redirect($uri = '', $method = 'location', $http_response_code = 302)
{
    if ( ! preg_match('#^https?://#i', $uri))
	{
		$uri = site_url($uri);
	}

	switch($method)
	{
		case 'refresh'	: header("Refresh:0;url=".$uri);
            break;
        default			: header("Location: ".$uri, TRUE, $http_response_code);
            break;
    }
    exit;
}

function url_title($str, $separator = 'dash', $lowercase = FALSE)
{
    $str = strtr($str, $array = array(
        "À"=> "A",
        "Á"=>"A",
        "Â"=>"A",
        "Ã"=>"A",
        "Ä"=>"A",
        "Å"=>"A",
        "à"=>"a",
        "á"=>"a",
        "â"=>"a",
        "ã"=>"a",
        "ä"=>"a",
        "å"=>"a",
        "Ò"=>"O",
        "Ó"=>"O",
        "Ô"=>"O",
        "Õ"=>"O",
        "Ö"=>"O",
        "Ø"=>"O",
        "ò"=>"o",
        "ó"=>"o",
        "ô"=>"o",
        "õ"=>"o",
        "ö"=>"o",
        "ø"=>"o",
        "È"=>"E",
        "É"=>"E",
        "Ê"=>"E",
        "Ë"=>"E",
        "è"=>"e",
        "é"=>"e",
        "ê"=>"e",
        "ë"=>"e",
        "Ç"=>"C",
        "ç"=>"c",
        "Ì"=>"I",
        "Í"=>"I",
        "Î"=>"I",
        "Ï"=>"I",
        "ì"=>"i",
        "í"=>"i",
        "î"=>"i",
        "ï"=>"i",
        "Ù"=>"U",
        "Ú"=>"U",
        "Û"=>"U",
        "Ü"=>"U",
        "ù"=>"u",
        "ú"=>"u",
        "û"=>"u",
        "ü"=>"u",
        "ÿ"=>"y",
        "Ñ"=>"N",
        "ñ"=>"n"
    ));
    
    if ($separator == 'dash') 
    {
        $separator = '-';
    }
    else if ($separator == 'underscore')
    {
        $separator = '_';
    }
		
    $q_separator = preg_quote($separator);

    $trans = array(
	   '&.+?;'                 => '',
	   '[^a-z0-9 _-]'          => '',
	   '\s+'                   => $separator,
	   '('.$q_separator.')+'   => $separator
    );

    $str = strip_tags($str);

    foreach ($trans as $key => $val)
    {
	   $str = preg_replace("#".$key."#i", $val, $str);
    }

    if ($lowercase === TRUE)
    {
        $str = strtolower($str);
    }

    return trim($str, $separator);
}

?>