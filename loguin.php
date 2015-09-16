<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loguin extends CI_Controller {
    public $user; 
	function __construct()
	{
		parent::__construct();
                $this->load->library('encrypt');
               // $this->user = @$this->session->userdata('usuario');
               
	}
	public function index()
	{
	
	 $this->load->view('loguin');//ver porque no me carga los css
	
	}
     	    	public function Registrarse()
	{      $this->load->model('Traza_modelo','tmodel');

                $datestring = "%Y-%m-%d %h:%m:%s";
                $time = time();
                 $fecha=mdate($datestring, $time);
                 $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"Registrarse",
                        'descripcion'=>"Error, existen campos vacíos",
                        'usuario'=>"",);

            if(!isset ($_POST['usuario']))
           {
                    $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"Registrarse",
                        'descripcion'=>"Error, el campo nombre del usuario se quedó vacío",
                        'usuario'=>"",);
                    $this->tmodel->insertar_traza($traza);
                      redirect ('loguin/index');
           }else
           {
               $this->form_validation->set_rules('usuario', 'Usuario', 'required');
               $this->form_validation->set_rules('password', 'Contraseña', 'required');
               //$this->form_validation->set_message('required', 'El campo %s es requerido.');
               $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');
               if($this->form_validation->run()== false)
               {
                       $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"Registrarse",
                        'descripcion'=>"Error, existen campos vacío",
                        'usuario'=>"0",);
                       $this->tmodel->insertar_traza($traza);
                          redirect ('loguin/index');
               }else
               {
                   $this->load->model('loguin_modelo');
                   $passw= $this->encrypt->decode($_POST['password']);
                   $usuario = $this->loguin_modelo->Validar_Usuario($_POST['usuario'],$passw);
                  if($usuario)
                  {
                      $datasession = array(
                     'usuario'  => $_POST['usuario'],
                     'nomb_usuario'=> $usuario->nomb_user,
                      'id_usuario'=> $usuario->id_user,
                     'id_rol'=> $usuario->id_rol,
                     'login_ok' => TRUE,   );
		    $this->session->set_userdata($datasession);
                    if($usuario->id_rol == '1')
               	      {
                        $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"Registrarse",
                        'descripcion'=>"El usuario ".$_POST['usuario']. " con permiso de administrador del sistema, se registró",
                        'usuario'=>$this->session->userdata['id_usuario']);
                        $this->tmodel->insertar_traza($traza);
		       $this->load->view('Administrar',$datasession);
	              }
                       else

                    if($usuario->id_rol == '2')
				     {
                         $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"Registrarse",
                        'descripcion'=>"El usuario ".$_POST['usuario']. " con permiso de oficial de producción en el sistema, se registró",
                        'usuario'=>$this->session->userdata['id_usuario']);
                          $this->tmodel->insertar_traza($traza);
		       $this->load->view('Normas',$datasession);
				     }
				    else
                  if($usuario->id_rol == '3')
				     {
                                            $traza= (object) array (
                                       'fecha'=>$fecha,
                                     'operacion'=>"Registrarse",
                                     'descripcion'=>"El usuario ".$_POST['usuario']. " con permiso de primer oficial de producción en el sistema, se registró",
                                    'usuario'=>$this->session->userdata['id_usuario']);
                          $this->tmodel->insertar_traza($traza);
				        $this->load->view('Reportes',$datasession);
				     }
				    else
                       {
                          $traza= (object) array (
                          'fecha'=>$fecha,
                         'operacion'=>"Registrarse",
                        'descripcion'=>"Error el usuario ".$_POST['usuario']. " no tiene permiso para registrarse en el sistema",
                          'usuario'=>'0');
                          $this->tmodel->insertar_traza($traza);
                       redirect('loguin/index');
                       }

                     }
                   else
                     { $traza= (object) array (
                          'fecha'=>$fecha,
                         'operacion'=>"Registrarse",
                        'descripcion'=>"Error el usuario con nombre ".$_POST['usuario']. " no tiene permiso para registrarse en el sistema",
                          'usuario'=>'0');
                          $this->tmodel->insertar_traza($traza);
                       $this->session->set_flashdata('error', 'El usuario o contraseña son incorrectos.');
                      redirect('loguin/index');
                     }

                   }

               }
       }
         public function logout()
            {
             $this->load->model('Traza_modelo','tmodel');

                $datestring = "%Y-%m-%d %h:%m:%s";
                $time = time();
                 $fecha=mdate($datestring, $time);
              $traza= (object) array (
                          'fecha'=>$fecha,
                         'operacion'=>"Cerrar Sesión",
                        'descripcion'=>"El usuario ".$this->session->userdata['nomb_usuario']. " saldrá del sistema",
                          'usuario'=>$this->session->userdata['id_usuario']);
                          $this->tmodel->insertar_traza($traza);
               //$this->user= NULL;
             $datasession= array (
                     'usuario_id'  => '',
                     'id_usuario'=>'',
                     'login_ok' => '',
                 );
              $this->session->unset_userdata($datasession);
               $this->session->sess_destroy();
	      //$this->cart->destroy();

               redirect ('loguin/index');
           }
		   
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */