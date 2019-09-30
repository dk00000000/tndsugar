
<?php 
  //session_start();
  require_once('dashboard.php');
 
  include('readfile.php');
  //require_once('header_menu.php');
  
  $lgs = new Logs();
  $qryObj = new Query();
  $dsbObj = new Dashboard(); 
  $rfObj = new ReadFile();
  $lang=strtolower($_SESSION['LANG']);
  $qryPath = "util/readquery/general/canereceipt_register.ini";

   $menucode = $_GET['menu_code'];
  //$compcode = $_SESSION['COMP_CODE'];
   $compcode = 'DS';
 
    if($_POST['season']!=''){    
      $season=$_POST['season'];
      $seasontext = $season;
    } 
    else{
       $season='';
       $seasontext = 'सर्व';
    } 
      if($_POST['fromdate']!=''){    
       $fromdate = $_POST['fromdate'];
       $fromdatetext = $fromdate;
    } 
    else{
       $fromdate='';
       $fromdatetext = 'सर्व';
    } 
      if($_POST['todate']!=''){    
      $todate = $_POST['todate'];
      $todatetext = $todate;
    } 
    else{
       $todate='';
       $todatetext = 'सर्व';
    } 

    if($_POST['section']!=''){    
      $section = explode('-',$_POST['section']);
      $sectiontext = $section[1];
    } 
    else{
       $section='';
       $sectiontext='सर्व';
    } 
     if($_POST['farmer']!=''){    
      $farmer = $_POST['farmer'];
      $farmertext = $farmer;
    } 
    else{
       $farmer='';
       $farmertext = 'सर्व';
     } 
    //$divison='SUGR';
    //$location='DUND';
   
  $oldFilter = array(':PCOMP_CODE');
  $newFilter = array( $compcode);
  $compnameQry = $qryObj->fetchQuery($qryPath,'Q001','COMPNAME',$oldFilter,$newFilter);
  $compnameaRes = $dsbObj->getData($compnameQry);

  //GET PRINT DATA
  $oldFilter = array(':PCOMP_CODE',':PSC_CODE',':PPRT_CODE',':PFR_DATE',':PTO_DATE',':PTXN_SEASON');
  $newFilter = array($compcode,$section[0],$farmer,$fromdate,$todate,$season);

  $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','SELECTQUERY',$oldFilter,$newFilter);
  //echo $printdataQry;
  $printdataRes = $dsbObj->getData($printdataQry);
  $lgs->lg->trace("In Farmer cane recipt report php  query: ".$printdataQry);
  $lgs->lg->trace("In Farmer cane recipt php  result: ".json_encode($printdataRes));
  $rowcnt = sizeof($printdataRes);

  //echo $printdataQry;
  if($rowcnt == 0){
  	echo '<h3 align=center>Data Not Found. !</h3>';
  	exit(0);
  }
?>

<!DOCTYPE>
<html>
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <style type="text/css" media="print">
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
	  h1,h2,h3,h4{
	    line-height:0.2em;
	  }
      .page{   
        page-break-before: always; 
  	    page-break-inside: avoid;
      }
  </style>
  
<title>Farmer Wise Cane Receipt Register-Detail</title>
<link rel="icon" href="images/favicon.png" />
</head>
<body style="font-family:verdana; font-size:13px;">


<?php
// Fetch the results of the query
$last_sc_code='';
$last_prt_code='';
$last_date='';
$section_name='';

$i=1;
$j=1;
$g_TOTAL_RECEIVED_WT=0;
$s_TOTAL_RECEIVED_WT=0;
$f_TOTAL_RECEIVED_WT=0;
$t_SLIP_COUNT=0;

//while ($row = $printdataRes) {
foreach ($printdataRes as $row)	{ 
	if ($last_sc_code <> $row['SC_CODE']) {
		// if not first section print totals
		if ($last_sc_code <> "") {
			if ($last_prt_code <> "") {
				//echo "Printing Totals";
				print '<tr style="font-weight: bold; font-size: 12px; font-family:verdana outline: thin dotted">';
				print "<td>";
				print "(".$t_SLIP_COUNT.")";
				print "</td>";
				print "<td colspan=2>";
				print "(बागायतदार) एकूण:"; //Total for Farmer:
				print "</td>";
				print '<td  align="right">';
				print $f_TOTAL_RECEIVED_WT;
				print "</td>";
				print "<td>";
				print "&nbsp";
				print "</td>";
				print "<td>";
				print "&nbsp";
				print "</td>";
				print "<td>";
				print "&nbsp";
				print "</td>";
				print "<td>";
				print "&nbsp";
				print "</td>";
				print "<td>";
				print "&nbsp";
				print "</td>";
				print "</tr>";
			}	
		    //echo "Printing Totals";
			print '<tr style="font-weight: bold; font-size: 12px; font-family:verdana outline: thin solid">';
			print "<td colspan=2>";
			print "$section_name";
			print "</td>";
			print "<td>";
			print "(गट) एकूण:";//Total for Section:
			print "</td>";
			print '<td  align="right">';
			print $s_TOTAL_RECEIVED_WT;
			print "</td>";
			print "<td>";
			print "&nbsp";
			print "</td>";
			print "<td>";
			print "&nbsp";
			print "</td>";
			print "<td>";
			print "&nbsp";
			print "</td>";
			print "<td>";
			print "&nbsp";
			print "</td>";
			print "<td>";
			print "&nbsp";
			print "</td>";
			print "</tr>";

			$last_prt_code = '';
			$last_sc_code = '';
			$section_name='';
		}		
	?>
        
 <table cellspacing="0" border="0" align="center" width="100%">
 
 <!-- <thead> 
  <tr>
    <th align="left" width="8%">तारीख</th>
    <th align="left" width="8%">स्लिप नं.</th>
    <th align="left" width="8%">वाहन प्रकार</th>
    <th align="right" width="8%">निव्वळ वजन</th>
    <th align="left" width="8%">कोड नं.</th>
    <th align="left" width="22%">वाहतूकदार /मुकादम</th>
    <th align="left" width="8%">वाहन /टायर नंबर </th>
    <th align="left" width="8%">कोड नं.</th>
    <th align="left" width="22%">तोडणीदार </th>
  </tr>
</thead> -->
<?php
	echo "<p class='page'>  </p>";
	echo '<h3 align="center">'.$compnameaRes[0]["COMP_NAME"].'</h3>';
    echo '<h4 align="center" style="border:medium; border-color:#000000">Farmer Wise Cane Receipt Register-Detail</h4>';
    echo '<p align="center">
            <b>हंगाम: </b>'.$seasontext.'&nbsp;&nbsp;&nbsp; <b>तारीख:</b> '.$fromdatetext.'-'.$todatetext.' <b>&nbsp;&nbsp;&nbsp; गट:</b> '.$sectiontext.' &nbsp;&nbsp;&nbsp;<b>बागायतदार:</b> '.$farmertext.' </p>';
	
	print '<tr  style="outline: thin solid; font-size: 13px; font-family:verdana">'; 
	print "<th colspan=9 align=left>";
	print $row['SC_CODE']." ".$row['SC_MNAME'];
	print "</th>";
	print "</tr>";

	$last_sc_code = $row['SC_CODE'];
	$section_name = $row['SC_CODE']." ".$row['SC_MNAME'];
	$g_TOTAL_RECEIVED_WT=$g_TOTAL_RECEIVED_WT+$s_TOTAL_RECEIVED_WT;
	$s_TOTAL_RECEIVED_WT=0;
	$last_prt_code='';
	
}
if ($last_prt_code <> $row['PRT_CODE']) {
		// if not first farmer print totals
		if ($last_prt_code <> "") {
			//echo "Printing Totals";
			print '<tr style="font-weight: bold; font-size: 12px; font-family:verdana outline: thin dotted">';
			print "<td>";
			print "(".$t_SLIP_COUNT.")";
			print "</td>";
			print "<td colspan=2>";
			print "(बागायतदार) एकूण:"; //Total for Farmer
			print "</td>";
			print '<td  align="right">';
			print $f_TOTAL_RECEIVED_WT;
			print "</td>";
			print "<td>";
			print "&nbsp";
			print "</td>";
			print "<td>";
			print "&nbsp";
			print "</td>";
			print "<td>";
			print "&nbsp";
			print "</td>";
			print "<td>";
			print "&nbsp";
			print "</td>";
			print "<td>";
			print "&nbsp";
			print "</td>";
			print "</tr>";
			print "</table>";

			print '<table cellspacing="0" border="1" width="85%" align="center">';
			print '<thead>
				   <tr style="font-size: 13px; font-family:verdana">
				   <th align="center" width="8%">प्लॉट नं.</th>
				   <th align="center" width="8%">सर्वे नं.</th>
				   <th align="center" width="20%">लागण तारीख</th>
				   <th align="center" width="15%">उसाचा प्रकार</th>
				   <th align="center" width="15%">जात</th>
				   <th align="center" width="8%">क्षेत्र</th>
				   <th align="center" width="8%">स्लिप संख्या</th>
				   <th align="center" width="8%">निव्वळ वजन</th>
				   </tr></thead>';

			foreach ($summaryRes as $res) {

				print '<tr style="outline: thin dotted; font-size: 12px; font-family:verdana">';
				$data = explode('/', $res['PLOT_NUMBER']);
				print "<td align='center'>";
				print $data[0];
				print "</td>";
				print "<td align='center'>";
				print $data[1];
				print "</td>";
				print "<td align='center'>";
				print $res['PLANTATION_DATE'];
				print "</td>";
				print "<td align='center'>";
				print $res['TYPE_CODE'].' '.$res['TYPE_MNAME'];
				print "</td>";
				print "<td align='center'>";
				print $res['VAR_CODE'].' '.$res['VAR_NAME'];
				print "</td>";
				print "<td align='center'>";
				print number_format((float)$res['BALANCED_AREA'], 1, '.', '');
				print "</td>";
				print "<td align='center'>";
				print $res['SLIP_CNT'];
				print "</td>";
				print "<td align='center'>";
				print $res['TOTAL_RECEIVED_WT'];
				print "</td>";
				print "</tr>";	
			}	
			print "</table>";
		}
		//print '<br/>';
		print '<table cellspacing="0" border="1" width="100%" align="center">';
		print '<thead>
			   <tr style="font-size: 12px; font-family:verdana">
		       <th align="left" width="8%">तारीख</th>
			   <th align="left" width="9%">स्लिप नं.</th>
			   <th align="left" width="9%">वाहन प्रकार</th>
			   <th align="right" width="9%">निव्वळ वजन</th>
			   <th align="left" width="8%">कोड नं.</th>
			   <th align="left" width="19%">वाहतूकदार /मुकादम</th>
			   <th align="left" width="11%">वाहन नंबर</th>
			   <th align="left" width="8%">कोड नं.</th>
			   <th align="left" width="19%">तोडणीदार</th></tr></thead>';

		print '<tr style="font-size: 12px; font-family:verdana; font-weight:bold">';
		print "<td colspan=9 align=left>";
		print $row['PRT_CODE']." ".$row['PRT_MNAME']." ".'('.$row['SHIVAR_MNAME'].')';
		print "</td>";
		print "</tr>";

		$last_prt_code = $row['PRT_CODE'];

		$s_TOTAL_RECEIVED_WT=$s_TOTAL_RECEIVED_WT+$f_TOTAL_RECEIVED_WT;
		$f_TOTAL_RECEIVED_WT=0;
		$t_SLIP_COUNT=0;
		$last_date='';

		$oldF = array(':PCOMP_CODE',':PPRT_CODE',':PFR_DATE',':PTO_DATE',':PTXN_SEASON');
        $newF = array($compcode,$last_prt_code,$fromdate,$todate,$season);

        $summaryQry = $qryObj->fetchQuery($qryPath,'Q001','SUMMARY',$oldF,$newF);
        $summaryRes = $dsbObj->getData($summaryQry);
	}

	if ($last_date <> $row['WS_DATE']) {
		// if not first farmer print totals
		print '<tr style="outline: thin dotted; font-size: 12px; font-family:verdana">';
		print "<td>";
		print $row['WS_DATE'];
		print "</td>";
		$last_date = $row['WS_DATE'];
		$last_date='';
	}
	print "<td>";
	print $row['TXN_SRNO'];
	print "</td>";
	print "<td>";
	print $row['VEHICLE_TYPE'];
	print "</td>";
	print '<td align="right">';
	print number_format($row['TOTAL_RECEIVED_WT'],3);
	print "</td>";
	print "<td>";
	print $row['TRNS_CODE'];
	print "</td>";
	print "<td>";
	print $row['TRNS_NAME'];
	print "</td>";
	print "<td>";
	print $row['TXN_VHNO'];
	print "</td>";
	print "<td>";
	print $row['HARV_CODE'];
	print "</td>";
	print "<td>";
	print $row['HARV_NAME'];
	print "</td>";
	print "</tr>";
    
	$f_TOTAL_RECEIVED_WT=$row['TOTAL_RECEIVED_WT']+$f_TOTAL_RECEIVED_WT;
	$t_SLIP_COUNT=$t_SLIP_COUNT+1;

}
	
//Printing Farmer Total
$f_TOTAL_RECEIVED_WT=$f_TOTAL_RECEIVED_WT+$d_TOTAL_RECEIVED_WT;

print '<tr style="font-weight: bold; font-size: 12px; font-family:verdana outline: thin dotted">';
print "<td>";
print "(".$t_SLIP_COUNT.")";
print "</td>";
print "<td colspan=2>";
print "(बागायतदार) एकूण:";	//Total for Farmer:
print "</td>";
print '<td  align="right">';
print $f_TOTAL_RECEIVED_WT;
print "</td>";
print "<td>";
print "&nbsp";
print "</td>";
print "<td>";
print "&nbsp";
print "</td>";
print "<td>";
print "&nbsp";
print "</td>";
print "<td>";
print "&nbsp";
print "</td>";
print "<td>";
print "&nbsp";
print "</td>";
print "</tr>";

//"Printing Section Totals";
$s_TOTAL_RECEIVED_WT=$s_TOTAL_RECEIVED_WT+$f_TOTAL_RECEIVED_WT;

print '<tr style="font-weight: bold; font-size: 12px; font-family:verdana outline: thin dotted">';
print "<td colspan=2>";
print "$section_name";
print "</td>";
print "<td>";
print "(गट) एकूण:"; //Total for Section
print "</td>";
print '<td  align="right">';
print $s_TOTAL_RECEIVED_WT;
print "</td>";
print "<td>";
print "&nbsp";
print "</td>";
print "<td>";
print "&nbsp";
print "</td>";
print "<td>";
print "&nbsp";
print "</td>";
print "<td>";
print "&nbsp";
print "</td>";
print "<td>";
print "&nbsp";
print "</td>";
print "</tr>";

print "</table>";

print '<table cellspacing="0" border="1" width="85%" align="center">';
print '<thead>
	   <tr style="font-size: 12px; font-family:verdana">
       <th align="center" width="8%">प्लॉट नं.</th>
       <th align="center" width="8%">सर्वे नं.</th>
	   <th align="center" width="20%">लागण तारीख</th>
	   <th align="center" width="15%">उसाचा प्रकार</th>
	   <th align="center" width="15%">जात</th>
	   <th align="center" width="8%">क्षेत्र</th>
	   <th align="center" width="8%">स्लिप संख्या</th>
	   <th align="center" width="8%">निव्वळ वजन</th></tr></thead>';

foreach ($summaryRes as $res) {

	print '<tr style="outline: thin dotted; font-size: 12px; font-family:verdana">';
	$data = explode('/', $res['PLOT_NUMBER']);
	print "<td align='center'>";
	print $data[0];
	print "</td>";
	print "<td align='center'>";
	print $data[1];
	print "</td>";
	print "<td align='center'>";
	print $res['PLANTATION_DATE'];
	print "</td>";
	print "<td align='center'>";
	print $res['TYPE_CODE'].' '.$res['TYPE_MNAME'];
	print "</td>";
	print "<td align='center'>";
	print $res['VAR_CODE'].' '.$res['VAR_NAME'];
	print "</td>";
	print "<td align='center'>";
	print $res['BALANCED_AREA'];
	print "</td>";
	print "<td align='center'>";
	print $res['SLIP_CNT'];
	print "</td>";
	print "<td align='center'>";
	print number_format($res['TOTAL_RECEIVED_WT'],3);
	print "</td>";
	print "</tr>";	
}	

?>
  
</table>
   
 </body>
</html>
