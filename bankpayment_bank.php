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
  $qryPath = "util/readquery/general/bankpayment_bank.ini";

   $menucode = $_GET['menu_code'];
   $compcode = 'DS';
   
    if($_POST['season']!= ''){    
       $season = $_POST['season'];
       $seasontext = $season;
    } 
    else{
       $season='';
       $seasontext = 'सर्व';
    } 

	$bnk_cbfcode = '';
    $bnk_cbfname = '';
	/*$bnk_ccode = '';
	$bnk_bcode = '';
	$bnk_fcode = '';
	$bnk_cname = '';
	$bnk_bname = '';
	$bnk_fname = '';*/
    if($_POST['contract_type'] != ''){  
	$bnk_cbfarr = $_POST['contract_type'];
    for($i=0;$i<sizeof($bnk_cbfarr);$i++){
        $bnk_cbf = explode('=',$bnk_cbfarr[$i]);
		$bnk_cbfcode=$bnk_cbfcode.','.$bnk_cbf[0];
        $bnk_cbfname=$bnk_cbfname.','.$bnk_cbf[1];
		
		/*$bnk_code = explode(':',$bnk_cbf[0]);
		$bnk_ccode=$bnk_ccode.','.$bnk_code[0];
		$bnk_bcode=$bnk_bcode.','.$bnk_code[1];
		$bnk_fcode=$bnk_fcode.','.$bnk_code[2];
		
		$bnk_name = explode(':',$bnk_cbf[1]);
		$bnk_cname=$bnk_cname.','.$bnk_name[0];
		$bnk_bname=$bnk_bname.','.$bnk_name[1];
		$bnk_fname=$bnk_fname.','.$bnk_name[2]; */       
      }
	  
	  $cbfcdstr = ltrim($bnk_cbfcode,',');
      $cbfnamestr = ltrim($bnk_cbfname,',');
	  
	  /*$ccdstr = ltrim($bnk_ccode,',');
	  $bcdstr = ltrim($bnk_bcode,',');
	  $fcdstr = ltrim($bnk_fcode,',');
	  
	  $cnmstr = ltrim($bnk_cname,',');
	  $bnmstr = ltrim($bnk_bname,',');
	  $fnmstr = ltrim($bnk_fname,',');*/
	  
    } 
    else{
	  $cbfcdstr='';
      $cbfnamestr='सर्व';
	  /*$ccdstr='';
	  $bcdstr='';
	  $fcdstr='';
	  $cnmstr='सर्व';
	  $bnmstr='सर्व';
	  $fnmstr='सर्व';*/
      
    } 
	
	//echo"Code=  ".$cbfcdstr."<br>";  //Required
	//echo $ccdstr."<br>".$bcdstr."<br>".$fcdstr."<br>";
	//echo"Name=  ".$cbfnamestr."<br>".$cnmstr."<br>".$bnmstr."<br>".$fnmstr."<br>";

    /*if($_POST['bill_type'] != ''){  
      $bill_type = explode('||',$_POST['bill_type']); 
      $bill_typetext = $bill_type[1]; 
    } 
    else{
      $bill_type='';
      $bill_typetext='सर्व';
    } 

    if($_POST['fortnight'] != ''){ 
      $fortnight = explode('||' ,$_POST['fortnight']);
      $fortnighttext = $fortnight[1];
    } 
    else{
      $fortnight='';
      $fortnighttext='सर्व';
    } */
    
    $bankcode = '';
    $bankname = '';
    if($_POST['bank']!=''){    
      $bankarr = $_POST['bank'];
      for($i=0;$i<sizeof($bankarr);$i++){
        $bank = explode('||',$bankarr[$i]);
        $bankcode=$bankcode.','.$bank[0];
        $bankname=$bankname.','.$bank[1];
      }
      $bcdstr = ltrim($bankcode,',');
      $bnamestr = ltrim($bankname,',');
    } 
    else{
      $bcdstr='';
      $bnamestr='सर्व';
    } 
	
	//echo $bcdstr;
   
  $oldFilter = array(':PCOMP_CODE');
  $newFilter = array( $compcode);
  $compnameQry = $qryObj->fetchQuery($qryPath,'Q001','COMPNAME',$oldFilter,$newFilter);
  $compnameaRes = $dsbObj->getData($compnameQry);

  $oldRptFilter = array(':PCOMP_CODE',':PTXN_SEASON',':PFORTNIGHT',':BANK_CODE',':PCT_CODE',':PBT_CODE');
  $newPrtFilter = array($compcode,$season,'',$bcdstr,$cbfcdstr,'');
 
  //Call Procedure
  $callProc = $qryObj->fetchQuery($qryPath,'Q001','PROCEDURE',$oldRptFilter,$newPrtFilter);
  $ProcRes = $dsbObj->getData($callProc);
  $lgs->lg->trace("In Bank wise Payment Procedure: ".$callProc);

  $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','SELECTQUERY',$oldRptFilter,$newPrtFilter);
  $printdataRes = $dsbObj->getData($printdataQry);
  //echo $callProc." <br>".$printdataQry;
  $lgs->lg->trace("In Bank wise Payment  query: ".$printdataQry);
  $lgs->lg->trace("In Bank wise Payment  result: ".json_encode($printdataRes));
  
  $rowcnt = sizeof($printdataRes);
  //Uncomment at last
  /*if($rowcnt ==0 ){
    echo "<h3 align='center'>Data Not Found !</h3>";
    exit(0);
  }*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="images/favicon.png">
<title>Bankwise Bank Payment Report</title>
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
  .page{
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
<body style="font-size:13px; font-family:Verdana, Arial, Helvetica, sans-serif">

<?php
$last_BANK_CODE='';
$i=0;
$g_amt=0;
$t_amt=0;
foreach ($printdataRes as $row) {
  if ($last_BANK_CODE <> $row['BANK_CODE']) {
    // if not first farmer print totals
    if ($last_BANK_CODE <> "") {
          //echo "Printing Totals"; 
  			print '<tr style="font-weight: bold; outline: thin solid">';                        
  			print "<td align=left>";
  			print "<b>(बँक) एकूण</b>";
  			print "</td>";
  			print "<td>";
  			print $marathinumber->getIndianCurrency($t_amt);
  			print "</td>";				
  			print '<td align="right">';
  			print money_format('%!i', $t_amt);
  			print "</td>";
        print '<td>';
        print '&nbsp;';
        print "</td>";
        print "</tr>";
        print "<tr>"; 
    }
	?>
  <table cellspacing="0" border="0" width="100%" align="center">
<?

  echo "<p class='page'>  </p>";
  
  echo '<h3 align="center" >'.$compnameaRes[0]["COMP_NAME"].'</h3>';
  echo '<h4 align="center" style="border:medium; border-color:#000000"> Bank wise Payment</h4>';
  /*echo '<p align="center" > <b>हंगाम : </b>'.$printdataRes[0]['SEASON'].'<b> &nbsp;&nbsp;पंधरवडा : &nbsp </b>'.$fnmstr.'<b>&nbsp;&nbsp; करार प्रकार : &nbsp;</b>'.$cnmstr.'<b> &nbsp;&nbsp; बिल : &nbsp; </b>'.$bnmstr.'<b> &nbsp;&nbsp; बँक : &nbsp; </b>'.$bnamestr.'</p>';*/  
  echo '<p align="center" > <b>हंगाम : </b>'.$printdataRes[0]['SEASON'].'<b>&nbsp;&nbsp; करार प्रकार : बिल : पंधरवडा : &nbsp;</b>'.$cbfnamestr.'<b> &nbsp;&nbsp; बँक : &nbsp; </b>'.$bnamestr.'</p>'; 

?>      
<thead>
  <tr>
    <th align="center" width="5%">अनु क्र.</th>
    <th align="left" width="40%">शाखा नाव</th>
    <th align="right" width="15%">रक्कम</th>
    <th align="center" width="22%">शेरा</th>
  </tr> 
</thead>

<?
    print '<tr style="outline: thin dotted">' ;
    print '<h3 align="center">';
    print "<b> ".$row['BANK_CODE']." ". $row['BANK_MNAME']." </b>";
    print "</h3>";
    	
    $last_BANK_CODE = $row['BANK_CODE'];
    $g_amt=$g_amt+$t_amt;
    $t_amt=0;
    $t_COUNT=0;
  }
  else {
    print '<tr style="outline: thin dotted">';
  }
 
  print '<td align="center">';
  print $row['SR'];
  print "</td>";
  print '<td align="left">';
  print $row['PRT_BANKBR']." - ".$row['BANK_BRANCH_NAME'];
  print "</td>";
  print '<td align="right">';
  print money_format('%!i', $row['AMT']);
  print "</td>";
  print '<td>';
  print '&nbsp;';
  print "</td>";
  print "</tr>";
  $t_amt=$row['AMT']+$t_amt;
   $t_COUNT=$t_COUNT+1;
}
// Print transport totals
print '<tr style="font-weight: bold; outline: thin solid">'; 
print "<td align=left>";
print "<b>(बँक) एकूण</b>";
print "</td>";
print "<td>";
print $marathinumber->getIndianCurrency($t_amt);
print "</td>";
print '<td align="right">';
print money_format('%!i', $t_amt);
print "</td>";
print '<td>';
print '&nbsp;';
print "</td>";
print "</tr>";

$g_amt=$g_amt+$t_amt;

//Print grand totals
print '<tr style="font-weight: bold; outline: thin solid">';
print "<td align='left'>";
print "<b>एकूण</b>";
print "</td>";
print "<td>";
print $marathinumber->getIndianCurrency($g_amt);
print "</td>";
print '<td align="right">';
print money_format('%!i', $g_amt);
print "</td>";
print '<td>';
print '&nbsp;';
print "</td>";
print "</tr>";

?> 
</table>
</html>