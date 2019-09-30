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
  $qryPath = "util/readquery/general/annexure.ini";

  $menucode = $_GET['menu_code'];
  $compcode = $_SESSION['COMP_CODE'];
  
  //$compcode = 'DS'; 
	if($menucode == 'L06202'){
	$annex_title1 = 'परिशिष्ट क्र. 2 - अ';
	$annex_subtit1 = 'कारखाना कार्यक्षेत्रातील गाव निहाय   सभासदांचे  ऊस नोंद क्षेत्राची माहिती';
	}
	if($menucode == 'L06203'){
	$annex_title2 = 'परिशिष्ट क्र. 2 - ब';
	$annex_subtit2 = 'कारखाना कार्यक्षेत्रातील गाव निहाय बिगर  सभासदांचे  ऊस नोंद क्षेत्राची माहिती';
	}
	if($menucode == 'L06205'){
	$annex_title3 = 'परिशिष्ट क्र. 2 - क';
	$annex_subtit3 = 'कारखाना कार्यक्षेत्राबाहेरील  ऊस उत्पादकाचे करार अंतर्गत  ऊस नोंद  क्षेत्राची माहिती';
	}
   
  if($_POST['season']!=''){    
     $season = $_POST['season'];
     $seasontext = $season;
  } 
  else{
     $season='';
     $seasontext = 'सर्व ';
  }   
  
  if($_POST['section']!=''){    
    $section = explode('-',$_POST['section']);
    $sectiontext = $section[1];
  } 
  else{
     $section='';
     $sectiontext='सर्व ';
  }

  if($_POST['village']!=''){    
    $village = explode('-',$_POST['village']);
    $villagetext = $village[1];
  } 
  else{
     $village='';
     $villagetext='सर्व ';
  }
  
  if($_POST['canetype']!=''){    
    $canetype = explode('-',$_POST['canetype']);
    $canetypetext = $canetype[1];
  } 
  else{
     $canetype='';
     $canetypetext='सर्व ';
  }

  if($_POST['canevariety']!=''){    
    $canevariety = explode('-',$_POST['canevariety']);
    $canevarietytext = $canevariety[1];
  } 
  else{
     $canevariety='';
     $canevarietytext='सर्व ';
  } 

  if($_POST['farmer']!=''){    
    $farmer = explode('-',$_POST['farmer']);
    $farmertext = $farmer[1];
  } 
  else{
     $farmer='';
     $farmertext='सर्व ';
  }  

  if($_POST['area']!=''){    
    $area = $_POST['area'];
    if($area == 'R'){
      $areatext = 'Registered';
    } 
    if($area == 'B'){
      $areatext = 'Balanced';
    } 
  } 
  else{
     $area='';
     $areatext='सर्व ';
  }   
    
  $oldFilter = array(':PCOMP_CODE',':PSEASON',':PAREA',':PSECTION',':PVILLAGE',':PCTYPE',':PCVARIETY',':PFARMER');
  $newFilter = array($compcode,$season,$area,$section[0],$village[0],$canetype[0],$canevariety[0],$farmer[0]);

  $compnameQry = $qryObj->fetchQuery($qryPath,'Q001','COMPNAME',$oldFilter,$newFilter);
  $compnameaRes = $dsbObj->getData($compnameQry);

  //Call Procedure
  $aOutPara="";
  $procedure = $qryObj->fetchQuery($qryPath,'Q001','PROCEDURE',$oldFilter,$newFilter);
  $procedureRes = $dsbObj->getOutProcData($procedure,$aOutPara);
  //echo $procedure;
  $lgs->lg->trace("In annexure Procedure : ".$procedure);
  $lgs->lg->trace("In annexure Procedure Res: ".$procedureRes);

  if($menucode == 'L06202'){
    $qry = 'SELECTQUERY_2A';
  }
  if($menucode == 'L06203'){
    $qry = 'SELECTQUERY_2B';
  }
  if($menucode == 'L06205'){
    $qry = 'SELECTQUERY_2C';
  }

  $printdataQry = $qryObj->fetchQuery($qryPath,'Q001',$qry,$oldFilter,$newFilter);
  $printdataRes = $dsbObj->getData($printdataQry);
  //echo $printdataQry;
 
  $lgs->lg->trace("annexure query: ".$printdataQry);
  $lgs->lg->trace("annexure result: ".json_encode($printdataRes));
  //echo json_encode($printdataRes);
  $rowcnt = sizeof($printdataRes);
   
  if($rowcnt ==0 ){
    echo "<h3 align='center'>Blank Table</h3>";
    exit(0);
  }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="images/favicon.png" >
    
<!--<title>कारखाना कार्यक्षत्रीतील गावनिहाय बिगर  सभासदांच्या नोंदणी व करारा अंतर्गत ऊस नोंद क्षेत्रामधील माहिती</title>-->
<title>परिशिष्ट रिपोर्ट</title>
<style>
  table {
    border:1px solid black;
	font-family:Verdana;
	font-size:13px;
  }  
  th {
    border:1px solid black;
  }
  td {
    border-left: 1px solid black;
    padding-bottom: 5px;
  }
  h1,h2,h3,h4{
    line-height:0.8em;
  }
 .page {
    page-break-before: always;      
    page-break-inside: avoid;
  }
</style>
</head>
<body style="font-size:13px; font-family: verdana"> 

  <table cellspacing="0" border="0" width="100%" align="center">
 
<?

echo '<h3 align="center">'.$compnameaRes[0]["COMP_NAME"].'</h3>';
echo '<h3 align="center" style="border:medium; border-color:#000000">गळीत हंगाम '.$seasontext.'</h3>';
if($menucode == 'L06202'){
echo '<h3 align="center" style="border:medium; border-color:#000000">'.$annex_title1.'</h3>';
echo '<h4 align="center" style="border:medium; border-color:#000000">'.$annex_subtit1.'</h4>';
}
if($menucode == 'L06203'){
echo '<h3 align="center" style="border:medium; border-color:#000000">'.$annex_title2.'</h3>';
echo '<h4 align="center" style="border:medium; border-color:#000000">'.$annex_subtit2.'</h4>';
}
if($menucode == 'L06205'){
echo '<h3 align="center" style="border:medium; border-color:#000000">'.$annex_title3.'</h3>';
echo '<h4 align="center" style="border:medium; border-color:#000000">'.$annex_subtit3.'</h4>';
}
echo '<p align="center" style="border:medium; border-color:#000000"><b>गट: &nbsp;</b>'.$sectiontext.'<b>&nbsp;&nbsp;&nbsp;गाव: </b>'.$villagetext.'&nbsp;&nbsp;&nbsp;<b>ऊस प्रकार:</b> &nbsp;'.$canetypetext.'&nbsp;&nbsp;&nbsp;<b>ऊसाची जात:</b> &nbsp;'.$canevarietytext.'&nbsp;&nbsp;&nbsp;<b>बागायतदार :</b> &nbsp;'.$farmertext.'&nbsp;&nbsp;&nbsp;<b>क्षेत्र :</b> &nbsp;'.$areatext.'</p> ';
?>      
<thead>
  <tr>
  <th align="left" width="3%">अ.क्र.</th>
  <th align="left" width="8%">तालुका</th>  
  <th align="left" width="9%">गाव</th>
  <? if($menucode == 'L06202'){?>
  <th align="left" width="15%">सभासदाचे नाव</th> 
  <th align="left" width="10%">सभासद (कोड / नंबर)</th>
  <? } ?>
  <? if($menucode == 'L06203'){?>
  <th align="left" width="15%">बिगर सभासद ऊस उत्पादकाचे नाव</th> 
  <th align="left" width="10%">बिगर सभासद (कोड / नंबर)</th>
  <? } ?>
  <? if($menucode == 'L06205'){?>
  <th align="left" width="15%">कारखाना कार्यक्षेत्राबाहेरील ऊस उत्पादकाचे नाव</th> 
  <th align="left" width="10%">बिगर सभासद (कोड / नंबर)</th>
  <? } ?>
  <? if($menucode == 'L06203' || $menucode == 'L06205'){ ?>
  <th align="left" width="3%">आधार क्रमांक</th>
  <th align="left" width="4%">मोबाईल क्रमांक</th>
  <th align="left" width="3%">बँक खाते क्रमांक</th>
  <th align="left" width="3%">सर्वे नंबर</th>
  <? } ?>
  <? if($menucode == 'L06202'){?>
  <th align="left" width="10%">मोबाईल क्रमांक</th>
  <? } ?>
  <th align="right" width="9%">गाळपासाठी नोंद झालेले एकुण ऊस क्षेत्र (हेक्टर)</th>
  <th align="center" width="9%">लागवड प्रकार</th>
  <th align="left" width="9%">जात</th>
  <th align="left" width="6%">लागवड दिनांक</th>
  <th align="left" width="9%">अंदाजीत तोडणी दिनांक</th>
  </tr> 
</thead>
   
<?php
$last_section='';
$section_name='';

$section_area=0;
//$section_plantarea=0;  //old

$total_area=0;
//$total_plantarea=0; //old

foreach ($printdataRes as $row) {
  /* if ($last_section <> $row['VL_SECTION']) {
    if ($last_section <> "") {
        
       print '<tr style="font-weight: bold; outline: thin solid">';                        
       print '<td align="left" colspan=6>';
       print '('.$section_name.') एकूण';
       print "</td>";
       print '<td align="right">';
       print number_format($section_area,2);
       print "</td>";
       print '<td align="right" colspan=4>';
       print '&nbsp;';
       print "</td>";    
       print "<tr>";

      $total_area=$section_area+$total_area;
      //$total_plantarea=$section_plantarea+$total_plantarea;  //old

      $section_area=0;
      $section_plantarea=0;   //old
    }

    print '<tr style="outline: thin solid">' ;
    print '<td colspan=12>';
    print "<b> ".$row['VL_SECTION']." ". $row['SC_MNAME']."</b>";
    print "</td>";
    print '</tr>';
  
    $last_section = $row['VL_SECTION'];
    $section_name = $row['VL_SECTION']." ". $row['SC_MNAME'];
  } */
  
  print '<tr style="outline: thin dotted">'; 
  print "<td align=left>";
  print $row['SR'];
  print "</td>";  
  print "<td align=left>";
  print $row['TALUKA'];
  print "</td>";  
  print "<td align=left>";
  print $row['VILLAGE'];
  print "</td>";
  print "<td align=left>";
  print $row['FARMER'];
  print "</td>";      
  print "<td align=left>";
  print $row['TXN_ACCD'];
  print "</td>";
  print "<td align=left>";
  print $row['FRMR_ADHAR'];
  print "</td>";
  print "<td align=left>";
  print $row['PRT_TEL'];
  print "</td>";   
  print "<td align=left>";
  print $row['FRMR_ACCOUNT'];
  print "</td>";
  print "<td align=left>";
  print $row['SURVEY_NUMBER'];
  print "</td>"; 
  print "<td align=right>";
  print number_format($row['TXN_CAREA'],2);
  print "</td>";
  print "<td align=center>";
  $ct_code = explode('CT00',$row['CT_CODE']);
  print $ct_code[1]; 
  print "</td>";
  print "<td align=left>";
  print $row['CV_NAME'];
  print "</td>";
  print "<td align=left>";
  print $row['PTXN_DATE1'];
  print "</td>";
  print "<td align=right>";
  print $row['TXN_HDATE'];
  print "</td>";

  $total_area=$row['TXN_CAREA']+$total_area;
  //$section_plantarea=$row['TXN_CAREA']+$section_plantarea;
}

$total_area=$section_area+$total_area;
//$total_plantarea=$section_plantarea+$total_plantarea;

print '<tr style="font-weight: bold; outline: thin solid">';                        
print '<td align="left" colspan=6>';
print 'एकूण';
print "</td>";
print '<td align="right">';
print number_format($total_area,2);
print "</td>";
print '<td align="right" colspan=4>';
print '&nbsp;';
print "</td>";    
print "<tr>";
?> 
</table>
</html>