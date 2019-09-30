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

$ls_code = $_REQUEST['ct_code'];
$lgs->lg->trace("--ct_code :--".$ls_code);
$ls_name = $_REQUEST['ct_name'];
$lgs->lg->trace("--ct_name :--".$ls_name);
$ls_mname = $_REQUEST['ct_mname'];
$lgs->lg->trace("--ct_mname :--".$ls_mname);
$ls_grp = $_REQUEST['ctgrp'];
$lgs->lg->trace("--ctgrp :--".$ls_dvcode);


if($action_flag == 'add')
{
	$lgs->lg->trace("--In save function--");
	//$sendData = $scmastObj->putScmastData($ls_code,$ls_name,$ls_mname,$ls_dvcode);
	$oldfilter = array(':PCOMP_CODE', ':PCT_CODE', ':PCT_NAME', ':PCT_MNAME', ':PCT_GROUP', ':PCREATED_BY');
	$newfilter = array($comp_code, $ls_code, $ls_name, $ls_mname, $ls_grp, $user_code);
	$sendData = $curd->InsertData($oldfilter, $newfilter, 'CANETMAST.ini', 'INSQRY');
	echo $sendData;
}

else if($action_flag == 'update')
{
	$lgs->lg->trace("--In update function--");
	//$sendData = $scmastObj->updateScmastData($ls_code,$ls_name,$ls_mname,$ls_dvcode);
	$oldfilter = array(':PCOMP_CODE', ':PCT_CODE', ':PCT_NAME', ':PCT_MNAME', ':PCT_GROUP', ':PMODIFIED_BY');
	$newfilter = array($comp_code, $ls_code, $ls_name, $ls_mname, $ls_grp, $user_code);
	$sendData = $curd->UpdateData($oldfilter, $newfilter, 'CANETMAST.ini', 'UPDQRY');
	echo $sendData;
}


?>