<?php 
  //session_start();
  require_once('dashboard.php');
  require_once('numbertomarathiword.php'); 
  $numtotext = new NumbertoMarathi();
  //$numtotext->getIndianCurrency(number);
  include('readfile.php');
  //require_once('header_menu.php');
  setlocale(LC_MONETARY, 'en_IN');

  $lgs = new Logs();
  $qryObj = new Query();
  $dsbObj = new Dashboard(); 
  $rfObj = new ReadFile();
  $lang=strtolower($_SESSION['LANG']);
  $qryPath = "util/readquery/general/billdtlmast_checklist.ini";

   $menucode = $_GET['menu_code']; 
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
      $contractor_type = explode('||',$_POST['contract_type']);
      $contractor_typetext = $contractor_type[1];      
    } 
    else{
       $contractor_type='';
       $contractor_typetext='सर्व';
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
      $fortnight = explode(' || ',$_POST['fortnight']);      
      $fortnighttext = $fortnight[1].' '.$fortnight[2];
    } 
    else{
       $fornight='';
       $fortnighttext='सर्व';
    }  

 	  if($_POST['contractor']!=''){    
      $contractor = explode('||',$_POST['contractor']);
      $contractortext = $contractor[1];      
    } 
    else{
       $contractor='';
       $contractortext='सर्व';
    } 

  $oldFilter = array(':PCOMP_CODE');
  $newFilter = array( $compcode);
  $compnameQry = $qryObj->fetchQuery($qryPath,'Q001','COMPNAME',$oldFilter,$newFilter);
  $compnameaRes = $dsbObj->getData($compnameQry);

  $oldRptFilter = array(':PCOMP_CODE',':PSEASON',':PFORTNIGHT',':PCONTRACT_TYPE',':PBILL_TYPE',':PCONTRACTOR');
  $newPrtFilter = array($compcode,$season,$fortnight[0],$contractor_type[0],$bill_type[0],$contractor[0]);

  $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','SELECTQRY',$oldRptFilter,$newPrtFilter);
  $lgs->lg->trace("In Bill Detail Checklist Qry: ".$printdataQry);
  $printdataRes = $dsbObj->getData($printdataQry);
  $lgs->lg->trace("In Bill Detail Checklist Res: ".$printdataRes);

  $rowcnt = sizeof($printdataRes);
  if($rowcnt == 0 ){
    echo "<h3 align='center'>Data Not Found !</h3>";
    exit(0);
  }
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<link rel="icon" href="images/favicon.png" >
<title>Bill Detail Checklist Report</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="images/favicon.png" >

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
  </style>
</head>
<body style="font-size:13px; font-family:Verdana">

<table cellspacing="0" border="0" width="100%" align="center">

<?php

echo '<h3 align="center">'.$compnameaRes[0]["COMP_NAME"].'</h3>';  
echo '<h4 align="center" style="border:medium; border-color:#000000"> Bill Detail Checklist Report</h4>';
echo '<p align="center" > <b>हंगाम :&nbsp </b>'.$season.'&nbsp;&nbsp;<b>कॉन्ट्रॅक्ट  प्रकार:&nbsp </b>'.$contractor_typetext.'&nbsp;&nbsp;<b>बिल प्रकार :&nbsp </b>'.$bill_typetext.'&nbsp;&nbsp;&nbsp;&nbsp;<b>पंधरवडा :&nbsp</b>'.$fortnighttext.' &nbsp;&nbsp;<b>मुकादम : </b>&nbsp;'.$contractortext.'</p>';


$last_header = '';  
$last_srno='';

foreach ($printdataRes as $row) {
   if ($last_srno <> $row['CM_SRNO']) {
      if ($last_srno <> '') {

        
    }   
      ?>
      <table cellspacing="0" border="0" width="100%" align="center">
      <?
       // echo '<p class="page"></p>';
        print "</br></br></br>";
        print '<tr>';
        print "<td><b>कॉन्ट्रॅक्ट प्रकार :</b> &nbsp;&nbsp;".$row['CONTRACT_TYPE']."</td>";
        print "<td><b>वाहन क्रमांक :</b> &nbsp;&nbsp;&nbsp;&nbsp;".$row['VH_NO']."</td>";
        print "<td><b>पंधरवडा :</b> &nbsp;&nbsp;&nbsp;&nbsp;".$row['FORTNIGHT']."</td>";
        print '</tr>';

        print '<tr>';
        print '<td><b>बिल प्रकार :</b> &nbsp;&nbsp;&nbsp;&nbsp;'.$row['BILL_TYPE'].'</td>';
        print '<td><b>मुकादम :</b> &nbsp;&nbsp;&nbsp;&nbsp;'.$row['PRT_NAME'].'</td>';
        print '<td><b>शेरा :</b> &nbsp;&nbsp;&nbsp;&nbsp;'.$row['CM_RMRK'].'</td>';
        print '</tr>';

      ?>

      </table>
      </br>
      <table cellspacing="0" border="0" width="100%" align="center">
      <thead>
      <tr>
        <th align="right" width="3%">नंबर</th>  
        <th align="right" width="3%">अ.क्र.</th>    
        <th align="left" width="25%">जॉब प्रकार</th>
        <th align="right" width="10%">डिपॉजिट परत %</th>
        <th align="right" width="10%">जादा वाहतूक रक्कम</th>
        <th align="right" width="10%">जादा तोडणी रक्कम</th>    
        <th align="right" width="10%">प्रति टन</th>
        <th align="right" width="10%">बक्षीस दर</th>
      </tr>
      </thead>      
    <?

    print "<tr style='outline: thin dotted'>";
    print '<td align="right">';          
    print $row['CM_SRNO'];
    print "</td>";  
    
      $last_srno = $row['CM_SRNO'];                       
  }       
   else {
    print "<tr style='outline: thin dotted'>";
    print "<td>";
    print "&nbsp";
    print "</td>";  
  } 
 // print '<tr style="outline: thin dotted">';
  /*print '<td align="right">';
  print "&nbsp";
  print "</td>";*/
  print '<td align="right">';
  print $row['CMD_RUNO'];
  print "</td>";  
  print '<td align="left">';
  print $row['JOBNAME'];
  print "</td>";
  print '<td align="right">';
  print number_format($row['CMD_SECDEPO'],2);
  print "</td>"; 
  print "<td align='right'>";
  print money_format('%!i',$row['CMD_TRNCOMM']);
  print "</td>";
  print "<td align='right'>";
  print money_format('%!i',$row['CMD_HRVCOMM']);
  print "</td>";
  print '<td align="right">';
  print number_format($row['CMD_INCPTON'],3);
  print "</td>";
  print '<td align="right">';
  print $row['CMD_INCRATE'];
  print "</td>";  
  print "</tr>"; 
  
} //foreach


?>  
</table>
</html> 




