<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class plan_insumos extends CI_Controller {
    public  $data= array();
                     
          function __construct()
          {
             parent::__construct();
	   $this->load->library('excel');
            $this->load->model('Produccion_modelo','rmodel');
           $this->load->model('Administrar_modelo','amodel');

          }
          public function index()
           {
              $this->data=NULL;
           }
  
    
	//--------------PLAN DE INSUMOS----------------------
        function imp_excel()
        { 
        $this->load->library('excel');
        $this->load->model('Produccion_modelo','rmodel');
         $this->load->model('Administrar_modelo','amodel');
    	 $obj_excel = PHPExcel_IOFactory::load("C:/wamp/www/SGCNTC/files/Plan_Produccion_real93.xls");
       	$sheetData = $obj_excel->getActiveSheet()->toArray(null,true,true,true);
        $sistemas= (object)array();
        $id_sist=(object) array();
        $norma_consumo=(object) array();
        $cant=0;
       	$arr_datos = array(
                     'cod'  => 0,
                    'sistema_informatico'  =>  0,
                    'series' =>  0,
                    'puntos'  => 0,
        );

        $medio= array();
        $j=0;
          $obt_normas_c= array();
          $data= array();
          $i=0;
          $v=0;
       	foreach ($sheetData as $index => $value) {//INDEX en 0 para poder acceder al medio de produccion
            if ( $index != 1 ){
                    $arr_datos = array(
                    'cod'  => $value['A'],
                    'sistema_productor' => $value['B'],
                    'sistema_informatico'  =>  $value['C'],
                    'series' =>  $value['D'],
                    'puntos'  =>  $value['E'],
                    'observ'  =>  $value['F'],//Puede ser de 2 tipos Sostenimiento o Nuevo(buscar todas las normas asociadas a dicho SIE)
                );
                    // $sistemas=$arr_datos['sistema_informatico'];
                     //$sistema_productor=$arr_datos['sistema_productor'];
                    // $observ= $arr_datos['observ'];
                     $puntos=$arr_datos['puntos'];
                     $series=$arr_datos['series'];
                     $this->data['nombre_sie']=  $arr_datos['sistema_informatico'];
                     $this->data['nombre_sp']= $arr_datos['sistema_productor'];
                     $this->data['punto']= $arr_datos['puntos'];
                     $this->data['serie']= $arr_datos['series'];
                     $this->data['medios_producc']= $this->amodel->get_medios_produccion();
                     $id_sist=$this->amodel->id_sistema_informatico($arr_datos['sistema_informatico']);
                     if($id_sist)
                     {
                      $norma_consumo=$this->rmodel->buscar_norma_consumo($id_sist->id_sistema_informatico,$arr_datos['observ']);
                      if($norma_consumo)
                            {
                        $this->data['result']=$this->rmodel->mostrar_consumo($norma_consumo->id_norma_consumo);
                        for($i=0; $i<sizeof($this->data['result']); $i++)
                        {
                         $medio=$this->amodel->mostrar_medios_produccion($this->data['result'][$i]->id_medio);
                         $this->data['medio'][$i]=$medio;
                         $this->data['count']=$j;
                          $temp=$medio->nomb_medio;
                          if($puntos=="")
                          {
                             $puntos=0;
                          }
                         $this->data['consumo'][$i]= ($puntos)* ($series) * ($this->data['result'][$i]->consumo_mínimo);

                         }}
                     else
                          {
                         echo "No esta normado el sistema informatico   </br>";
                         break;
                          }
                     }
                   else {
                         echo "No se encuentra registrado en el sistema el sistema informatico </br>";
                         break;
                         }
           $i++;
	           }
                   $j++;
            if($i>0)
          $this->load->view('pdf/pdf_plan_insumos',$this->data);
         }
       $this->generar_indicacion_produccion($this->load->view('pdf/pdf_plan_insumos',$this->load->view('pdf/pdf_plan_insumos',$this->data)));
         }
        function exportar_excel()
        {
           $this->load->model('Produccion_modelo','rmodel');
          $this->load->model('Administrar_modelo','amodel');
          $query= $this->rmodel->prueba();
          $this->load->library('PHPExcel');

        $objPHPExcel= new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
        $fields= $query->list_fields();
        $col= 0;
        $mp=$this->amodel->get_medios_produccion();

         //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:D1');
         //for($i=0; $i<sizeof($v); $i++)
         $i=0;
        foreach($fields as $field)//CREA LAS COLUMNAS
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $field);
            $col++;
            $i++;
        }
        // Fetching the table data
        $row= 2;
        for($i=0; $i<sizeof($medio); $i++)
      //  foreach($medio as$data) //RECORRE LAS FILAS
        {
            $col= 0;
          //  foreach($fields as $field)
           // {
            echo $medio[$i];
              //  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $medio[$i]);
             //   $col++;
           // }
            $row++;
        }
       // $objPHPExcel->setActiveSheetIndex(0);
       // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        // Sending headers to force the user to download the file
      //  header('Content-Type: application/vnd.ms-excel');
      //  header('Content-Disposition: attachment;filename="Plan de Insumos'.date('dMy').'.xls"');
      //  header('Cache-Control: max-age=0');
      //  $objWriter->save('php://output');
        }
 public function upload_file(){
         //Config the parameters to upload the file to the server.
        //Configuramos los parametros para subir el archivo al servidor.
          $config['upload_path'] = realpath(APPPATH.'../files');
          $config['allowed_types'] = 'xls';
          $config['max_size']     = '0';
          $this->load->helper('file');
          //Load the Upload CI library
          //Cargamos la libreria CI para Subir
          // $this->load->library('upload', $config);
         //  $this->load->library('upload');
            $this->load->library('upload', $config);
           if ( ! $this->upload->do_upload('userfile') ){
              //Displaying Errors.
               //Mostramos los errores.
              //print_r($this->upload-display_errors());
             $error = array('error' => $this->upload->display_errors());

               redirect ('produccion/cargar_reportes');
            //echo "El documento excel, no se encuentra correctamente confeccionado";
              }
              else{
               //Uploads the excel file and read it with the PHPExcel Library.
              //Subimos el archivo de excel y lo leemos con la libreria PHPExcel.
              $data = array('upload_data' => $this->upload->data());//CREO el arreglo

              $this->load->library('PHPExcel');
              $this->load->library('excel');
             // this->load->library('PHPExcel');


             // $excel = $this->excel->read_file($data['upload_data']['file_name']);
               $excel = $this->excel->read_file($data['upload_data']['file_name']);
             // $plan= array ();
               //$this->excel->sheet
               $data['excel'] = $excel;
             //$value=  $this->PHPExcel->getCellByColumnAndRow(1,1)->getValue();
                     }

        if (!$excel )
        {
            echo "El documento excel, no se encuentra correctamente confeccionado";
            
        }
 else
       {
       $this->load->view('cargar_excel',$data);

      
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
function generar_indicacion_produccion()//Crear PDFs
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
        $this->html2pdf->paper('a3','landscape');

        $this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_plan_insumos', $this->data, true)));
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