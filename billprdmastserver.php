<?php

//require_once('dashboard.php');
//include('TRANSHIST.php');
require('curdClass.php');

$lgs = new Logs();
$qryObj = new Query();
$dsbObj = new Dashboard();

$curd = new CURD();

$lgs->lg->trace("--START BILLPRDMAST SERVER FILE--");

$comp_code = $_SESSION['COMP_CODE'];
$user_code = $_SESSION['USER'];

$action_flag = $_GET['action'];
$lgs->lg->trace("--action_flag :--".$action_flag);

if(isset($_REQUEST['hpscode']))
{
	$ls_code = $_REQUEST['hpscode'];
	$lgs->lg->trace("--hpscode :--".$ls_code);
	$temp = explode(',', $ls_code);
	$lgs->lg->trace("--temp :--".json_encode($temp));
	$ls_code = $temp[0];
}
else
{
	$ls_code = $_SESSION['SEASON'];
}
$lgs->lg->trace("--hpscode :--".$ls_code);
$contype = $_REQUEST['contype'];
$lgs->lg->trace("--contype :--".$contype);

$dataset = $_GET['dataset'];
$lgs->lg->trace("--detail table dataset :--".json_encode($dataset)." size: ".sizeof($dataset));

if($action_flag == 'add')
{
	$lgs->lg->trace("--In save function--");
	$oldfilter = array(':PCOMP_CODE', ':PHP_SCODE', ':PHP_CTYPE', ':PCREATED_BY');
	$newfilter = array($comp_code, $ls_code, $contype, $user_code);
	$sendData = $curd->InsertData($oldfilter, $newfilter, 'HTPERDMAST.ini', 'INSQRY');
	
	for($i = 0; $i < sizeof($dataset); $i++)
	{
		$fnno = $dataset[$i][0];
		$fnnd = $dataset[$i][1];
		$btype = explode('-',$dataset[$i][2]);
		$frdt = $dataset[$i][3];
		$todt = $dataset[$i][4];
		$dudt = $dataset[$i][5];
		$lck = $dataset[$i][6];
		
		$dtloldfilter = array(':PCOMP_CODE', ':PHPD_SCODE', ':PHPD_CTYPE', ':PHPD_FNNO', ':PHPD_FRDT', ':PHPD_TODT', ':PHPD_DUDT', ':PHPD_LOCK', ':PCREATED_BY',':PHPD_FNND',':PHPD_BTYPE');
		$dtlnewfilter = array($comp_code, $ls_code, $contype, $fnno, $frdt, $todt, $dudt, $lck, $user_code,$fnnd,$btype[0]);
		$lgs->lg->trace("--dtloldfilter--".json_encode($dtloldfilter));
		$lgs->lg->trace("--dtlnewfilter--".json_encode($dtlnewfilter));
		$sendData = $curd->InsertData($dtloldfilter, $dtlnewfilter, 'HTPERDMAST.ini', 'DTL_INSQRY');
	}
		
	echo $sendData;
}

else if($action_flag == 'update')
{
	$lgs->lg->trace("--In update function--".json_encode($dataset));
	$oldfilter = array(':PCOMP_CODE', ':PHP_SCODE', ':PHP_CTYPE', ':PMODIFIED_BY');
	$newfilter = array($comp_code, $ls_code, $contype, $user_code);
	$sendData = $curd->UpdateData($oldfilter, $newfilter, 'HTPERDMAST.ini', 'UPDQRY');
	echo $sendData;
	
	for($i = 0; $i < sizeof($dataset); $i++)
	{
		$fnno = $dataset[$i][0];
		$fnnd = $dataset[$i][1];
		$btype = explode('-',$dataset[$i][2]);
		$frdt = $dataset[$i][3];
		$todt = $dataset[$i][4];
		$dudt = $dataset[$i][5];
		$lck = $dataset[$i][6];
		$flag = $dataset[$i][7];
		
		if($flag == 'ins')
		{
		$lgs->lg->trace("In New row insert".$flag);	
		$dtloldfilter = array(':PCOMP_CODE', ':PHPD_SCODE', ':PHPD_CTYPE', ':PHPD_FNNO', ':PHPD_FRDT', ':PHPD_TODT', ':PHPD_DUDT', ':PHPD_LOCK', ':PCREATED_BY',':PHPD_FNND',':PHPD_BTYPE');
		$dtlnewfilter = array($comp_code, $ls_code, $contype, $fnno, $frdt, $todt, $dudt, $lck, $user_code,$fnnd,$btype[0]);
		$lgs->lg->trace("--dtloldfilter--".json_encode($dtloldfilter));
		$lgs->lg->trace("--dtlnewfilter--".json_encode($dtlnewfilter));
		$sendData = $curd->InsertData($dtloldfilter, $dtlnewfilter, 'HTPERDMAST.ini', 'DTL_INSQRY');
		}
		else if($flag == 'upd')
		{
			$dtloldfilter = array(':PCOMP_CODE', ':PHPD_SCODE', ':PHPD_CTYPE', ':PHPD_FNNO', ':PHPD_FRDT', ':PHPD_TODT', ':PHPD_DUDT', ':PHPD_LOCK', ':PMODIFIED_BY',':PHPD_BTYPE');
			$lgs->lg->trace("--dtloldfilter--".json_encode($dtloldfilter));
			$dtlnewfilter = array($comp_code, $ls_code, $contype, $fnno, $frdt, $todt, $dudt, $lck, $user_code,$btype[0]);
			$lgs->lg->trace("--dtlnewfilter--".json_encode($dtlnewfilter));
			$sendData = $curd->UpdateData($dtloldfilter, $dtlnewfilter, 'HTPERDMAST.ini', 'DTL_UPDQRY');
		}
	}
}

$deldataset = $_GET['deldataset'];
$lgs->lg->trace("--detail table deldataset :--".json_encode($deldataset)." size: ".sizeof($deldataset));

for($i = 0; $i < sizeof($deldataset); $i++)
{
	$fnno = $deldataset[$i][0];

	$dtloldfilter = array(':PCOMP_CODE', ':PHPD_SCODE', ':PHPD_FNNO', ':PHPD_CTYPE');
	$lgs->lg->trace("--dtloldfilter--".json_encode($dtloldfilter));
	$dtlnewfilter = array($comp_code, $ls_code, $fnno, $contype);
	$lgs->lg->trace("--dtlnewfilter--".json_encode($dtlnewfilter));
	$sendData = $curd->DeleteData($dtloldfilter, $dtlnewfilter, 'HTPERDMAST.ini', 'DTL_DELQRY');
}


?>