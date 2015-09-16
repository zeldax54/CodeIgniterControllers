<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reportes extends CI_Controller {
   
	function __construct()
	{
		parent::__construct();
               $this->user = @$this->session->userdata('usuario');
               
	}
	public function index()
	{
	
	 $this->load->view('Normas');//ver porque no me carga los css
	
	}
     	    
        public function imprimir_reportes()
        {
            echo'aaaa';
        }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */