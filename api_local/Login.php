<?php
// Include all configurations
header('Content-type: application/json');
require_once('../dashboard.php');
$qryObj = new Query();
$dsbObj = new Dashboard();
$qryPath = $qryPath."ini/web_service.ini";
   
      $username=strtoupper($_REQUEST['Username']);
      $revpass="";
      $revpass.=$_REQUEST['Password'];
      $revpass.=strrev($revpass);
      $password=strtoupper($revpass);
      $db = "(DESCRIPTION =
             (ADDRESS = (PROTOCOL = TCP)(HOST = 203.127.5.12)(PORT = 1521))
             (CONNECT_DATA =
             (SID = aspl)))";


      $conn = @oci_pconnect($username,$password,DATABASE, AL32UTF8);
      if (!$conn) {
       $m = oci_error();
       //echo $m['message'], "\n";
        echo json_encode(array('Success'=>'false','Message'=>'Invalid credentials.'));
       exit;
      }else {
      $_SESSION['USER']=$username;  
      $_SESSION['IDTTY']=$password;
      $oldFilter = array(':PCOMP_CODE',':PUSER_CODE');
      $newFilter = array('DS',$_SESSION['USER']);
      $userQry = $qryObj->fetchQuery($qryPath,'Q001','GET_USER',$oldFilter,$newFilter);
     // echo $userQry;
      $userRes = $dsbObj->getData($userQry);
      //$userdata=json_encode($userRes);
      echo  json_encode(array('Success'=>'true','Message'=>'User details available.','UserDetails'=>$userRes));
    }
exit();
if( $Result->num_rows > 0 ) {
    $UserDetails = $Result->fetch_assoc();
    $ReturnArr['Success'] = 'true';
    $ReturnArr['Message'] = 'User details available.';
    $ReturnArr['UserDetails'] = $UserDetails;
} else {
    $ReturnArr['Success'] = 'false';
    $ReturnArr['Message'] = "Invalid credentials.";
}


echo json_encode( $ReturnArr ); die;
?>