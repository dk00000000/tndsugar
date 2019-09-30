<?php
require_once('dashboard.php');

$lgs = new Logs();
$qryObj = new Query();
$dsbObj = new Dashboard();

$lgs->lg->info("--START Bank Branch Mast SERVER FILE--");

$qryPath = $_SESSION['QRYPATH'];
$qryPath = $qryPath."general/bankbranchmast.ini";
$comp_code = $_SESSION['COMP_CODE'];
$user_code = $_SESSION['USER'];

$action_flag = $_REQUEST['action'];
$flag = $_REQUEST['flag'];


//For Insert and Update data
if($action_flag == 'fullform')
{  
		$lgs->lg->trace("--In Bank Mast Server--:");
		$br_code=$_REQUEST['br_code'];
		$br_name=$_REQUEST['br_name'];
		$br_mname=$_REQUEST['br_mname'];
		$br_sname=$_REQUEST['br_sname'];
		$br_email=$_REQUEST['br_email'];
		$br_phone=$_REQUEST['br_phone'];
		$br_addr=$_REQUEST['br_addr'];
		$br_bank=$_REQUEST['br_bank'];
		$br_accd=$_REQUEST['br_accd'];
		$br_acno=$_REQUEST['br_acno'];
		$br_ifsc=$_REQUEST['br_ifsc'];
		$br_village=$_REQUEST['br_village'];
		$br_manager_name=$_REQUEST['br_manager_name'];
		$br_crate=$_REQUEST['br_crate'];


		$oldInsertFilter = array();
		$newInsertFilter = array();
		array_push($oldInsertFilter,':PCOMP_CODE',':PBR_CODE',':PBR_NAME',':PBR_MNAME',':PBR_SNAME',':PBR_BANK',':PBR_VILLAGE',':PBR_ADDR',':PBR_PHONE',':PBR_EMAIL',':PBR_ACCD',':PBR_ACNO',':PBR_IFSC',':PBR_MANAGER',':PBR_CRATE');
		array_push($newInsertFilter,$_SESSION['COMP_CODE'],$br_code,$br_name,$br_mname,$br_sname,$br_bank,$br_village,$br_addr,$br_phone,$br_email,$br_accd,$br_acno,$br_ifsc,$br_manager_name,$br_crate);

		$lgs->lg->trace("--Old Filter-:".json_encode($oldInsertFilter));
		$lgs->lg->trace("--New Filter--:".json_encode($newInsertFilter));

		//For Add Insert New Record              
		if($flag=="add")
		{
			$insQry = $qryObj->fetchQuery($qryPath,'Q001','INSERT_BBMAST',$oldInsertFilter,$newInsertFilter);
			//echo "QUERY".$insQry;
			$insRes = $dsbObj->updateData($insQry);
			$lgs->lg->trace("--BANK MAST INSERT QUERY-:".$insQry);
			$lgs->lg->trace("--BANK INSERT QUERY RESULT--:".$insRes);
		    echo $insRes;
			exit(0);
		}
        else
		{
		  //UPDATE_SEQUERY
		  $updQry = $qryObj->fetchQuery($qryPath,'Q001','UPDATE_BBMAST',$oldInsertFilter,$newInsertFilter);
		  $updRes = $dsbObj->updateData($updQry);
		  $lgs->lg->trace("--BANK MAST UPDATE QUERY-:".$updQry);
		  $lgs->lg->trace("--BANK UPDATE QUERY RESULT--:".$updRes);
		  echo $updRes;
		  exit(0);
		}
}//end if

?>