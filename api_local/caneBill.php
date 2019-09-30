<?php
// Include all configurations
header('Content-type: application/json');
//require_once('../dashboard.php');
require_once( "../dashboard.php" );
$qryObj = new Query();
$dsbObj = new Dashboard();
$qryPath = "ini/web_service.ini";

$oldFilter=array(':PTXN_SEASON',':PPRT_CODE');
$newFilter=array($_REQUEST['season'],$_REQUEST['farmer']);
$getFieldRegQry = $qryObj->fetchQuery($qryPath,'Q001','GET_FARMERBILL',$oldFilter,$newFilter);
$getFieldRegRes = $dsbObj->getData($getFieldRegQry);
echo json_encode($getFieldRegRes);

exit();
           
          /* if(sizeof($getcontractRes) > 0 ){
           	  foreach ($getcontractRes as $key => $Row) {
           	  	$Contractors[$key]["ComboField"] = $Row["CONTRACTORNAME"].'-'.$Row["CONTRACTORCODE"].'-'.$Row["SUBCONTRACTORNAME"].'-'.$Row["SUBCONTRACTORCODE"].'-'.$Row["VEHICLETYPE"].'-'.$Row["TXN_VHNO"];
            	$Contractors[$key]["SequenceNo"] = $Row["SEQUENCENO"];
            	$Contractors[$key]["Field1"]["DisplayName"] = "Contractor";
				$Contractors[$key]["Field1"]["Value"] = $Row["CONTRACTORNAME"];
				$Contractors[$key]["Field2"]["DisplayName"] = "Contractor Code";
				$Contractors[$key]["Field2"]["Value"] = $Row["CONTRACTORCODE"];
				$Contractors[$key]["Field3"]["DisplayName"] = "Sub Contractor";
				$Contractors[$key]["Field3"]["Value"] = $Row["SUBCONTRACTORNAME"];
				$Contractors[$key]["Field4"]["DisplayName"] = "Sub Contractor Code";
				$Contractors[$key]["Field4"]["Value"] = $Row["SUBCONTRACTORCODE"];
				$Contractors[$key]["Field5"]["DisplayName"] = "Vehicle Type";
				$Contractors[$key]["Field5"]["Value"] = $Row["VEHICLETYPE"];
				$Contractors[$key]["Field6"]["DisplayName"] = "Vehicle No.";
				$Contractors[$key]["Field6"]["Value"] = $Row["TXN_VHNO"];
				$Contractors[$key]["Photo"] = $Row["PHOTO"];
				$Contractors[$key]["FingerPrint"] = $Row["FINGERPRINT"];
              }
              echo json_encode(array('Success'=>'true','Message'=>'Contractors available.','Contractors'=>$Contractors));
           }else{
              echo json_encode(array('Success'=>'true','Message'=>'Contractors not available.'));
           }*/

?>