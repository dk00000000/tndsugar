
<?php 
  require_once('dashboard.php');
  include('readfile.php');
  //require_once('header_menu.php');
  
  $lgs = new Logs();
  $qryObj = new Query();
  $dsbObj = new Dashboard(); 
  $rfObj = new ReadFile();
  $lang=strtolower($_SESSION['LANG']);
  $qryPath = "util/readquery/general/unloadslip_tran.ini";
  
  $txn_seq =$_REQUEST['txn_seq'];
  //GET PRINT DATA
  $oldFilter = array(':PCOMP_CODE',':PTXN_SEQ');
  $newFilter = array($_SESSION['COMP_CODE'],$txn_seq);
 
  $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','PRINTDATAQRY',$oldFilter,$newFilter);
  //echo $printdataQry;
  $printdataRes = $dsbObj->getData($printdataQry);
  //print_r($printdataRes);

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">      
      <title> Truck/TRactor Bill Print</title>
    <link href="https://fonts.googleapis.com/css?family=Hind" rel="stylesheet">
    <style>    
      *{padding:0; margin:0;}
         html, body {font-family: 'verdana', sans-serif;font-size: 14px;}
        .printer-wrapper{width: 210mm; margin:0 auto;}
    .opacity0{opacity:0; margin-right: 7px;}
    .left-side-table{margin-top: 17px;}
    .left-side-table tr td{height:18.5px;}
    .left-side-table2{margin-top: 15px;}
    .left-side-table2 tr td{height:20.8px;}   
    .left-side-table tr td:nth-child(2) .opacity0,
    .left-side-table2 tr td:nth-child(2) .opacity0{margin-right:40px;}
    
    .right-side-table{vertical-align: center;margin-top: 10px;}
    .right-side-table tr td{height:30.8px;}
    </style>
   </head>
   <body style="font-size:14px">
      <div class="printer-wrapper">
   
         <table style="margin:0 auto;" width="100%" cellpadding="0" cellspacing="0" border="0">
           
<tbody>
<tr style="height: 20px;" style="opacity: 0" colspan="9"></tr>
<tr style="height: 20px;" style="opacity: 0" colspan="9"></tr>
<tr style="height: 20px;" style="opacity: 0" colspan="9"></tr>
<tr style="height: 21px;" bgcolor="">
<td style="width: 40px; " colspan="4"><?php echo $printdataRes[0]['SEASON']; ?></td>
<td style="width: 40px; " colspan="3" valign="top" align="center">&nbsp;</td>
</tr> 

<tr style="height: 22px;"  >
<td style="width: 40px; " valign="bottom" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['WS_SRNO']; ?></td>
<td style="width: 56px; " valign="bottom" colspan="3" align="center"><?php echo $printdataRes[0]['WS_DATE']; ?></td>
<td style="width: 68px; " valign="bottom" colspan="2"><?php echo $printdataRes[0]['SHIFT_CODE']; ?></td>
<td style="width: 66px; ">&nbsp;</td>
</tr>

<tr style="height: 20px;" bgcolor="red">
<td style="width: 50px; " valign="bottom"  colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <?php echo $printdataRes[0]['FRMR_NAME']."  ".$printdataRes[0]['FRMR_CODE11']; ?></td> 
<td style="width: 40px; ">&nbsp;</td>
<td style="width: 68px; " valign="bottom" colspan="2" align="right">&nbsp;<?php echo $printdataRes[0]['FREG_NUMBER']; ?></td>
<td style="width: 66px; " valign="bottom" colspan="2" align="center">&nbsp;<?php echo $printdataRes[0]['WS_SRNO']; ?></td>
</tr>

<tr style="height: 20px;" >
<td style="width: 40px; " valign="bottom" colspan="3" ><?php echo $printdataRes[0]['SEC_NAME']; ?></td>
<td style="width: 56px; " valign="bottom" colspan="2" align="right">&nbsp;<?php echo $printdataRes[0]['SEC_NAME']; ?></td>
<td style="width: 68px; " valign="bottom" colspan="2" align="center">&nbsp;&nbsp;<?php echo $printdataRes[0]['SURVEY_NUMBER']; ?></td>
<td style="width: 74px; " valign="bottom" colspan="2" align="center">&nbsp;</td>
</tr>

<tr style="height: 20px;"  >
<td style="width: 40px; " valign="bottom" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['CANE_TYPE']; ?></td>
<td style="width: 56px; " valign="bottom" colspan="2" align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $printdataRes[0]['CV_NAME']; ?></td>
<td style="width: 68px; " valign="bottom" colspan="2" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['CANE_QUALITY']; ?></td>
<td style="width: 74px; " valign="top" colspan="2" align="center"><?php echo $printdataRes[0]['DSL_DATE']; ?></td>


</tr>
<tr style="height: 20px;">
<td style="width: 40px; " colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['HRV_NAME']; ?></td>
<td style="width: 74px; " colspan="2" align="center">&nbsp;<?php echo $printdataRes[0]['SHIFT_CODE']; ?></td>
</tr>

<tr style="height: 20px;">
<td style="width: 40px; " colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['TRNS_NAME']; ?></td>
<td style="width: 74px; " colspan="2" align="center">&nbsp;<?php echo $printdataRes[0]['DSL_VILLAGE_NAME']; ?></td>
</tr>

<tr style="height: 20px;">
<td style="width: 40px; " valign="top" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['VEHICLE_NUMBER']; ?></td>
<td style="width: 65px; " valign="top" colspan="2" align="right"><?php echo $printdataRes[0]['TRAILER']; ?></td>
<td style="width: 56px; " colspan="4">&nbsp;</td>

</tr>
<tr style="height: 20px;">
<td style="width: 40px; " valign="top" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['VEHICLE_TYPE']; ?></td>
<td style="width: 65px; " valign="top" colspan="2" align="right">&nbsp;<?php echo $printdataRes[0]['WIRE_ROPE']; ?></td>
<td style="width: 56px; " valign="top" colspan="2">&nbsp;:</td>
<td style="width: 56px; " valign="top" colspan="2" align="center">&nbsp;<?php echo $printdataRes[0]['SHORT_DISTANCE']; ?></td>
</tr>

<tr style="height: 20px;">
<td style="width: 40px; " valign="top" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['HARVESTING_TYPE']; ?></td>
<td style="width: 65px; " valign="top" colspan="2" align="right">&nbsp;<?php echo $printdataRes[0]['SHORT_DISTANCE']; ?></td>
<td style="width: 56px; " valign="top" colspan="4">&nbsp;</td>
</tr>

<tr style="height: 20px;">
<td style="width: 40px; " colspan="7">&nbsp;</td>
<td style="width: 74px; " valign="top"  colspan="2">&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['TRNS_NAME']; ?></td>
</tr>

<tr style="height: 26px;" >
<td colspan="4" width="23%"></td>
<td width="10%" valign="bottom">&nbsp;<?php echo $printdataRes[0]['LOADED_WT_MT']; ?></td>
<td width="10%" valign="bottom">&nbsp;<?php echo $printdataRes[0]['LOADED_WT_QT']; ?></td>
<td width="10%" valign="bottom">&nbsp;<?php echo $printdataRes[0]['LOADED_WT_KGS']; ?></td>
<td colspan="2" width="20%"></td>

</tr>
<tr style="height: 29px;" >
<td style="width: 40px;" colspan="4"></td>
<td style="width: 40px;" valign="bottom">&nbsp;<?php echo $printdataRes[0]['UNLOADED_WT_MT']; ?></td>
<td style="width: 40px;" valign="bottom">&nbsp;<?php echo $printdataRes[0]['UNLOADED_WT_QT']; ?></td>
<td style="width: 64px;" valign="bottom">&nbsp;<?php echo $printdataRes[0]['UNLOADED_WT_KGS']; ?></td>
<td style="width: 74px;" colspan="2" valign="top" align="center">&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['VEHICLE_TYPE']; ?></td>

</tr>
<tr style="height: 6px;" >
<td style="width: 40px; " colspan="9"></td>
</tr>

<tr style="height: 25px;" >
<td style="width: 40px; " colspan="4"></td>
<td style="width: 40px; " valign="bottom">&nbsp;<?php echo $printdataRes[0]['CANE_WT_MT']; ?></td>
<td style="width: 40px; " valign="bottom">&nbsp;<?php echo $printdataRes[0]['CANE_WT_QT']; ?></td>
<td style="width: 64px; " valign="bottom">&nbsp;&nbsp;<?php echo $printdataRes[0]['CANE_WT_KGS']; ?></td>
<td style="width: 74px; " valign="bottom" colspan="2" align="center">&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['VEHICLE_NUMBER']; ?></td>

</tr>
<tr style="height: 29px;" >
<td style="width: 40px; " colspan="4"></td>
<td style="width: 68px; " valign="bottom">&nbsp;<?php echo $printdataRes[0]['BINDING_WT_MT']; ?></td>
<td style="width: 64px; " valign="bottom">&nbsp;<?php echo $printdataRes[0]['BINDING_WT_QT']; ?></td>
<td style="width: 64px; " valign="bottom">&nbsp;<?php echo $printdataRes[0]['BINDING_WT_KGS']; ?></td>
<td style="width: 74px; " colspan="2" valign="bottom" align="center"></td>

</tr>
<tr style="height: 30px;" >
<td style="width: 56px; " colspan="4"></td>
<td style="width: 65px; height: 24px; " valign="bottom">&nbsp;<?php echo $printdataRes[0]['NET_WT_MT']; ?></td>
<td style="width: 68px; height: 24px;" valign="bottom">&nbsp;<?php echo $printdataRes[0]['NET_WT_QT']; ?></td>
<td style="width: 64px; height: 24px;" valign="bottom">&nbsp;<?php echo $printdataRes[0]['NET_WT_KGS']; ?></td>
<!-- <td style="width: 74px; ">&nbsp;</td>
<td style="width: 66px; ">&nbsp;</td> -->
<td style="width: 74px; " colspan="2" valign="top" align="center"><?php echo $printdataRes[0]['DSL_ALLOTED_QTY']; ?></td>

</tr>

<tr style="height: 22px;">
<td style="width: 56px; height: 20px;" colspan="9"></td>

</tr>

<tr style="height: 20px;">
<td style="width: 56px; height: 20px;" colspan="2">&nbsp;</td>
<td style="width: 65px; height: 20px;" colspan="3">&nbsp;<?php echo $printdataRes[0]['VEHICLE_IN_TIME']; ?></td>
<td style="width: 74px; height: 20px;" colspan="2">&nbsp;<?php echo $printdataRes[0]['VEHICLE_OUT_TIME']; ?></td>
<td style="width: 66px; height: 20px;" colspan="2">&nbsp;</td>
</tr>

<tr style="height: 20px;" >
<td style="width: 56px; height: 20px;" colspan="1">&nbsp;<?php echo $printdataRes[0]['WEIGHING_USER']; ?></td>
<td style="width: 66px; height: 20px;" colspan="1">&nbsp;</td>
<td style="width: 66px; height: 20px;" colspan="1">&nbsp;</td>
<td style="width: 66px; height: 20px;" colspan="1">&nbsp;</td>
<td style="width: 74px; height: 20px;" colspan="1">&nbsp;  <?php echo $printdataRes[0]['UNLOADING_USER']; ?></td>

<td style="width: 66px; height: 20px;" align="right">&nbsp;<?php echo $printdataRes[0]['CHIT_BOY']; ?></td>
<td style="width: 66px; height: 20px;">&nbsp;</td>

<td style="width: 66px; height: 20px;">&nbsp;</td>
<td style="width: 66px; height: 20px;">&nbsp;  <?php echo $printdataRes[0]['UNLOADING_USER']; ?></td>
</tr>

</tbody>
</table>      
      </div>
   </body>
</html>

<script>
window.print();
</script>
</html>
