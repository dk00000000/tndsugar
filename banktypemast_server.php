<?php
require_once('dashboard.php');

$lgs = new Logs();
$qryObj = new Query();
$dsbObj = new Dashboard();

$lgs->lg->info("--START ITMAST SERVER FILE--");

$qryPath = $_SESSION['QRYPATH'];
$qryPath = $qryPath."general/banktypemast.ini";
$comp_code = $_SESSION['COMP_CODE'];
$user_code = $_SESSION['USER'];

$div_code = $_REQUEST['div_code'];
$ploc_code=$_REQUEST['location'];
$name = $_REQUEST['name'];
$action_flag = $_REQUEST['action'];


//For Insert and Update data
if($action_flag == 'fullform')
{  
        $st_code=$_REQUEST['st_code'];
		$st_name=$_REQUEST['st_name'];
		$st_mname=$_REQUEST['st_mname'];
		
		//Set Filter
		$oldFilter = array(':PCOMP_CODE',':PBT_CODE',':PST_NAME',':PST_MNAME');
		$newFilter = array($_SESSION['COMP_CODE'],$st_code,$st_name,$st_mname);

        //For Add Insert New Record              
		if($_REQUEST['flag']=="add")
		{

		$chkstCdQry = $qryObj->fetchQuery($qryPath,'Q001','CHECK_STATECODE',$oldFilter,$newFilter);
		//echo "code check query".$chkstCdQry;
		$chkstCdRes = $dsbObj->getData($chkstCdQry);
		$foundcode = sizeof($chkstCdRes);
		//If State code Is not Prasent
		if($foundcode!==1)
		{
		  $prtinsQry = $qryObj->fetchQuery($qryPath,'Q001','INSERT_STMAST',$oldFilter,$newFilter);
		  $prtinsRes = $dsbObj->updateData($prtinsQry);
		  $lgs->lg->trace("--STMAST INSERT QUERY-:".$prtinsQry);
		  $lgs->lg->trace("--STMAST INSERT QUERY RESULT--:".$prtinsRes);
	      echo $prtinsRes;
		  exit(0);
        }else{
        	
		    $nu=2;
		    echo $nu;
        }
		
		
        }else
		{
		  //UPDATE_SEQUERY
		  $UpdseQry = $qryObj->fetchQuery($qryPath,'Q001','UPDATE_STMAST',$oldFilter,$newFilter);
		//  echo $UpdseQry;
		  $UpdseRes = $dsbObj->updateData($UpdseQry);
		  echo $UpdseRes;
		  exit(0);
		}
}//end if

?>