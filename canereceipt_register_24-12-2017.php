
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
 
    if(isset($_POST['season'])){    
      $season=$_POST['season'];
      $seasontext = $season;
    } 
    else{
       $season='';
       $seasontext = 'All';
    } 
      if(isset($_POST['fromdate'])){    
       $fromdate = $_POST['fromdate'];
       $fromdatetext = $fromdate;
    } 
    else{
       $fromdate='';
       $fromdatetext = 'All';
    } 
      if(isset($_POST['todate'])){    
      $todate = $_POST['todate'];
      $todatetext = $todate;
    } 
    else{
       $todate='';
       $todatetext = 'All';
    } 
    if(isset($_POST['section'])){    
      $section = $_POST['section'];
      $sectiontext = $section;
    } 
    else{
       $section='';
       $sectiontext='All';
    } 
     if(isset($_POST['farmer'])){    
      $farmer = $_POST['farmer'];
      $farmertext = $farmer;
    } 
    else{
       $farmer='';
       $farmertext = 'All';
     } 

    $divison='SUGR';
    $location='JSML';
   /* $season='2017-18';
    $fromdate = '20170101';
    $todate = '20171110';
    $section = ''; //G03
    $farmer = ''; //G004315*/

  $oldFilter = array(':PCOMP_CODE');
  $newFilter = array( $compcode);
  $compnameQry = $qryObj->fetchQuery($qryPath,'Q001','COMPNAME',$oldFilter,$newFilter);
  $compnameaRes = $dsbObj->getData($compnameQry);

  //GET PRINT DATA
  $oldFilter = array(':PCOMP_CODE',':PLOC_CODE',':PTXN_DIVN',':PSC_CODE',':PPRT_CODE',':PFR_DATE',':PTO_DATE',':PTXN_SEASON');
  $newFilter = array( $compcode,$location,$divison,$section,$farmer,$fromdate,$todate,$season);
 
  $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','SELECTQUERY',$oldFilter,$newFilter);
  $printdataRes = $dsbObj->getData($printdataQry);
  $lgs->lg->trace("In Farmer cane recipt report php  query: ".$printdataQry);
  $lgs->lg->trace("In Farmer cane recipt php  result: ".json_encode($printdataRes));

  //echo $printdataQry;
  //print_r($printdataRes);

 /* $sectionQry = $qryObj->fetchQuery($qryPath,'Q001','SECTIONQRY',$oldFilter,$newFilter);
  $sectionRes = $dsbObj->getData($sectionQry);
  $lgs->lg->trace("In shift report php  query: ".$sectionQry);
  $lgs->lg->trace("In shift report php  result: ".json_encode($sectionRes));*/


  //echo $sectionQry;
  //print_r($sectionRes);
 
 
  $rowcnt = sizeof($printdataRes);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jqu   ery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
 <style type="text/css" media="print">
    /* Set height of the grid so .sidenav can be 100% (adjust if needed) */
    .row.content {height: 1500px}
    
    /* Set gray background color and 100% height */
    .sidenav {
/*      background-color: #f1f1f1;*/
      height: 100%;
    }
    
    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }
    
    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height: auto;} 
    }

    table {
    table-layout: auto;
    }
	
	
	body {
                background: #eaeaed;
                -webkit-print-color-adjust: exact;
            }
            .my-footer{
                background: #2db34a;
                bottom: 0;
                left: 0;
                position: fixed;
                right: 0;
            }
            .my-header {
                
                top: 0;
                left: 0;
                position: fixed;
                right: 0;
            }

       .page
      {
        page-break-before: always; 
       
        
        page-break-inside: avoid;
      }
      .tableprint{
      	page-break-after: auto;
      }
  </style>
  
<title>Farmer Wise Cane Recipt Report </title>
</head>
<body style="font-size:13px">
<!-- <div class="col-md-12 col-sm-12 col-xs-12"> -->


<?php
// Fetch the results of the query
$last_sc_code='';
$last_prt_code='';
$last_date='';

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
			    //echo "Printing Totals";
				print '<tr style="outline: thin solid">';
				print "<td>";
				print "$last_sc_code";
				print "</td>";
				print "<td colspan=2>";
				print "Total for Section:";
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
				print "</tr>";
				
			}
		//print "<div style='page-break-before:always'>";
			
		?>
        
  <table border=1 cellspacing="0" border="0" >
  <colgroup width="8%"></colgroup>
  <colgroup width="8%"></colgroup>
  <colgroup width="8%"></colgroup>
  <colgroup width="8%"></colgroup>
  <colgroup width="8%"></colgroup>
  <colgroup width="22%"></colgroup>
  <colgroup width="8%"></colgroup>
  <colgroup width="8%"></colgroup>
  <colgroup width="22%"></colgroup>
 
 <thead> 
  <tr>
    <th style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" width="8%">तारीख</th>
    <!--<th class=xl66 height=32 class=xl65 width=70 style='height:24.0pt;width:53pt'>स्लिप नं.</th>-->
	<th style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" width="8%">स्लिप नं.</th>
    <th style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" width="8%">वाहन प्रकार</th>
    <th style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" width="8%">निव्वळ वजन</th>
    <th style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" width="8%">कोड  नं.</th>
    <th style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" width="22%">वाहतूकदार /मुकादम</th>
    <th style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" width="8%">वाहन /टायर नंबर </th>
    <th style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" width="8%">कोड  नं.</th>
    <th style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" width="22%">तोडणीदार </th>
  </tr>
</thead>
		<?php
		//echo "Ankush";
		echo "<p class='page'>  </p>";
		echo '<h1 align="center">'.$compnameaRes[0]["COMP_NAME"].'</h1>';
	echo '<h2 align="center" style="border:medium; border-color:#000000"> Farmer Wise Cane Recipt Report </h2>';
          echo '<h3 align="center" style="border:medium; border-color:#000000" >
                <b>Filters:</b> From Season: '.$seasontext.', From Date: '.$fromdatetext.', To Date: '.$todatetext.', For Section: '.$sectiontext.', For Farmer: '.$farmertext.' </h3>';
		
		print '<tr  style="outline: thin solid">';
		print "<th colspan=9 align=left>";
		print $row['SC_CODE']." ".$row['SC_MNAME'];
		print "</th>";
		print "</tr>";
		//print "</div>";
		$last_sc_code = $row['SC_CODE'];
		$g_TOTAL_RECEIVED_WT=$g_TOTAL_RECEIVED_WT+$s_TOTAL_RECEIVED_WT;
		$s_TOTAL_RECEIVED_WT=0;
		$last_prt_code='';
		
	}
if ($last_prt_code <> $row['PRT_CODE']) {
		// if not first farmer print totals
		if ($last_prt_code <> "") {
			    //echo "Printing Totals";
				print '<tr style="outline: thin solid">';
				print "<td>";
				print "(".$t_SLIP_COUNT.")";
				print "</td>";
				print "<td colspan=2>";
				print "Total for Farmer:";
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
		
		print '<tr  style="outline: thin solid">';
		print "<th colspan=8 align=left>";
		print $row['PRT_CODE']." ".$row['PRT_MNAME'];
		print "</th>";
		print "</tr>";
		$last_prt_code = $row['PRT_CODE'];
		$s_TOTAL_RECEIVED_WT=$s_TOTAL_RECEIVED_WT+$f_TOTAL_RECEIVED_WT;
		$f_TOTAL_RECEIVED_WT=0;
		$t_SLIP_COUNT=0;
		$last_date='';
	}
	if ($last_date <> $row['WS_DATE']) {
		// if not first farmer print totals
		print '<tr  style="outline: thin solid">';
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
	print $row['TOTAL_RECEIVED_WT'];
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
	
	print '<tr style="outline: thin solid">';
	print "<td>";
	print "(".$t_SLIP_COUNT.")";
	print "</td>";
	print "<td colspan=2>";
	print "Total for Farmer:";
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
	print '<tr style="outline: thin solid"  >';
	print "<td>";
	print "$last_sc_code";
	print "</td>";
	print "<td colspan=2>";
	print "Total for Section:";
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
?>
  
</table>
  
<!-- </div>
 -->
<p id="data">  </p>


<br />
<br />
<br /> 

   <div class="ln_solid"></div>
      <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">  
         <a href='javascript:history.back(1);'>Back</a>
      </div>
   </div>  
   
 </body>
</html>
<!-- <script>
    function(){}
    	alert('**');
        // Wrap each tr and td's content within a div
        // (todo: add logic so we only do this when printing)
        //$("table th, table td").wrapInner("<div></div>");
    }
</script> -->
