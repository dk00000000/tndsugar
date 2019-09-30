
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
 // print_r($printdataRes);

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">      
      <title> Weighing Print</title>
    <link href="https://fonts.googleapis.com/css?family=Hind" rel="stylesheet">
    <style>    
      *{padding:0; margin:0;}
         html, body {font-family: 'verdana', sans-serif;font-size: 12px;}
        .printer-wrapper{width: 210mm; margin:0 auto;}
    .opacity0{opacity:0; margin-right: 7px;}
    .left-side-table{margin-top: 18px;}
    .left-side-table tr td{height:18.5px;}
    .left-side-table2{margin-top: 15px;}
    .left-side-table2 tr td{height:20.8px;}   
    .left-side-table tr td:nth-child(2) .opacity0,
    .left-side-table2 tr td:nth-child(2) .opacity0{margin-right:40px;}
    
    .right-side-table{vertical-align: center;margin-top: 12px;}
    .right-side-table tr td{height:30.8px;}
    </style>
   </head>
   <body style="font-size:11px">
      <div class="printer-wrapper">
   
         <table style="width: 210mm; margin:0 auto;" cellpadding="0" cellspacing="0" border="0">
            <tbody>
               <tr>
                  <td style="width: 100%; vertical-align: top;">
                     <table style="width: 100%; vertical-align: top;">                        
            <tbody>
                           <tr>
                              <td style="width: 50%;height: 16px;" valign="bottom"><span class="opacity0"></span> <?php echo $printdataRes[0]['SEASON']; ?></td>
                              <td style="width: 50%;">&nbsp;</td>
                           </tr>
                           <tr bgcolor="">
                              <td style="width: 50%;height: 26px;" align="left" valign="bottom"><span class="opacity0"></span> 
                                <?php echo $printdataRes[0]['WS_SRNO']; ?></td>
                              <td style="width: 50%;">&nbsp;</td>
                           </tr>
                        </tbody>
                     </table>
                  </td>                  
               </tr>
               <tr style="">
                  <td colspan="3">                     
                     <table style="width: 100%;"  >
                        <tbody>
                           <tr>
                              <td style="width: 50%; vertical-align:top;">
                                 <table style="width:100%;" class="left-side-table " border="0" cellpadding="0" cellspacing="0">  
                                    <tbody>
                                  <tr >
                                          <td style="width:50%;padding-top: 20px;padding-bottom: 10px;"><span class="opacity0"> :</span><?php echo $printdataRes[0]['WS_DATE']; ?></td>
                                          <td style="width:50%;"><span class="opacity0"> :  </span> <?php echo $printdataRes[0]['SHIFT_CODE']; ?></td>
                                       </tr>
                     
                     <tr >
                     <td style="width:50%;"><span class="opacity0">:</span> <?php echo $printdataRes[0]['SEC_NAME']; ?></td> 
                                          <td style=""><span class="opacity0 ">:</span> <?php echo $printdataRes[0]['SEC_NAME']; ?></td>
                                       </tr>
                                       <tr>
                                          <td style="width:50%;padding-top: 15px;padding-bottom: 3px; "><span class="opacity0">:</span> &nbsp;&nbsp;<?php echo $printdataRes[0]['SABHASAD']; ?> </td>
                                          <td style="width:50%; " align="center"><span class="opacity0">लागाण फॉर्म नं:सभासद प्रकार:</span> <?php echo $printdataRes[0]['FREG_NUMBER']; ?> </td>
                                       </tr>
                                       <tr>
                                          <td style="width:50%; "><span class="opacity0">ऊस पुरवताचे :</span> <?php echo $printdataRes[0]['FRMR_NAME']; ?></td>
                                          <td style="width:50%; ">&nbsp;</td>
                                       </tr>
                     </tbody>
                     </table>
                     <table style="width:100%;" class="left-side-table2" border="0" cellpadding="0" cellspacing="0">  
                                    <tbody>
                                       <tr>
                                          <td style="width:50%; "><span class="opacity0">नं:</span> <?php echo $printdataRes[0]['SURVEY_NUMBER']; ?></td>
                                          <td style="width:50%; "><span class="opacity0" style="margin-right:30px;">:  </span>&nbsp;&nbsp;<?php echo $printdataRes[0]['ANTAR']; ?></td>
                                       </tr>
                                       <tr>
                                          <td style="width:50%; "><span class="opacity0">:</span> <?php echo $printdataRes[0]['CV_NAME']; ?></td>
                                          <td style="width:50%; "><span class="opacity0" style="margin-right:20px;">उ:  </span> &nbsp;&nbsp;<?php echo $printdataRes[0]['CANE_TYPE']; ?></td>
                                       </tr>
                                       <tr>
                                          <td style="width:50%;padding-top: 10px;padding-bottom: 8px; "><span class="opacity0">:</span><?php echo $printdataRes[0]['HARVESTING_TYPE']; ?></td>
                                          <td style="width:50%; "><span class="opacity0" style="margin-right:20px;">: </span> &nbsp;&nbsp;<?php echo $printdataRes[0]['CANE_QUALITY']; ?></td>
                                       </tr>
                                       <tr>
                                          <td style="width:50%; padding-top: 10px;padding-bottom: 8px;"><span class="opacity0">:</span> <?php echo $printdataRes[0]['VEHICLE_TYPE']; ?></td>
                                          <td style="width:50%; "><span class="opacity0" style="margin-right:20px;"> रोप: </span> &nbsp;&nbsp;<?php echo $printdataRes[0]['WIRE_ROPE']; ?></td>
                                       </tr>
                                       <tr bgcolor="">
                                          <td style=" height:25px;width:60%; " ><span class="opacity0">वाहतूकदारवाहतूकदार:</span><?php echo $printdataRes[0]['TRNS_NAME']; ?></td>
                                          <td style="width:40%; ">&nbsp;</td>
                                       </tr>
                                       <tr bgcolor="">
                                          <td style="height:25px;width:50%; "><span class="opacity0">वाहतूकदारवाहतू</span> <span style="vertical-align: text-bottom;"><?php echo $printdataRes[0]['HRV_NAME']; ?></span></td>
                                          <td style="width:50%; ">&nbsp;</td>
                                       </tr>
                                       <tr bgcolor="">
                                          <td style="height:30px;width:50%; " valign="bottom"><span class="opacity0"> :</span> <span><?php echo $printdataRes[0]['VEHICLE_NUMBER']; ?></span></td>
                                          <td style="height:30px;width:50%; " valign="bottom"><span class="opacity0"> : </span><span> <?php echo $printdataRes[0]['TRAILER']; ?></span></td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </td>
                              <td style="width: 50%; vertical-align:top;">
                                 <table style="width:100%;" class="right-side-table" border="0" cellpadding="0" cellspacing="0">
                                    <tbody>
                                       <tr>
                                          <td style="width: 50%;" colspan="2">
                                             <table style="width:100%;" border="0" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                   <tr>
                                                      <td style="width:5cm; " class="opacity0">वजन तपशील</td>
                                                      <td style="width:2cm" class="opacity0">में. टन</td>
                                                      <td style="width:2cm" class="opacity0">किवंटल</td>
                                                      <td style="width:2cm" class="opacity0">किलो</td>
                                                   </tr>
                                                   <tr>
                                                      <td style="width:5cm; " class="opacity0">उसासह गाडीचे वजन</td>
                                                      <td style="">&nbsp;&nbsp;&nbsp;
                                                        <?php echo $printdataRes[0]['LOADED_WT_MT']; ?>
                                                      </td> 
                                                      <td style="">&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['LOADED_WT_QT']; ?></td>
                                                      <td style="">&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['LOADED_WT_KGS']; ?></td>
                                                   </tr>
                                                   <tr>
                                                      <td style="width:5cm; " class="opacity0">वजा रिकाया गाडीचे वजन</td>
                                                      <td style="">&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['UNLOADED_WT_MT']; ?></td>
                                                      <td style="">&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['UNLOADED_WT_QT']; ?></td>
                                                      <td style="">&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['UNLOADED_WT_KGS']; ?></td>
                                                   </tr>
                                                   <tr>
                                                      <td style="width:5cm; " class="opacity0">उसाचे वजन</td>
                                                      <td style="">&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['CANE_WT_MT']; ?></td>
                                                      <td style="">&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['CANE_WT_QT']; ?></td>
                                                      <td style="">&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['CANE_WT_KGS']; ?></td>
                                                   </tr>
                                                   <tr>
                                                      <td style="width:5cm; " class="opacity0">वजा बांडिंग मटेरियल:</td>
                                                      <td style="">&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['BINDING_WT_MT']; ?></td>
                                                      <td style="">&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['BINDING_WT_QT']; ?></td>
                                                      <td style="">&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['BINDING_WT_KGS']; ?></td>
                                                   </tr>
                                                   <tr>
                                                      <td style="width:5cm; " class="opacity0">निव्वळ उसाचे वजन</td>
                                                      <td style="">&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['NET_WT_MT']; ?></td>
                                                      <td style="">&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['NET_WT_QT']; ?></td>
                                                      <td style="">&nbsp;&nbsp;&nbsp;<?php echo $printdataRes[0]['NET_WT_KGS']; ?></td>
                                                   </tr>
                                                </tbody>
                                             </table>
                                          </td>
                                       </tr>
                                       <tr style="">
                                          <td style="vertical-align:bottom;"><span class="opacity0" style="margin-left: :10px;">स्लिप बॉय : &nbsp;</span> <?php echo $printdataRes[0]['CHIT_BOY']; ?></td>
                                          <td style="vertical-align:bottom;"><span class="opacity0" style="margin-right:20px;">स्लिप भरणार:</span> <?php echo $printdataRes[0]['CHIT_BOY']; ?></td>
                                       </tr>
                                       <tr style="" bgcolor="">
                                          <td style="height: 44px; vertical-align:bottom;"><span class="opacity0" style="margin-right:20px;">भरवजन क्लार्क: &nbsp;</span> <?php echo $printdataRes[0]['WEIGHING_USER']; ?></td>
                                          <td style=" height: 44px;vertical-align:bottom;"><span class="opacity0" style="margin-right:20px;">रिकामे वजन क्लार्क :</span> <?php echo $printdataRes[0]['UNLOADING_USER']; ?></td>
                                       </tr>
                                       <tr style="" bgcolor="">
                                          <td style="height: 24px; vertical-align:bottom;" colspan="2"><span class="opacity0" style="margin-right:25px; padding-top: 15px;padding-bottom: 5px;">गाड़ी आलेली वेळ: </span><?php echo $printdataRes[0]['VEHICLE_IN_TIME']; ?></td>
                                       </tr>
                                       <tr style="" bgcolor="">
                                          <td style="height: 34px; vertical-align:bottom;" colspan="2"><span class="opacity0" style="margin-right:25px; padding-top: 15px;padding-bottom: 5px;">गाड़ी आलेली वेळ: </span><?php echo $printdataRes[0]['VEHICLE_OUT_TIME']; ?></td>
                                       </tr>
                                      <!--  <tr style=""  >
                                          <td style="height: 34px; vertical-align:bottom;"><span class="opacity0" style="margin-right:50px; " ></span> <?php// echo $printdataRes[0]['VEHICLE_OUT_TIME']; ?></td>
                                          
                                       </tr> -->
                                      
                                    </tbody>
                                 </table>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </td>
               </tr>
               <tr style="">
                  <td style="" colspan="3">                     
                     <table style="width:100%;"  border="0" cellpadding="0" cellspacing="0">
                        <tbody>           
                           <tr >
                              <td style="padding-top: 25px;padding-bottom:0px;"><span class="opacity0">:</span> <?php echo $printdataRes[0]['DSL_NO']; ?></td>
                              <td style="padding-top: 25px;padding-bottom: 0px;"><span class="opacity0">:</span> <?php echo $printdataRes[0]['TRNS_NAME']; ?></td>
                              <td style="padding-top: 25px;padding-bottom: 0px;"><span class="opacity0">स्लिप:</span> <?php echo $printdataRes[0]['DSL_ALLOTED_QTY']; ?></td>
                              <td style="">&nbsp;</td>
                           </tr>
              
              <!-- <tr>
                              <td style=""><span class="opacity0">स्लिप नं:</span> </td>
                           </tr>
               -->
                           <tr bgcolor="">
                              <td style="padding-top: 25px;padding-bottom:0px;"><span class="opacity0" >: </span><?php echo $printdataRes[0]['DSL_DATE']; ?></td>
                              <td style="padding-top: 25px;padding-bottom:0px;"><span class="opacity0">:</span> <?php echo $printdataRes[0]['VEHICLE_NUMBER']; ?></td>
                              <td style=""><span class="opacity0">ऑइल: - </span></td>
                              <td style=""><span class="opacity0">क्लार्क सही</span></td>
                           </tr>
                        </tbody>
                     </table>
                  </td>
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
