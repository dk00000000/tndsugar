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
  $qryPath = "util/readquery/general/bankbranch_vlgfarmer.ini";

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
      $fornight = explode('*',$_POST['fornight']);
      $fornighttext = $fornight[1];
    } 
    else{
       $fornight='';
       $fornighttext='All';
    } 
    if(isset($_POST['bankbranch'])){    
      $bankbranch = $_POST['bankbranch'];
      $bankbranchtext = $bankbranch;
    } 
   else{
      $bankbranch='';
      $bankbranchtext='All';
    } 
    if(isset($_POST['section'])){    
      $section = $_POST['section'];
      $sectiontext = $section;
    } 
    else{
       $section='';
       $sectiontext='All';
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
   for($i=0;$i<sizeof($bankbranch);$i++){
    $bankbranchkstr = implode(',',$bankbranch);
  }
  $oldRptFilter = array(':PCOMP_CODE',':PTXN_SEASON',':PFORTNIGHT',':PPRT_BANKBR',':PSECTION',':PBT_CODE');
  $newPrtFilter = array($compcode,$season,$fornight[0],$bankbranchkstr,$section,$bill_type);
 
  //Call Procedure
  $callProc = $qryObj->fetchQuery($qryPath,'Q001','PROCEDURE',$oldRptFilter,$newPrtFilter);
  $ProcRes = $dsbObj->getData($callProc);
  $lgs->lg->trace("In Bank branch Village farmer summary  Procedure: ".$callProc);

  $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','SELECTQUERY',$oldRptFilter,$newPrtFilter);
  $printdataRes = $dsbObj->getData($printdataQry);
  $lgs->lg->trace("In Bank branch Village farmer summary  query: ".$printdataQry);
  $lgs->lg->trace("In Bank branch Village farmer summary  result: ".json_encode($printdataRes));
  //echo json_encode($printdataRes);
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
  <meta name="viewport" content="width=device-width, initial-scale=1">
<title> Bank Branch Village Farmer Summary</title>
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
<body style="font-size:13px; font-family: verdana">


  
   
<?php
// Fetch the results of the query
$last_BR_CODE='';
$last_vl_code='';
$i=0;
$g_nett_amt=0;
$t_nett_amt=0;
foreach ($printdataRes as $row) {
  if ($last_BR_CODE <> $row['BR_CODE']) {
    // if not first farmer print totals
    if ($last_BR_CODE <> "") {
          //echo "Printing Totals"; 
  			print '<tr style="font-weight: bold; outline: thin solid">';                        
  			/*print "<td>";
  			print "$last_BR_CODE";
  			print "</td>";*/
  			print "<td align=left>";
  			print "<B> (शाखा) एकूण:</B>";
  			print "</td>";
  			print "<td colspan='5' align='right'>";
  			print $marathinumber->getIndianCurrency($t_nett_amt);
  			print "</td>";
  							
  			print '<td align="right">';
  			print money_format('%!i', $t_nett_amt);
  			print "</td>";
  			/*print "</tr>"; 
        print "<tr> <td colspan=8>&nbsp</td>";
        print "</tr>";*/
        //sign of cane accountant
        print "</tr>";
        print "<tr>";
        print "<td colspan='5' height='50'>";
        print "केन अकाउंटंट :";
        print "</td>";
        print "<td colspan='5' height='50'>";
        print "चीफ अकाउंटंट :";
        print "</td>";
        print "<tr>";
    }
	?>
    <table cellspacing="0" border="0" width="100%" align="center">
 
<?

echo "<p class='page'>  </p>";
  echo '<h1 align="center">'.$compnameaRes[0]["COMP_NAME"].'</h1>';
  echo '<h2 align="center" style="border:medium; border-color:#000000"> Bank Branch Village Farmer Summary</h2>';
    //rate,bank_branch_sname,season_start_dt,season_end_dt,fortnight,bt_mname,season
    echo '<div class="box">
  <p class="item"> <b>हंगाम : </b>'.$row['SEASON'].'</p>
  <p class="item">&nbsp;</p>
  <p class="item">&nbsp;</p>
  <p class="item">&nbsp;</p>
  <p class="item">&nbsp;</p>
  <p class="item">&nbsp;</p>
  <p class="item">&nbsp;</p>
  <p class="item">&nbsp;</p>
  <p class="item">&nbsp;</p>
  <p class="item">&nbsp;</p>
  <p class="item">&nbsp;</p>
   <p class="item">&nbsp;</p>
  <p class="item">&nbsp;</p>
  <p class="item"><b> दर </b>:'.$row['RATE'].'</p>
   </div>';
   echo '<div class="box">
  <p class="item"><b>'.$row['BT_MNAME'].'</b></p>
  <p class="item">&nbsp;</p>
  <p class="item">&nbsp;</p>
  <p class="item">&nbsp;</p>
  
  <p class="item"> पंधरवडा &nbsp'.$row['FORTNIGHT'].' '.$row['SEASON_START_DT'].' - '.$row['SEASON_END_DT'].'</p>

  <p class="item"></p>
</div>';


?>      



<thead>
  <tr>
  <!--   <th align="left" width="20%">बँक</th>  --> 
    <th align="left" width="10%">गाव</th>
    <th align="right" width="05%">अनु क्र.</th>
    <th align="left" width="05%">बिल न.</th>
    <th align="left" width="05%">बागायतदार </th>
    <th align="left" width="15%">बागायतदारांचे नाव</th>
    <th align="left" width="10%">खाते क्र.</th>
    <th align="right" width="10%">रक्कम</th>
  </tr> 
</thead>

<?

    print '<tr style="outline: thin dotted">' ;
    // print '<td align="center">';
    print '</br><h3 align="center">';
    print "<b> ".$row['BR_CODE']." ". $row['BR_MNAME']." (". $row['BANK_BRANCH_SNAME'].")</b>";
    print "</h3>";
    /*print '<td align="left" >';
    print "<b> Bank".$row['BR_CODE']." ". $row['BR_MNAME']."</b>";
    print "</td>";*/
	
    $last_BR_CODE = $row['BR_CODE'];
    $g_nett_amt=$g_nett_amt+$t_nett_amt;
    $t_nett_amt=0;
    $t_COUNT=0;
  }
  else {
    print '<tr style="outline: thin dotted">';
    /*print "<td>";
    print "&nbsp";
    print "</td>"; */ 
  }
  if ($last_vl_code <> $row['VILLAGE_CODE']) {
    $count = $count + 1;
    //print '<tr  style="outline: thin solid">';
    print "<td align=left>";
    print "<b>".$row['VILLAGE_CODE']." ".$row['VL_MNAME']."</b>";
    print "</td>";
    //print "</tr>";
    $last_vl_code = $row['VILLAGE_CODE'];
    //$count=1;
  }
  else {
    print "<td>";
    print "&nbsp";
    print "</td>";  
  }

  print '<td align="right">';
  print $row['SR'];
  print "</td>";
  print '<td align="left">';
  print $row['BILL_NO'];
  print "</td>";
  print '<td align="left">';
  print $row['FARMER'];
  print "</td>";
  print '<td align="left">';
  print $row['PRT_MNAME'];
  print "</td>";
  print '<td align="left">';
  print $row['PRT_ACNO'];
  print "</td>";
  print '<td align="right">';
  print money_format('%!i', $row['NETT_AMT']);
  print "</td>";
  print "</tr>";
  $t_nett_amt=$row['NETT_AMT']+$t_nett_amt;
   $t_COUNT=$t_COUNT+1;
}
// Print transport totals
print '<tr style="font-weight: bold; outline: thin solid">'; 
/*print "<td>";
print $last_BR_CODE;
print "</td>";*/
print "<td align=left>";
print "<B> (शाखा)एकूण:</B>";
print "</td>";
print "<td colspan='5' align='right'>";
print $marathinumber->getIndianCurrency($t_nett_amt);
print "</td>";
print '<td align="right">';
print money_format('%!i', $t_nett_amt);
print "</td>";
print "</tr>";

//sign of cane accountant
  print "</tr>";
        print "<tr>";
        print "<td colspan='5' height='50'>";
        print "केन अकाउंटंट :";
        print "</td>";
        print "<td colspan='5' height='50'>";
        print "चीफ अकाउंटंट :";
        print "</td>";
        print "<tr>";

$g_nett_amt=$g_nett_amt+$t_nett_amt;

//Print grand totals
print '<tr style="font-weight: bold; outline: thin solid">';
/*print "<td>";
print "&nbsp";
print "</td>";*/
print "<td align='left'>";
print "<B>  एकूण</B>";
print "</td>";
print "<td>";
print "&nbsp";
print "</td>";
print "<td>";
print "&nbsp";
print "<td>";
print "<td>";
print "&nbsp";
print "</td>";
print "<td>";
print "&nbsp";
print "</td>";
print '<td align="right">';
print money_format('%!i', $g_nett_amt);
print "</td>";


?> 
</table>
</html>