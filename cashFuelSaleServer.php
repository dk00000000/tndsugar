<?php

//require_once('dashboard.php');
//include('TRANSHIST.php');
require('curdClass.php');

$lgs = new Logs();
$qryObj = new Query();
$dsbObj = new Dashboard();

$curd = new CURD();

$lgs->lg->trace("--START CASH FUEL SALE FILE--");

$comp_code = $_SESSION['COMP_CODE'];
$user_code = $_SESSION['USER'];
$user_cat = $_SESSION['USER_CAT'];
$user_accd = $_SESSION['USER_ACCD'];

$action_flag = $_GET['action'];
$lgs->lg->trace("--action_flag :--".$action_flag);
$lgs->lg->trace("--request array :--".json_encode($_REQUEST));


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
$txn_refseq = $_REQUEST['txn_refseq'];
$txn_date = $_REQUEST['header_date'];
$txn_date1 = $_REQUEST['date'];
$location = $_REQUEST['location'];
$series = $_REQUEST['series'];
$div_code = $_REQUEST['div_code'];
$doc_code = $_REQUEST['doc_code'];

$vhno = $_REQUEST['vhno'];
$fuel = $_REQUEST['fuel'] || $_GET['fuel'];
if($fuel == 'Diesel')
	$fuel = 'D';
else if($fuel == 'Petrol')
	$fuel = 'P';
$alwdqty = $_REQUEST['alwdqty'];
$isdqty = $_REQUEST['isdqty'];
$rate = $_REQUEST['rate'];
$amt = $_REQUEST['amt'];
$inv_loctn = $_REQUEST['inv_loctn'];
$supp_code = $_REQUEST['suppcd'];
$txn_doc = $_REQUEST['txn_doc'];

$date1 = date('Ymd');
$date = new DateTime($date1);
$perd_code = $date->format('Ym');

if($action_flag == 'add')
{
	$lgs->lg->trace("--In save function--");
	$oldfilter = array(':PTXN_SEQ', ':PTXN_DIVN', ':PTXN_DOC', ':PCOMP_CODE', ':PPLOC_CODE', ':PSSEG_CODE', ':PPERD_CODE', ':PTXN_SRNO', ':PTXN_DATE', ':PTXN_HDATE', ':PTXN_SEASON', ':PTXN_VHNO', ':PTXN_FLAG1', ':PTXN_BLEXRT', ':PTXN_NETWT', ':PTXN_AMT', ':PTXN_FLOCN', ':PTXN_ACCD', ':PCREATED_BY', ':PTXD_REFDOC');
	$newfilter = array($seq, $div_code, $doc_code, $comp_code, $location, $series, $perd_code, $seq, $txn_date, $txn_date1, $ls_code, $vhno, $fuel, $isdqty, $rate, $amt, $inv_loctn, $supp_code, $user_code, $txn_doc);
	
	
	$sendData = $curd->InsertData($oldfilter, $newfilter, 'cashFuelSale.ini', 'INSQRY');
	if($sendData == true)
	{
		//$del_refRes = $curd->DeleteData($oldfilter, $newfilter, 'cashFuelSale.ini', 'DEL_QRY_MMMREF');
		//$ins_refRes = $curd->InsertData($oldfilter, $newfilter, 'cashFuelSale.ini', 'QRY_MMMREF');
		$auto_approve = $curd->InsertData($oldfilter, $newfilter, 'cashFuelSale.ini', 'QRY_APPROVE');
	}
		
	echo $sendData;
}


else if($action_flag == 'update')
{
	$lgs->lg->trace("--In update function--");
	$oldfilter = array(':PCOMP_CODE', ':PTXN_DATE', ':PTXN_HDATE', ':PTXN_SEQ', ':PMODIFIED_BY');
	$newfilter = array($comp_code, $txn_date, $txn_date1, $seq, $user_code);
	$sendData = $curd->UpdateData($oldfilter, $newfilter, 'cashFuelSale.ini', 'UPDQRY');
	echo $sendData;
}


if($action_flag == 'inv_loctn')
{
	$lgs->lg->trace("--In slipno function--");

	$sncode = $_GET['sn_code'];
	$loctn = $_GET['loctn'];
	$divn = $_GET['divn'];
	$series = $_GET['series'];
	$txn_date = $_GET['txn_date'];
	
	$oldLovFilter = array(':PCOMP_CODE', ':PDIV_CODE', ':PLOC_CODE', ':PTXN_SEASON', ':PUSER_CAT', ':PUSER_CODE', ':PSUPP', ':PSERIES', ':PTXN_FLAG1', ':PTXN_DATE');
	$newLovFilter = array($_SESSION['COMP_CODE'], $divn, $loctn, $sncode, $user_cat, $user_code, $supp_code, $series, $fuel, $txn_date);

	$invLoctn_res = $curd->GetSelData($oldLovFilter, $newLovFilter, 'cashFuelSale.ini', 'INVLOCTN_QRY');
	//print_r($RegNo_LOV);

	$rate_res = $curd->GetSelData($oldLovFilter, $newLovFilter, 'cashFuelSale.ini', 'RATE_QRY');
	$rate = $rate_res[0]['RATE'];

	echo json_encode($invLoctn_res).'***'.$rate;
}

if($action_flag == 'rate')
{
	$sncode = $_GET['sn_code'];
	$suppcd = $_GET['suppcd'];
	$txn_date = $_GET['txn_date'];
	$loctn = $_GET['loctn'];

	$oldLovFilter = array(':PCOMP_CODE', ':PTXN_SEASON', ':PSUPP', ':PTXN_FLAG1', ':PTXN_DATE', ':PLOC_CODE');
	$newLovFilter = array($_SESSION['COMP_CODE'], $sncode, $suppcd, $fuel, $txn_date, $loctn);

	$rate_res = $curd->GetSelData($oldLovFilter, $newLovFilter, 'cashFuelSale.ini', 'RATE_QRY');
	$rate = $rate_res[0]['RATE'];

	$txndate_res = $curd->GetSelData($oldLovFilter, $newLovFilter, 'cashFuelSale.ini', 'TXNDATE_QRY');
	$txndate = $txndate_res[0]['TXN_DATE'];
	$lgs->lg->trace("--Diesel Date Qry--".json_encode($txndate_res));
	echo $rate.'***'.$txndate;
}

if($action_flag == 'inv_loctn1')
{

	$lgs->lg->trace("--In inv_loctn function--");

	$loctn = $_GET['loctn'];
	$series = $_GET['series'];
	
	$oldLovFilter = array(':PCOMP_CODE', ':PLOC_CODE', ':PSERIES');
	$newLovFilter = array($_SESSION['COMP_CODE'], $loctn, $series);
	$invLoctn_res1 = $curd->GetSelData($oldLovFilter, $newLovFilter, 'cashFuelSale.ini', 'INVLOCTN_QRY');

	echo json_encode($invLoctn_res1);
}

?>