<?php 

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
  $qryPath = "util/readquery/general/bank_payment.ini";

  $menucode = $_GET['menu_code'];
  $compcode = $_SESSION['COMP_CODE'];
   //$compcode = 'DS';

  if($_POST['season']!=''){    
    $season = $_POST['season'];
    $seasontext = $season;
  } 
  else{
    $season='';
    $seasontext = 'सर्व';
  } 
  
  if($_POST['contract_type'] != ''){  
    $contract_type = explode('||', $_POST['contract_type']);  
    $contract_typetext = $contract_type[1];
  } 
  else{
    $contract_type='';
    $contract_typetext='सर्व';
  } 

  if($_POST['bill_type'] != ''){  
    $bill_type = explode('||',$_POST['bill_type']); 
    $bill_typetext = $bill_type[1]; 
  } 
  else{
    $bill_type='';
    $bill_typetext='सर्व';
  }

  if($_POST['fortnight'] != ''){ 
    $fortnight = explode('||' ,$_POST['fortnight']);
    $fortnighttext = $fortnight[0].'&nbsp;&nbsp;'.$fortnight[1];
  } 
  else{
    $fortnight='';
    $fortnighttext='सर्व';
  }  

  $oldFilter = array(':PCOMP_CODE');
  $newFilter = array( $compcode);
  $compnameQry = $qryObj->fetchQuery($qryPath,'Q001','COMPNAME',$oldFilter,$newFilter);
  $compnameaRes = $dsbObj->getData($compnameQry);

  //GET PRINT DATA
  $oldRptFilter = array(':PCOMP_CODE',':PTXN_SEASON',':PFORTNIGHT',':PCT_CODE',':PBT_CODE');
  $newPrtFilter = array($compcode,$season,$fortnight[0],$contract_type[0],$bill_type[0]);
  
  $procureQry = $qryObj->fetchQuery($qryPath,'Q001','PROCEDURE',$oldRptFilter,$newPrtFilter);
  $procureRes = $dsbObj->getData($procureQry);
  $lgs->lg->trace("In Bank Payment report php  procedure: ".$procureQry);

  $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','SELECTQUERY',$oldRptFilter,$newPrtFilter);
  $printdataRes = $dsbObj->getData($printdataQry);
  $lgs->lg->trace("In Bank Payment report php  query: ".$printdataQry);
  $lgs->lg->trace("In Bank Payment php  result: ".json_encode($printdataRes));

  //echo $procureQry;
  //echo $printdataQry;

  $rowcnt = sizeof($printdataRes);
  if($rowcnt ==0 ){
    echo "<h3 align='center'>Data Not Found !</h3>";
    exit(0);
  }
  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<title>Bank Payment Generation Report</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="images/favicon.png">

<style>
  table {
    border:1px solid black;
  }  
  th {
      border:1px solid black;
  }
  td {
     border-left: 1px solid black;
     padding-bottom: 5px;
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
/*For Page Break*/
.page{
    page-break-before: always; 
    page-break-inside: avoid;
  }

  </style>
</head>
<body style="font-size:13px; font-family:Verdana, Arial, Helvetica, sans-serif">

<?php 
  echo '<h3 align="center" class="page">'.$compnameaRes[0]["COMP_NAME"].'</h3>';
  echo '<h4 align="center" style="border:medium; border-color:#000000"> Bank Payment Generation</h4>';
  echo '<p align="center" > <b>हंगाम : </b>'.$seasontext.'<b>&nbsp;&nbsp; करार प्रकार: &nbsp;</b>'.$contract_typetext.'&nbsp;&nbsp;<b>बिल प्रकार : &nbsp</b>'.$bill_typetext.'&nbsp;&nbsp;<b>पंधरवडा :&nbsp</b>'.$fortnighttext.'</p>';
 ?>  
 <table cellspacing="0" border="0" width="100%" align="center">
<thead>
  <tr>
    <th align="centre" width="2%">अनु क्र.</th>
    <th align="right" width="12%">रक्कम</th>
    <th align="center" width="12%">आय एफ एस सी</th>
	  <th align="left" width="15%">खाते क्र.</th>
	  <th align="left" width="30%">पार्टी कोड आणि पार्टी नाव</th>
	  <th align="left" width="30%">बँक शाखा नाव</th>
  </tr>
</thead>
<?php
// Fetch the results of the query

$t_nett_amt=0;

foreach ($printdataRes as $row) {

  print '<tr style="outline: thin dotted">';
  print '<td align="center">';
  print $row['SR'];
  print "</td>";
  print '<td align="right">';
  print money_format('%!i',$row['NETT_AMT']);
  print "</td>";
  print '<td align="center">';
  print $row['BR_IFSC'];
  print "</td>";
  print "<td align='left'>";
  print $row['PRT_ACNO'];
  print "</td>";
  print "<td align='left'>";
  print $row['PARTY_CODE']." ".$row['PRT_NAME'];
  print "</td>";
  print '<td align="left">';
  print $row['BANK_BRANCH_NAME'];
  print "</td>";
  print "</tr>";
  $t_nett_amt = $t_nett_amt + $row['NETT_AMT'];
  
} //foreach

//Print grand totals
print '<tr style="font-weight: bold; outline: thin solid">';
print "<td>";
print "<B>एकूण</B>";
print "</td>";
print '<td align="right">';
print money_format('%!i',$t_nett_amt);
print "</td>";
print "<td colspan=4>";
print $marathinumber->getIndianCurrency($t_nett_amt);
print "</td>";
print "</tr>";

print '<tr style="font-weight: bold">';
print "<td align=left height='60' style=' vertical-align: text-center;' colspan=2>अकाउंटंट :";
print "</td>";
print "<td align=left height='60' style=' vertical-align: text-center;' colspan=1>चीफ अकाउंटंट :";
print "</td>";
print "</tr>";
?>  
</table>
</html>