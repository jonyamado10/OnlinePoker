<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nusoapserver extends CI_Controller {

 function __construct()
 {
   parent::__construct();
   $ns = base_url();
   $this->load->library("Nusoap_library");
   $this->load->Model("Service");

   $this->server = new soap_server(); // it is a soap server object
   $this->server->configureWSDL("SOAP", $ns); // configure wsdl
   $this->server->wsdl->schemaTargetNamespace = $ns;
 }
  public function index()
 {
   $ns = base_url();
      $input_array = array ('id' => 'xsd:integer','username' => 'xsd:string'); // method parameters
   $return_array = array ('return'=> 'xsd:string');
   $this->server->register('Service.info_partida', $input_array, $return_array, "urn:SOAPServerWSDL", "urn:".$ns."/info_partida", "rpc", "encoded", "Game info");
   $this->server->register('Service.aposta_partida',array ('id' => 'xsd:integer','username' => 'xsd:string','password' => 'xsd:string','jogada' => 'xsd:string','valor' => 'xsd:integer'), $return_array, "urn:SOAPServerWSDL", "urn:".$ns."/info_partida", "rpc", "encoded", "Game info");
   $this->server->register('Service.login',array ('username' => 'xsd:string','password' => 'xsd:string'), array ('return'=> 'xsd:integer'), "urn:SOAPServerWSDL", "urn:".$ns."/info_partida", "rpc", "encoded", "login");
 
   $this->server->service(file_get_contents("php://input"));



   


 }

  public function client(){
  	$this->load->library('table');
    $this->client = new nusoap_client("http://appserver-01.alunos.di.fc.ul.pt/~asw014/index.php/Nusoapserver?wsdl");
    $this->load->view("client");
 }



}