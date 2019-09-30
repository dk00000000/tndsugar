<?php 

  require_once('dashboard.php');
  include('readfile.php');

  setlocale(LC_MONETARY, 'en_IN');
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
      $areatext = 'Balance';
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
  $lgs->lg->trace("In annexure 2D Procedure : ".$procedure);
  $lgs->lg->trace("In annexure 2D Procedure Res: ".$procedureRes);

  $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','SELECTQUERY_2D',$oldFilter,$newFilter);
  $printdataRes = $dsbObj->getData($printdataQry);
  //print_r($newPrtFilter);
  //echo $printdataQry;
 
  $lgs->lg->trace("annexure 2D query: ".$printdataQry);
  $lgs->lg->trace("annexure 2D result: ".json_encode($printdataRes));
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
<!--<title>कारखाना कार्यक्षेत्राबाहेरील ऊस पुरवठादरांचे करारा अंतर्गत ऊस नोंद क्षेत्राची माहिती</title>-->
<title>परिशिष्ट रिपोर्ट</title>
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
  h1,h2,h3,h4{
    line-height:0.5em;
  }
  .page{
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
echo '<h3 align="center" style="border:medium; border-color:#000000">परिशिष्ट क्र 2 - ड</h3>';
//echo '<h4 align="center" style="border:medium; border-color:#000000">कारखाना कार्यक्षेत्राबाहेरील  ऊस पुरवठादरांचे करारा अंतर्गत ऊस नोंद क्षेत्राची माहिती</h4>';
echo '<h4 align="center" style="border:medium; border-color:#000000">राज्याबाहेरील ऊस उत्पादकाचे करार अंतर्गत ऊस नोंद क्षेत्राची माहिती</h4>';
echo '<p align="center" style="border:medium; border-color:#000000"><b>गट: &nbsp;</b>'.$sectiontext.'<b>&nbsp;&nbsp;&nbsp;गाव: </b>'.$villagetext.'&nbsp;&nbsp;&nbsp;<b>ऊस प्रकार:</b> &nbsp;'.$canetypetext.'&nbsp;&nbsp;&nbsp;<b>ऊसाची जात:</b> &nbsp;'.$canevarietytext.'&nbsp;&nbsp;&nbsp;<b>बागायतदार :</b> &nbsp;'.$farmertext.'&nbsp;&nbsp;&nbsp;<b>क्षेत्र :</b> &nbsp;'.$areatext.'</p>';
?>      
<thead>
  <tr>
  <th align="left" width="3%">अ.क्र.</th>
  <th align="left" width="8%">राज्य</th>  
  <th align="left" width="8%">जिल्हा</th>
  <th align="left" width="8%">तालुका</th> 
  <th align="left" width="8%">गाव</th> 
  <th align="left" width="10%">राज्याबाहेरील ऊस उत्पादकांचे नाव</th> 
  <th align="right" width="3.5"> सभासद (कोड/नंबर)</th>
  <th align="right" width="3.5">आधार क्रमांक</th>
  <th align="left" width="6%">मोबाईल क्रमांक</th>
  <th align="right" width="6%">बँक खाते क्रमांक</th>
  <th align="right" width="6%">सर्वे नंबर</th>
  <th align="right" width="6%">गाळपासाठी नोंद झालेले एकूण ऊस क्षेत्र (हेक्टर)</th>
  <th align="center" width="6%">लागवड हंगाम</th>
  <th align="left" width="6%">जात</th>
  <th align="left" width="6%">लागवड दिनांक</th>
  <th align="left" width="6%">अंदाजीत तोडणी दिनांक</th>
  </tr> 
</thead>
   
<?php
// Fetch the results of the query
$last_section='';
$section_name='';

$section_area=0;
//$section_plantarea=0;  //old 

$total_area=0;
//$total_plantarea=0;  //old

foreach ($printdataRes as $row) {
  /*if ($last_section <> $row['VL_SECTION']) {
    if ($last_section <> "") {
        
     print '<tr style="font-weight: bold; outline: thin solid">';                        
     print '<td align="left" colspan=7>';
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
    //$section_plantarea=0;  //old
  }

    print '<tr style="outline: thin solid">' ;
    print '<td colspan=12>';
    print "<b> ".$row['VL_SECTION']." ". $row['SC_MNAME']."</b>";
    print "</td>";
    print '</tr>';
  
    $last_section = $row['VL_SECTION'];
    $section_name = $row['VL_SECTION']." ". $row['SC_MNAME'];
  }*/
    
  print '<tr style="outline: thin dotted">'; 
  print "<td align=left>";
  print $row['SR'];
  print "</td>"; 
  print "<td align=left>";
  print $row['STATE'];
  print "</td>"; 
  print "<td align=left>";
  print $row['DISTRICT'];
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
  print "<td align=right>";
  print $row['TXN_ACCD'];
  print "</td>"; 
  print "<td align=right>";
  print $row['FRMR_ADHAR'];
  print "</td>";      
  print "<td align=left>";
  print $row['PRT_TEL'];
  print "</td>";
  print "<td align=right>";
  print $row['FRMR_ACCOUNT'];
  print "</td>";
  print "<td align=right>";
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
  print "<td align=left>";
  print $row['TXN_HDATE'];
  print "</td>";
  
  $total_area=$row['TXN_CAREA']+$total_area;
  //$section_plantarea=$row['TXN_CAREA']+$section_plantarea; //old

}

$total_area=$section_area+$total_area;
//$total_plantarea=$section_plantarea+$total_plantarea;  //old

print '<tr style="font-weight: bold; outline: thin solid">';                        
print '<td align="left" colspan=7>';
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