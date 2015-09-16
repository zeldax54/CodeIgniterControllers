<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class administrar extends CI_Controller {
   function __construct()
    {
		parent::__construct();
                $this->load->library('encrypt');
                //
		 $this->user = @$this->session->userdata('usuario');
                                
	}
   public function index()
   {
		$this->load->view('Administrar');
               
	}
        function cargar_reportes()
        {
                $this->load->model('Traza_modelo','tmodel');
               $user=$this->session->userdata['id_usuario'];
                $datestring = "%Y-%m-%d %h:%m:%s";
                $time = time();
                 $fecha=mdate($datestring, $time);
                 $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"Cargar una vista",
                        'descripcion'=>"se realizó un link a la vista Normas Productivas",
                        'usuario'=>$user,);
                   $this->tmodel->insertar_traza($traza);
                $this->load->view('Reportes');
        }
         function cargar_normas()
        {
                 $this->load->model('Traza_modelo','tmodel');
               $user=$this->session->userdata['id_usuario'];
                $datestring = "%Y-%m-%d %h:%m:%s";
                $time = time();
                 $fecha=mdate($datestring, $time);
                 $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"Cargar una vista",
                        'descripcion'=>"se realizó un link a la vista Reportes",
                        'usuario'=>$user,);
                   $this->tmodel->insertar_traza($traza);
           $this->load->view('Normas');
        }
	//-
	//--------------CARGAR VISTAS----------------------

       public function salvar_bd()
       {
                date_default_timezone_set("Europe/Madrid");
                // Carga la clase de utilidades de base de datos
                $this->load->dbutil();
                $fecha_hora = date("Ymd_His");

                $prefs = array(
            'tables'      => array(),                   // Arreglo de tablas para respaldar.
            'ignore'      => array(),           // Lista de tablas para omitir en la copia de seguridad
            'format'      => 'zip',             // gzip, zip, txt
            'filename'    => 'mybackup.sql',    // Nombre de archivo - NECESARIO SOLO CON ARCHIVOS ZIP
            'add_drop'    => TRUE,              // Agregar o no la sentencia DROP TABLE al archivo de respaldo
            'add_insert'  => TRUE,              // Agregar o no datos de INSERT al archivo de respaldo
            'newline'     => "\n"               // Caracter de nueva línea usado en el archivo de respaldo
        );

                // Crea una copia de seguridad de toda la base de datos y la asigna a una variable
                $this->load->model('Traza_modelo','tmodel');
               $user=$this->session->userdata['id_usuario'];
                $datestring = "%Y-%m-%d %h:%m:%s";
                $time = time();
                 $fecha=mdate($datestring, $time);
                 $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"Salva a la Base de Datos",
                        'descripcion'=>"se realizó un copia de seguridad a la vista Base de Datos del sistema",
                        'usuario'=>$user,);
                   $this->tmodel->insertar_traza($traza);
                $copia_de_seguridad = $this->dbutil->backup($prefs);

                //print_r($copia_de_seguridad);
                // Carga el asistente de archivos y escribe el archivo en su servidor
                $this->load->helper('file');


                if ( ! write_file('./backup/backup_'.$fecha_hora.'.zip', $copia_de_seguridad))
                {
                    echo ('No se ha podido crear la copia.');
                    }
                else
                {
                 echo ('Copia creada satisfactoriamente');
                }

                // Carga el asistente de descarga y envía el archivo a su escritorio
                $this->load->helper('download');
                force_download('copia_de_seguridad.zip', $copia_de_seguridad);
                //$this->smarty->view('index');

           //$datos = file_get_contents("/path/to/photo.jpg"); // Leer el contenido del archivo
         //  $nombre = 'normas_productivas.';
           //force_download($nombre, $datos);
       }


       public function cargar_user()
    {
	   $this->load->model('Administrar_modelo','rmodel');
          $temp =$this->rmodel->get_rol();
          if($temp)
          {
        $data['result']=$this->rmodel->get_rol();  
		$this->load->view('crear_usuario', $data);
          }
          else
          {
              redirect(site_url('administrar/index'));
              echo "No existen roles definidos en el sistema. Es imposible crear un usuario.";
          }
	} 	  
    public function cargar_rol()
    {
		$this->load->view('crear_rol');
	} 	  
    public function cargar_fase()
    {
		$this->load->view('crear_fase');
	} 	
	   public function cargar_umedida()
    {
		$this->load->view('crear_unidad_medida');
	}  
        public function cargar_utiempo()
        {
            $this->load->view('crear_unidad_tiempo');
        }
   public function cargar_medio_produccion()
        {
            $this->load->view('crear_medios_productivos');
        }
        public function cargar_sistema_productor()
        {
            $this->load->view('crear_sistema_productor');
        }
           public function cargar_sistema_informatico()
        {
            $this->load->model('Administrar_modelo','rmodel');
               $data['productor']=$this->rmodel->get_sistema_productor();
               $temp=$this->rmodel->get_sistema_productor();
               if($temp)
               {
            $data['trabajo']=$this->rmodel->get_trabajo();
            $this->load->view('crear_sistema_informatico',$data);
             //  $this->load->view('crear_sistema_informatico');
               }
               else
               {redirect(site_url('administrar/index'));
              echo "No existen sistemas productores, por lo que no es posible crear un sistema informatico.";

              }
        }
           public function cargar_tipo_produccion()
        {
            $this->load->view('crear_tipo_trabajo');
        }
            public function cargar_fases_por_tipo_produccion()
        {
            $this->load->model('Administrar_modelo','rmodel');
            $t=$this->rmodel->get_trabajo();
            $s= $this->rmodel->get_fase();
            if( ($t) && ($s))
            {
              $data['tipo']=$this->rmodel->get_trabajo();
              $data['fases']= $this->rmodel->get_fase();
                $this->load->view('crear_fase_por_tipo_trabajo', $data);
            }
            else
            {
             redirect(site_url('administrar/index'));
              echo "No existen tipos de producciones o/y fases de trabajo registradas en el sistemma, por lo que no es posible definir las fases asignadas a un tipo de produccion.";

            }
        }
       public function cargar_medios_por_tipo_produccion()
        {
            $this->load->model('Administrar_modelo','rmodel');
            $tp=$this->rmodel->get_medios_produccion();
            $t=$this->rmodel->get_trabajo();
            if(($tp)&&($t))
            {
              $data['tipo']=$this->rmodel->get_trabajo();
              $data['medio']= $this->rmodel->get_medios_produccion();
                $this->load->view('crear_medios_por_tipo_produccion', $data);
            }
       else
            {
             edirect(site_url('administrar/index'));
              echo "No existen tipos de producciones o/y medios de produccion registradas en el sistemma, por lo que no es posible definir los medios asignados a un tipo de produccion.";

            }
         }

//----------------GESTIONAR USUARIO----------------------------
		
	  //----INSERTAR//----
    public function insertar_usuario()
    {
       $nom_rol=  $this->input->get_post("nomb_rol");
       //echo '$nom_rol';
            if($nom_rol == "Administrador")
            {
           
             $tipo_rol= 1;
            }
          if($nom_rol == "Operador")
              {
                 $tipo_rol= 2;
               }
           if($nom_rol=="Oficial de Produccion")
           {

             $tipo_rol= 3;
           }
         $user=$this->session->userdata['id_usuario'];
                    $datestring = "%Y-%m-%d %h:%m:%s";
                    $time = time();
                    $fecha=mdate($datestring, $time);
        $this->load->model('Administrar_modelo','rmodel');
        $this->load->model('Traza_modelo','tmodel');
        //$data['result']=$this->rmodel->get_rol();
        $this->form_validation->set_rules('nomb_user', 'Nombre del Usuario', 'required');  
        $this->form_validation->set_rules('password', 'Contrasenna', 'required');
        $this->form_validation->set_rules('conf_password', 'Confirmar contrasenna', 'required');
        $this->form_validation->set_message('required', '<span id="msgvalid">El campo %s es obligatorio. </span>');               
        if ($this->form_validation->run() == FALSE)
        { ///echo 'ERROR';
             $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"INSERTAR USUARIO",
                        'descripcion'=>"Error, existen campos vacios ". $this->input->get_post('nomb_user'),
                        'usuario'=>$user,);
                      $this->tmodel->insertar_traza($traza);
            $data['result']=$this->rmodel->get_rol();
            $this->load->view('crear_usuario',$data);
        }
        else
        {
            $passw =$this->input->get_post('password');
            $conf_passw =$this->input->get_post('conf_password');
            if ( $passw == $conf_passw)
            {
                   //$encrypted_pass= md5($passw);//REVISAR
                   $encrypted_pass= $this->encrypt->encode($passw);
                    $datos = (object)array(
		   'nomb_user'=> $this->input->get_post('nomb_user'),
                    'password' => $encrypted_pass,
                    'tipo_rol' =>  $tipo_rol,
                    );
                    $u= FALSE;
                    $u= $this->rmodel->si_user($datos);
		    if ($u)
                    {           
                        $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"INSERTAR USUARIO",
                        'descripcion'=>"fue insertado en el sistema un nuevo usuario con nombre ". $this->input->get_post('nomb_user'),
                        'usuario'=>$user,);

                       $this->rmodel->insertar_user($datos);
                   $this->tmodel->insertar_traza($traza);
                  redirect(site_url('administrar/listado_usuarios')); //CREAR LA VISTA DE LISTADO DE USUARIOS
                    }
                    else{
                        $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"INSERTAR USUARIO",
                        'descripcion'=>"Error, ya existe un usuario registrado con el mismo nombre y contrasenna",
                        'usuario'=>$user,);
                        $this->tmodel->insertar_traza($traza);
                        echo "Ya existe un usuario registrado con el Nombre y Contraseñas";

                        }
               $data['result']=$this->rmodel->get_rol();
            $this->load->view('crear_usuario',$data);
             }
            else
            {
                 $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"INSERTAR",
                        'descripcion'=>"Error, no coinciden las contraseñas",
                        'usuario'=>$user,);
                        $this->tmodel->insertar_traza($traza);
            }
            $data['result']=$this->rmodel->get_rol();
            $this->load->view('crear_usuario',$data);
	   }
        }
            	
	//----MODIFICAR//----
	
    function editar_usuario($id, $editar = TRUE) //METODO MODIFICAR
    {
      $data['id'] = $id;

      $this->load->model('Administrar_modelo','amodel');   
      $this->load->model('Traza_modelo','tmodel');
      $data['usuario'] = $this->amodel->mostrar_user($id);
     $id_rol=$data['usuario']->id_rol;
      $data['rol']= $this->amodel->mostrar_rol($id_rol);
      $data['result'] = $this->amodel->get_rol();
      //$data['passw']=$p;
      if($editar==FALSE)
      {

           $this->load->view('editar_usuario',$data);   //CREAR LA VISTA DE EDITAR LOS USUARIOS
      }
      else
      {
	 // $this->form_validation->set_rules('id_user', 'Identificador del Usuario', 'required');
        $this->form_validation->set_rules('nomb_user', 'Usuario', 'required');
        $this->form_validation->set_rules('password', 'Contrasenna', 'required');
        //$this->form_validation->set_rules('nomb_rol', 'Tipo Rol', 'required');
         $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');              
        if ($this->form_validation->run() == FALSE)
        {
             $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"EDITAR USUARIO",
                        'descripcion'=>"Error, existen campos vacios ". $this->input->get_post('nomb_user'),
                        'usuario'=>$user,);
                      $this->tmodel->insertar_traza($traza);
            $this->load->view('editar_usuario',$data); //CREAR LA VISTA DE EDITAR LOS USUARIOS
        }
        else
        {         

            $datos['id_user'] = $id;
            $datos['nomb_user'] = $this->input->post('nomb_user');
            $datos['password'] = $this->input->post('password');
            $datos['nomb_rol'] = $this->input->post('nomb_rol');
            $result = $this->amodel->modificar_user($id, $datos );
            if( $result )
            { 
                $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"EDITAR USUARIO",
                        'descripcion'=>"Fue modificado el usuario". $this->input->get_post('nomb_user'),
                        'usuario'=>$user,);
                      $this->tmodel->insertar_traza($traza);
                redirect(site_url('administrar/listado_usuarios'));}
            else {
                $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"EDITAR USUARIO",
                        'descripcion'=>"Error, no pudo modificarse el usuario ". $this->input->get_post('nomb_user'),
                        'usuario'=>$user,);
                      $this->tmodel->insertar_traza($traza);
                echo 'Error!'.$this->db->last_query();}
        }
      }
    }
	  //----ELIMINAR//----
    function eliminar_usuario($id)
    {        
        $this->load->model('administrar_modelo','amodel');
        $this->load->model('traza_modelo','tmodel');
        $cant_user=  $this->amodel->cantd_usuarios_admin();
        $u=$this->amodel->mostrar_user($id);
         $user=$this->session->userdata['id_usuario'];
                       $datestring = "%Y-%m-%d %h:%m:%s";
                       $time = time();
                       $fecha=mdate($datestring, $time);
                        //$operacion='INSERT' ;
                        $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"ELIMINAR USUARIO",
                        'descripcion'=>"Fue eliminado del sistema el usuario ". $u->nomb_user,
                        'usuario'=>$user,);
        if(($cant_user>1)||(($u->id_rol)!='1'))
        {
       // $nomb_user=
            if($this->session->userdata['id_usuario'] != $id)
        {
                       
                     $res = $this->amodel->eliminar_user($id);
                     $this->tmodel->insertar_traza($traza);
                    if( $res ) redirect(site_url('administrar/listado_usuarios'));
                     else{
                         $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"EDITAR USUARIO",
                        'descripcion'=>"Error, no pudo eliminarse al usuario ". $this->input->get_post('nomb_user'),
                        'usuario'=>$user,);
                      $this->tmodel->insertar_traza($traza);
                         echo 'Error!'.$this->db->last_query();}
        }
          else
           {
              $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"ELIMINAR USUARIO",
                        'descripcion'=>"Error no se pudo eliminar al usuario".$u->nomb_user."ya que es el usuario registrado",
                        'usuario'=>$user,);
              //$traza['descripcion']="Error no se pudo eliminar ya que es el usuario registrado";
                $this->tmodel->insertar_traza($traza);
             echo 'El administrador registrado no  puede  eliminarse !';
           }
        }
         else
        {
              $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"ELIMINAR USUARIO",
                        'descripcion'=>"Error, el sistema no puede quedarse sin administrador",
                        'usuario'=>$user,);
            // $traza['descripcion']="Error, el sistema no puede quedarse sin administrador";
               $this->tmodel->insertar_traza($traza);
            echo 'El sistema no puede quedarse sin administrador !';
        }
    }
    //----LISTAR//----
    function listado_usuarios()
    {
         $this->load->model('Administrar_modelo');
           $this->load->model('Traza_modelo','tmodel');
        $data['result']=$this->Administrar_modelo->get_user();
        $data['rol']=$this->Administrar_modelo->get_rol();
        $cant=$this->Administrar_modelo->get_user();
        $user=$this->session->userdata['id_usuario'];
                       $datestring = "%Y-%m-%d %h:%m:%s";
                       $time = time();
                       $fecha=mdate($datestring, $time);
                        //$operacion='INSERT' ;
                        $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"LISTAR USUARIOS",
                        'descripcion'=>"se mostró el listado de los usuarios registrados en el sistema",
                        'usuario'=>$user,);
        if( (sizeof($cant))>1)
        {
        $data['page_title']="Listado de usuarios";
        
          $this->tmodel->insertar_traza($traza);
           $this->load->view('listado_usuarios',$data);
        }
        else
        {     $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"LISTAR USUARIOS",
                        'descripcion'=>"Error no existen usuarios registrados en el sistema",
                        'usuario'=>$user,);
              $this->tmodel->insertar_traza($traza);
            echo "Error no existen usuarios registrados en el sistema";
        }
    }
    
  //---------------------------GESTIONAR ROL----------------------
    
  //----INSERTAR//----
    public function insertar_rol()
    {
      $this->load->model('Administrar_modelo','rmodel');
       $this->load->model('Traza_modelo','tmodel');
       $user=$this->session->userdata['id_usuario'];
       $datestring = "%Y-%m-%d %h:%m:%s";
                       $time = time();
                       $fecha=mdate($datestring, $time);
                        //$operacion='INSERT' ;
                        $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"INSERTAR ROL",
                        'descripcion'=>"se insertó un nuevo rol:".$this->input->get_post('tipo_rol')." al sistema",
                        'usuario'=>$user,);
    
        $this->form_validation->set_rules('tipo_rol', 'Nombre del Rol', 'required');  
        $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');
                          
        if ($this->form_validation->run() == FALSE)
        {
             $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"INSERTAR ROL",
                        'descripcion'=>"Error, no se insertó un nuevo rol de :".$this->input->get_post('tipo_rol')." al sistema",
                        'usuario'=>$user,);
             $this->tmodel->insertar_traza($traza);
           $this->load->view('crear_rol');
        }
        else
        {
            
            $datos = (object)array(
                    'tipo_rol'=> $this->input->get_post('tipo_rol'),                                              
                );
		   $rol= $this->rmodel->buscar_rol($datos);
		    if ($rol)  
		      {  $this->rmodel->insertar_rol($datos);
                          $this->tmodel->insertar_traza($traza);
                       redirect(site_url('administrar/listado_roles')); //CREAR LA VISTA DE LISTADO DE USUARIOS
			}
			else
			{
                        $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"INSERTAR ROL",
                        'descripcion'=>"Error,el rol con nombre:".$this->input->get_post('tipo_rol')." ya existe en el sistema",
                        'usuario'=>$user,);
                       $this->tmodel->insertar_traza($traza);
			$this->load->view('crear_rol');
			}
		}
              
    }
//----ELIMINAR//----	
    function eliminar_rol($id)
    {        
        $this->load->model('administrar_modelo','amodel');
        $this->load->model('Traza_modelo','tmodel');
       $user=$this->session->userdata['id_usuario'];
       $datestring = "%Y-%m-%d %h:%m:%s";
                       $time = time();
                       $fecha=mdate($datestring, $time);
                        //$operacion='INSERT' ;

        $res = $this->amodel->eliminar_rol($id);
        if( $res ) {
             $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"ELIMINAR ROL",
                        'descripcion'=>"Se eliminó el rol de:".$this->input->get_post('tipo_rol')." en el sistema",
                        'usuario'=>$user,);
             $this->tmodel->insertar_traza($traza);
                        redirect(site_url('administrar/listado_roles'));}
        else
            {  $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"ELIMINAR ROL",
                        'descripcion'=>"Se eliminó el rol de:".$this->input->get_post('tipo_rol')." en el sistema",
                        'usuario'=>$user,);
            $this->tmodel->insertar_traza($traza);
            echo 'Error!'.$this->db->last_query();
            }
        }
 //----LISTAR//----
    function listado_roles()
    {
        $this->load->model('Administrar_modelo');
         $this->load->model('Traza_modelo','tmodel');
       $user=$this->session->userdata['id_usuario'];
       $datestring = "%Y-%m-%d %h:%m:%s";
                       $time = time();
                       $fecha=mdate($datestring, $time);

        $data['result']=$this->Administrar_modelo->get_rol();
        $cant=$this->Administrar_modelo->get_rol();
        if( (sizeof($cant))>1)
        {
             $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"LISTAR ROL",
                        'descripcion'=>"Se mostr'o el listado de los roles definidos en el sistema",
                        'usuario'=>$user,);
             $this->tmodel->insertar_traza($traza);
        $data['page_title']="Listado de los roles";
        $this->load->view('listado_roles',$data);
        }
        else
        {
                $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"LISTAR ROL",
                        'descripcion'=>"Error, no existen roles definidos en el sistema",
                        'usuario'=>$user,);
             $this->tmodel->insertar_traza($traza);
            echo "Error no existen roles registrados en el sistema";
        }
    }
  
  //---------------------------GESTIONAR FASES------------------
      //----INSERTAR//----
   public function insertar_fase()
   {
      $this->load->model('Administrar_modelo','rmodel');   
       $this->load->model('Administrar_modelo');
         $this->load->model('Traza_modelo','tmodel');
       $user=$this->session->userdata['id_usuario'];
       $datestring = "%Y-%m-%d %h:%m:%s";
                       $time = time();
                       $fecha=mdate($datestring, $time);
        $this->form_validation->set_rules('nomb_fase', 'Nombre de la Fase de Trabajo', 'required');  
         $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');                    
        if ($this->form_validation->run() == FALSE)
        {
            $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"INSERTAR FASE DE TRABAJO",
                        'descripcion'=>"Error, no se pudo insertar la fase, no existen datos vacios",
                        'usuario'=>$user,);
             $this->tmodel->insertar_traza($traza);
            redirect ('administrar/cargar_fase');
        }
        else
        {
            
            $datos = (object)array(
                    'nomb_fase'=> $this->input->get_post('nomb_fase'),   
                                                               
                );
              $fase= $this->rmodel->buscar_fase($datos);
		    if ($fase)
		      {
                         $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"INSERTAR FASE DE TRABAJO",
                        'descripcion'=>"Se insertó la fase de trabajo ".$this->input->get_post('nomb_fase'),
                        'usuario'=>$user,);
             $this->tmodel->insertar_traza($traza);
		       $this->rmodel->insertar_fase($datos);
            redirect(site_url('administrar/listado_fases_trabajo')); //CREAR LA VISTA DE LISTADO DE fases de trabajos
		     }
		     else
		     {
                            $traza= (object) array (
                        'fecha'=>$fecha,
                       'operacion'=>"INSERTAR FASE DE TRABAJO",
                        'descripcion'=>"Error, no existen fases de trabajo registradas en el sistema",
                        'usuario'=>$user,);
                         $this->tmodel->insertar_traza($traza);//HASTA AQUI
			 $this->load->view('crear_fase');
		 }
		}
      
    }	
	//----ELIMINAR//----
   function eliminar_fase($id)
    {        
        $this->load->model('administrar_modelo','amodel');    
        $res = $this->amodel->eliminar_fase($id);
        if( $res ) 
        {
            redirect(site_url('administrar/listado_fases_trabajo'));
            
        }
        else 
        {
            echo 'Error!'.$this->db->last_query();
        }
    }
	 //----MODIFICAR//----
   function editar_fase($id, $editar = TRUE) //METODO MODIFICAR
    {
      $data['id'] = $id;
      $this->load->model('Administrar_modelo','amodel');   
      $data['fase'] = $this->amodel->mostrar_fase($id);
      if($editar==FALSE)
      { 
           $this->load->view('editar_usuario',$data);   //CREAR LA VISTA DE EDITAR LOS USUARIOS
      }
      else
      {
	  //$this->form_validation->set_rules('id_fase_trabajo', 'Identificador de la fase de trabajo', 'required');
        $this->form_validation->set_rules('nomb_fase', 'Fase de Trabajo', 'required');
         $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');              
        if ($this->form_validation->run() == FALSE)
        {
           //  redirect(site_url('administrar/listado_fases_trabajo'));
           $this->load->view('editar_fase',$data); //CREAR LA VISTA DE EDITAR LOS USUARIOS
        }
        else
        {
            $datos['id_fase_trabajo'] = $id;
            $datos['nomb_fase'] = $this->input->post('nomb_fase');
           // echo $this->input->post('nomb_fase');
            $result = $this->amodel->modificar_fase($id, $datos );
            
            if( $result ) 
            {
                redirect(site_url('administrar/listado_fases_trabajo'));
            }
            else 
            {
                echo 'Error!'.$this->db->last_query();
            }
        }
      }
    }
	 //----LISTAR//----
    function listado_fases_trabajo()
    {
        $this->load->model('administrar_modelo');
        $data['result']=$this->administrar_modelo->get_fase();
        $data['page_title']="Listado de las fases de trabajo";
        $this->load->view('listado_fases',$data);
    }
  
    //---------------------------GESTIONAR UNIDADES DE TIEMPO------------------
      //----INSERTAR//----
   public function insertar_unidad_tiempo()
   {
      $this->load->model('Administrar_modelo','rmodel');   
      $this->form_validation->set_rules('unidad_tiempo', 'La Unidad de Tiempo', 'required');  
       $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');                     
        if ($this->form_validation->run() == FALSE)
        {
           $this->load->view('crear_unidad_tiempo');
        }
        else
        {
            $datos = (object)array(
                    'tiempo'=> $this->input->get_post('unidad_tiempo'), ); 
	}
        $tiempo= $this->rmodel->buscar_tiempo($datos);
        if($tiempo)
        {
            $this->rmodel->insertar_unidad_tiempo($datos);
            redirect(site_url('administrar/listado_unidad_tiempo')); //CREAR LA VISTA DE LISTADO DE UNIDAD TIEMPO
        }
        else
        {
              $this->load->view('crear_unidad_tiempo');            
        }
      }	
    //----ELIMINAR//----
    function eliminar_unidad_tiempo($id)
    {        
        $this->load->model('administrar_modelo','amodel');    
        $res = $this->amodel->eliminar_unidad_tiempo($id);
        if($res)
        { redirect(site_url('administrar/listado_unidad_tiempo'));}
        else
        {echo 'Error!'.$this->db->last_query();}
    }
    //----MODIFICAR//----
   function editar_unidad_tiempo($id, $editar = TRUE) //METODO MODIFICAR
    {
      $data['id'] = $id;

      $this->load->model('administrar_modelo','amodel');   
     
      $data['ut'] = $this->amodel->mostrar_unidad_tiempo($id);

      if($editar==FALSE)
      { 
           $this->load->view('editar_unidad_tiempo',$data);   //CREAR LA VISTA DE EDITAR LOS USUARIOS
      }
      else
      {
	// $this->form_validation->set_rules('id_unidad_tiempo', 'Identificador de la Unidad de Tiempo', 'required');
        $this->form_validation->set_rules('unidad_tiempo', 'Unidad de Tiempo', 'required');   
        $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('editar_unidad_tiempo',$data); //CREAR LA VISTA DE EDITAR LOS USUARIOS
        }
        else
        {
            $datos['id_unidad_tiempo'] = $this->input->post('id_user');
            $datos['unidad_tiempo'] = $this->input->post('nomb_user');
            $result = $this->amodel->modificar_unidad_tiempo($this->input->post('id'), $datos );
            if($result) 
                { redirect(site_url('administrar/listado_unidad_tiempo'));
                
                }
            else 
                {echo 'Error!'.$this->db->last_query();}
        }
      }
    }
    function listado_unidad_tiempo()
    {
        $this->load->model('Administrar_modelo');
        $data['result']=$this->Administrar_modelo->get_unidad_tiempo();
        $data['page_title']="Listado de las unidades de tiempo";
        $this->load->view('listado_unidad_tiempo',$data);
    }
  
   //---------------------------GESTIONAR UNIDADES DE UNIDAD MEDIDA------------------
      //----INSERTAR//----
    function insertar_unidad_medida()
	{
      $this->load->model('Administrar_modelo','rmodel');   
      $this->form_validation->set_rules('unidad_medida', 'La Unidad de Medida', 'required');  
       $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');                   
        if ($this->form_validation->run()==FALSE)
        {           // echo "ERROR:::";
          $this->load->view('crear_unidad_medida');
        }
        else
        {
            $datos = (object)array(
                    'unidad_medida'=> $this->input->get_post('unidad_medida'),
                ); 
         }
         $medida= $this->rmodel->buscar_medida($datos);
         if($medida)
         {
           $this->rmodel->insertar_unidad_medida($datos);
            redirect(site_url('administrar/listado_unidad_medida')); //CREAR LA VISTA DE LISTADO DE UNIDAD MEDIDA
         }
         else
         {
           $this->load->view('crear_unidad_medida');
           echo 'Error al insertar la unidad de medida';//PROBAR
         }
       }
         //----ELIMINAR//----
       function eliminar_unidad_medida($id)
    {        
        $this->load->model('administrar_modelo','amodel');    
        $res = $this->amodel->eliminar_unidad_medida($id);
        if( $res ) 
        {
           redirect(site_url('administrar/listado_unidad_medida'));
        }
        else {
            echo 'Error!'.$this->db->last_query();
             
             }
    }
     //----MODIFICAR//----
    	function editar_unidad_medida($id, $editar = TRUE) //METODO MODIFICAR
    {
      $data['id'] = $id;

      $this->load->model('Administrar_modelo','amodel');   
     
      $data['um'] = $this->amodel->mostrar_unidad_medida($id);

      if($editar==FALSE)
      { 
           $this->load->view('editar_unidad_medida',$data);   //CREAR LA VISTA DE EDITAR LOS USUARIOS
      }
      else
      {
	// $this->form_validation->set_rules('id_unidad_medida', 'Identificador de la Unidad de Medida', 'required');
        $this->form_validation->set_rules('unidad_medida', 'Unidad de Medida', 'required');        
       $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('editar_unidad_medida',$data); //CREAR LA VISTA DE EDITAR LOS USUARIOS
        }
        else
        {
            $datos['id_unidad_medida'] = $this->input->post('id_unidad_medida');
            $datos['unidad_medida'] = $this->input->post('unidad_medida');
            $result = $this->amodel->modificar_unidad_medida($this->input->post('id'), $datos );
            if($result) 
            {
                redirect(site_url('administrar/listado_unidad_medida'));
                
            }
            else 
            {
                echo 'Error!'.$this->db->last_query();
                
            }
        }
      }
    }
    function listado_unidad_medida()
    {
        $this->load->model('Administrar_modelo');
        $data['result']=$this->Administrar_modelo->get_unidad_medida();
        $data['page_title']="Listado de las unidades de medidas";
        $this->load->view('listado_unidad_medida',$data);
    }
      //---------------------------GESTIONAR MEDIOS PRODUCTIVOS------------------
      //----INSERTAR//----
   public function insertar_medios_productivos()
   {
      $this->load->model('Administrar_modelo','rmodel');   
      $this->form_validation->set_rules('nomb_medio', 'Nombre del Medio', 'required');  
     //  $this->form_validation->set_rules('descripcion', 'Descripcion', 'required');  //VER SI ES NECESQARIO
       // $this->form_validation->set_rules('id_medio', 'Identificador del medio', 'required');
       $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');                
        if ($this->form_validation->run() == FALSE)
        {
           $this->load->view('crear_medios_productivos');
        }  
        else
        {
            $datos = (object)array(
                    'nomb_medio'=> $this->input->get_post('nomb_medio'), 
                    'descripcion'=> $this->input->get_post('descripcion'),  
                    //'id_medio' => $this->input->get_post('id_medio'),                         
                ); 
        
        $medio_produc= $this->rmodel->buscar_medio_produccion($datos);
        if($medio_produc)
        {
            $this->rmodel->insertar_medios_produccion($datos);
            redirect(site_url('administrar/listado_medios_productivos')); //CREAR LA VISTA DE LISTADO DE UNIDAD MEDIDA
       }
       else
       {
          $this->load->view('crear_medios_productivos');

       }
       }
   }
       //----ELIMINAR//----
   function eliminar_medios_productivos($id)
    {        
        $this->load->model('Administrar_modelo','amodel');    
        $res = $this->amodel->eliminar_medios_produccion($id);
        if( $res ) 
            { 
            redirect(site_url('administrar/listado_medios_productivos'));
            
            }
        else 
            {
            echo 'Error!'.$this->db->last_query();
            
            }
    }
    //----MODIFICAR//----
   function editar_medios_productivos($id, $editar = TRUE) //METODO MODIFICAR
    {
      $data['id'] = $id;

      $this->load->model('Administrar_modelo','amodel');   
     
      $data['ut'] = $this->amodel->mostrar_medios_produccion($id);

      if($editar==FALSE)
      { 
           $this->load->view('editar_medios_productivos',$data);   //CREAR LA VISTA DE EDITAR LOS USUARIOS
      }
      else
      {
	// $this->form_validation->set_rules('id_medio', 'Identificador del Medios Productivo', 'required');
        $this->form_validation->set_rules('nomb_medio', 'Medio Productivo', 'required');      
        $this->form_validation->set_rules('descripcion', 'Descripcion', 'required');  
        $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('editar_medios_productivos',$data); //CREAR LA VISTA DE EDITAR LOS USUARIOS
        }
        else
        {
            $datos['nomb_medio']= $this->input->post('nomb_medio');
            $datos['descripcion']= $this->input->post('descripcion');                   
            $result = $this->amodel->modificar_medios_produccion( $data['id'],$datos );
            if( $result ) redirect(site_url('administrar/listado_medios_productivos'));
            else echo 'Error!'.$this->db->last_query();
        }
      }
    }
    function listado_medios_productivos()
    {
        $this->load->model('Administrar_modelo');
        $data['result']=$this->Administrar_modelo->get_medios_produccion();
        $data['page_title']="Listado de los medios productivos";
        $this->load->view('listado_medios_productivos',$data);
    }
      
    

//---------------------------GESTIONAR MEDIOS PRODUCTIVOS------------------
      //----INSERTAR//----
   public function insertar_sistema_productor()
   {
      $this->load->model('Administrar_modelo','rmodel');   
      $this->form_validation->set_rules('nomb_sistema', 'Nombre del Sistema Productor', 'required');  
      // $this->form_validation->set_rules('descripcion', 'Descripcion', 'required');  //VER SI ES NECESQARIO
       $this->form_validation->set_rules('fecha_registro', 'Fecha de Registro', 'required');
       $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');                
        if ($this->form_validation->run() == FALSE)
        {
           $this->load->view('crear_sistema_productor');
        }  
        else
        {
            $datos = (object)array(
                    'nomb_sistema_productor'=> $this->input->get_post('nomb_sistema'), 
                    'descripcion'=> $this->input->get_post('descripcion'),  
                    'fecha_registro' => $this->input->get_post('fecha_registro'),                         
                );
          }
          $sp=true;
        $sp = $this->rmodel->buscar_sistema_productor($datos);
        if($sp)
        {
            $this->rmodel->insertar_sistema_productor($datos);
            redirect(site_url('administrar/listado_sistema_productor')); //CREAR LA VISTA DE LISTADO DE UNIDAD MEDIDA
        }

       }
       //----ELIMINAR//----
   function eliminar_sistema_productor($id)
    {        
        $this->load->model('Administrar_modelo','amodel');
      //  $sist= $this->amodel->eliminar
        $res = $this->amodel->eliminar_sistema_productor($id);
        if( $res ) 
            { 
            redirect(site_url('administrar/listado_sistema_productor'));
            
            }
        else 
            {
            echo 'Error!'.$this->db->last_query();
            
            }
    }
    //----MODIFICAR//----
   function editar_sistema_productor($id, $editar = TRUE) //METODO MODIFICAR
    {
      $data['id'] = $id;

      $this->load->model('Administrar_modelo','amodel');   
     
      $data['ut'] = $this->amodel->mostrar_sistema_productor($id);

      if($editar==FALSE)
      { 
           $this->load->view('editar_sistema_productor',$data);   //CREAR LA VISTA DE EDITAR LOS USUARIOS
      }
      else
      {
	// $this->form_validation->set_rules('id_medio', 'Identificador del Medios Productivo', 'required');
        $this->form_validation->set_rules('nomb_sistema', 'Nombre del Sistema Productor', 'required');      
        $this->form_validation->set_rules('descripcion', 'Descripcion', 'required');  
        $this->form_validation->set_rules('fecha_registro', 'Fecha de Registro', 'required');  
        $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('editar_sistema_productor',$data); //CREAR LA VISTA DE EDITAR LOS USUARIOS
        }
        else
        {
            $datos= array(
                    'id_sistema_productor'=> $id, 
                    'nomb_sistema_productor'=> $this->input->get_post('nomb_sistema'),  
                    'descripcion'=> $this->input->get_post('descripcion'),    
                   'fecha_registro'=> $this->input->get_post('fecha_registro'),  
                ); 
            $result = $this->amodel->modificar_sistema_productor($id, $datos );
            if( $result ) redirect(site_url('administrar/listado_sistema_productor'));
            else echo 'Error!'.$this->db->last_query();
        }
      }
    }
    function listado_sistema_productor()
    {
        $this->load->library('pagination');
       $opciones = array();
       $desde = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->load->model('Administrar_modelo');
        $temp= $this->Administrar_modelo->get_sistema_productor();
        $cant= sizeof($temp);
       $opciones['per_page'] =9;
       $opciones['base_url'] = base_url().'index.php/administrar/listado_sistema_productor';
       $opciones['total_rows'] = $cant;
       $opciones['uri_segment'] = 3;
        $this->pagination->initialize($opciones);
        $data['result']=$this->Administrar_modelo->get_todos_sistemas_productores($opciones['per_page'],$desde);
        $data['page_title']="Listado de los sistemas productores";
        $data['paginacion'] = $this->pagination->create_links();
        if($temp)
        {
        $this->load->view('listado_sistemas_productores',$data);
         }
         else
         {
             echo "ERROR no existen sistemas productores";
             
         }
    }
    
    
    //---------------------------GESTIONAR SISTEMAS INFORMATICOS------------------
      //----INSERTAR//----
   public function insertar_sistema_informatico()
   {
      $this->load->model('Administrar_modelo','rmodel');   
     
      $this->form_validation->set_rules('nomb_sistema', 'Nombre del Sistema Productor', 'required');
       $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');                
        if ($this->form_validation->run() == FALSE)
        {
            $data['productor']=$this->rmodel->get_sistema_productor();
            $data['trabajo']=$this->rmodel->get_trabajo();
            $this->load->view('crear_sistema_informatico',$data);
        }  
        else
        {
        
            $sistema_prod=$this->rmodel->obtener_id_sistema_productor($this->input->get_post('sistema'));
            $tipo_trabajo=$this->rmodel->obtener_id_tipo_trabajo($this->input->get_post('tipo_trabajo'));

            $d = (object)array(
                    'nomb_sistema_informatico'=> $this->input->get_post('nomb_sistema'),
                    'id_sistema_productor'=>$sistema_prod->id_sistema_productor,
                    'id_tipo_trabajo' =>$tipo_trabajo->id_tipo_produccion,
                );
          $validar=$this->rmodel->get_sistema_informatico();
          if($validar)
          {
          $si = $this->rmodel->buscar_sistema_informatico($d);

        if($si)
        {
            $this->rmodel->insertar_sistema_informatico($d);
            redirect(site_url('administrar/listado_sistema_informatico')); //CREAR LA VISTA DE LISTADO DE UNIDAD MEDIDA
        }
        else
        {
          echo 'Ya exite registrado el sistema informatico';
        redirect(site_url('administrar/cargar_sistema_informatico'));
        }
       }
       else
       {
          $this->rmodel->insertar_sistema_informatico($d);
            redirect(site_url('administrar/listado_sistema_informatico'));
       }
          }
  

       }
       //----ELIMINAR//----
   function eliminar_sistema_informatico($id)
    {        
        $this->load->model('Administrar_modelo','amodel');    
        $res = $this->amodel->eliminar_sistema_informatico($id);
        if( $res ) 
            { 
            redirect(site_url('administrar/listado_sistema_informatico'));
            
            }
        else 
            {
            echo 'Error!'.$this->db->last_query();
            
            }
    }
    //----MODIFICAR//----
   function editar_sistema_informatico($id, $editar = TRUE) //METODO MODIFICAR
    {
      $data['id'] = $id;

      $this->load->model('Administrar_modelo','amodel');   
     
      $data['si'] = $this->amodel->mostrar_sistema_informatico($id);
      $data['productor']=$this->amodel->get_sistema_productor();
     // $data['trabajo']=$this->amodel->get_trabajo();
      $id_sistema_productor=$data['si']->id_sistema_productor;
      $data['sistema_productor']= $this->amodel->mostrar_sistema_productor($id_sistema_productor);
       if($editar==FALSE)
      { 
           $this->load->view('editar_sistema_informatico',$data);   //CREAR LA VISTA DE EDITAR LOS USUARIOS
      }
      else
      {
	// $this->form_validation->set_rules('id_medio', 'Identificador del Medios Productivo', 'required');
        $this->form_validation->set_rules('nomb_sistema', 'Nombre del Sistema Productor', 'required');       
        $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('editar_sistema_informatico',$data); //CREAR LA VISTA DE EDITAR LOS USUARIOS
        }
        else
        {
            $sistema_prod=$$this->rmodel->obtener_id_sistema_productor($this->input->get_post('nomb_sistema'));
            $tipo_trabajo=$$this->rmodel->obtener_id_tipo_trabajo($this->input->get_post('tipo_trabajo'));
            $datos= array(
                    'id_sistema_informatico'=> $id,
                    'nomb_sistema_informatico'=> $this->input->get_post('nomb_sistema'),
                    'id_sistema_productor'=> $sistema_prod->id_sistema_productor,
                   'id_tipo_trabajo'=> $tipo_trabajo->id_tipo_trabajo,
                ); 
            $result = $this->amodel->modificar_sistema_informatico($id, $datos );
            if( $result ) redirect(site_url('administrar/listado_sistema_informatico'));
            else echo 'Error!'.$this->db->last_query();
        }
      }
    }
    function listado_sistema_informatico()
    {
        $this->load->model('Administrar_modelo');
         $this->load->library('pagination');
       $opciones = array();
       $desde = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $temp=$this->Administrar_modelo->get_sistema_informatico();
        $cant_filas= sizeof($temp);
       $opciones['per_page'] = 9;
       $opciones['base_url'] = base_url().'index.php/administrar/listado_sistema_informatico';
       $opciones['total_rows'] = $cant_filas;
       $opciones['uri_segment'] = 3;
       $this->pagination->initialize($opciones);
        $data['sistema_productor']= "";
        $data['result']=$this->Administrar_modelo->get_todos_sistemas_informaticos($opciones['per_page'],$desde);
        $temp= $this->Administrar_modelo->get_todos_sistemas_informaticos($opciones['per_page'],$desde);
       $sist=$this->Administrar_modelo->get_sistema_productor();
       /* for($i=0; $i<sizeof($temp); $i++)
          {
          for($j=0; $j<sizeof($sist); $j++)
         {
          if($temp[$i]->id_sistema_productor==$sist[$j]->id_sistema_productor)
          {
             $data['sistema_productor']=$sist[$j]->nomb_sistema_productor;
          }
           }
        }*/
      $data['paginacion'] = $this->pagination->create_links();
       if($temp)
       {
        $data['page_title']="Listado de los sistemas informáticos existentes";
        $this->load->view('listado_sistema_informatico',$data);

        }
        else
        {
         echo 'Error! No existen registrados sistemas informaticos en el sistema';
            
        }
    }


 //---------------------------GESTIONAR UNIDADES DE UNIDAD MEDIDA------------------
      //----INSERTAR//----
    function insertar_tipo_trabajo()
	{
      $this->load->model('Administrar_modelo','rmodel');
      $this->form_validation->set_rules('tipo_trabajo', 'La Unidad de Medida', 'required');
       $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');
        if ($this->form_validation->run()==FALSE)
        {           // echo "ERROR:::";
          $this->load->view('crear_tipo_trabajo');
        }
        else
        {
            $datos = (object)array(
                    'tipo_trabajo'=> $this->input->get_post('tipo_trabajo'),
                );
         }
         $trab= $this->rmodel->buscar_tipo_trabajo($datos);
         if($trab)
         {
           $this->rmodel->insertar_tipo_trabajo($datos);
            redirect(site_url('administrar/listado_tipo_trabajo')); //CREAR LA VISTA DE LISTADO DE UNIDAD MEDIDA
         }
         else
         {
           $this->load->view('crear_tipo_trabajo');
           echo 'Error al insertar un tipo de produccion';//PROBAR
         }
       }
         //----ELIMINAR//----
       function eliminar_tipo_trabajo($id)
    {
        $this->load->model('Administrar_modelo','amodel');
        $res = $this->amodel->eliminar_tipo_trabajo($id);
        if( $res )
        {
            redirect(site_url('administrar/listado_tipo_trabajo'));
        }
        else {
            echo 'Error!'.$this->db->last_query();

             }
    }
     //----MODIFICAR//----
    	function editar_tipo_trabajo($id, $editar = TRUE) //METODO MODIFICAR
    {
      $data['id'] = $id;

      $this->load->model('Administrar_modelo','amodel');

      $data['tt'] = $this->amodel->mostrar_tipo_trabajo($id);

      if($editar==FALSE)
      {
           $this->load->view('editar_tipo_trabajo',$data);   //CREAR LA VISTA DE EDITAR LOS USUARIOS
      }
      else
      {
	// $this->form_validation->set_rules('id_unidad_medida', 'Identificador de la Unidad de Medida', 'required');
        $this->form_validation->set_rules('tipo_trabajo', 'Tipo de Trabajo', 'required');
       $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('editar_tipo_trabajo',$data); //CREAR LA VISTA DE EDITAR LOS USUARIOS
        }
        else
        {
            $datos['id_tipo_trabajo'] = $id;
            $datos['tipo_trabajo'] = $this->input->post('tipo_trabajo');
            $result = $this->amodel->modificar_tipo_trabajo($this->input->post('id'), $datos );
            if($result)
            {
                redirect(site_url('administrar/listado_tipo_trabajo'));
            }
            else
            {
                echo 'Error!'.$this->db->last_query();

            }
        }
      }
    }
    function listado_tipo_trabajo()
    {
        $this->load->model('Administrar_modelo');
        $data['result']=$this->Administrar_modelo->get_trabajo();
        $data['page_title']="Listado de los posibles tipos de trabajo";
        $this->load->view('listado_tipo_trabajo',$data);
    }

 //---------------------------GESTIONAR FASES DE TRABAJO POR TIPO DE PRODUCCION------------------
  function definir_fase_por_tipos()
    {
      $fase= array();
      $id_fases=0;
     /* if(!isset($_POST['tema']))
      {
          echo"Debe seleccionar al menos una fase de trabajo.";
      }
      else
      {*/
          $f= $_POST['tema'];
          if($f)
          {
           $this->load->model('Administrar_modelo','amodel');
          
       $trabajo= $this->amodel->obtener_id_tipo_trabajo($this->input->post('tipo_trabajo'));
       $datos = (object)array(
                    'id_trabajo'=> $trabajo->id_tipo_produccion,
                );
        $si_existe= $this->amodel->si_id_trabajo_existe($datos);
        if($si_existe)
        {
         for($i=0; $i<sizeof($f); $i++)
        {
             $id_fases= $this->amodel->obtener_id_fase($f[$i]);
             $fases= (object) array (
              'id_fases'=>$id_fases->id_fase_trabajo,
               );
            
      $this->amodel->insertar_fase_por_tipo_trabajo($fases,$datos); //PROBLEMA DE OBTENER
      // echo $f[$i];
     // 
     // $id_fases=0;
      //$fases=NULL;
        //$this->load->view('listado_fase_por_tipo',$fases);
       }
       redirect(site_url('administrar/listado_fases_por_tipo'));
      }
       else
           {
         echo 'Ya existen fases de trabajo asignadas a el tipo de produccion seleccionado';
         redirect(site_url('administrar/cargar_fases_por_tipo_produccion'));
         
           }
           echo sizeof($f);
      }
     else {
     {  redirect(site_url('administrar/cargar_fases_por_tipo_produccion'));}
      }
    }
      //}
      function eliminar_fase_por_tipo_trabajo($id)
    {
        $this->load->model('Administrar_modelo','amodel');
        $res = $this->amodel->eliminar_fase_por_tipo_trabajo($id);
        if( $res )
        {
            redirect(site_url('administrar/listado_fases_por_tipo'));
        }
        else {
            echo 'Error!'.$this->db->last_query();

             }
    }
    function listado_fases_por_tipo()
    {
        $this->load->model('Administrar_modelo');
        $data['result']=$this->Administrar_modelo->get_fase_por_tipo();
        $data['fase']=$this->Administrar_modelo->get_fase();
        $data['tipo_trabajo']=$this->Administrar_modelo->get_trabajo();
        $data['page_title']="Listado de las fases asignadas a un tipo de Produccion";
        $this->load->view('listado_fase_por_tipo',$data);
    }
    function editar_fase_por_tipo_trabajo($id, $editar = TRUE) //METODO MODIFICAR
    { $fases= array();
      $data['id'] = $id;//identificador del tipo de prod

      $this->load->model('Administrar_modelo','amodel');
      $data['tt'] = $this->amodel->get_trabajo(); // cargo la lista de tipos de trabajo existentes
      $data['fases_por_tipo']=$this->amodel->obtener_fases_por_un_tipo_trabajo($id);
      $data['ft']= $this->amodel->get_fase();
     // $id_pro= 1;
      
       $data['p']=$this->amodel->mostrar_tipo_trabajo($id);

       if($editar==FALSE)
       {
           redirect(site_url('administrar/listado_fases_por_tipo'));
          // $this->load->view('editar_fases_por_tipo',$data,$fases_por_tipo);   //CREAR LA VISTA DE EDITAR LOS USUARIOS
       }  
      else
      {
            $this->load->view('editar_fases_por_tipo',$data); //CREAR LA VISTA DE EDITAR LOS USUARIOS
      
           // $sistema_prod=$$this->rmodel->obtener_id_sistema_productor($this->input->get_post('nomb_sistema'));
            //$tipo_trabajo=$$this->rmodel->obtener_id_tipo_trabajo($this->input->get_post('tipo_trabajo'));
       $trab= $this->amodel->obtener_id_tipo_trabajo($this->input->post('tipo_trabajo'));
       $datos = (object)array(
                    'id_trabajo'=> $trab->id_tipo_produccion,
                );
        $si_existe= $this->amodel->si_id_trabajo_existe($datos);
        if($si_existe)
        {
         for($i=0; $i<sizeof($fases_por_tipo); $i++)
        {
             $id_fases= $this->amodel->obtener_id_fase($fases_por_tipo[$i]);

               $fases= (object) array (
              'id_fases'=>$id_fases->id_fase_trabajo,


         );
              $result = $this->amodel->modificar_fases_por_tipo($id,$fases );
            if( $result ) redirect(site_url('administrar/listado_fases_por_tipo'));
            else echo 'Error!'.$this->db->last_query();
       // }
      }
   
    }
    
    }
    }
//---------------------------GESTIONAR FASES DE TRABAJO POR TIPO DE PRODUCCION------------------
  function definir_medios_productivos_por_tipos()
    {
      $fase= array();
      $id_medio=0;
     
     /* if(!isset($_POST['tema']))
      {
             echo "Debe seleccionar al menos una Tipo de Trabajo.";
      }
      else
      {*/
         $this->load->model('Administrar_modelo','amodel');
          $f= $_POST['tema'];
          if($f)
          {
         $trabajo= $this->amodel->obtener_id_tipo_trabajo($this->input->post('tipo_trabajo'));
        // $sistema= $this->amodel->obtener_id_sistema($this->input->post('tipo_trabajo'));
       $datos = (object)array(
                    'id_trabajo'=> $trabajo->id_tipo_produccion,

                );
        $si_existe= $this->amodel->si_id_trabajo_existe_para_medios($datos);
        if($si_existe)
        {
         for($i=0; $i<sizeof($f); $i++)
        {
             $id_medio= $this->amodel->obtener_id_medio_productivo($f[$i]);
             $medio= (object) array (
              'id_medio'=>$id_medio->id_medio,


         );

      $this->amodel->insertar_medios_productivos_por_tipo($medio,$datos); //PROBLEMA DE OBTENER
       }
        redirect(site_url('administrar/listado_medios_productivos_por_tipos'));
      }
       else
           {
         echo 'Ya existen medios productivos asignadas a el tipo de produccion seleccionado';
         redirect(site_url('administrar/cargar_medios_por_tipo_produccion'));

           }
      
     // }
    //$this->load->view('listado_fase_por_tipo',$fases);
      }
      else
      { redirect(site_url('administrar/cargar_medios_por_tipo_produccion'));}
    }
      function eliminar_medios_productivos_por_tipos($id)
    {
        $this->load->model('Administrar_modelo','amodel');
        $res = $this->amodel->eliminar_medios_productivos_por_tipo($id);
        if( $res )
        {
            redirect(site_url('administrar/listado_medios_productivos_por_tipos'));
        }
        else {
            echo 'Error!'.$this->db->last_query();

             }
    }
    function listado_medios_productivos_por_tipos()
    {
        $this->load->model('Administrar_modelo');
        $data['result']=$this->Administrar_modelo->get_medios_productivos_por_tipo();
        $data['medio']=$this->Administrar_modelo->get_medios_produccion();
        $data['tipo_trabajo']=$this->Administrar_modelo->get_trabajo();
        $data['page_title']="Listado de los medios productivos asignados a un tipo de Produccion";
        $this->load->view('listado_medios_productivos_por_tipo',$data);
    }
    function editar_medios_productivos_por_tipos($id, $editar = TRUE) //METODO MODIFICAR
    { $medio= array();
      $data['id'] = $id;//identificador del tipo de prod

      $this->load->model('Administrar_modelo','amodel');
      $data['tt'] = $this->amodel->get_trabajo(); // cargo la lista de tipos de trabajo existentes
      $data['medios_por_tipo']=$this->amodel->obtener_medios_productivos_por_tipo($id);
      $data['m']= $this->amodel->get_medios_produccion();
      $data['material_produccion']=$this->amodel->mostrar_tipo_trabajo($id);
       if($editar==FALSE)
       {
           redirect(site_url('administrar/listado_medios_productivos_por_tipos'));
          // $this->load->view('editar_fases_por_tipo',$data,$fases_por_tipo);   //CREAR LA VISTA DE EDITAR LOS USUARIOS
       }
      else
      {
            $this->load->view('editar_medios_productivos_por_tipo',$data); //CREAR LA VISTA DE EDITAR LOS USUARIOS

           // $sistema_prod=$$this->rmodel->obtener_id_sistema_productor($this->input->get_post('nomb_sistema'));
            //$tipo_trabajo=$$this->rmodel->obtener_id_tipo_trabajo($this->input->get_post('tipo_trabajo'));
       $trab= $this->amodel->obtener_id_tipo_trabajo($this->input->post('tipo_trabajo'));
       $datos = (object)array(
                    'id_trabajo'=> $trab->id_tipo_produccion,
                );
        $si_existe= $this->amodel->si_id_trabajo_existe($datos);
        if($si_existe)
        {
         for($i=0; $i<sizeof($medios_por_tipo); $i++)
        {
             $id_medio_prod= $this->amodel->obtener_id_medio_productivo($medios_por_tipo[$i]);

               $medio= (object) array (
              'id_medio'=>$id_medio_prod->id_medio,


         );
              $result = $this->amodel->modificar_medios_productivos_por_tipo($id,$medio );
            if( $result ) redirect(site_url('administrar/listado_medios_productivos_por_tipos'));
            else echo 'Error!'.$this->db->last_query();
       // }
      }

    }
    }
    }
     function listado_traza()
    {
        $this->load->model('Administrar_modelo');
        $this->load->model('Traza_modelo');
        $data['result']=$this->Traza_modelo->get_traza();		
		
		$data['user']=$this->Administrar_modelo->get_user();
		
        $data['page_title']="Listado de las trazas almacenadas en el sistema";		
        $this->load->view('listado_traza',$data);
    }
     function mostrar_traza($id) //METODO MODIFICAR
    { 
      $medio= array();
      $data['id'] = $id;//identificador del tipo de prod

      $this->load->model('Administrar_modelo','amodel');
        $this->load->model('Traza_modelo','tmodel');
        $nomb="";
      
           $traza= $this->tmodel->mostrar_trazas($id);
            $result = $this->amodel->get_user();
             for($i=0; $i<sizeof($result); $i++)
               {
                if(($traza->id_user) == ($result[$i]->id_user))
                {
                    $nomb=$result[$i]->nomb_user;
				
                    $data['usuario']=$nomb;
					
                    $data['traza']=$traza;
                    break;
                }
               }
              
             $this->load->view('detalles_traza',$data); //
         }
    
}