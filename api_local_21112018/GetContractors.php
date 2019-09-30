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
            	$Contractors[$key]["MainContractor"] = $Row["CONTRACTORNAME"];
            	$Contractors[$key]["Contractor Code"] = $Row["CONTRACTORCODE"];
            	$Contractors[$key]["Vehicle Type"] = $Row["VEHICLETYPE"];
            	$Contractors[$key]["Vehicle No"] = $Row["TXN_VHNO"];

              }
              echo json_encode(array('Success'=>'true','Message'=>'Contractors available.','Contractors'=>$Contractors));
           }else{
              echo json_encode(array('Success'=>'true','Message'=>'Contractors not available.'));
           }
//http://203.127.5.7/tndsugar/api/GetContractors.php
?>