<?php

session_start();
header('Content-type: application/json');
require_once( "../dashboard.php" );
class AllMasterData {
	 
   private $lgs,$qryObj,$dsbObj,$rfObj,$xmlObj,$conn,$stid,$prefetch;
   function __construct() {	
        $filepath = '../util/readquery/general/DBINFO.ini';
        $ini_array = parse_ini_file($filepath, true);
        $dbid='MAIN';
        $host = $ini_array[$dbid]['HOSTIP'];
		$service_name = $ini_array[$dbid]['SERVICENAME'];
		$compCd = $ini_array[$dbid]['COMPCODE'];
		$server = $ini_array[$dbid]['SERVER'];

		define('DATABASE',"(DESCRIPTION =
					(ADDRESS_LIST =
						(ADDRESS = 
							(PROTOCOL = TCP)(HOST = ".$host.")(PORT = 1521))
			   			)
					(CONNECT_DATA =	(SERVER = DEDICATED)
					(SERVICE_NAME = ".$service_name.")
				)
				
	  	  )");
        $_SESSION['USER'] =strtoupper($_REQUEST['username']);
        $password =strtoupper($_REQUEST['password']);
        $_SESSION['IDTTY'] =$password.strrev($password);
        
        if(isset($_SESSION['USER']) && isset($_SESSION['IDTTY'])){
       
        $this->conn = @oci_pconnect($_SESSION['USER'], $_SESSION['IDTTY'], DATABASE, AL32UTF8);
			if (!$this->conn) {
			   $m = oci_error();
			   //echo $m['message'];
			   echo json_encode(array('Success'=>'false','Message'=>'Login Fail'));
			   exit;
			}else {
				//echo json_encode(array('Success'=>'true','Message'=>'Login Success'));
			}
		}

    }

    public function getAllmasterData()
	{
		$dsbObj = new Dashboard();
		$res=array();

		$filepath = 'ini/harvesting_slip.ini';
        $queries = parse_ini_file($filepath, true);
        $getcontractRes = $dsbObj->getData($queries['SECTION_QRY']);
        if(sizeof($getcontractRes) > 0 ){
	      $result['Success']="true";
	      $result['SECMAST']['NEW']=$getcontractRes;
	      $delsctionRes =array();
	      $result['SECMAST']['DELETE']=$delsctionRes;
	         echo json_encode($result);die;
            }else{
              echo json_encode(array('Success'=>'true','Message'=>'Details not available.'));
           }

	     
	}

}

$obj = new AllMasterData();
$obj ->getAllmasterData();
?>