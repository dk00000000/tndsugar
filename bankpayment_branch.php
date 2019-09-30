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
  $qryPath = "util/readquery/general/bankpayment_branch.ini";

   $menucode = $_GET['menu_code'];
   $compcode = 'DS'; 

    if(isset($_POST['season'])){    
      $season = $_POST['season'];
      $seasontext = $season;
    } 
    else{
      $season='';
      $seasontext = 'सर्व';
    } 
	
	$bank_cbfcode = '';
    $bank_cbfname = '';
	/*$bank_ccode = '';
	$bank_bcode = '';
	$bank_fcode = '';
	$bank_cname = '';
	$bank_bname = '';
	$bank_fname = '';*/
    if($_POST['contract_type'] != ''){  
	$bank_cbfarr = $_POST['contract_type'];
    for($i=0;$i<sizeof($bank_cbfarr);$i++){
        $bank_cbf = explode('=',$bank_cbfarr[$i]);
		$bank_cbfcode=$bank_cbfcode.','.$bank_cbf[0];
        $bank_cbfname=$bank_cbfname.','.$bank_cbf[1];
		
		/*$bank_code = explode(':',$bank_cbf[0]);
		$bank_ccode=$bank_ccode.','.$bank_code[0];
		$bank_bcode=$bank_bcode.','.$bank_code[1];
		$bank_fcode=$bank_fcode.','.$bank_code[2];
		
		$bank_name = explode(':',$bank_cbf[1]);
		$bank_cname=$bank_cname.','.$bank_name[0];
		$bank_bname=$bank_bname.','.$bank_name[1];
		$bank_fname=$bank_fname.','.$bank_name[2];  */      
      }
	  
	  $cbfcdstr = ltrim($bank_cbfcode,',');
      $cbfnamestr = ltrim($bank_cbfname,',');
	  
	  /*$ccdstr = ltrim($bank_ccode,',');
	  $bcdstr = ltrim($bank_bcode,',');
	  $fcdstr = ltrim($bank_fcode,',');
	  
	  $cnmstr = ltrim($bank_cname,',');
	  $bnmstr = ltrim($bank_bname,',');
	  $fnmstr = ltrim($bank_fname,',');*/
	  
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
	  
    $bank_branchcode = '';
    $bank_branchname = '';
    if($_POST['bank_branch']!=''){    
      $bank_brancharr = $_POST['bank_branch'];
      for($i=0;$i<sizeof($bank_brancharr);$i++){
        $bank_branch = explode('||',$bank_brancharr[$i]);
        $bank_branchcode=$bank_branchcode.','.$bank_branch[0];
        $bank_branchname=$bank_branchname.','.$bank_branch[1];
      }
      $bbcdstr = ltrim($bank_branchcode,',');
      $bbnamestr = ltrim($bank_branchname,',');
    } 
    else{
      $bbcdstr='';
      $bbnamestr='सर्व';
    } 
	
	//echo $bbcdstr;
	
  $oldFilter = array(':PCOMP_CODE');
  $newFilter = array( $compcode);
  $compnameQry = $qryObj->fetchQuery($qryPath,'Q001','COMPNAME',$oldFilter,$newFilter);
  $compnameaRes = $dsbObj->getData($compnameQry);

  $oldRptFilter = array(':PCOMP_CODE',':PTXN_SEASON',':PFORTNIGHT',':PRT_BANKBR',':PCT_CODE',':PBT_CODE');
  $newPrtFilter = array($compcode,$season,'',$bbcdstr,$cbfcdstr,'');
 
  //Call Procedure
  $callProc = $qryObj->fetchQuery($qryPath,'Q001','PROCEDURE',$oldRptFilter,$newPrtFilter);
  $ProcRes = $dsbObj->getData($callProc);
  $lgs->lg->trace("In Bank Payment Branch Procedure: ".$callProc);

  $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','SELECTQUERY',$oldRptFilter,$newPrtFilter);
  $printdataRes = $dsbObj->getData($printdataQry);
  //echo $callProc." <br>".$printdataQry;
  $lgs->lg->trace("In Bank Payment Branch query: ".$printdataQry);
  $lgs->lg->trace("In Bank Payment Branch result: ".json_encode($printdataRes));

  $rowcnt = sizeof($printdataRes);
  //Uncomment atlast
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
<title> BranchWise Bank Payment Report </title>
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
$last_PRT_BANKBR='';
$i=0;
$g_nett_amt=0;
$t_nett_amt=0;
foreach ($printdataRes as $row) {
  if ($last_PRT_BANKBR <> $row['PRT_BANKBR']) {
    // if not first farmer print totals
    if ($last_PRT_BANKBR <> "") {
  			print '<tr style="font-weight: bold; outline: thin solid">';                        
  			print "<td align=left>";
  			print "<b>(शाखा)एकूण</b>";
  			print "</td>";
  			print "<td colspan='2' >";
  			print $marathinumber->getIndianCurrency($t_nett_amt);
  			print "</td>";				
  			print '<td align="right">';
  			print money_format('%!i', $t_nett_amt);
  			print "</td>";
        print '<td>';
        print '&nbsp;';
        print "</td>";
        print "</tr>";
        print "<tr>"; 

      print '<tr style="font-weight: bold">';
      print "<td align=left height='60' style=' vertical-align: text-center;' colspan=2>अकाउंटंट :";
      print "</td>";
      print "<td align=left height='60' style=' vertical-align: text-center;' colspan=2>चीफ अकाउंटंट :";
      print "</td>";
      print "</tr>";
    }
	?>
    <table cellspacing="0" border="0" width="100%" align="center">
 
<?

echo "<p class='page'>  </p>";
  
 echo '<h3 align="center" >'.$compnameaRes[0]["COMP_NAME"].'</h3>';
  echo '<h4 align="center" style="border:medium; border-color:#000000"> Branchwise Bank Payment </h4>';
  //echo '<p align="center" > <b>हंगाम : </b>'.$printdataRes[0]['SEASON'].'<b> &nbsp;&nbsp;पंधरवडा : &nbsp </b>'.$fnmstr.'<b>&nbsp;&nbsp; करार प्रकार : &nbsp;</b>'.$cnmstr.'<b> &nbsp;&nbsp; बिल : &nbsp; </b>'.$bnmstr.'<b> &nbsp;&nbsp; शाखा  : &nbsp; </b>'.$bbnamestr.'</p>';
  echo '<p align="center"> <b>हंगाम : </b>'.$printdataRes[0]['SEASON'].'<b>&nbsp;&nbsp; करार प्रकार : बिल : पंधरवडा :  &nbsp;</b>'.$cbfnamestr.'<b> &nbsp;&nbsp; शाखा  : &nbsp; </b>'.$bbnamestr.'</p>';

?>      
<thead>
  <tr>
    <th align="center" width="5%">अनु क्र.</th>
    <th align="left" width="37%">पार्टी कोड आणि पार्टी नाव</th>
    <th align="left" width="20%">खाते क्र.</th>
    <th align="right" width="20%">रक्कम</th>
    <th align="center" width="20%">शेरा</th>
  </tr> 
</thead>

<?

    print '<tr style="outline: thin dotted">' ;
    print '<h3 align="center">';
    print "<b> ".$row['PRT_BANKBR']." ". $row['BANK_BRANCH_MNAME']." (". $row['BANK_BRANCH_SNAME'].")</b>";
    print "</h3>";
	
    $last_PRT_BANKBR = $row['PRT_BANKBR'];
    $g_nett_amt=$g_nett_amt+$t_nett_amt;
    $t_nett_amt=0;
    $t_COUNT=0;
  }
  else {
    print '<tr style="outline: thin dotted">';
  }
 
  print '<td align="center">';
  print $row['SR'];
  print "</td>";
  print '<td align="left">';
  print $row['PARTY_CODE']." - ".$row['PRT_NAME'];
  print "</td>";
  print '<td align="left">';
  print $row['PRT_ACNO'];
  print "</td>";
  print '<td align="right">';
  print money_format('%!i', $row['NETT_AMT']);
  print "</td>";
  print '<td>';
  print '&nbsp;';
  print "</td>";
  print "</tr>";
  $t_nett_amt=$row['NETT_AMT']+$t_nett_amt;
   $t_COUNT=$t_COUNT+1;
}
// Print transport totals
print '<tr style="font-weight: bold; outline: thin solid">'; 
print "<td align=left>";
print "<b>(शाखा)एकूण</b>";
print "</td>";
print "<td colspan='2' >";
print $marathinumber->getIndianCurrency($t_nett_amt);
print "</td>";
print '<td align="right">';
print money_format('%!i', $t_nett_amt);
print "</td>";
print '<td>';
print '&nbsp;';
print "</td>";
print "</tr>";

$g_nett_amt=$g_nett_amt+$t_nett_amt;

//Print grand totals
print '<tr style="font-weight: bold; outline: thin solid">';
print "<td align='left'>";
print "<b>एकूण</b>";
print "</td>";
print "<td colspan=2>";
print $marathinumber->getIndianCurrency($g_nett_amt);
print "</td>";
print '<td align="right">';
print money_format('%!i', $g_nett_amt);
print "</td>";
print '<td>';
print '&nbsp;';
print "</td>";
print "</tr>";

print '<tr style="font-weight: bold">';
print "<td align=left height='60' style=' vertical-align: text-center;' colspan=2>अकाउंटंट :";
print "</td>";
print "<td align=left height='60' style=' vertical-align: text-center;' colspan=2>चीफ अकाउंटंट :";
print "</td>";
print "</tr>";

?> 
</table>
</html>