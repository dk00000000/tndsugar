<?php
require_once('dashboard.php');

$lgs = new Logs();
$qryObj = new Query();
$dsbObj = new Dashboard();

$lgs->lg->info("--START Taluka Mast SERVER FILE--");

$qryPath = $_SESSION['QRYPATH'];
$qryPath = $qryPath."general/bankmast.ini";
$comp_code = $_SESSION['COMP_CODE'];
$user_code = $_SESSION['USER'];

$div_code = $_REQUEST['div_code'];
$ploc_code=$_REQUEST['location'];
$name = $_REQUEST['name'];
$action_flag = $_REQUEST['action'];


//For Insert and Update data
if($action_flag == 'fullform')
{  
		$lgs->lg->trace("--In Bank Mast Server--:");
		$bk_code=$_REQUEST['bk_code'];
		$bk_name=$_REQUEST['bk_name'];
		$bk_mname=$_REQUEST['bk_mname'];
		$bk_sname=$_REQUEST['bk_sname'];
		$bk_email=$_REQUEST['email'];
		$bk_phone=$_REQUEST['bk_phone'];
		$bk_addr=$_REQUEST['bk_addr'];
		$bk_btype=$_REQUEST['bk_btype'];
		
		$oldFilter = array(':PCOMP_CODE', ':PBK_CODE', ':PBK_NAME', ':PBK_MNAME',':PBK_SNAME',':PBK_BTYPE',':PBK_ADDR',':PBK_PHONE',':PBK_EMAIL');
		$newFilter = array($_SESSION['COMP_CODE'],$bk_code,$bk_name,$bk_mname,$bk_sname,$bk_btype,$bk_addr,$bk_phone,$bk_email);
		
        //For Add Insert New Record              
		if($_REQUEST['flag']=="add")
		{
		$prtinsQry = $qryObj->fetchQuery($qryPath,'Q001','INSERT_BANKMAST',$oldFilter,$newFilter);
		$prtinsRes = $dsbObj->updateData($prtinsQry);
		$lgs->lg->trace("--BANK MAST INSERT QUERY-:".$prtinsQry);
		$lgs->lg->trace("--BANK INSERT QUERY RESULT--:".$prtinsRes);
	    echo $prtinsRes;
		exit(0);
		
        }
        else
		{
		  //UPDATE_SEQUERY
		  $UpdseQry = $qryObj->fetchQuery($qryPath,'Q001','UPDATE_BANKMAST',$oldFilter,$newFilter);
		  $UpdseRes = $dsbObj->updateData($UpdseQry);
		  $lgs->lg->trace("--BANK MAST UPDATE QUERY-:".$UpdseQry);
		  $lgs->lg->trace("--BANK UPDATE QUERY RESULT--:".$UpdseRes);
		  echo $UpdseRes;
		  exit(0);
		}
}//end if

?>