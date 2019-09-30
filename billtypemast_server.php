<?php
require_once('dashboard.php');

$lgs = new Logs();
$qryObj = new Query();
$dsbObj = new Dashboard();

$lgs->lg->info("--START BILL TYPE MAST SERVER FILE--");

$qryPath = $_SESSION['QRYPATH'];
$qryPath = $qryPath."general/billtypemast.ini";
$comp_code = $_SESSION['COMP_CODE'];
$user_code = $_SESSION['USER'];

$action_flag = $_REQUEST['action'];


//For Insert and Update data
if($action_flag == 'fullform')
{  
        $st_code=$_REQUEST['st_code'];
		$st_name=$_REQUEST['st_name'];
		$st_mname=$_REQUEST['st_mname'];
		
		//Set Filter
		$oldFilter = array(':PCOMP_CODE',':PBT_CODE',':PBT_NAME',':PBT_MNAME');
		$newFilter = array($_SESSION['COMP_CODE'],$st_code,$st_name,$st_mname);

        //For Add Insert New Record              
	if($_REQUEST['flag']=="add")
		{
		  $prtinsQry = $qryObj->fetchQuery($qryPath,'Q001','INSERT_STMAST',$oldFilter,$newFilter);
		  $prtinsRes = $dsbObj->updateData($prtinsQry);
		  $lgs->lg->trace("--STMAST INSERT QUERY-:".$prtinsQry);
		  $lgs->lg->trace("--STMAST INSERT QUERY RESULT--:".$prtinsRes);
	      echo $prtinsRes;
		  exit(0);
        }else
		{
		  //UPDATE_SEQUERY
		  $UpdseQry = $qryObj->fetchQuery($qryPath,'Q001','UPDATE_STMAST',$oldFilter,$newFilter);
		  $UpdseRes = $dsbObj->updateData($UpdseQry);
		  echo $UpdseRes;
		  exit(0);
		}
}//end if

?>