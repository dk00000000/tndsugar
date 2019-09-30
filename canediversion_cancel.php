<?php 
  require_once('dashboard.php');
  include('readfile.php');
 
  setlocale(LC_MONETARY, 'en_IN');
  $lgs = new Logs();
  $qryObj = new Query();
  $dsbObj = new Dashboard(); 
  $rfObj = new ReadFile();
  $lang=strtolower($_SESSION['LANG']);
  $qryPath = "util/readquery/general/canediversion_cancel.ini";

  $menucode = $_GET['menu_code'];
 
  if($_POST['season']!=''){    
    $season=$_POST['season'];
    $seasontext = $season;
  } 
  else{
    $season='';
    $seasontext = 'सर्व';
  } 
 
  if($_POST['date']!=''){    
    $date = $_POST['date'];
    $datetext = $date;
  } 
  else{
    $date='';
    $datetext='सर्व';
  } 

  	$canecode = '~';
    $canename = '';
    if($_POST['cane']!=''){    
      $canearr = $_POST['cane'];
      for($i=0;$i<sizeof($canearr);$i++){
        $cane = explode('||',$canearr[$i]);
        $canecode.=$cane[0].'~';
        $canename.=','.$cane[1];
      }
      //$ccdstr = ltrim($canecode,'~');
      $cnamestr = ltrim($canename,',');
    } 
    else{
      $canecode='';
      $cnamestr='सर्व';
    } 

  $oldFilter = array(':PCOMP_CODE',':PSEASON',':PDATE',':PCANETYPE',':PTYPE');
  $newFilter = array( $_SESSION['COMP_CODE'],$_SESSION['SEASON'],$date,$canecode,'CANE_DIVR_SEC');
  
  $compnameQry = $qryObj->fetchQuery($qryPath,'Q001','COMPNAME',$oldFilter,$newFilter);
  $compnameaRes = $dsbObj->getData($compnameQry);

  $aOutPara="";
  $procedure = $qryObj->fetchQuery($qryPath,'Q001','PROCEDURE',$oldFilter,$newFilter);
  $procedureRes = $dsbObj->getOutProcData($procedure,$aOutPara);
  
  $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','SELECTQUERY1',$oldFilter,$newFilter);
  $printdataRes = $dsbObj->getData($printdataQry);
  echo $procedure."<br>".$printdataQry;

  $lgs->lg->trace("Cane Diversion/Cancellation report Procedure: ".$procedure);
  $lgs->lg->trace("Cane Diversion/Cancellation report php  query: ".$printdataQry);

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
  $headings = Array();
  $headers = Array('आडसाली','पूर्व हंगामी','सुरु','खोडवा','बेणे खोडवा','एकूण');

  foreach ($printdataRes as $row)	{

    array_push($headings,$row['REASON_NAME']);
    $headings = array_unique($headings);

    $idivision = $row['DIVISION_CODE'];
    if (!is_array($matrix[$idivision])) {
      $matrix[$idivision] = Array();
      //if($row['DIVISION_NAME']!='Report Total'){
       $ghead[$idivision] = $row['DIVISION_NAME'];
      //}
    }

    $isection = $row['SECTION_NAME'];
    if (!is_array($matrix[$idivision][$isection])) {
      $matrix[$idivision][$isection] = Array();
    }

    $iweek = $row['REASON_CODE'];
    if (!is_array($matrix[$idivision][$isection][$iweek])) {
      $matrix[$idivision][$isection][$iweek] = Array();
    }

    $ict = $row['CTYPE_CODE'];
    
    if (!is_array($rhead[$idivision])) {
      $rhead[$idivision] = Array();
    }
    //if($row['SECTION_NAME']!=''){
      $rhead[$idivision][$isection] = $row['SECTION_NAME'].'||'.$row['TONNAGE'].'||'.$row['PER_TONN'];
    //}  

    $iweek = $row['REASON_CODE'];
    if (!is_array($chead[$idivision])) {
      $chead[$idivision] = Array();
    }
    //if($row['WEEK_NAME']!='Total'){
      $chead[$idivision][$iweek] = $row['REASON_NAME'];
    //}

    if (!is_array($colname[$idivision])) {
      $colname[$idivision] = Array();
    }
    //if($row['CTYPE_NAME']!=''){
      $colname[$idivision][$ict] = $row['CTYPE_NAME'];
    //}

    $matrix[$idivision][$isection][$iweek][$ict] = $matrix[$idivision][$isection][$iweek][$ict]+ $row['AREA'];
  }
  //print_r($matrix);
  //exit(0);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <link rel="icon" href="images/favicon.png" />
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

<title>गाळप हंगाम करीता चारा, गु-हाळ व पाण्याअभावी कमी झालेल्या ऊस क्षेत्राचा रिपोर्ट</title>
</head>
<body style="font-size:13px; font-family:Verdana">

<?php
  
  print '<h3 align="center" class="page">'.$compnameaRes[0]["COMP_NAME"].'</h3>';
  print '<h3 align="center">गाळप हंगाम करीता चारा, गु-हाळ व पाण्याअभावी कमी झालेल्या ऊस क्षेत्राचा रिपोर्ट</h3>';
  print '<p align="center"> <b>हंगाम :</b>&nbsp;'.$seasontext.'<b>&nbsp;&nbsp;दिनांक  :&nbsp </b>'.$datetext.'<b>&nbsp;&nbsp; कारण :&nbsp;</b>'.$cnamestr.'</p>';  

  print '<table cellspacing="0" border="0" width="100%">';
  print '<thead>';
  print '<tr style="outline: thin solid; font-weight: bold">';
  print '<td align="left"   width="10%" rowspan=2>गट</td>';
  foreach ($headings as $key => $value) {
    if(strpos($value,'UPTO')==0){
      $value = str_replace('UPTO','एकूण शिल्लक ऊस नोंद क्षेत्र',$value);
    }
    print '<td align="center" width="20%" colspan=6>'.$value.'</td>';
  } 
  print '<td align="center" width="10%" rowspan=2>अपेक्षित टनेज हे/आर</td>';
  print '<td align="right" width="10%" rowspan=2>अंदाजे हेक्टरी उत्पादन</td>';
  print '</tr>';
  print '<tr style="font-weight: bold">';
  for ($i=0; $i<count($headings); $i++) { 
    foreach ($headers as $key => $value) {
      print '<td align="right" width="5%" style="outline: thin dotted">'.$value.'</td>';  
    } 
  }  
  print '</tr>';
  print '</thead>';   
  $grandtotal = array();
  $expected_tonnage = 0;
  $approx_tonnage = 0;
  $grand_expected_tonnage = 0;
  $grand_approx_tonnage = 0;
  foreach ($ghead as $gkey=>$gval) {
    if(strpos($gval,'Reort Total')!=0){
      print "<tr style='outline: thin solid; font-weight: bold'>";
      print "<td align=left valign=top colspan=11>";
      print 'विभाग: '.$gval;                                             //Divison
      print "</td>";
      print "</tr>";
    }
    $total = array();
		foreach ($rhead[$gkey] as $rkey=>$rvalue) {
			print "<tr style='outline: thin dotted'>";
      $section = explode('||', $rvalue);
      if($gval=='Report Total'){
        print '<td align=left valign=top style="font-weight: bold; outline: thin solid">';
        print 'एकूण :';                                                 //Grand total
      }
      else{
        if($section[0]!=''){
          print '<td align=left valign=top>';
          print $section[0];                                              //Section
  			}
        else{
          print '<td align=left valign=top style="font-weight: bold; outline: thin solid">';
          print 'विभाग एकूण :';
        } 
      }  
			print "</td>";

      $rowtotal=0;
      ksort($colname[$gkey]);
			foreach ($chead[$gkey] as $ckey=>$cvalue) {
				foreach($colname[$gkey] as $colkey=>$colvalue){
        if($gval=='Report Total'){
           $style = '<td align=right valign=top style="font-weight: bold; outline: thin solid">';
        }  
        else{
         if($section[0]!=''){
            $style = '<td align=right valign=top>';
          }
          else{
            $style = '<td align=right valign=top style="font-weight: bold; outline: thin solid">';
          } 
        }  
          print $style;
          if (isset($matrix[$gkey][$rkey][$ckey][$colkey])) {
           
  					print number_format($matrix[$gkey][$rkey][$ckey][$colkey],2);
            $total[$ckey][$colkey] = $total[$ckey][$colkey] + $matrix[$gkey][$rkey][$ckey][$colkey];
            $grandtotal[$ckey][$colkey] = $grandtotal[$ckey][$colkey] + $matrix[$gkey][$rkey][$ckey][$colkey];
            $rowtotal = $rowtotal + $matrix[$gkey][$rkey][$ckey][$colkey];
  				}
  				else {
  					print "0";
  				}
          print "</td>";
        }  
			}
      $expected_tonnage=$expected_tonnage+$section[1];
      $approx_tonnage=$approx_tonnage+$section[2];
      print $style;
      print number_format($section[1],2);                                   //tonnage
      print "</td>";
      print $style;
      print number_format($section[2],2);                                   //approx tonnage
      print "</td>";
			print "</tr>";
		}
}
?>
  
</table>
</body>
</html