<?php
// Include all configurations
header('Content-type: application/json');
//require_once('../dashboard.php');
require_once( "../dashboard.php" );
$qryObj = new Query();
$dsbObj = new Dashboard();
$qryPath = $qryPath."ini/web_service.ini";
$Contractors = array();
$getcontractQry = $qryObj->fetchQuery($qryPath,'Q001','GET_CONTRACTOR');
$getcontractRes = $dsbObj->getData($getcontractQry);
           
           if(sizeof($getcontractRes) > 0 ){
           	  foreach ($getcontractRes as $key => $Row) {
           	  	$Contractors[$key]["ComboField"] = $Row["SEQUENCENO"].'-'.$Row["CONTRACTORNAME"].'-'.$Row["CONTRACTORCODE"].'-'.$Row["VEHICLETYPE"].'-'.$Row["TXN_VHNO"];
            	$Contractors[$key]["ContractNo"] = $Row["SEQUENCENO"];
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
           }

?>