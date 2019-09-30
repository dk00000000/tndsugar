
<?php 
  require_once('dashboard.php');
  include('readfile.php');
  require_once("numbertomarathiword.php");
  $marathinumber = new NumbertoMarathi;
  $lgs = new Logs();
  $qryObj = new Query();
  $dsbObj = new Dashboard(); 
  $rfObj = new ReadFile();
  $lang=strtolower($_SESSION['LANG']);
  $qryPath = "util/readquery/general/canebill_print.ini";  
 

  $oldFilter = array(':PCOMP_CODE',':PTXN_SEASON',':PFORT_NIGHT',':PSECTION',':PBILL_TYPE',':PFARMER');
  $newFilter = array($_SESSION['COMP_CODE'],$_REQUEST['season'],$_REQUEST['fornight'],$_REQUEST['section'],$_REQUEST['bill_type'],$_REQUEST['farmer']); 
  

  $procedure = $qryObj->fetchQuery($qryPath,'Q001','PROCEDURE',$oldFilter,$newFilter);

  $procedureRes = $dsbObj->getData($procedure);
  
  //GET DATA
  $getAllData = $qryObj->fetchQuery($qryPath,'Q001','GET_DATA',$oldFilter,$newFilter);
  $allDataRes = $dsbObj->getData($getAllData);
//  echo $procedure." ".$getAllData;
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="icon" href="images/favicon.png" >
      <title>Astoria Cane Bill</title>
	  <link href="https://fonts.googleapis.com/css?family=Hind" rel="stylesheet">
      <style>
		*{padding:0; margin:0;}
         html, body {font-family: 'Hind', sans-serif;font-size: auto; padding:0; margin:0;}
       .printer-wrapper{width: 210mm; margin:0 auto;}
		   .opacity0{opacity:;}
       .table_class tr td{height:30.8px;}
       .table_class tr th{height:15.0px;}

       tr.no-spacing {
        border-spacing:0; /* Removes the cell spacing via CSS */
        border-collapse: collapse;  /* Optional - if you don't want to have double border where cells touch */
   }
     /* For Page Breack */
     .page
      {
        page-break-before: always; 
        page-break-inside: avoid;
      }

      </style>
   </head>
   <body style="font-size:12px;" >
   <?php 
       foreach ($allDataRes as $key => $value)
         {
          $oldFilter = array(':PCOMP_CODE',':PTXN_SEQ');
          $newFilter = array($_SESSION['COMP_CODE'],$value['TXN_SEQ']);
          $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','HEADERPRINTQRY',$oldFilter,$newFilter);
          $printdataRes = $dsbObj->getData($printdataQry);
          $detailQry = $qryObj->fetchQuery($qryPath,'Q001','DEATAILPRINTQRY',$oldFilter,$newFilter);
          //echo $detailQry;
        
          $detailRes = $dsbObj->getData($detailQry);
   ?>
      <div class="printer-wrapper page">
         <table style="width: 210mm; margin:0 auto;" border="1" class="table_class" cellpadding="0" cellspacing="0">
            <tbody>
               <tr style="">
                  <td style="width: 33.3%;">
                     <table style="width:100%;"  cellpadding="0" cellspacing="0">
                        <tbody>
                           <tr>
                              <td style="width: 33.3%; text-align:center; opacity:0;">logo</td>
                              <td style="width: 33.3%; text-align:center; opacity:;">
                                 <p style="font-size: 12px;font-weight: bold;">अस्टोरिया अँग्रो अँण्ड अलाईड इंडस्ट्रीज प्रायव्हेट लिमिटेड </p>
                                 <p>समशेरपूर, ता. जि. नंदुरबार </p>
                                 <p>ऊस खरेदी बिल</p>

                              </td> 
                              <td style="width: 33.3%; text-align: center;padding-top: 35px;"><span class="opacity0"><strong>हंगाम:</strong></span> <?php  echo $printdataRes[0]['SEASON'];?></td>
                           </tr>
                        </tbody>
                     </table>
                  </td>
               </tr>
			    <tr style="display:none;">
                  <td style="text-align: right; padding-right:30px;">                     
                    <table style="width:100%;"  cellpadding="0" cellspacing="0">
                        <tbody>
                         
                           <tr>
                              <td style="width: 33.3%; text-align:center;">&nbsp;</td>
                              <td style="width: 33.3%; text-align:center;">&nbsp;</td>
                              <td style="width: 19.3%; text-align: left;" height="100"><span class="opacity0"><strong>हंगाम:</strong></span> <?php  echo $printdataRes[0]['SEASON'];?></td>
                           </tr>
                        </tbody>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td style="width: 33.3%;">
                     <table style="width:100%;" cellpadding="0" cellspacing="0">
                        <tbody>
                           <tr>
                              <td style="width: 75%;" colspan="2" ><span class="opacity0" style="margin-right:25px;"><strong>उस उत्पादकाचे नाव:</strong></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php  echo $printdataRes[0]['FARMER']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$printdataRes[0]['PRT_MNAME'];?></td>
                              <td style="width: 25%;" rowspan="2"><span class="opacity0"><strong>बिल क्र:</strong></span> <?php echo $printdataRes[0]['BILL_NO']; ?></td>
                           </tr>
                           <tr>
                              <td style="width: 37.5%"><span class="opacity0" style="margin-right:25px;"><strong>गाव:</strong></span><?php echo $printdataRes[0]['SR_MNAME']; ?></td>
                              <td style="width: 37.5%"><span class="opacity0"><strong>गट:</strong></span> <?php echo $printdataRes[0]['SC_MNAME']; ?></td>
                           </tr>
                        </tbody>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td style="width: 33.3%;">
                     <table style="width:100%; margin:0 auto;" border="1" cellpadding="0" cellspacing="0">
                        <tbody>
                           <tr style="">
                              <td style="width: 30.0%; vertical-align: center; text-align:center;" rowspan="2"><span class="opacity0"><strong>पंधरवडा दि.</strong></span> <?php echo $printdataRes[0]['SEASON_START_DT']; ?> <span style="padding:0 15px;" class="opacity0"><strong>ते</strong></span> <?php echo $printdataRes[0]['SEASON_END_DT']; ?></td>
                              <td style="width: 21.5%; vertical-align: bottom; text-align:center;" rowspan="2" class="opacity0">अखेर आलेला उस</td>
                              <td style="width: 13%; vertical-align: bottom; text-align: center" class="opacity0"><strong>वजन (मे. टन)</strong></td>
                              <td style="width: 13%; vertical-align: bottom; text-align: center" class="opacity0"><strong>प्रती टनास दर रु.</strong></td>
                              <td style="width: 13%; vertical-align: bottom; text-align: center" class="opacity0" colspan="2"><strong>रक्कम रुपये</strong></td>
                           </tr>
                           <tr style="">
                              <td style="text-align:center;"><?php echo $printdataRes[0]['TONNAGE']; ?></td>
                              <td style="text-align:center;"><?php echo $printdataRes[0]['RATE']; ?></td>
                              <td style="text-align:center;" colspan="2"><?php echo $printdataRes[0]['TXD_AMT']; ?></td>
                           </tr>
                           <tr style="">
                              <td style="text-align:center;" colspan="2" class="opacity0"> वजावटीचा तपशील</td>
                              <!-- <td style="">&nbsp;</td> -->
                              <td style="text-align: center;" colspan="4" ><span class="opacity0"><strong>स्लीप संख्या:</strong></span> <?php echo $printdataRes[0]['SLIPS']; ?></td>                              

                             </tr>
	

                           <tr style="">
                              <th style="vertical-align: text-top;text-align: left;font-weight: bold;">&nbsp; Name</td>
                              <th style="text-align:right;vertical-align: text-top;font-weight: bold;">Amount &nbsp;</td>
                              <th style="text-align:left;vertical-align: text-top;text-align: left;font-weight: bold;" colspan="2">&nbsp; Name</td> 
                              <th style="text-align:right;vertical-align: text-top;font-weight: bold;" colspan="2">Amount &nbsp;</td>
                           </tr>
                        
                        <?php for ($x = 0; $x < 5; $x++) { if($detailRes[$x]['SR'] !='') { ?>        
                           <tr style="">
                              <th style="vertical-align: text-top;text-align: left;font-weight: normal;">&nbsp;<?php echo $detailRes[$x]['SR']." ".$detailRes[$x]['DED_NAME']; ?></td>
                              <th style="text-align:right;vertical-align: text-top;font-weight: normal;"><?php echo $detailRes[$x]['COULUMN2'];?> &nbsp;</td>
                              <th style="text-align:left;vertical-align: text-top;text-align: left;font-weight: normal;" colspan="2">&nbsp;<?php echo $detailRes[$x]['COULUMN3'];?></td>
                              <th style="text-align:right;vertical-align: text-top;font-weight: normal;" colspan="2"><?php echo $detailRes[$x]['TXD_AMT'];?> &nbsp;</td>
                              <?php }else { ?>
                               <th style="vertical-align: text-top;text-align: left;font-weight: normal;">&nbsp;</td>
                              <th style="text-align:right;vertical-align: text-top;font-weight: normal;"> &nbsp;</td>
                              <th style="text-align:left;vertical-align: text-top;text-align: left;font-weight: normal;" colspan="2">&nbsp; </td>
                              <th style="text-align:right;vertical-align: text-top;font-weight: normal;" colspan="2"> &nbsp;</td>
                               <?php } ?>
                           </tr>
                        <?php } ?>   
                           
                        
                           <tr style="">
                              <td style="padding-left: 0px;vertical-align:text-top;padding-top:5px;text-align: left;" colspan="2"><strong>&nbsp;अक्षरी रुपये:</strong><?php echo $marathinumber->getIndianCurrency($printdataRes[0]['NETT_AMT']); ?> फक्त</td>
                              <td style="text-align:right;" colspan="3" class="opacity0"><strong>एकूण वजावट: </strong></td>
                              <td style="text-align:right;padding-top: 6px;"><span style="vertical-align: 14px;"><?php echo $printdataRes[0]['DEDUCTION']; ?></span></td>
                           </tr>
                          
                           <tr style="">
                              <td style="padding-left: 0px;vertical-align: text-top;padding-top: 5px;" colspan="2"><span class="opacity0"><strong>&nbsp;बँक शाखा:</strong></span> <span style="padding-left: 0px;padding-top: 3px;"><?php echo $printdataRes[0]['BANK_BRANCH_NAME']; ?></span></td>
                              <td style="text-align:right;" colspan="3" class="opacity0"><strong>निव्वळ देणे:</strong></td>
                              <td style="text-align:right;"><span style="vertical-align: 14px;"><?php echo $printdataRes[0]['NETT_AMT']; ?></span></td>
                           </tr>
                           
                           <tr style="">
                              <td style="" colspan="5"><span class="opacity0"><strong>&nbsp;खाते नं.: </strong></span><span style="padding-left: 40px;"><?php echo $printdataRes[0]['PRT_ACNO']; ?></span></td>
                           </tr>                                                      
                        </tbody>
                     </table>
                  </td>
               </tr>
            </tbody>
         </table>         
      </div>
      <?php  }  ?> 
   </body>
   
<script>
   window.print();
</script>
</html>

