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
  $qryPath = "util/readquery/general/cane_diversioncancel.ini";

   $menucode = $_GET['menu_code'];
  //$compcode = $_SESSION['COMP_CODE'];
   $compcode = 'DS'; 

   
    if($_POST['season']!=''){    
       $season = $_POST['season'];
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
  
    if($_POST['section']!=''){    
      $section = explode('||',$_POST['section']);
      $sectiontext = $section[1];
    } 
    else{
       $section='';
       $sectiontext='सर्व';
    }  
    
  $oldFilter = array(':PCOMP_CODE');
  $newFilter = array( $compcode);
  $compnameQry = $qryObj->fetchQuery($qryPath,'Q001','COMPNAME',$oldFilter,$newFilter);
  $compnameaRes = $dsbObj->getData($compnameQry);

  //GET PRINT DATA
   /*for($i=0;$i<sizeof($bankbranch);$i++){
    $bankbranchkstr = implode(',',$bankbranch);
  }*/


  $oldRptFilter = array(':PCOMP_CODE',':PSEASON',':PFR_TRANSFER_DT',':PTO_TRANSFER_DT',':PSECTION');
  $newPrtFilter = array($compcode,$season,$fromdate,$todate,$section[0]);
 
  //Call Procedure
 /* $callProc = $qryObj->fetchQuery($qryPath,'Q001','PROCEDURE',$oldRptFilter,$newPrtFilter);
  $ProcRes = $dsbObj->getData($callProc);
  $lgs->lg->trace("In Bank branch Village farmer summary  Procedure: ".$callProc);
*/
  $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','SELECTQUERY',$oldRptFilter,$newPrtFilter);

  //print_r($newPrtFilter);
  //echo $printdataQry;
  
  $printdataRes = $dsbObj->getData($printdataQry);
  //echo $printdataRes;
  //echo $callProc." <br>".$printdataQry;
  $lgs->lg->trace("Cane Diversion Cancellation Registration query: ".$printdataQry);
  $lgs->lg->trace("Cane Diversion Cancellation Registration result: ".json_encode($printdataRes));
  //echo json_encode($printdataRes);
   $rowcnt = sizeof($printdataRes);

    

    //If Data Not Found
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
  <link rel="icon" href="images/favicon.png" >
<title> Cane Diversion Cancellation Summary Report </title>
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
   /*.page
      {
        page-break-before: always; 
       
        
        page-break-inside: avoid;
      }*/

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

<table cellspacing="0" border="0" width="100%" align="center">
<?
echo '<h3 align="center">'.$compnameaRes[0]["COMP_NAME"].'</h3>';
//echo '<h4 align="center" style="border:medium; border-color:#000000"> गटवार गावावर ऊस मोड स्लिप सरासरी  </h4>';
echo '<h4 align="center" style="border:medium; border-color:#000000"> Cane Diversion Cancellation Summary Report  </h4>';
echo '<p align="center" style="border:medium; border-color:#000000"><b>हंगाम: </b>'.$seasontext.'&nbsp; &nbsp; <b>दिनांक:</b> &nbsp;'.$fromdatetext.' - '.$todatetext.'&nbsp; &nbsp;<b>गट:</b> &nbsp;'.$sectiontext.'</p> ';

?>      
<thead>
  <tr>
  <th align="left" width="10%">शिवार</th>
  <th align="left" width="8%">प्लॉट नंबर </th>
  <th align="left" width="26%">बागायतदार </th>  
  <th align="left" width="15%">ऊस प्रकार</th>
  <th align="left" width="16%">उसाची जात </th> 
  <th align="left" width="7%">लागण तारीख </th>
  <th align="right" width="6%">नोंदवलेले क्षेत्र </th>
  <th align="right" width="6%">मोडीत क्षेत्र </th> 
  <th align="right" width="6%">शिल्लक क्षेत्र </th>
  </tr> 
</thead>
   
<?php
// Fetch the results of the query
$last_SECTION_CODE='';
$last_SHIVAR_CODE='';
//$last_vl_code='';
$i=0;

$sr_balarea=0;
$sr_regarea=0;
$sr_remarea=0;
$sc_balarea=0;
$sc_regarea=0;
$sc_remarea=0;
$g_balarea=0;
$g_regarea=0;
$g_remarea=0;
foreach ($printdataRes as $row) {
  if ($last_SECTION_CODE <> $row['SECTION_CODE']) {
    if ($last_SECTION_CODE <> "") {
      if ($last_SHIVAR_CODE <> "") {
        print '<tr style="font-weight: bold; outline: thin solid">';                        
        print '<td align="right">';
        print '&nbsp;';
        print "</td>";
        print '<td align="right">';
        print '&nbsp;';
        print "</td>";
        print '<td align="right">';
        print '&nbsp;';
        print "</td>";
        print '<td align="right">';
        print '&nbsp;';
        print "</td>";
        print '<td align="right">';
        print '&nbsp;';
        print "</td>";              
        print "<td align=left>";
        print "<B>शिवार एकूण:</B>";
        print "</td>";
        print '<td align="right">';
        print number_format($sr_regarea,2);
        print "</td>";
        print '<td align="right">';
        print number_format($sr_remarea,2);
        print "</td>";        
        print '<td align="right">';
        print number_format($sr_balarea,2);
        print "</td>";
        print "<tr>";
        $sc_balarea=$sc_balarea+$sr_balarea;
        $sc_regarea=$sc_regarea+$sr_regarea;
        $sc_remarea=$sc_remarea+$sr_remarea;
      }
          //echo "Printing Totals"; 
        print '<tr style="font-weight: bold; outline: thin solid">';                        
        print '<td align="right">';
        print '&nbsp;';
        print "</td>";
        print '<td align="right">';
        print '&nbsp;';
        print "</td>";
        print '<td align="right">';
        print '&nbsp;';
        print "</td>";
        print '<td align="right">';
        print '&nbsp;';
        print "</td>";
        print '<td align="right">';
        print '&nbsp;';
        print "</td>";
        print "<td align=left>";
        print "<B>गट एकूण:</B>";
        print "</td>";
        print '<td align="right">';
        print number_format($sc_regarea,2);
        print "</td>";
        print '<td align="right">';
        print number_format($sc_remarea,2);
        print "</td>";               
        print '<td align="right">';
        print number_format($sc_balarea,2);
        print "</td>";
        print "<tr>";

        $g_balarea=$g_balarea+$sc_balarea;
        $g_regarea=$g_regarea+$sc_regarea;
        $g_remarea=$g_remarea+$sc_remarea;
        $last_SHIVAR_CODE='';

        $sr_balarea=0;
        $sr_regarea=0;
        $sr_remarea=0;

        $sc_balarea=0;
        $sc_regarea=0;
        $sc_remarea=0;

    }
 
    print '<tr style="outline: thin solid" >' ;
    print '<td colspan=9>';
    print "<b>गट- ".$row['SECTION_CODE']." ". $row['SECTION_NAME']."</b>";
    print "</td>";
    print "</tr>";
  
    $last_SECTION_CODE = $row['SECTION_CODE'];

    $sc_balarea=0;
    $sc_regarea=0;
    $sc_remarea=0;
  }
  if ($last_SHIVAR_CODE <> $row['SHIVAR_CODE']) {
    if ($last_SHIVAR_CODE <> "") {
      print '<tr style="font-weight: bold; outline: thin solid">';                        
      print '<td align="right">';
      print '&nbsp;';
      print "</td>";
      print '<td align="right">';
      print '&nbsp;';
      print "</td>";
      print '<td align="right">';
      print '&nbsp;';
      print "</td>";
      print '<td align="right">';
      print '&nbsp;';
      print "</td>";
      print '<td align="right">';
      print '&nbsp;';
      print "</td>";
      print "<td align=left>";
      print "<B>शिवार एकूण:</B>";
      print "</td>";
      print '<td align="right">';
      print number_format($sr_regarea,2);
      print "</td>";
      print '<td align="right">';
      print number_format($sr_remarea,2);
      print "</td>";
      print '<td align="right">';
      print number_format($sr_balarea,2);
      print "</td>";
      print "<tr>";
      
      $sc_balarea=$sc_balarea+$sr_balarea;
      $sc_regarea=$sc_regarea+$sr_regarea;
      $sc_remarea=$sc_remarea+$sr_remarea;

      $sr_balarea=0;
      $sr_regarea=0;
      $sr_remarea=0;
    }
    print '<tr style="outline: thin dotted">' ;
    print '<td>';
    print "<b> ".$row['SHIVAR_CODE']." ". $row['SHIVAR_NAME']."</b>";
    print "</td>";
    $last_SHIVAR_CODE=$row['SHIVAR_CODE'];
    }
    else{
    //print '<tr style="outline: thin dotted">' ;
    print '<tr>' ;
    print '<td>';
    print "&nbsp";
    print "</td>";
    }

    //print '<tr style="outline: thin dotted">';
    print "<td align=left>";
    print $row['PLOT_NO'];
    print "</td>";  
    print "<td align=left>";
    print $row['FARMER_CODE']."-".$row['FARMER_NAME'];
    print "</td>";
    print "<td align=left>";
    print $row['CTYPE_CODE']."-".$row['CTYPE_MNAME'];
    print "</td>";
    print "<td align=left>";
    print $row['CVAR_CODE']."-".$row['CVAR_NAME'];
    print "</td>";
    print "<td align=left>";
    print $row['PLANTATION_DATE'];
    print "</td>";
    print "<td align=right>";
    print $row['REGISTERED_AREA'];
    print "</td>";
    print "<td align=right>";
    print $row['CANCELLED_TRANSFER_AREA'];
    print "</td>";
    print "<td align=right>";
    print $row['BALANCE_AREA'];
    print "</td>";  
    print "</tr>";
  
  $sr_regarea=$row['REGISTERED_AREA']+$sr_regarea;
  $sr_balarea=$row['BALANCE_AREA']+$sr_balarea;
  $sr_remarea=$row['CANCELLED_TRANSFER_AREA']+$sr_remarea;
}
print '<tr style="font-weight: bold; outline: thin solid">';                        
print '<td align="right">';
print '&nbsp;';
print "</td>";
print '<td align="right">';
print '&nbsp;';
print "</td>";
print '<td align="right">';
print '&nbsp;';
print "</td>";
print '<td align="right">';
print '&nbsp;';
print "</td>";
print '<td align="right">';
print '&nbsp;';
print "</td>";
print "<td align=left>";
print "<B>शिवार एकूण:</B>";
print "</td>";
print '<td align="right">';
print number_format($sr_regarea,2);
print "</td>";
print '<td align="right">';
print number_format($sr_remarea,2);
print "</td>";
print '<td align="right">';
print number_format($sr_balarea,2);
print "</td>";
print "</tr>";

$sc_balarea=$sc_balarea+$sr_balarea;
$sc_regarea=$sc_regarea+$sr_regarea;
$sc_remarea=$sc_remarea+$sr_remarea;

print '<tr style="font-weight: bold; outline: thin solid">';                        
print '<td align="right">';
print '&nbsp;';
print "</td>";
print '<td align="right">';
print '&nbsp;';
print "</td>";
print '<td align="right">';
print '&nbsp;';
print "</td>";
print '<td align="right">';
print '&nbsp;';
print "</td>";
print '<td align="right">';
print '&nbsp;';
print "</td>";
print "<td align=left>";
print "<B>गट एकूण:</B>";
print "</td>";
print '<td align="right">';
print number_format($sc_regarea,2);
print "</td>";
print '<td align="right">';
print number_format($sc_remarea,2);
print "</td>";
print '<td align="right">';
print number_format($sc_balarea,2);
print "</td>";
print "</tr>";

$g_balarea=$g_balarea+$sc_balarea;
$g_regarea=$g_regarea+$sc_regarea;
$g_remarea=$g_remarea+$sc_remarea;

print '<tr style="font-weight: bold; outline: thin solid">';                        
print '<td align="right">';
print '&nbsp;';
print "</td>";
print '<td align="right">';
print '&nbsp;';
print "</td>";
print '<td align="right">';
print '&nbsp;';
print "</td>";
print '<td align="right">';
print '&nbsp;';
print "</td>";
print '<td align="right">';
print '&nbsp;';
print "</td>";      
print "<td align=left>";
print "<B> एकूण :</B>";
print "</td>";
print '<td align="right">';
print number_format($g_regarea,2);
print "</td>";
print '<td align="right">';
print number_format($g_remarea,2);
print "</td>";
print '<td align="right">';
print number_format($g_balarea,2);
print "</td>";
print "</tr>";
?> 
</table>
</html>