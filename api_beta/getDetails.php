<?php
// Include all configurations
header('Content-type: application/json');
//require_once('../dashboard.php');
require_once( "../dashboard.php" );
$qryObj = new Query();
$dsbObj = new Dashboard();
$qryPath = $qryPath."ini/web_service.ini";
$Contractors = array();

$txn_srno=$_REQUEST['ContractNo'];
$oldFilter=array(':PTXN_SRNO');
$newFilter=array($txn_srno);

$getcontractQry = $qryObj->fetchQuery($qryPath,'Q001','GET_DETAILS',$oldFilter,$newFilter);
$Row = $dsbObj->getData($getcontractQry);
if(sizeof($Row) > 0 ){
	$result['Success']="true";
              $result['Message']="Details available";

              $m1['Field1']['DisplayName']="Contractor";
              $m1['Field1']['Value']=$Row[0]["CONTRACTORNAME"];
              $m1['Field2']['DisplayName']="Contractor Code";
              $m1['Field2']['Value']=$Row[0]["CONTRACTORCODE"];
              $m1['Photo']=$Row[0]["PHOTOM"];
              $m1['FingerPrint']=$Row[0]["FINGERPRINTM"];
               
              $m2['Field1']['DisplayName']="Mukadam";
              $m2['Field1']['Value']=$Row[0]["SCONTRACTOR"];
              $m2['Field2']['DisplayName']="Contractor Code";
              $m2['Field2']['Value']=$Row[0]["SCONTRACTOR_CODE"];
              $m2['Photo']=$Row[0]["PHOTOB"];
              $m2['FingerPrint']=$Row[0]["FINGERPRINTB"];

              $m3['Field1']['DisplayName']="FirstGuaranter";
              $m3['Field1']['Value']=$Row[0]["FGUARANTER"];
              $m3['Field2']['DisplayName']="Contractor Code";
              $m3['Field2']['Value']=$Row[0]["FGUARANTER_CODE"];
              $m3['Photo']=$Row[0]["PHOTOF"];
              $m3['FingerPrint']=$Row[0]["FINGERPRINTF"];

              $m4['Field1']['DisplayName']="SecondGuaranter";
              $m4['Field1']['Value']=$Row[0]["SGUARANTER"];
              $m4['Field2']['DisplayName']="Contractor Code";
              $m4['Field2']['Value']=$Row[0]["SGUARANTER_CODE"];
              $m4['Photo']=$Row[0]["PHOTOS"];
              $m4['FingerPrint']=$Row[0]["FINGERPRINTS"];

              $m5['Field1']['DisplayName']="ThirdGuaranter";
              $m5['Field1']['Value']=$Row[0]["TGUARANTER"];
              $m5['Field2']['DisplayName']="Contractor Code";
              $m5['Field2']['Value']=$Row[0]["TGUARANTER_CODE"];
              $m5['Photo']=$Row[0]["PHOTOT"];
              $m5['FingerPrint']=$Row[0]["FINGERPRINTT"];

              $result['Details']['MainContractor']=$m1;
              $result['Details']['Mukadam']=$m2;
              $result['Details']['FirstGuaranter']=$m3;
              $result['Details']['SecondGuaranter']=$m4;
              $result['Details']['ThirdGuaranter']=$m5;
              echo json_encode($result);die;
             
              //echo json_encode(array('Success'=>'true','Message'=>'Details available.','Details'=>$Contractors));
           }else{
              echo json_encode(array('Success'=>'true','Message'=>'Details not available.'));
           }
   /* foreach ($getcontractRes as $key => $Row) {
           	  	$Contractors["MainContractor"][$key]["Field1"]["DisplayName"]= "Contractor" ;
           	  	$Contractors["MainContractor"][$key]["Field1"]["Value"]= $Row["CONTRACTORNAME"] ;
           	    $Contractors["MainContractor"][$key]["Field2"]["DisplayName"]= "Contractor Code" ;
           	  	$Contractors["MainContractor"][$key]["Field2"]["Value"]= $Row["CONTRACTORCODE"] ;
           	  	$Contractors["MainContractor"][$key]["Photo"]= $Row["PHOTOM"] ;
           	  	$Contractors["MainContractor"][$key]["FingerPrint"]= $Row["FINGERPRINTM"] ;
                
           	  	$Contractors["Mukadam"][$key]["Field1"]["DisplayName"]= "Mukadam" ;
           	  	$Contractors["Mukadam"][$key]["Field1"]["Value"]= $Row["SCONTRACTOR"] ;
           	    $Contractors["Mukadam"][$key]["Field2"]["DisplayName"]= "Mukadam Code" ;
           	  	$Contractors["Mukadam"][$key]["Field2"]["Value"]= $Row["SCONTRACTOR_CODE"] ;
           	  	$Contractors["Mukadam"][$key]["Photo"]= $Row["PHOTOB"] ;
           	  	$Contractors["Mukadam"][$key]["FingerPrint"]= $Row["FINGERPRINTB"] ;

           	  	$Contractors["FirstGuaranter"][$key]["Field1"]["DisplayName"]= "FirstGuaranter Name" ;
           	  	$Contractors["FirstGuaranter"][$key]["Field1"]["Value"]= $Row["FGUARANTER"] ;
           	    $Contractors["FirstGuaranter"][$key]["Field2"]["DisplayName"]= "FirstGuaranter Code" ;
           	  	$Contractors["FirstGuaranter"][$key]["Field2"]["Value"]= $Row["FGUARANTER_CODE"] ;
           	  	$Contractors["FirstGuaranter"][$key]["Photo"]= $Row["PHOTOF"] ;
           	  	$Contractors["FirstGuaranter"][$key]["FingerPrint"]= $Row["FINGERPRINTF"] ;

           	  	$Contractors["SecondGuaranter"][$key]["Field1"]["DisplayName"]= "SecondGuaranter Name" ;
           	  	$Contractors["SecondGuaranter"][$key]["Field1"]["Value"]= $Row["SGUARANTER"] ;
           	    $Contractors["SecondGuaranter"][$key]["Field2"]["DisplayName"]= "SecondGuaranter Code" ;
           	  	$Contractors["SecondGuaranter"][$key]["Field2"]["Value"]= $Row["SGUARANTER_CODE"] ;
           	  	$Contractors["SecondGuaranter"][$key]["Photo"]= $Row["PHOTOS"] ;
           	  	$Contractors["SecondGuaranter"][$key]["FingerPrint"]= $Row["FINGERPRINTS"] ;

           	  	$Contractors["ThirdGuaranter"][$key]["Field1"]["DisplayName"]= "ThirdGuaranter Name" ;
           	  	$Contractors["ThirdGuaranter"][$key]["Field1"]["Value"]= $Row["TGUARANTER"] ;
           	    $Contractors["ThirdGuaranter"][$key]["Field2"]["DisplayName"]= "ThirdGuaranter Code" ;
           	  	$Contractors["ThirdGuaranter"][$key]["Field2"]["Value"]= $Row["TGUARANTER_CODE"] ;
           	  	$Contractors["ThirdGuaranter"][$key]["Photo"]= $Row["PHOTOT"] ;
           	  	$Contractors["ThirdGuaranter"][$key]["FingerPrint"]= $Row["FINGERPRINTT"] ;


				
              }*/
              
//http://203.127.5.7/tndsugar/api/getDetails.php?ContractNo=DD17A00000276
?>