<?php if (!defined('BASEPATH')) exit('No direct access allowed.');

class MY_Input extends CI_Input {

    public function __construct()
    {
        $this->_POST_RAW = $_POST;
        parent::__construct(); 
    }

    public function post($index = null, $xss_clean = TRUE) { 
        if( ! $xss_clean)
        {
            return $this->_POST_RAW[$index];
        }
       
        return parent::post($index, $xss_clean); 
    }
}

?>