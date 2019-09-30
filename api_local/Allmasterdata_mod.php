<?php
session_start();
header('Content-type: application/json');
require_once( "../dashboard.php" );
require_once( "../query.php" );
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
			   echo json_encode(array('Success'=>'TRUE','Message'=>'Login Fail'));
			   exit;
			}else {
				//echo json_encode(array('Success'=>'true','Message'=>'Login Success'));
			}
		}

    }

    public function getAllmasterData($data)
	{
		$dsbObj = new Dashboard();
		$qry = new Query();
		$res=array();

		$oldFilter=array(':PUSER_CODE');
	    $newFilter=array($_SESSION['USER']);


		$filepath = 'ini/harvesting_slip.ini';
        $queries = parse_ini_file($filepath, true);


        //FOR JSON TO ARRAY
		$data =json_decode($data, true);		
        
        $newSectonRes = array();
        foreach ($data['AllMasters'][0] as $key => $value) 
        {
			$last_key = $key;
			if($value==''){
				$sync_date = '01/01/0001';
			}else{
				$sync_date =explode(" ", $value); //'01/01/0001';//$value;
			}
			$oldFilter = array(':PSYNC_DATE');
			$newFilter = array($sync_date[0]);
			$newSectonQry = $qry->fetchQuery($filepath,'Q002',$key,$oldFilter,$newFilter);
			print_r($newSectonQry)."<br>";
			$newSectonRes = $dsbObj->getData($newSectonQry);
			$result[$key]['NEW']=$newSectonRes;
		}

		$result['Success']="true";
	    /*FOR SECMAST MAST*/
	    echo json_encode($result);
        die;      		
	}
}


$obj = new AllMasterData();
$data=$_REQUEST['data'];
$obj ->getAllmasterData($data);

?>
