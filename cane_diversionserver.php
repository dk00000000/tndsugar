<?
	require_once("curdClass.php");
	$curd = new CURD();
	
	$code = $_GET['code'];
	$type = $_GET['type'];
	$name_english = $_GET['txtEnglish'];
	$name_marathi = $_GET['txtMarathi'];
	$filename = 'cane_diversion.ini';
	$oldFilter = array(':PCOMP_CODE', ':PCDT_CODE', ':PCDT_TYPE', ':PCDT_NAME',':PCDT_MNAME',':PCREATED_BY',':PMODIFIED_BY');
	$newFilter = array($_SESSION['COMP_CODE'],$code,$type,$name_english,$name_marathi,$_SESSION['USER'],$_SESSION['USER']);

	if($_GET['action'] == 'add'){
		$query = 'INSQRY';
		$res = $curd->InsertData($oldFilter,$newFilter,$filename,$query);
		if($res==1){
			echo 'add';
		}
	}
	
	if($_GET['action'] == 'update'){
		$query = 'UPDQRY';
		$res = $curd->UpdateData($oldFilter,$newFilter,$filename,$query);
		if($res==1){
			echo 'update';
		}
	}
?>