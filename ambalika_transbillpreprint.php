<?php 
  require_once('dashboard.php');
  include('readfile.php');
  include("./mpdf7/mpdf.php");
  //require_once('header_menu.php');
  //For number to marathi word conversion
  require_once("numbertomarathiword.php");
  $marathinumber = new NumbertoMarathi;
  //echo $marathinumber->getIndianCurrency(115665);
 
  $lgs = new Logs();
  $qryObj = new Query();
  $dsbObj = new Dashboard(); 
  $rfObj = new ReadFile();
  $lang=strtolower($_SESSION['LANG']);
  $qryPath = "util/readquery/general/ambalika_transbillpreprint.ini"; 
  

  $oldFilter = array(':PCOMP_CODE',':PTXN_SEASON',':PFORT_NIGHT',':PCONTRACTTYPE',':PBILL_TYPE',':PCONTRACTOR');
  $newFilter = array($_SESSION['COMP_CODE'],$_REQUEST['season'],$_REQUEST['fornight'],$_REQUEST['contract_type'],$_REQUEST['bill_type'],$_REQUEST['contractor']); 


  $procedure = $qryObj->fetchQuery($qryPath,'Q001','PROCEDURE',$oldFilter,$newFilter);
  $procedureRes = $dsbObj->getData($procedure);
  
  //GET DATA
  $getAllData = $qryObj->fetchQuery($qryPath,'Q001','GET_DATA',$oldFilter,$newFilter);
  /*echo $procedure;
  echo "<br>".$getAllData;
  exit(0);*/
 
  $allDataRes = $dsbObj->getData($getAllData);
  
  if(sizeof($allDataRes) == 0){
    echo "<h2 align='center'>Data Not Found. !</h2>";
    exit(0);
  }
 
?>

 <?php 
  //$i = 0;

  foreach ($allDataRes as $key => $value)
    {
       //$srno=$i+1;
          
          //Set Filters
          $oldFilter = array(':PCOMP_CODE',':PBILL_NO');
          $newFilter = array($_SESSION['COMP_CODE'],$value['BILL_NO']);
          //For Header Data
          if($_REQUEST['contract_type'] == 'CT001'){
           
          $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','HEADERPRINTQRY',$oldFilter,$newFilter);
          $printdataRes = $dsbObj->getData($printdataQry);
          }else
          {
           
          $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','HEADERPRINTQRY_HRV',$oldFilter,$newFilter);
          $printdataRes = $dsbObj->getData($printdataQry);
          }
          //For Farmer Data 
          $farmerQry = $qryObj->fetchQuery($qryPath,'Q001','FARMER_QRY',$oldFilter,$newFilter);
          $farmerRes = $dsbObj->getData($farmerQry);
          
          //For Deduction Data 
          $deductionQry = $qryObj->fetchQuery($qryPath,'Q001','DEDUCTION_QRY',$oldFilter,$newFilter);
          $deductionRes = $dsbObj->getData($deductionQry);
          //echo "<br>".sizeof($detailRes);
        
       
   $html = '
<style>
.gradient {
      border:0.1mm solid #220044;
      background-color: #f0f2ff;
      background-gradient: linear #c7cdde #f0f2ff 0 1 0 0.5;
}
h4 {
      font-family: sans;
      font-weight: bold;
      margin-top: 1em;
      margin-bottom: 0.5em;
}
div {
      padding:4px 0px 0px 0px; //top right bottom left
      margin: 0px 0px 0px 0px;
      text-align:justify;
}
.header { position: absolute;
      overflow: visible;
      
     
      top:2mm;
      left: 0;
      height: 31mm;
      max-height: 31mm;
      padding: 3px 0px 0px 1px;
      font-family:sans;
      margin:  0px 0px 0px 0px;
}

.deductions-l { position: absolute;
      overflow: visible;
      
  top:46mm;
      left: 0;
      height: 75mm;
  max-height: 75mm;
  width: 125mm;
      padding: 0px 0px 0px 60px;
      font-family:sans;
      margin: 0px 0px 0px 0px;
}

.summary { position: absolute;
      overflow: visible;
    top: 119mm;
      left: 0;
      height: 40mm;
  max-height: 40mm;
      padding: 0px 60px 0px 65px;
       
      font-family:sans;
      margin:  0px 0px 0px 0px;
}
</style>
<body>
<div class="header" style="width: 100%;margin= 5px 0px 0px 0px;">
<table width="100%" >
<tr > 
<td style="width:25%" ></td>
<td valign="bottom" align="left" style="height:80px; width=45%">'.$printdataRes[0]['MAIN_CONTRACOR']."  ".$printdataRes[0]['PRT_MNAME'].'</td>
<td valign="bottom" align="left" style="height:80px; width=30%">'.$printdataRes[0]['SEASON'].'</td>
</tr>
<tr > 
<td style="width:25%" ></td>
<td valign="bottom" align="left" style="height:20px; width=55%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$printdataRes[0]['VH_NO'].' </td>
<td valign="bottom" align="left" height="20px" width="25%">'.$printdataRes[0]['BILL_NO'].'</td>
</tr>
<tr > 
<td style="width:25%" ></td>
<td valign="bottom" align="left" style="height:20px; width=65%">'.$printdataRes[0]['SEASON_START_DT']."&nbsp;&nbsp;&nbsp;".$printdataRes[0]['SEASON_END_DT'].' </td>
<td valign="bottom" align="left" style="height:20px; width=15%">'.$printdataRes[0]['JT_MNAME'].'</td>
</tr>
</table>
</div>

<div class="deductions-l" style="float: left; width: 240mm;">
<table style="width:93%" border="0">
<tr>
<td align="left" valign="top" style="width:23%;">'.$farmerRes[0]['FARMER_NAME']."  ".$farmerRes[0]['FARMER_CODE'].'</td>
<td align="center" style="width:7%;">'.$farmerRes[0]['VL_MNAME'].' </td>
<td align="left" style="width:8%;">'.$farmerRes[0]['TONNAGE'].'</td>
<td align="left" style="width:3%;">'.$farmerRes[0]['TXN_KMS'].'</td>
<td align="left" style="width:5%;">'.$farmerRes[0]['RATE'].'</td>
<td align="left" style="width:8%;">'.$farmerRes[0]['AMT'].'</td>
<td align="left" style="width:10%;">'.$deductionRes[0]['DED_MNAME']."  ".$deductionRes[0]['LTR'].'</td>
<td align="left" style="width:20%;">'.$deductionRes[0]['DED_AMT'].'</td>
</tr>
<tr>
<td align="left" valign="top" style="width:23%;">'.$farmerRes[1]['FARMER_NAME']."  ".$farmerRes[1]['FARMER_CODE'].'</td>
<td align="center" style="width:7%;">'.$farmerRes[1]['VL_MNAME'].' </td>
<td align="left" style="width:8%;">'.$farmerRes[1]['TONNAGE'].'</td>
<td align="left" style="width:3%;">'.$farmerRes[1]['TXN_KMS'].'</td>
<td align="left" style="width:5%;">'.$farmerRes[1]['RATE'].'</td>
<td align="left" style="width:8%;">'.$farmerRes[1]['AMT'].'</td>
<td align="left" style="width:10%;">'.$deductionRes[1]['DED_MNAME']."  ".$deductionRes[1]['LTR'].'</td>
<td align="left" style="width:20%;">'.$deductionRes[1]['DED_AMT'].'</td>
</tr>
<tr>
<td align="left" valign="top" style="width:23%;">'.$farmerRes[2]['FARMER_NAME']."  ".$farmerRes[2]['FARMER_CODE'].'</td>
<td align="center" style="width:7%;">'.$farmerRes[2]['VL_MNAME'].' </td>
<td align="left" style="width:8%;">'.$farmerRes[2]['TONNAGE'].'</td>
<td align="left" style="width:3%;">'.$farmerRes[2]['TXN_KMS'].'</td>
<td align="left" style="width:5%;">'.$farmerRes[2]['RATE'].'</td>
<td align="left" style="width:8%;">'.$farmerRes[2]['AMT'].'</td>
<td align="left" style="width:10%;">'.$deductionRes[2]['DED_MNAME']."  ".$deductionRes[2]['LTR'].'</td>
<td align="left" style="width:20%;">'.$deductionRes[2]['DED_AMT'].'</td>
</tr>
<tr>
<td align="left" valign="top" style="width:23%;">'.$farmerRes[3]['FARMER_NAME']."  ".$farmerRes[3]['FARMER_CODE'].'</td>
<td align="center" style="width:7%;">'.$farmerRes[3]['VL_MNAME'].' </td>
<td align="left" style="width:8%;">'.$farmerRes[3]['TONNAGE'].'</td>
<td align="left" style="width:3%;">'.$farmerRes[3]['TXN_KMS'].'</td>
<td align="left" style="width:5%;">'.$farmerRes[3]['RATE'].'</td>
<td align="left" style="width:8%;">'.$farmerRes[3]['AMT'].'</td>
<td align="left" style="width:10%;">'.$deductionRes[3]['DED_MNAME']."  ".$deductionRes[3]['LTR'].'</td>
<td align="left" style="width:20%;">'.$deductionRes[3]['DED_AMT'].'</td>
</tr>
<tr>
<td align="left" valign="top" style="width:23%;">'.$farmerRes[4]['FARMER_NAME']."  ".$farmerRes[4]['FARMER_CODE'].'</td>
<td align="center" style="width:7%;">'.$farmerRes[4]['VL_MNAME'].' </td>
<td align="left" style="width:8%;">'.$farmerRes[4]['TONNAGE'].'</td>
<td align="left" style="width:3%;">'.$farmerRes[4]['TXN_KMS'].'</td>
<td align="left" style="width:5%;">'.$farmerRes[4]['RATE'].'</td>
<td align="left" style="width:8%;">'.$farmerRes[4]['AMT'].'</td>
<td align="left" style="width:10%;">'.$deductionRes[4]['DED_MNAME']."  ".$deductionRes[4]['LTR'].'</td>
<td align="left" style="width:20%;">'.$deductionRes[4]['DED_AMT'].'</td>
</tr>
<tr>
<td align="left" valign="top" style="width:23%;">'.$farmerRes[5]['FARMER_NAME']."  ".$farmerRes[5]['FARMER_CODE'].'</td>
<td align="center" style="width:7%;">'.$farmerRes[5]['VL_MNAME'].' </td>
<td align="left" style="width:8%;">'.$farmerRes[5]['TONNAGE'].'</td>
<td align="left" style="width:3%;">'.$farmerRes[5]['TXN_KMS'].'</td>
<td align="left" style="width:5%;">'.$farmerRes[5]['RATE'].'</td>
<td align="left" style="width:8%;">'.$farmerRes[5]['AMT'].'</td>
<td align="left" style="width:10%;">'.$deductionRes[5]['DED_MNAME']."  ".$deductionRes[5]['LTR'].'</td>
<td align="left" style="width:20%;">'.$deductionRes[5]['DED_AMT'].'</td>
</tr>
<tr>
<td align="left" valign="top" style="width:23%;">'.$farmerRes[6]['FARMER_NAME']."  ".$farmerRes[6]['FARMER_CODE'].'</td>
<td align="center" style="width:7%;">'.$farmerRes[6]['VL_MNAME'].' </td>
<td align="left" style="width:8%;">'.$farmerRes[6]['TONNAGE'].'</td>
<td align="left" style="width:3%;">'.$farmerRes[6]['TXN_KMS'].'</td>
<td align="left" style="width:5%;">'.$farmerRes[6]['RATE'].'</td>
<td align="left" style="width:8%;">'.$farmerRes[6]['AMT'].'</td>
<td align="left" style="width:10%;">'.$deductionRes[6]['DED_MNAME']."  ".$deductionRes[6]['LTR'].'</td>
<td align="left" style="width:20%;">'.$deductionRes[6]['DED_AMT'].'</td>
</tr>
<tr>
<td align="left" valign="top" style="width:23%;">'.$farmerRes[7]['TXD_NARR'].'</td>
<td align="center" style="width:7%;">'.$farmerRes[7]['VL_MNAME'].' </td>
<td align="left" style="width:8%;">'.$farmerRes[7]['TONNAGE'].'</td>
<td align="left" style="width:3%;">'.$farmerRes[7]['TXN_KMS'].'</td>
<td align="left" style="width:5%;">'.$farmerRes[7]['RATE'].'</td>
<td align="left" style="width:8%;">'.$farmerRes[7]['AMT'].'</td>
<td align="left" style="width:10%;">'.$deductionRes[7]['DED_MNAME']."  ".$deductionRes[7]['LTR'].'</td>
<td align="left" style="width:20%;">'.$deductionRes[7]['DED_AMT'].'</td>
</tr>
<tr>
<td align="left" valign="top" style="width:23%;">'.$farmerRes[8]['FARMER_NAME']."  ".$farmerRes[8]['FARMER_CODE'].'</td>
<td align="center" style="width:7%;">'.$farmerRes[8]['VL_MNAME'].' </td>
<td align="left" style="width:8%;">'.$farmerRes[8]['TONNAGE'].'</td>
<td align="left" style="width:3%;">'.$farmerRes[8]['TXN_KMS'].'</td>
<td align="left" style="width:5%;">'.$farmerRes[8]['RATE'].'</td>
<td align="left" style="width:8%;">'.$farmerRes[8]['AMT'].'</td>
<td align="left" style="width:10%;">'.$deductionRes[8]['DED_MNAME']."  ".$deductionRes[8]['LTR'].'</td>
<td align="left" style="width:20%;">'.$deductionRes[8]['DED_AMT'].'</td>
</tr>
<tr>
<td align="left" valign="top" style="width:23%;">'.$farmerRes[9]['FARMER_NAME']."  ".$farmerRes[9]['FARMER_CODE'].'</td>
<td align="center" style="width:7%;">'.$farmerRes[9]['VL_MNAME'].' </td>
<td align="left" style="width:8%;">'.$farmerRes[9]['TONNAGE'].'</td>
<td align="left" style="width:3%;">'.$farmerRes[9]['TXN_KMS'].'</td>
<td align="left" style="width:5%;">'.$farmerRes[9]['RATE'].'</td>
<td align="left" style="width:8%;">'.$farmerRes[9]['AMT'].'</td>
<td align="left" style="width:10%;">'.$deductionRes[9]['DED_MNAME']."  ".$deductionRes[9]['LTR'].'</td>
<td align="left" style="width:20%;">'.$deductionRes[9]['DED_AMT'].'</td>
</tr>
<tr>
<td align="left" valign="top" style="width:23%;">'.$farmerRes[10]['FARMER_NAME']."  ".$farmerRes[10]['FARMER_CODE'].'</td>
<td align="center" style="width:7%;">'.$farmerRes[10]['VL_MNAME'].' </td>
<td align="left" style="width:8%;">'.$farmerRes[10]['TONNAGE'].'</td>
<td align="left" style="width:3%;">'.$farmerRes[10]['TXN_KMS'].'</td>
<td align="left" style="width:5%;">'.$farmerRes[10]['RATE'].'</td>
<td align="left" style="width:8%;">'.$farmerRes[10]['AMT'].'</td>
<td align="left" style="width:10%;">'.$deductionRes[10]['DED_MNAME']."  ".$deductionRes[10]['LTR'].'</td>
<td align="left" style="width:20%;">'.$deductionRes[10]['DED_AMT'].'</td>
</tr>


</table>
</div>


<div class="summary" style="width: 100%; margin= 0px 0px 0px 0px;">
<table align="left" style="width:84%" border="0" >
<tr bgcolor="">
<td align="right" style="width:40%;" ><b>'.$printdataRes[0]['TRANS_TONNAGE'].'</b></td>
<td  align="center" style="width:10%" ></td>
<td  align="left" style="width:14%" >&nbsp;'.$printdataRes[0]['TRANS_AMT'].'</b></td>
<td align="center" style="width:1%;"></td>
<td align="" style="width:15%;"><b>'.$printdataRes[0]['DEDUCTION'].'</b></td>
</tr>
<tr bgcolor="">

<td  style="width:40%;" colspan="4" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$marathinumber->getIndianCurrency($printdataRes[0]['NETT_AMT']).' फक्त </td>

<td align="" style="width:15%;" valign="bottom">'.$printdataRes[0]['NETT_AMT'].'</td>
</tr>
<tr bgcolor="">
<td  style="width:40%;" align="left" valign="bottom" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$printdataRes[0]['BANK_BRANCH_NAME'].'  </td>
<td  align="right" style="width:10%" valign="bottom"></td>
<td   style="width:14%" valign="bottom">'.$printdataRes[0]['PRT_ACNO'].'</td>
<td align="center" style="width:1%;" valign="bottom"></td>
<td align="center" style="width:15%;" valign="bottom"></td>
</tr>
</tr>


</table>
</div>
';

//==============================================================
//==============================================================
//==============================================================
$i = $i + 1; 
//$lgs->lg->trace("Generating bill: ".$i);
if ($i == 1) {
      // For first bill, create PDF
      $mpdf=new mPDF('utf-8', array(250, 152.4));
      $mpdf->debug = true;
      $mpdf->autoScriptToLang = true;
      $mpdf->autoLangToFont = true;
      $mpdf->allow_output_buffering=true;
      //echo "Generating bill: ".$i;
}
else {
    // For subsequent bills, add page
      //echo "Generating bill: ".$i;
      $mpdf->AddPage();
}
$mpdf->WriteHTML($html);   // Separate Paragraphs  defined by font
}
// Show PDF with all bills
$mpdf->Output('TransporterBills.pdf','I');
//==============================================================
//==============================================================
//==============================================================
?>