<?php

//require_once('dashboard.php');
//include('TRANSHIST.php');
//require('SECMAST.php');
require('curdClass.php');

$lgs = new Logs();
$qryObj = new Query();
$dsbObj = new Dashboard();

//$scmastObj = new Secmast();
$curd = new CURD();

$lgs->lg->trace("--START CANETMAST SERVER FILE--");

$comp_code = $_SESSION['COMP_CODE'];
$user_code = $_SESSION['USER'];

$action_flag = $_GET['action'];
$lgs->lg->trace("--action_flag :--".$action_flag);

$ls_code = $_REQUEST['cv_code'];
$lgs->lg->trace("--cv_code :--".$ls_code);
$ls_name = $_REQUEST['cv_name'];
$lgs->lg->trace("--cv_name :--".$ls_name);
//$cv_cat = $_REQUEST['cv_cat'];
//$lgs->lg->trace("--cv_cat :--".$cv_cat);
$cv_mat = $_REQUEST['cv_mat'];
$lgs->lg->trace("--cv_mat :--".$cv_mat);
$cv_mprd = $_REQUEST['cv_mprd'];
$lgs->lg->trace("--cv_mprd :--".$cv_mprd);


if($action_flag == 'add')
{
	$lgs->lg->trace("--In save function--");
	//$sendData = $scmastObj->putScmastData($ls_code,$ls_name,$ls_mname,$ls_dvcode);
	//$oldfilter = array(':PCOMP_CODE', ':PCV_CODE', ':PCV_NAME', ':PCV_CAT', ':PCV_MAT',':PCV_MPERD', ':PCREATED_BY');
	$oldfilter = array(':PCOMP_CODE', ':PCV_CODE', ':PCV_NAME', ':PCV_MAT',':PCV_MPERD', ':PCREATED_BY');
	//$newfilter = array($comp_code, $ls_code, $ls_name, $cv_cat, $cv_mat, $cv_mprd, $user_code);
	$newfilter = array($comp_code, $ls_code, $ls_name, $cv_mat, $cv_mprd, $user_code);
	$sendData = $curd->InsertData($oldfilter, $newfilter, 'CANEVMAST.ini', 'INSQRY');
	echo $sendData;
}

else if($action_flag == 'update')
{
	$lgs->lg->trace("--In update function--");
	//$sendData = $scmastObj->updateScmastData($ls_code,$ls_name,$ls_mname,$ls_dvcode);
	//$oldfilter = array(':PCOMP_CODE', ':PCV_CODE', ':PCV_NAME', ':PCV_CAT', ':PCV_MAT',':PCV_MPERD',':PMODIFIED_BY');
	$oldfilter = array(':PCOMP_CODE', ':PCV_CODE', ':PCV_NAME', ':PCV_MAT',':PCV_MPERD',':PMODIFIED_BY');
	//$newfilter = array($comp_code, $ls_code, $ls_name, $cv_cat, $cv_mat, $cv_mprd, $user_code);
	$newfilter = array($comp_code, $ls_code, $ls_name, $cv_mat, $cv_mprd, $user_code);
	$sendData = $curd->UpdateData($oldfilter, $newfilter, 'CANEVMAST.ini', 'UPDQRY');
	echo $sendData;
}


?>