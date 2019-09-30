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
  $qryPath = "util/readquery/general/billperiodmast_checklist.ini";

   $menucode = $_GET['menu_code'];
  //$compcode = $_SESSION['COMP_CODE'];
   $compcode = 'DS';
  
    if($_POST['season']!=''){    
       $season = $_POST['season'];
       $seasontext = $season;
    } 
    else{
       $season='';
       $seasontext = 'सर्व';
    } 

    if($_POST['contract_type']!=''){      
      $contract_type = $_POST['contract_type'];
      $ctdata=(explode("||",$contract_type));
	    $contract_typetext = $ctdata[0].' '.$ctdata[1];
    } 
    else{
      $contract_type='';
      $contract_typetext='सर्व';
    }     

    if($_POST['bill_type']!=''){    
      $bill_type = explode('||',$_POST['bill_type']);
      $bill_typetext = $bill_type[1];
    } 
    else{
       $bill_type='';
       $bill_typetext='सर्व';
    } 

    if($_POST['fortnight']!=''){   
      $fortnight = explode(" || ",$_POST['fortnight']);
      $fortnighttext = $fortnight[1].' '.$fortnight[2];
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
  $oldRptFilter = array(':PCOMP_CODE',':PSEASON',':PHP_CTYPE',':PHP_BTYPE',':PFORTNIGHT');
  $newPrtFilter = array($compcode,$season,$ctdata[0],$bill_type[0],$fortnight[0]);
  
  $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','SELECTQUERY',$oldRptFilter,$newPrtFilter);
  $printdataRes = $dsbObj->getData($printdataQry);
  $lgs->lg->trace("Bill Period master Checklist report php  query: ".$printdataQry);
  $lgs->lg->trace("Bill Period master Checklist php  result: ".json_encode($printdataRes));
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
<link rel="icon" href="images/favicon.png" >
<title>Bill Period master Checklist</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

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
</style>
</head>
<body style="font-size:13px; font-family:Verdana">

<?php 
  echo '<h2 align="center" class="page">'.$compnameaRes[0]["COMP_NAME"].'</h2>';
  echo '<h3 align="center" style="border:medium; border-color:#000000">Bill Period Master Checklist</h3>';
  echo '<p align="center"><b>हंगाम : &nbsp;</b>'.$seasontext.'<b>&nbsp;&nbsp;&nbsp;'.'कॉन्ट्रॅक्टर :&nbsp;</b>'.$contract_typetext.'&nbsp;&nbsp;&nbsp;<b>बिल प्रकार :&nbsp </b>'.$bill_typetext.'&nbsp;&nbsp;&nbsp;<b>पंधरवडा : &nbsp;</b>'.$fortnighttext.'</p>';
 
?>  
<table cellspacing="0" border="0" width="100%" align="center">
  <thead>
  <tr>
  <th align="left" width="20%">कॉन्ट्रॅक्टर प्रकार</th>
  <th align="left" width="10%">पंधरवडा </th>
  <th align="left" width="10%">दिनांक पासून </th>
  <th align="left" width="10%">दिनांक पर्यंत </th>
  <th align="left" width="10%">पेमेंट दिनांक</th>
  <th align="left" width="10%">Locked?</th>
  </tr>
</thead>
<?php
$last_ctype='';
$last_btype='';

foreach ($printdataRes as $row) {
  if ($last_btype <> $row['HT_CODE']) {
     if ($last_btype <> "") { 
       print '<tr  style="outline: thin dotted">';  
       print '</tr>';
      }  
      if ($last_ctype <> "") {
        print '<tr  style="outline: thin dotted">';  
        print '</tr>';
      }
    print '<tr style="outline: thin solid">';  
    print "<td>";
    print "<b>".$row['HT_CODE']." ".$row['HT_MNAME']."</b>";
    print "</td>";  
    print '</tr>';
    $last_ctype = '';
    $last_btype = $row['HT_CODE'];  
  }

  if ($last_ctype <> $row['HP_CTYPE']) {
     if ($last_ctype <> "") { 
       print '<tr  style="outline: thin dotted">';  
       print '</tr>';
      }  
    print '<tr>';  
    print "<td>";
    print "<b>".$row['HP_CTYPE']." ".$row['CT_MNAME']."</b>";
    print "</td>";  
    $last_ctype = $row['HP_CTYPE'];  
  }
  else{
  print "<td>";
  print "&nbsp";
  print "</td>";
  }

  print '<td align=left>';
  print $row['HPD_FNNO'];
  print "</td>";
  print '<td align=left>';
  print $row['FROMDATE'];
  print "</td>";
  print '<td align=left>';
  print $row['TODATE'];
  print "</td>";
  print '<td align=left>';
  print $row['PAYMENT_DT'];
  print "</td>";
  print '<td align=left>';
  print $row['HPD_LOCK'];
  print "</td>";
  print "</tr>";
  }

?> 
</table>
</html>