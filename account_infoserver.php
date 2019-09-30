<?
	require_once("curdClass.php");
	$curd = new CURD();
	
	$code = $_REQUEST['code'];
	$branch = $_REQUEST['branch'];
	$chequename = $_REQUEST['chequename'];
	$accountno = $_REQUEST['accountno'];
	$aadhar = $_REQUEST['aadhar'];
	$mobile = $_REQUEST['mobile'];
	

	$filename = 'accountinfo.ini';

	$oldFilter = array(':PCOMP_CODE',':PPRT_CODE',':PPRT_BANKBR',':PPRT_CNAME',':PPRT_ACNO', ':PCREATED_BY', ':PMODIFIED_BY',':PAADHAR',':PMOBILE');
	$newFilter = array($_SESSION['COMP_CODE'],$code,$branch,$chequename,$accountno,$_SESSION['USER'],$_SESSION['USER'],$aadhar,$mobile);
	//print_r($oldFilter);
	//print_r($newFilter);
	if($_REQUEST['action'] == 'update'){
		$query = 'UPDQRY';
		$res = $curd->UpdateData($oldFilter,$newFilter,$filename,$query);
		echo $res;	
	}
?>
