<?php
require_once('dashboard.php');

$lgs = new Logs();
$qryObj = new Query();
$dsbObj = new Dashboard();

$lgs->lg->info("--START HARVESTMAST SERVER FILE--");

$qryPath = $_SESSION['QRYPATH'];
$qryPath = $qryPath."general/advance_paymast.ini";
$comp_code = $_SESSION['COMP_CODE'];
$user_code = $_SESSION['USER'];

//$div_code = $_REQUEST['div_code'];
//$ploc_code=$_REQUEST['location']; 
$name = $_REQUEST['name'];
$action_flag = $_REQUEST['action'];


//For Insert and Update data
if($action_flag == 'fullform')
{  
    $season=$_SESSION['SEASON'];
	$date=$_REQUEST['date'];
	$vtype=explode('-',$_REQUEST['vtype']);	
	$amt=$_REQUEST['amnt'];
	$slb1=$_REQUEST['slb1'];
	$slb2=$_REQUEST['slb2'];
	$slb3=$_REQUEST['slb3'];
	$slb4=$_REQUEST['slb4'];
	$slb5=$_REQUEST['slb5'];
	

	//Set Filter
	$oldFilter = array(':PCOMP_CODE',':PHA_SCODE',':PHA_DATE',':PHA_VTYPE',':PHA_AMT',':PHA_SLABP1',':PHA_SLABP2',':PHA_SLABP3',':PHA_SLABP4',':PHA_SLABP5');
	$newFilter = array($_SESSION['COMP_CODE'],$season,$date,$vtype[0],$amt,$slb1,$slb4,$slb3,$slb4,$slb5);

    //For Add Insert New Record              
	if($_REQUEST['flag']=="add")
		{		
		  
		  $prtinsQry = $qryObj->fetchQuery($qryPath,'Q001','INSERT_HARVEST',$oldFilter,$newFilter);
		  $prtinsRes = $dsbObj->updateData($prtinsQry);
		  $lgs->lg->trace("--ADVPAYMAST INSERT QUERY-:".$prtinsQry);
		  $lgs->lg->trace("--ADVPAYTMAST INSERT QUERY RESULT--:".$prtinsRes);
	      echo $prtinsRes;
		  exit(0);      

        }else{
		  //UPDATE_SEQUERY
		  $UpdseQry = $qryObj->fetchQuery($qryPath,'Q001','UPDATE_PAY',$oldFilter,$newFilter);
		  $UpdseRes = $dsbObj->updateData($UpdseQry);
		    $lgs->lg->trace("--HARVESTMAST UPDATE QUERY-:".$UpdseQry);
		  $lgs->lg->trace("--HARVESTMAST UPDATE QUERY RESULT--:".$UpdseRes);
		  echo $UpdseRes;
		  exit(0);
		}
}//end if



?>