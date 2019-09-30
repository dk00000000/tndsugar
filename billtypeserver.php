<?php

require('curdClass.php');

$lgs = new Logs();
$qryObj = new Query();
$dsbObj = new Dashboard();

$curd = new CURD();

$lgs->lg->trace("--START HTBLTMAST SERVER FILE--");

$comp_code = $_SESSION['COMP_CODE'];
$user_code = $_SESSION['USER'];

$action_flag = $_GET['action'];
$lgs->lg->trace("--action_flag :--".$action_flag);

$ls_code = $_REQUEST['bt_code'];
$lgs->lg->trace("--bt_code :--".$ls_code);
$ls_name = $_REQUEST['bt_name'];
$lgs->lg->trace("--bt_name :--".$ls_name);
$ls_mname = $_REQUEST['bt_mname'];
$lgs->lg->trace("--bt_mname :--".$ls_mname);


if($action_flag == 'add')
{
	$lgs->lg->trace("--In save function--");
	//$sendData = $dvmastObj->putDvmastData($ls_code,$ls_name,$ls_mname);
	$oldfilter = array(':PCOMP_CODE', ':PHT_CODE', ':PHT_NAME', ':PHT_MNAME', ':PCREATED_BY');
	$newfilter = array($comp_code, $ls_code, $ls_name, $ls_mname, $user_code);
	$sendData = $curd->InsertData($oldfilter, $newfilter, 'HTBLTMAST.ini', 'INSQRY');
	echo $sendData;
}

else if($action_flag == 'update')
{
	$lgs->lg->trace("--In update function--");
	//$sendData = $dvmastObj->updateDvmastData($ls_code,$ls_name,$ls_mname);
	$oldfilter = array(':PCOMP_CODE', ':PHT_CODE', ':PHT_NAME', ':PHT_MNAME', ':PMODIFIED_BY');
	$newfilter = array($comp_code, $ls_code, $ls_name, $ls_mname, $user_code);
	$sendData = $curd->UpdateData($oldfilter, $newfilter, 'HTBLTMAST.ini', 'UPDQRY');
	echo $sendData;
}


?>