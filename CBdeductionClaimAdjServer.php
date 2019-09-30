<?php

//require_once('dashboard.php');
//include('TRANSHIST.php');
require('curdClass.php');

$lgs = new Logs();
$qryObj = new Query();
$dsbObj = new Dashboard();

$curd = new CURD();

$lgs->lg->trace("--START CANE BILL DEDUCTION CLAIM / ADJUSTMENT SERVER FILE--");

$comp_code = $_SESSION['COMP_CODE'];
$user_code = $_SESSION['USER'];
$user_cat = $_SESSION['USER_CAT'];
$user_accd = $_SESSION['USER_ACCD'];

$action_flag = $_GET['action'];
$lgs->lg->trace("--action_flag :--".$action_flag);


if(isset($_REQUEST['sncode']))
{
	$ls_code = $_REQUEST['sncode'];
	$lgs->lg->trace("--sncode :--".$ls_code);
	$temp = explode(',', $ls_code);
	$lgs->lg->trace("--temp :--".json_encode($temp));
	$ls_code = $temp[0];
}
else
{
	$ls_code = $_REQUEST['sn_code'];
}

$lgs->lg->trace("--sncode :--".$ls_code);
$seq = trim($_REQUEST['seq'], ' ');
$txn_date = $_REQUEST['date'];
$location = $_REQUEST['location'];
$series = $_REQUEST['series'];
$div_code = $_REQUEST['div_code'];
$doc_code = $_REQUEST['doc_code'];

$fortnight = $_REQUEST['fortnight_hidden'];
$btype = $_REQUEST['btype'];
$aln_ded = $_REQUEST['aln_ded'];
$brnch = $_REQUEST['brnch'];
$soc = $_REQUEST['soc'];
$farmer = $_REQUEST['farmer_hidden'];
$amt = $_REQUEST['amt'];
$rmrk = $_REQUEST['rmrk'];
$effect = $_REQUEST['effect'];

$date1 = date('Ymd');
$date = new DateTime($date1);
$perd_code = $date->format('Ym');


if($action_flag == 'add')
{
	$lgs->lg->trace("--In save function--");
	$oldfilter1 = array(':PCOMP_CODE', ':PLOC_CODE', ':PTXN_DIVN', ':PTXN_ACCD', ':PTXN_SEASON', ':PTXN_BTYPE', ':PTXN_FORMNO', ':PTXN_UNIT', ':PTXN_SEQ', ':PTXN_FRCITY', ':PTXN_TOCITY');
	$newfilter1 = array($comp_code, $location, $div_code, $farmer, $ls_code, $btype, $fortnight, $aln_ded, $seq, $brnch, $soc);
	$isAllow = $curd->GetSelData($oldfilter1, $newfilter1, 'CBdeductionClaimAdj.ini', 'ISALLOW_QRY');
	if($isAllow[0]['ISALLOW'] == 0)
	{
		$oldfilter = array(':PTXN_SEQ', ':PTXN_DIVN', ':PTXN_DOC', ':PCOMP_CODE', ':PPLOC_CODE', ':PSSEG_CODE', ':PPERD_CODE', ':PTXN_SRNO', ':PTXN_DATE', ':PTXN_REFSEQ', ':PTXN_SEASON', ':PTXN_FORMNO', ':PTXN_BTYPE', ':PTXN_UNIT', ':PTXN_FRCITY', ':PTXN_TOCITY', ':PTXN_ACCD', ':PTXN_AMT', ':PTXN_RMRK', ':PCREATED_BY', ':PTXD_REFDOC', ':PTXN_FLAG1', ':PTXN_STAT');
		$newfilter = array($seq, $div_code, $doc_code, $comp_code, $location, $series, $perd_code, $seq, $txn_date, $txn_refseq, $ls_code, $fortnight, $btype, $aln_ded, $brnch, $soc, $farmer, $amt, $rmrk, $user_code, $txn_doc, $effect, 'O');
		
		
		$sendData = $curd->InsertData($oldfilter, $newfilter, 'CBdeductionClaimAdj.ini', 'INSQRY');
		$lgs->lg->trace("Insert Result : ".$sendData);
		if($sendData == 1)
		{
			$lgs->lg->trace("--In if--");
			//$del_refRes = $curd->DeleteData($oldfilter, $newfilter, 'CBdeductionClaimAdj.ini', 'DEL_QRY_MMMREF');
			//$ins_refRes = $curd->InsertData($oldfilter, $newfilter, 'CBdeductionClaimAdj.ini', 'QRY_MMMREF');
			$auto_approve = $curd->InsertData($oldfilter, $newfilter, 'CBdeductionClaimAdj.ini', 'QRY_APPROVE');
		}
		echo $sendData;
	} //IsAllow if
	else
		$error = 'Can not insert Duplicate Record';
		echo $error;
}


else if($action_flag == 'update')
{
	$lgs->lg->trace("--In update function--");
	$oldfilter = array(':PCOMP_CODE', ':PTXN_DATE', ':PTXN_FORMNO', ':PTXN_BTYPE', ':PTXN_UNIT', ':PTXN_FRCITY', ':PTXN_TOCITY', ':PTXN_ACCD', ':PTXN_AMT', ':PTXN_RMRK', ':PTXN_SEQ', ':PMODIFIED_BY', ':PTXN_FLAG1');
	$newfilter = array($comp_code, $txn_date, $fortnight, $btype, $aln_ded, $brnch, $soc, $farmer, $amt, $rmrk, $seq, $user_code, $effect);
	$sendData = $curd->UpdateData($oldfilter, $newfilter, 'CBdeductionClaimAdj.ini', 'UPDQRY');
	echo $sendData;
}


else if($action_flag == 'frnghtno')
{
	$lgs->lg->trace("--In frnghtno function--");
	$sncode = $_GET['sn_code'];
	if(isset($_GET['view']))
		$view = $_GET['view'];
	else
		$view = 'add';
	//Added By Ankush For Season
	$sncode=$_SESSION['SEASON'];
	$oldLovFilter = array(':PCOMP_CODE', ':PTXN_SEASON', ':PSN_CODE',':PSEASON',':PBTYPE');
	$newLovFilter = array($_SESSION['COMP_CODE'], $sncode, $sncode,$_SESSION['SEASON'],$_REQUEST['btype']);
	$frnghtno_lov = $dsbObj->getLovQry(137,$oldLovFilter,$newLovFilter);
	echo json_encode($frnghtno_lov);
	/*if($view != 'add')
		$frnghtno_lov = $dsbObj->getLovQry(63,$oldLovFilter,$newLovFilter);
	else
		$frnghtno_lov = $dsbObj->getLovQry(101,$oldLovFilter,$newLovFilter);
	    echo json_encode($frnghtno_lov);*/
}

else if($action_flag =='item'){

$oldLovFilter = array(':PCOMP_CODE', ":PSEARCH");
$newLovFilter = array($_SESSION['COMP_CODE'], "'%".$_REQUEST['type_string']."%'");
   $regnoRes = $dsbObj->getLovQry(105, $oldLovFilter, $newLovFilter);	
/*echo $regnoRes['DATA_STRING'];
$lgs->lg->trace("--AUTO GET RESULT--:".json_encode($regnoRes));*/
$array = array();
	foreach($regnoRes as $key=>$row)
	{
		$array[] = array (
		            'value' => $row['DATA_STRING'], 
		        );
	}
	echo json_encode($array);
}

else if($action_flag == 'farmerTable')
{
	$lgs->lg->trace("--In farmerTable function--");
	$season = $_GET['season'];
	$farmer = $_GET['farmer'];
	$formno = $_GET['formno'];
	$oldfilter = array(':PCOMP_CODE', ':PTXN_SEASON', ':PTXN_ACCD', ':PTXN_FORMNO');
	$newfilter = array($comp_code, $season, $farmer, $formno);
	$farmerData = $curd->GetSelData($oldfilter, $newfilter, 'CBdeductionClaimAdj.ini', 'FARMER_QRY');
	for($i = 0; $i < sizeof($farmerData); $i++)
	{
		$temp[]=array_values($farmerData[$i]);
	}
	$temp1=json_encode($temp,JSON_PRETTY_PRINT);
	echo '{"data":'.$temp1.'}';
}

?>