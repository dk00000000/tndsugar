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

    public function getAllmasterData()
	{
		$dsbObj = new Dashboard();
		$qry = new Query();
		$res=array();

		$oldFilter=array(':PUSER_CODE');
	    $newFilter=array($_SESSION['USER']);


		$filepath = 'ini/harvesting_slip.ini';
        $queries = parse_ini_file($filepath, true);
        /*FOR SECMAST MAST*/
        $newSectonRes = $dsbObj->getData($queries['SECTION_QRY']);
        $delSectonRes = $dsbObj->getData($queries['DELSECTION_QRY']);
       // $delSectonRes =array();
        //$delSectonRes = array('G24');

        /*FOR VLGMAST MAST*/
        $newVlgRes = $dsbObj->getData($queries['VLGMAST_QRY']);
        $delVlgRes =array();

        /*FOR SVRMAST MAST*/
        $newSvrRes = $dsbObj->getData($queries['SVRMAST_QRY']);
        $delSvrRes =array();

        /*FOR CANETMAST MAST*/
        $newCntRes = $dsbObj->getData($queries['CANETMAST_QRY']);
        $delCntRes =array();

        /*FOR CANEDTMAST MAST*/
        $newCndRes = $dsbObj->getData($queries['CANEDTMAST_QRY']);
        $delCndRes =array();

        /*FOR CANEVMAST MAST*/
        $newCnvRes = $dsbObj->getData($queries['CANEVMAST_QRY']);
        $delCnvRes =array();

        /*FOR VLGMAST MAST*/
        $newIrsRes = $dsbObj->getData($queries['IRRSRCMAST_QRY']);
        $delIrsRes =array();

        /*FOR IRRMTDMAST MAST*/
        $newIrmRes = $dsbObj->getData($queries['IRRMTDMAST_QRY']);
        $delIrmRes =array();

        /*FOR PLNTMMAST MAST*/
        $newPlntRes = $dsbObj->getData($queries['PLNTMMAST_QRY']);
        $delPlntRes =array();

        /*FOR JOBTMAST MAST*/
        $newJbtRes = $dsbObj->getData($queries['JOBTMAST_QRY']);
        $delJbtRes =array();

        /*FOR PRTMAST MAST*/
        $newPrtRes = $dsbObj->getData($queries['PRTMAST_QRY']);
        $delPrtRes =array();

        /*FOR FIELDREQ  TRANSACTION*/
        $fieldregQry = $qry->fetchQuery($filepath,'Q001','FIELDREQ_QRY',$oldFilter,$newFilter);        
        $newfieldRes = $dsbObj->getData($fieldregQry);
        $delfieldRes =array();
      
        //For SSNMAST_QRY  

        /*FOR PRTMAST MAST*/
        $ssnMastRes = $dsbObj->getData($queries['SSNMAST_QRY']);
        $delMastRes =array();

        //FOR SSEGMAST_QRY
        $ssegMastRes = $dsbObj->getData($queries['SSEGMAST_QRY']);
        $ssegdelMastRes =array();

        //FOR HSPRGMAST_QRY
        $hsprgMastRes = $dsbObj->getData($queries['HSPRGMAST_QRY']);
        $hsprgdelMastRes =array();

        //FOR VHLTMAST_QRY
        $vhltMastRes = $dsbObj->getData($queries['VHLTMAST_QRY']);
        $vhltdelMastRes =array();

        //FOR GADIMAST_QRY
        $gadiMastRes = $dsbObj->getData($queries['GADIMAST_QRY']);
        $gadidelMastRes =array();

        //For V_VEHICLE_JOINT_QRY
        $vehjntMastRes = $dsbObj->getData($queries['V_VEHICLE_JOINT_QRY']);
        $vehjntdelMastRes =array();

        //For CANEQMAST_QRY
        $caneqMastRes = $dsbObj->getData($queries['CANEQMAST_QRY']);
        $caneqdelMastRes =array();

        //For SERIESHS_SSEGMAST_QRY
        $serihsMastRes = $dsbObj->getData($queries['SERIESHS_SSEGMAST_QRY']);
        $serihsdelMastRes =array();

        //For BACK_QRY
        $backMastRes = $dsbObj->getData($queries['BACK_QRY']);
        $backdelMastRes =array();

        //For FACTORY_DATA_QRY
        $factdMastRes = $dsbObj->getData($queries['FACTORY_DATA_QRY']);
        $factddelMastRes =array();
 
	    $result['Success']="true";
	    /*FOR SECMAST MAST*/
	    $result['SECMAST']['NEW']=$newSectonRes;
	    $result['SECMAST']['DELETE']=$delSectonRes;

	    /*FOR VLGMAST MAST*/
	    $result['VLGMAST']['NEW']=$newVlgRes;
	    $result['VLGMAST']['DELETE']=$delVlgRes;

	    /*FOR SVRMAST MAST*/
	    $result['SVRMAST']['NEW']=$newSvrRes;
	    $result['SVRMAST']['DELETE']=$delSvrRes;

	    /*FOR CANETMAST MAST*/
	    $result['CANETMAST']['NEW']=$newCntRes;
	    $result['CANETMAST']['DELETE']=$delCntRes;

	    /*FOR CANEDTMAST MAST*/
	    $result['CANEDTMAST']['NEW']=$newCndRes;
	    $result['CANEDTMAST']['DELETE']=$delCndRes;

	    /*FOR CANEVMAST MAST*/
	    $result['CANEVMAST']['NEW']=$newCnvRes;
	    $result['CANEVMAST']['DELETE']=$delCnvRes;

	    /*FOR IRRSRCMAST MAST*/
	    $result['IRRSRCMAST']['NEW']=$newIrsRes;
	    $result['IRRSRCMAST']['DELETE']=$delIrsRes;

	    /*FOR IRRMTDMAST MAST*/
	    $result['IRRMTDMAST']['NEW']=$newIrmRes;
	    $result['IRRMTDMAST']['DELETE']=$delIrmRes;

	    /*FOR PLNTMMAST MAST*/
	    $result['PLNTMMAST']['NEW']=$newPlntRes;
	    $result['PLNTMMAST']['DELETE']=$delPlntRes;

	    /*FOR JOBTMAST MAST*/
	    $result['JOBTMAST']['NEW']=$newJbtRes;
	    $result['JOBTMAST']['DELETE']=$delJbtRes;

	    /*FOR PRTMAST MAST*/
	    $result['PRTMAST']['NEW']=$newPrtRes;
	    $result['PRTMAST']['DELETE']=$delPrtRes;

	    /*FOR FIELDREG MAST*/
	    $result['FIELDREG']['NEW']=$newfieldRes;
	    $result['FIELDREG']['DELETE']=$delfieldRes;
        
        /*FOR SSNMAST MAST*/
	    $result['SSNMAST']['NEW']=$ssnMastRes;
	    $result['SSNMAST']['DELETE']=$delMastRes;

	    /*FOR SSEGMAST MAST*/
	    $result['SSEGMAST']['NEW']=$ssegMastRes;
	    $result['SSEGMAST']['DELETE']=$ssegdelMastRes;

	    /*FOR HSPRGMAST MAST*/
	    $result['HSPRGMAST']['NEW']=$hsprgMastRes;
	    $result['HSPRGMAST']['DELETE']=$hsprgdelMastRes;

	    /*FOR VHLTMAST_QRY*/
	    $result['VHLTMAST']['NEW']=$vhltMastRes;
	    $result['VHLTMAST']['DELETE']=$vhltdelMastRes;	    

	    /*FOR GADIMAST_QRY*/
	    $result['GADIMAST']['NEW']=$gadiMastRes;
	    $result['GADIMAST']['DELETE']=$gadidelMastRes;	    

	    /*FOR V_VEHICLE_JOINT_QRY*/
	    $result['V_VEHICLE_JOINT']['NEW']=$vehjntMastRes;
	    $result['V_VEHICLE_JOINT']['DELETE']=$vehjntdelMastRes;

	    /*FOR CANEQMAST_QRY*/
	    $result['CANEQMAST']['NEW']=$caneqMastRes;
	    $result['CANEQMAST']['DELETE']=$caneqdelMastRes;

	    /*FOR SERIESHS_SSEGMAST_QRY*/
	    $result['SERIESHS_SSEGMAST']['NEW']=$serihsMastRes;
	    $result['SERIESHS_SSEGMAST']['DELETE']=$serihsdelMastRes;


	    /*FOR BACK_QRY*/
	    $result['BACK_DATA']['NEW']=$backMastRes;
	    $result['BACK_DATA']['DELETE']=$backdelMastRes;

	    /*FOR FACTORY_DATA_QRY*/
	    $result['FACTORY_DATA']['NEW']=$factdMastRes;
	    $result['FACTORY_DATA']['DELETE']=$factddelMastRes;

        echo json_encode($result);
        die;        
	}
}

$obj = new AllMasterData();
$obj ->getAllmasterData();
?>
