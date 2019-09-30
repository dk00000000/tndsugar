<?php 
  require_once('dashboard.php');
  include('readfile.php');
 
  setlocale(LC_MONETARY, 'en_IN');
  $lgs = new Logs();
  $qryObj = new Query();
  $dsbObj = new Dashboard(); 
  $rfObj = new ReadFile();
  $lang=strtolower($_SESSION['LANG']);
  $qryPath = "util/readquery/general/canetype_group.ini";

  $menucode = $_GET['menu_code'];
 
  if($_POST['season']!=''){    
    $season=$_POST['season'];
    $seasontext = $season;
  } 
  else{
    $season='';
    $seasontext = 'सर्व';
  } 
  if($_POST['section']!=''){    
    $section = explode('-',$_POST['section']);
    $sectiontext = $section[1];
  } 
  else{
    $section='';
    $sectiontext='सर्व';
  } 
  if($_POST['fromdate']!=''){    
    $fromdate = $_POST['fromdate'];
    $fromdatetext = $fromdate;
  } 
  else{
    $fromdate='';
    $fromdatetext='सर्व';
  } 

  if($_POST['todate']!=''){    
    $todate = $_POST['todate'];
    $todatetext = $todate;
  } 
  else{
    $todate='';
    $todatetext='सर्व';
  } 

  if($_POST['area']!=''){    
    $area = $_POST['area'];
    if($area == 'R'){
      $areatext = 'Registered';
    }
    if($area == 'B'){
      $areatext = 'Balanced';
    }
    if($area == 'C'){
      $areatext = 'Cancelled';
    }
  } 
  else{
    $area='';
    $areatext='सर्व';
  } 

  $oldFilter = array(':PCOMP_CODE');
  $newFilter = array( $compcode);
  $compnameQry = $qryObj->fetchQuery($qryPath,'Q001','COMPNAME',$oldFilter,$newFilter);
  $compnameaRes = $dsbObj->getData($compnameQry);
 
  //GET PRINT DATA
  $oldFilter = array(':PCOMP_CODE',':PSEASON',':PSECTION',':PAREA',':PFROM_DATE',':PTO_DATE');
  $newFilter = array($_SESSION['COMP_CODE'],$season,$section[0],$area,$fromdate,$todate);

  $aOutPara="";
  $procedure = $qryObj->fetchQuery($qryPath,'Q001','PROCEDURE',$oldFilter,$newFilter);
  $procedureRes = $dsbObj->getOutProcData($procedure,$aOutPara);

  $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','SELECTQUERY',$oldFilter,$newFilter);
  $printdataRes = $dsbObj->getData($printdataQry);
  //echo $procedure."<br>".$printdataQry;

  $lgs->lg->trace("In canetype group report php  query: ".$printdataQry);
  $lgs->lg->trace("In canetype group report php  result: ".json_encode($printdataRes));
  
  $dedsummaryQry = $qryObj->fetchQuery($qryPath,'Q001','DEDUCTIONSUMMARY',$oldFilter,$newFilter);
  $dedsummaryRes = $dsbObj->getData($dedsummaryQry);
  $lgs->lg->trace("In canetype group Summary Query: ".$dedsummaryQry);
  $lgs->lg->trace("In canetype group Deduction Summary Result: ".$dedsummaryRes);

  $rowcnt = sizeof($printdataRes);
  if($rowcnt == 0){
    echo '<h3 align=center>Data Not Found !.</h3>';
    exit(0);
  }
  
  $matrix = Array();
  $ghead = Array();
  $rhead = Array();
  $chead = Array();
  $colname = Array();
  $area = Array();

  //echo "Looping on data";
  foreach ($printdataRes as $row)	{
	 $isection = $row['SECTION_NAME'];
    if (!is_array($matrix[$isection])) {
      $matrix[$isection] = Array();
      $ghead[$isection] = $row['SECTION_NAME'];
    }

    $imonth = $row['MONTH_DISP'];
    if (!is_array($matrix[$isection][$imonth])) {
      $matrix[$isection][$imonth] = Array();
    }

    $icv = $row['CV_NAME'];
    if (!is_array($matrix[$isection][$imonth][$icv])) {
      $matrix[$isection][$imonth][$icv] = Array();
    }

    $igp = $row['CT_GROUPNM'];
   /* if (!is_array($matrix[$isection][$imonth][$icv][$igp])) {
      //$matrix[$isection][$imonth][$icv][$igp] = Array();
    }
*/
    
    if (!is_array($rhead[$isection])) {
      $rhead[$isection] = Array();
    }
    $rhead[$isection][$imonth] = $row['MONTH_DISP'];

    $icv = $row['CV_NAME'];
    if (!is_array($chead[$isection])) {
      $chead[$isection] = Array();
    }
    if($row['CV_NAME']!='Total'){
      $chead[$isection][$icv] = $row['CV_NAME'];
    }

    if (!is_array($colname[$isection])) {
      $colname[$isection] = Array();
    }
    if($row['CT_GROUPNM']!=''){
      $colname[$isection][$igp] = $row['CT_GROUPNM'];
    }

    $matrix[$isection][$imonth][$icv][$igp] = $matrix[$isection][$imonth][$icv][$igp]+ $row['AREA'];
  }
 // print_r($matrix);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <link rel="icon" href="images/favicon.png" >
  <style>
    .page {
      page-break-before: always;            
      page-break-inside: avoid;
    }
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

<title>Month wise Variety wise Lagan type group (Lagan/Khodava) wise registered field area Summary</title>
</head>
<body style="font-size:13px; font-family:Verdana">

<?php

  foreach ($ghead as $gkey=>$gval) {
 
		print '<table cellspacing="0" border="0" width="100%">';
		print '<thead>';

		print '<h3 align="center" class="page">'.$compnameaRes[0]["COMP_NAME"].'</h3>';
		print '<h3 align="center">Month wise Variety wise Lagan type group (Lagan/Khodava) wise registered field area Summary</h3>';
    print '<p align="center"><b>हंगाम:</b> &nbsp;'.$seasontext.'&nbsp; &nbsp;<b>गट:</b> &nbsp;'.$sectiontext.'&nbsp;&nbsp;&nbsp; &nbsp; <b>दिनांक:</b> &nbsp;'.$fromdatetext.' - '.$todatetext.'&nbsp;&nbsp;&nbsp; &nbsp; <b> क्षेत्र:</b> &nbsp;'.$areatext.'</h3>';  
	
    print "<h3 align=center>";
    print $gval;                                        //section
    print "</h3>";

    print '<tr>';
		print '<td style="font-weight: bold" align="left" width="20%" rowspan=2>Plantation Month</td>'; 

		ksort($chead[$gkey]);
    //ksort($colname[$gkey]);
    foreach ($chead[$gkey] as $ckey=>$cvalue) {
			print '<td style="font-weight: bold" align="center" width="7%" colspan=2>'.$cvalue.'</td>';
    } 
    print '<td style="font-weight: bold" align="right" width="7%" rowspan=2>एकूण</td>';
    print '</tr>';

    print '<tr style="font-weight: bold">';
    $last_cv = '';
    foreach ($chead[$gkey] as $ckey=>$cvalue) {
      if($last_cv <> $cvalue){
        foreach ($colname[$gkey] as $colkey=>$colvalue) {
          print '<td align="right" width="6%" style="outline: thin dotted">'.$colvalue.'</td>';
        }
        $last_cv = $cvalue;
      }  
    }    
    print '</tr>';

    $total = array();
		foreach ($rhead[$gkey] as $rkey=>$rvalue) {
      if($rkey == 'Total'){
        //$month = 'एकूण';
        $tr = "<tr style='font-weight: bold; outline: thin solid'>";
      }
      else{
        //$month = $rkey;
        $tr = "<tr style='outline: thin dotted'>";
      }
			print $tr;
			print "<td align=left valign=top>";
			print $rkey;                                             //Plantation Month
			print "</td>";

      $rowtotal=0;
			foreach ($chead[$gkey] as $ckey=>$cvalue) {
				foreach($colname[$gkey] as $colkey=>$colvalue){
          print "<td align=right valign=top>";
          if (isset($matrix[$gkey][$rkey][$ckey][$colkey])) {
  					print number_format($matrix[$gkey][$rkey][$ckey][$colkey],2);
            $total[$ckey][$colkey] = $total[$ckey][$colkey] + $matrix[$gkey][$rkey][$ckey][$colkey];
            $rowtotal = $rowtotal + $matrix[$gkey][$rkey][$ckey][$colkey];
  				}
  				else {
  					print "0";
  				}
          print "</td>";
        }  
			}
      print "<td align=right valign=top>";
      print number_format($rowtotal,2);
      print "</td>";
			print "</tr>";
		}
		/*print "<tr style='font-weight: bold; outline: thin solid'>";
    print "<td align=left valign=top>";
    print 'एकूण :';
    print "</td>";
    $rowtotal = 0 ;
    foreach ($chead[$gkey] as $ckey=>$cvalue){
      foreach($colname[$gkey] as $colkey=>$colvalue){
       print "<td align=right valign=top>";
        if (isset($total[$ckey][$colkey])){
          print number_format($total[$ckey][$colkey],2);    
          $rowtotal = $rowtotal + $total[$ckey][$colkey];                    //Total
        }
        else{
          print "0";
        }
        print "</td>";
      }
    }
    print "<td align=right valign=top>";
    print number_format($rowtotal,2);
    print "</td>";
    print "</tr>";*/
}

?>
  
</table>
</body>
</html>