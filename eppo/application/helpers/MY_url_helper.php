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

?>