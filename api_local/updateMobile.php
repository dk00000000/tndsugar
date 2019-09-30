<?php
// Include all configurations
header('Content-type: application/json');
//require_once('../dashboard.php');
require_once( "../dashboard.php" );
$qryObj = new Query();
$dsbObj = new Dashboard();
$qryPath = $qryPath."ini/web_service.ini";

$oldFilter=array(':PPRT_TEL',':PPRT_CODE');
$newFilter=array($_REQUEST['mobile'],$_REQUEST['farmer']);
$updateMobileqry = $qryObj->fetchQuery($qryPath,'Q001','UPDATE_MOBILE',$oldFilter,$newFilter);
$updateMobileRes = $dsbObj->updateData($updateMobileqry);
if($updateMobileRes == 1 ){
           	   echo json_encode(array('OUTPUT'=>'Successful'));
           }else{
              echo json_encode(array('OUTPUT'=>'Unsuccessful'));
           }

?>