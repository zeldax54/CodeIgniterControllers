<?php

class trazas extends CI_Controller{
     
     
     public function __construct() {
         parent::__construct();
         
     }
     
    public function InspectInsert($usuario,$funcion){
        $this->load->model('Trazas_modelo','tmodel');
       $datestring = "%Y-%m-%d %h:%m:%s";
       $time = time();
       $fecha=mdate($datestring, $time);
       $operacion='INSERT' ;
         $traza= array (
            'fecha'=>$fecha,
           'operacion'=>$operacion,
           'descripcion'=>$funcion,
           'usuario'=>$usuario,
       );
       $this->tmodel->insertar_traza($traza);
      // $traza=array('idusuario'=>$usuario,'operacion'=>$operacion,'registro'=>$registro,'tabla'=>$tabla,'fecha'=>$fecha);
       //$this->db->insert('ttraza',$traza);
    } 
    public function InspectDelete($usuario,$tabla,$registro){
       $datestring = "%Y-%m-%d %h:%m:%s";
       $time = time();
       $fecha=mdate($datestring, $time);
       $operacion='DELETE' ;
       $traza=array('idusuario'=>$usuario,'operacion'=>$operacion,'registro'=>$registro,'tabla'=>$tabla,'fecha'=>$fecha);
       $this->db->insert('ttraza',$traza);
 } 
    public function InspectUpdate($usuario,$tabla,$registro){
        
       $datestring = "%Y-%m-%d %h:%m:%s";
       $time = time();
       $fecha=mdate($datestring, $time);
       $operacion='UPDATE' ;
       $traza=array('idusuario'=>$usuario,'operacion'=>$operacion,'registro'=>$registro,'tabla'=>$tabla,'fecha'=>$fecha);
       $this->db->insert('ttraza',$traza);
 } 
     
}
?>
