<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class produccion extends CI_Controller {
  
          function __construct()
          {
             parent::__construct();
	  }
   public function index()
          {        
               // $this->data= array();
       		$this->load->view('Reportes');
	 }
         function cargar_reportes()
        {
           $this->load->view('buscar_plan_produccion');
        }
         public function cargar_norma_tiempo()
         {
              $this->load->model('Administrar_modelo','rmodel');
                $nt['tipo']=$this->rmodel->get_trabajo();
              $nt['medio']= $this->rmodel->get_medios_produccion();
                 $nt['sp']=$this->rmodel->get_sistema_productor();
              $nt['tiempo']= $this->rmodel->get_unidad_tiempo();
              $nt['sistema']= $this->rmodel->get_sistema_informatico();
              $nt['value']=  true;
              //$data['id']=$this->user->id_rol;
               $this->load->view('crear_norma_tiempo', $nt);

        }
             public function cargar_norma_consumo()
        {
            $this->load->model('Administrar_modelo','rmodel');
              $nc['tipo']=$this->rmodel->get_trabajo();
              $nc['sp']=$this->rmodel->get_sistema_productor();
             // $data['medio']= $this->rmodel->get_medios_produccion();
              //$data['medida']= $this->rmodel->get_unidad_medida();
              $nc['sistema']= $this->rmodel->get_sistema_informatico();
              $nc['value']=  true;
               $this->load->view('crear_norma_consumo',$nc);

        }
       function actualizar_norma_tiempo()
    {  
        // $medio_produccion=array();
       //  $medio_prod=  array();
        $data['value']=  false;
        $data['error']= $this->input->get_post('tipo_trabajo');
        $this->load->model('Administrar_modelo','amodel');
        $data['t']=$this->input->get_post('tipo_trabajo');
        $data['sistemas']= $this->input->get_post('sistema_informatico');
        $data['tiempos']=$this->input->get_post('tiempo');
        $data['medios']=$this->input->post('medio_produccion');
        $data['descripcion']=$this->input->get_post('descripcion');
        $data['fecha']=$this->input->get_post('fecha');
      $trabajo= $this->amodel->obtener_sistema_productor($this->input->get_post('tipo_trabajo'));
       //$temp=$this->amodel->buscar_sistema_productor($trabajo->id_sistema_productor);
      // $s=$this->input->get_post('sistema_informatico');
       $si=$this->amodel->sistema_informatico($this->input->get_post('sistema_informatico'));
       if($trabajo)
       {
       $data['fases_por_tipo']=$this->amodel->obtener_fases_por_un_tipo_trabajo($si->id_tipo_produccion);
       $data['ft']=  $this->amodel->get_fase();
          $this->form_validation->set_rules('fecha', 'La Fecha de Registro', 'required');
       $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');
        if ($this->form_validation->run() == FALSE)
        {
                 redirect(site_url('produccion/cargar_norma_tiempo'));
        }
        else
        {
      $this->load->view('actualizar_norma_tiempo',$data);
      //redirect(site_url('produccion/cargar_norma_tiempo'));
      }
       }
       else
       {
                  echo "No existen medios asociados al tipo de producci'on seleccionado, por lo que no es posible normar consumo del sistema informatico seleccionado.";
                     redirect(site_url('produccion/cargar_norma_tiempo'));
                  }
    }
    function insertar_normar_tiempo()
    {
       // $fase= array();
         $tiempo_minim=array();
         $tiempo_minim= $_POST['cant_tiempo'];
         $f=$_POST['tema'];
      if($tiempo_minim==NULL)
      {
            echo "Debe seleccionar al menos una fase de trabajo y asignarle el tiempo m'inimo que le corresponde";
             redirect(site_url('produccion/cargar_norma_tiempo'));
      }
      else
      {
       $this->load->model('Administrar_modelo','amodel');
        $this->load->model('Produccion_modelo','nmodel');
       $trabajo= $this->amodel->obtener_id_tipo_trabajo($this->input->post('tipo_trabajo'));
       $sistema= $this->amodel->obtener_id_sistema_informatico($this->input->post('sistema_informatico'));
       $tiempo= $this->amodel->obtener_id_unidad_tiempo($this->input->post('tiempo'));
       $medio= $this->amodel->obtener_id_medio_produccion($this->input->post('medio'));
       $descripcion= $this->input->post('descripcion');
       $fecha= $this->input->post('fecha');
       
       $datos = (object)array(
                    'id_trabajo'=> $trabajo->id_tipo_produccion,
                    'id_sistema'=> $sistema->id_sistema_informatico,
                    'id_tiempo'=> $tiempo->id_unidad_tiempo,
                    'id_medio'=> $medio->id_medio,
                    'descripcion'=>$descripcion,
                    'fecha'=>$fecha,
                );
        // $si_existe= $this->amodel->si_id_trabajo_existe($datos);
        $leng=sizeof($tiempo_minim);
      //  echo $leng;
        $count=0;
       for($i=0; $i<sizeof($tiempo_minim); $i++)
       {
         if($tiempo_minim[$i]!="")
         {
             $count++;
         }
       }   
       if($count>0)
          {
          $this->nmodel->insertar_norma_tiempo($datos);
          $nt=$this->nmodel->obtener_norma_tiempo($datos);
          if($nt)
          {          
          $id_norma= $nt->id_norma_tiempo;
            for($i=0; $i<sizeof($f); $i++)
            { //echo $fases[$i];
              if($tiempo_minim[$i])
              {
             $id_fases= $this->amodel->obtener_id_fase($f[$i]);
             $fases= (object) array (
              'id_fases'=>$id_fases->id_fase_trabajo,
              'tiempo_min'=>$tiempo_minim[$i],
      
              );

             $this->nmodel->insertar_tiempos_normas($fases,$id_norma); //PROBLEMA DE OBTENER
             }
            }
          redirect(site_url('produccion/listado_normas_tiempo'));
          }
            }
          else
          {
               echo 'Hay que asignarle un tiempo a cada fase';
               redirect(site_url('produccion/cargar_norma_tiempo'));
           }
        
     }
      
        //   redirect(site_url('produccion/listado_normas_tiempo'));
        }//}

      function eliminar_norma_tiempo($id)
    {
        $this->load->model('Produccion_modelo','pmodel');
        $res = $this->pmodel->eliminar_norma_tiempo($id);
        if( $res )
        {
            redirect(site_url('produccion/listado_normas_tiempo'));
        }
        else {
            echo 'Error!'.$this->db->last_query();
             }
    }
    function listado_normas_tiempo()
    {
        $this->load->library('pagination');
       $opciones = array();
       $desde = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
       $this->load->model('Produccion_modelo','pmodel');
       $this->load->model('Administrar_modelo','amodel');
       $temp=$this->pmodel->get_normas_tiempo();
        $cant_filas= sizeof($temp);
       $opciones['per_page'] = 10;
       $opciones['base_url'] = base_url().'index.php/produccion/listado_normas_tiempo';
       $opciones['total_rows'] = $cant_filas;
       $opciones['uri_segment'] = 3;

    $this->pagination->initialize($opciones);

     $data['normas']= $this->pmodel->get_todas_normas_tiempo($opciones['per_page'],$desde);
    $data['paginacion'] = $this->pagination->create_links();
     
           // $data['normas']=$this->pmodel->get_normas_tiempo();//COMPARAR
            
            if(!$temp)
            {
            echo "No existen normas registradas en el sistema.";
            // redirect(site_url('produccion/listado_normas_tiempo'));
            }
            else
            {
            $data['t']=$this->pmodel->get_tiempo();//COMPARAR
            $data['ut']=$this->amodel->get_unidad_tiempo();
            $data['mp']=$this->amodel-> get_medios_produccion();
            $data['s']=$this->amodel->get_sistema_informatico();
            $this->load->view('listado_normas_tiempo',$data);
             }
        }
    function editar_norma_tiempo($id, $editar = TRUE) //METODO MODIFICAR PENDIENTE
    { $norma= array();
      $data['id'] = $id;//identificador del tipo de prod
      $this->load->model('Administrar_modelo','amodel');
      $this->load->model('Produccion_modelo','pmodel');
      $data['tiempo'] = $this->amodel->get_unidad_tiempo(); // cargo la lista de tipos de trabajo existentes
      $data['nt']=$this->pmodel->mostrar_norma_tiempo($id);
      $data['t']=$this->pmodel->get_tiempo();
      $data['medios']= $this->amodel->get_medios_produccion();
      $data['sistema']= $this->amodel->get_sistema_informatico();
      $data['tt']= $this->amodel->get_trabajo();
      $data['fases']=$this->amodel->get_fase();
       if($editar==FALSE)
       {
           redirect(site_url('produccion/listado_normas_tiempo'));
       }
      else
      {
    $this->load->view('editar_norma_tiempo',$data); //CREAR LA VISTA DE EDITAR LOS USUARIO
     
      $tiempo_por_normas=$this->pmodel->mostrar_tiempo($id);
      $tipo_prod=  $this->input->post('tipo_trabajo');
      $id_tt= $this->amodel->obtener_id_tipo_trabajo($tipo_prod);
      $fases_por_trab=$this->amodel->obtener_fases_por_un_tipo_trabajo($id_tt->id_tipo_produccion);
      $f=$_POST['tema'];
//COMO GARANTIZO QUE LOS IMPUT NO ESTEN VACiOS
    // $f= array();
      for($i=0; $i<sizeof($fases_por_trab); $i++)
          {
             
        //   $fase= $this->input->post('tema');
      //  $this->form_validation->set_rules('tema', 'La Fecha de Registro', 'required');
      ////  $this->form_validation->set_rules('cant_tiempo', 'El tiempo minimo', 'required');
       //  $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');
     //   if ($this->form_validation->run() == FALSE)
      //  {
     //            redirect(site_url("produccion/editar_norma_tiempo/$id/"));
     //   }
       // else
        //{
           //$min= $this->input->post('cant_tiempo');

              //  $f= (object)array (
               //'nomb_fase'=>$fase,
               //'tiempo'=>$fase,

      //  );
         }
                 }
                // $data['result']=$f;

               // $this->load->view('editar_norma_tiempo',$data);
                 }
 function actualizar_norma_consumo()
    {
        // $medio_produccion=array();
       //  $medio_prod=  array();
        $data['value']=  false;
        $data['error']= $this->input->get_post('tipo_trabajo');
        $this->load->model('Administrar_modelo','amodel');
        $data['t']=$this->input->get_post('tipo_trabajo');
        $data['sistemas']= $this->input->get_post('sistema_informatico');
        $data['descripcion']=$this->input->get_post('descripcion');
        $data['fecha']=$this->input->get_post('fecha');
        $trabajo= $this->amodel->obtener_sistema_productor($this->input->get_post('tipo_trabajo'));
       //$temp=$this->amodel->buscar_sistema_productor($trabajo->id_sistema_productor);
      // $s=$this->input->get_post('sistema_informatico');
       $si=$this->amodel->sistema_informatico($this->input->get_post('sistema_informatico'));
       if($trabajo)
       {
        $data['medio_por_tipo']=$this->amodel->obtener_medios_productivos_por_tipo($si->id_tipo_produccion);
       $data['mp']=  $this->amodel->get_medios_produccion();
       $data['um']=  $this->amodel->get_unidad_medida();
          $this->form_validation->set_rules('fecha', 'La Fecha de Registro', 'required');
       $this->form_validation->set_message('required', '<span>El campo %s es obligatorio. </span>');
        if ($this->form_validation->run() == FALSE)
        {
                 redirect(site_url('produccion/cargar_norma_consumo'));
        }
        else
        {
      $this->load->view('actualizar_norma_consumo',$data);
      //redirect(site_url('produccion/cargar_norma_tiempo'));
      }
    }
 else {
        echo "No existen medios asociados al tipo de producci'on seleccionado, por lo que no es posible normar consumo del sistema informatico seleccionado.";
     redirect(site_url('produccion/cargar_norma_consumo'));
        }
    }

 function insertar_normar_consumo()
    {
         //$tiempo_minim=array();
         $consumo_minim= $_POST['cant_consumo'];
         $medio=$_POST['tema'];
          $um=$_POST['unidad_medida'];
      if(!isset($_POST['tema']) && (!isset($_POST['cant_consumo']) ))
      {
            echo "Debe seleccionar al menos un medio de produccion  y asignarle el consumo m'inimo que le corresponde";

             redirect(site_url('produccion/cargar_norma_consumo'));
      }
      else
      {
       $this->load->model('Administrar_modelo','amodel');
        $this->load->model('Produccion_modelo','nmodel');
       $trabajo= $this->amodel->obtener_id_tipo_trabajo($this->input->post('tipo_trabajo'));
       $sistema= $this->amodel->obtener_id_sistema_informatico($this->input->post('sistema_informatico'));
       $tiempo= $this->amodel->obtener_id_unidad_tiempo($this->input->post('tiempo'));
      // $medio= $this->amodel->obtener_id_medio_produccion($this->input->post('medio'));
       $descripcion= $this->input->post('descripcion');
       $fecha= $this->input->post('fecha');

       $datos = (object)array(
                    'id_trabajo'=> $trabajo->id_tipo_produccion,
                    'id_sistema'=> $sistema->id_sistema_informatico,
                    'descripcion'=>$descripcion,
                    'fecha'=>$fecha,
                );
        // $si_existe= $this->amodel->si_id_trabajo_existe($datos);
       $count=0;
      // $leng=sizeof($consumo_minim,NULL);
        for($i=0; $i<sizeof($consumo_minim); $i++)
       {
         if($consumo_minim[$i]!="")
         {
             $count++;
         }
       }
       if($count>0)
          {
          $this->nmodel->insertar_norma_consumo($datos);
          $nc=$this->nmodel->obtener_norma_consumo($datos);
          if($nc)
          {

          $id_norma= $nc->id_norma_consumo;
            for($i=0; $i<sizeof($medio); $i++)
            {// echo $medio[$i];
              if($consumo_minim[$i])
              {
             $id_medio= $this->amodel->obtener_id_medio_productivo($medio[$i]);
             $id_unidad_medida= $this->amodel->obtener_id_unidad_medida($um[$i]);
             $consumos= (object) array (
              'id_medio'=>$id_medio->id_medio,
                 'consumo_min'=>$consumo_minim[$i],
                 'id_unidad_medida'=>$id_unidad_medida->id_unidad_medida,

              );

             $this->nmodel->insertar_consumo_normas($consumos,$id_norma); //PROBLEMA DE OBTENER

             }
             }
            redirect(site_url('produccion/listado_normas_consumo'));
          }
          else {echo 'No se pudo insertar la norma de consumo';}
             redirect(site_url('produccion/cargar_norma_consumo'));
            }
          else
          {
               echo 'Hay que asignarle un consumo minimo a cada medio de produccion';
                  redirect(site_url('produccion/cargar_norma_consumo'));
               //redirect(site_url('produccion/cargar_norma_consumo'));
           }

     }

           //redirect(site_url('produccion/listado_normas_consumo'));
        }

      function eliminar_norma_consumo($id)
    {
        $this->load->model('Produccion_modelo','pmodel');
        $res = $this->pmodel->eliminar_norma_consumo($id);
        if( !$res )
        {
         echo 'Error!'.$this->db->last_query();
            //$this->pmodel->eliminar_tiempo($id);
          //  redirect(site_url('produccion/listado_normas_tiempo'));
        }
       else {
           redirect(site_url('produccion/listado_normas_consumo'));
        //    echo 'Error!'.$this->db->last_query();

           }
    }
     function listado_normas_consumo()
    { $this->load->library('pagination');
    $this->load->model('Produccion_modelo','pmodel');
    $this->load->model('Administrar_modelo','amodel');
    $opciones = array();
    $temp=$this->pmodel->get_normas_consumo();
    $cant_filas= sizeof($temp);
    $desde = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
    $opciones['per_page'] = 10;
    $opciones['base_url'] = base_url().'index.php/produccion/listado_normas_consumo';
    $opciones['total_rows'] = $cant_filas;
    $opciones['uri_segment'] = 3;

    $this->pagination->initialize($opciones);
//$this->pagination->create_links();

    $data['normas'] = $this->pmodel->get_todas_normas_consumo($opciones['per_page'],$desde);
    $data['paginacion'] = $this->pagination->create_links();
    
           // $data['normas']=$this->pmodel->get_normas_consumo();//COMPARAR
            $temp=$this->pmodel->get_normas_consumo();
            if(!$temp)
            {
            echo "No existen normas registradas en el sistema.";
            // redirect(site_url('produccion/listado_normas_consumo'));
            }
            else
            {
            //$data['t']=$this->pmodel->get_tiempo();//COMPARAR
           // $data['ut']=$this->amodel->get_unidad_tiempo();
           // $data['mp']=$this->amodel-> get_medios_produccion();
             $data['s']=$this->amodel->get_sistema_informatico();
           
        $this->load->view('listado_normas_consumo',$data);
              }
        }
function generar_plan_insumos()
{
   // $result= $_POST['row'];
    
    $this->load->model('Produccion_modelo','pmodel');
    $this->load->model('Administrar_modelo','amodel');
    foreach($result as $row)
    {

        foreach($row as $col)
        {
            echo $col;
        }
    }
}

     function generar_indicacion_produccion($value)//Crear PDFs
       {
    //cargamos la libreria html2pdf
        $this->load->library('html2pdf');
        //cargamos el modelo pdf_model
        $this->load->model('administrar_modelo');
        if(!is_dir("./files"))
        {
            mkdir("./files", 0777);
            mkdir("./files/pdfs", 0777);
        }
           //importante el slash del final o no funcionará correctamente
        $this->html2pdf->folder('./files/pdfs/');

        //establecemos el nombre del archivo
        $this->html2pdf->filename('Plan_Insumos.pdf');

        //establecemos el tipo de papel
        $this->html2pdf->paper('a3', 'landscape');

        //datos que queremos enviar a la vista, lo mismo de siempre
       /* $data = array(
            'title' => 'Listado de las provincias españolas en pdf',
            'result' => $this->administrar_modelo->get_user()
        );*/

        //hacemos que coja la vista como datos a imprimir
        //importante utf8_decode para mostrar bien las tildes, ñ y demás
        // for($i=0; $i<sizeof($value); $i++)
        $value=  $this->data;
        $this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_plan_insumos',$value, true)));

        //si el pdf se guarda correctamente lo mostramos en pantalla
        if($this->html2pdf->create('save'))
        {
            $this->show();
        }


}
//esta función muestra el pdf en el navegador siempre que existan
    //tanto la carpeta como el archivo pdf
    public function show()
    {
        if(is_dir("./files/pdfs"))
        {
            $filename = "Plan_Insumos.pdf";
            $route = base_url("files/pdfs/Plan_Insumos.pdf");
            if(file_exists("./files/pdfs/".$filename))
            {
                header('Content-type: application/pdf');
                readfile($route);
            }
        }
    }























}