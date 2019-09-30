<?php 
  //session_start();
  require_once('dashboard.php');
 
  include('readfile.php');
  //require_once('header_menu.php');
  setlocale(LC_MONETARY, 'en_IN');
  //For number to marathi word conversion
  require_once("numbertomarathiword.php");
  $marathinumber = new NumbertoMarathi;

  $lgs = new Logs();
  $qryObj = new Query();
  $dsbObj = new Dashboard(); 
  $rfObj = new ReadFile();
  $lang=strtolower($_SESSION['LANG']);
  $qryPath = "util/readquery/general/cb_bank_ack.ini";

   $menucode = $_GET['menu_code'];
  //$compcode = $_SESSION['COMP_CODE'];
   $compcode = 'DS';

   
    if(isset($_POST['season'])){    
       $season = $_POST['season'];
       $seasontext = $season;
    } 
    else{
       $season='';
       $seasontext = 'All';
    } 
    
    if(isset($_POST['fornight'])){    
      $fornight = explode('||',$_POST['fornight']);
      $fornighttext = $fornight[1];
    } 
    else{
       $fornight='';
       $fornighttext='All';
    } 
   if(isset($_POST['bank'])){    
      $bank = $_POST['bank'];
      $banktext = $bank;
    } 
   else{
      $bank='';
      $banktext='All';
    } 
    if(isset($_POST['bill_type'])){    
      $bill_type = $_POST['bill_type'];
      $bill_typetext = $bill_type;
    } 
    else{
       $bill_type='';
       $bill_typettext='All';
    } 

  $oldFilter = array(':PCOMP_CODE');
  $newFilter = array( $compcode);
  $compnameQry = $qryObj->fetchQuery($qryPath,'Q001','COMPNAME',$oldFilter,$newFilter);
  $compnameaRes = $dsbObj->getData($compnameQry);

  //GET PRINT DATA
  for($i=0;$i<sizeof($bank);$i++){
    $bankstr = implode(',',$bank);
  }
  $oldRptFilter = array(':PCOMP_CODE',':PTXN_SEASON',':PFORTNIGHT',':PBANK_CODE',':PBT_CODE');
  $newPrtFilter = array($compcode,$season,$fornight[0],$bankstr,$bill_type);

     //Call Procedure
  $callProc = $qryObj->fetchQuery($qryPath,'Q001','PROCEDURE',$oldRptFilter,$newPrtFilter);
  //echo $callProc;
  $ProcRes = $dsbObj->getData($callProc);
  $lgs->lg->trace("In Consolidated bank  Procedure: ".$callProc);
 
  $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','SELECTQUERY',$oldRptFilter,$newPrtFilter);
  $printdataRes = $dsbObj->getData($printdataQry);
  $lgs->lg->trace("In Bank Branch Acknowledgement Summary report php  query: ".$printdataQry);
  $lgs->lg->trace("In Bank Branch Acknowledgement Summary  recipt php  result: ".json_encode($printdataRes));
  //echo $printdataQry;
  //echo json_encode($printdataRes);
   $rowcnt = sizeof($printdataRes);
   //If Data Not Found
  if($rowcnt ==0 ){
    echo "<h3 align='center'>Data Not Found !</h3>";
    exit(0);
  }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<title>Bank Branch Acknowledgement Letter</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="images/favicon.png" >

<style>
    .page
      {
        page-break-before: always; 
       
        
        page-break-inside: avoid;
      }

      .box{
  display: flex;
  flex-flow: row nowrap;
  justify-content: center;
  align-content: center;
  align-items:center;
}
.item{
  flex: 1 1 auto;
  
}
</style>
</head>
<body style="font-size:15px; font-family: verdana">

   
<?php
// Fetch the results of the query
$page_number=0;
foreach ($printdataRes as $row) {
?>
	<table cellspacing="0" border="0" width="90%" align="center">
	<thead>
	  <tr>
		<td align="right" width="10%"></td>
		<td align="right" width="80%"></td>
	  </tr>
	</thead>
<?php
    $page_number = $page_number + 1;
	if ($page_number > 1) {
		echo "<p class='page'>  </p>";
	}
	echo '<h2 align="center" style="border:medium; border-color:#000000"> पोहोच </h2>';
	print "<tr><td>&nbsp;</td></tr>";
	print "<tr><td align=left colspan=2>"."प्रती,"."</td></tr>";
	print "<tr><td align=left colspan=2>"."माननीय कार्यकारी संचालक साहेब,"."</td></tr>";
	print "<tr><td align=left colspan=2>".ucwords(strtolower($compnameaRes[0]["COMP_NAME"])).".</td></tr>";
	print "<tr><td colspan=2>&nbsp;</td></tr>";
	print "<tr><td align=center colspan=2>"."दिनांक:".$row['SEASON_START_DT']." ते:".$row['SEASON_END_DT']." या कालावधीतील उस खरेदी बिलासाठीचा"."</td></tr>";
	print "<tr><td align=center colspan=2>"."चेक / डीडी / अॅडव्हाईस बागायतदार यादी सह आज रोजी आमचेकडे पोहोच झाला आहे."."</td></tr>";
	print "<tr><td colspan=2>&nbsp;</td></tr>";
	print "<tr><td align=left colspan=2>"."सोबत:"."</td></tr>";
	print "<tr><td align=left colspan=2>"."१) 	बागायतदार यादी"."</td></tr>";
	print "<tr><td align=left colspan=2>"."२) 	चेक / डीडी / अॅडव्हाईस ............................................ दिनांक ......................."."</td></tr>";
	print "<tr><td>&nbsp;</td><td align=left>"."रक्कम:<strong>".money_format('%!i',$row['NETT_AMT'])."/- फक्त</strong></td></tr>";
	print "<tr><td>&nbsp;</td><td align=left>"."रक्कम अक्षरी:".$marathinumber->getIndianCurrency($row['NETT_AMT'])." फक्त</td></tr>";
	print "<tr><td colspan=2>&nbsp;</td></tr>";
	print "<tr><td align=right colspan=2>"."शाखाधिकारी"."</td></tr>";
	print "<tr><td align=right colspan=2>".$row['BR_MNAME']."</td></tr>";
	print "<tr><td colspan=2>&nbsp;</td></tr>";
	print "<tr><td colspan=2>&nbsp;</td></tr>";
	print "<tr><td colspan=2>&nbsp;</td></tr>";
	print "<tr><td align=right colspan=2>"."(सही व शिक्का)"."</td></tr>";

	print "</tr>";

}

?>  
</table>
</html>