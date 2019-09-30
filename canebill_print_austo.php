
<?php 
  require_once('dashboard.php');
  include('readfile.php');
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
  $qryPath = "util/readquery/general/canebill_print.ini";  
 

  $oldFilter = array(':PCOMP_CODE',':PTXN_SEASON',':PFORT_NIGHT',':PSECTION',':PBILL_TYPE',':PFARMER');
  $newFilter = array($_SESSION['COMP_CODE'],$_REQUEST['season'],$_REQUEST['fornight'],$_REQUEST['section'],$_REQUEST['bill_type'],$_REQUEST['farmer']); 
  

  $procedure = $qryObj->fetchQuery($qryPath,'Q001','PROCEDURE',$oldFilter,$newFilter);
  //echo $procedure;
  $procedureRes = $dsbObj->getData($procedure);
  
  //GET DATA
  $getAllData = $qryObj->fetchQuery($qryPath,'Q001','GET_DATA',$oldFilter,$newFilter);
  //echo "<br>".$getAllData;
  $allDataRes = $dsbObj->getData($getAllData);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Dund Bill Print</title>
    <link href="https://fonts.googleapis.com/css?family=Hind" rel="stylesheet">
      <style>
    *{padding:0; margin:0;}
         html, body {font-family: 'Hind', sans-serif;font-size: auto; padding:0; margin:0;}
       .printer-wrapper{width: 210mm; margin:0 auto;}
     .opacity0{opacity:0;}
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
          
          //Set Filters
          $oldFilter = array(':PCOMP_CODE',':PTXN_SEQ');
          $newFilter = array($_SESSION['COMP_CODE'],$value['TXN_SEQ']);
          //For Header Data
          $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','HEADERPRINTQRY',$oldFilter,$newFilter);

          $printdataRes = $dsbObj->getData($printdataQry);
          
          //For Details Data 
          $detailQry = $qryObj->fetchQuery($qryPath,'Q001','DEATAILPRINTQRY',$oldFilter,$newFilter);
         /* echo $printdataQry;
          echo "<br>".$detailQry;
          exit(0);*/
          $detailRes = $dsbObj->getData($detailQry);
   ?>
      <div class="printer-wrapper page">
         <table style="width: 210mm; margin:0 auto;" class="table_class" cellpadding="0" cellspacing="0">
            <tbody>
               <tr style="">
                  <td style="width: 33.3%;">
                     <table style="width:100%;"  cellpadding="0" cellspacing="0">
                        <tbody>
                           <tr>
                              <td style="width: 33.3%; text-align:center; opacity:0;">logo</td>
                              <td style="width: 33.3%; text-align:center; opacity:0;">
                                 <p>दौंड शुगर लिमिटेड</p>
                                 <p>आलेगाव, ता. दौंड, जि. पुणे</p>
                              </td> 
                              <td style="width: 33.3%; text-align: center;padding-top: 35px;"><span class="opacity0">हंगाम:</span> <?php  echo $printdataRes[0]['SEASON'];?></td>
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
                              <td style="width: 19.3%; text-align: left;" height="100"><span class="opacity0"> हंगाम:</span> 2015-16</td>
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
                              <td style="width: 75%;" colspan="2" ><span class="opacity0" style="margin-right:25px;">उस उत्पादकाचे नाव:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php  echo $printdataRes[0]['FARMER']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$printdataRes[0]['PRT_MNAME'];?></td>
                              <td style="width: 25%;" rowspan="2"><span class="opacity0">बिल क्र:</span> <?php echo $printdataRes[0]['BILL_NO']; ?></td>
                           </tr>
                           <tr>
                              <td style="width: 37.5%"><span class="opacity0" style="margin-right:25px;">गाव:</span><?php echo $printdataRes[0]['SR_MNAME']; ?></td>
                              <td style="width: 37.5%"><span class="opacity0">गट:</span> <?php echo $printdataRes[0]['SC_MNAME']; ?></td>
                           </tr>
                        </tbody>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td style="width: 33.3%;">
                     <table style="width:96%; margin:0 auto; " cellpadding="0" cellspacing="0">
                        <tbody>
                           <tr style="">
                              <td style="width: 30.0%; vertical-align: center; text-align:center;" rowspan="2"><span class="opacity0">पंधरवडा दि.</span> <?php echo $printdataRes[0]['SEASON_START_DT']; ?> <span style="padding:0 15px;" class="opacity0">ते</span> <?php echo $printdataRes[0]['SEASON_END_DT']; ?></td>
                              <td style="width: 21.5%; vertical-align: bottom; text-align:center;" rowspan="2" class="opacity0">अखेर आलेला उस</td>
                              <td style="width: 13%; vertical-align: bottom; text-align: center" class="opacity0">वजन (मे. टन)</td>
                              <td style="width: 13%; vertical-align: bottom; text-align: center" class="opacity0">प्रती टनास दर रु.</td>
                              <td style="width: 13%; vertical-align: bottom; text-align: center" class="opacity0">रक्कम रुपये</td>
                           </tr>
                           <tr style="">
                              <td style="text-align:left;"><?php echo $printdataRes[0]['TONNAGE']; ?></td>
                              <td style="text-align:left;"><?php echo $printdataRes[0]['RATE']; ?></td>
                              <td style="text-align:left;"><?php echo $printdataRes[0]['TXD_AMT']; ?></td>
                           </tr>
                           <tr style="">
                              <td style="text-align:right;" colspan="2" class="opacity0"> वजावटीचा तपशील</td>
                              <td style="">&nbsp;</td>
                              <td style="" colspan="2"><span class="">स्लीप संख्या  :</span> <?php echo $printdataRes[0]['SLIPS']; ?></td>                              
                           </tr>
                 
                            <tr>
                            </tr>
                           <?php for ($x = 0; $x < 5; $x++) { if($detailRes[$x]['SR'] !='') { ?>
                           <tr style="">
                              <th style="vertical-align: text-top;text-align: left; font-weight: normal;"><?php echo $detailRes[$x]['SR']." ".$detailRes[$x]['DED_NAME']; ?></th>
                              <th style="text-align:center;vertical-align: text-top;text-align: left;"><?php echo $detailRes[$x]['COULUMN2'];?> </th>
                              <th style="text-align:left;vertical-align: text-top;text-align: left;" colspan="2"><?php echo $detailRes[$x]['COULUMN3'];?></th>   
                              <th style="text-align:left;vertical-align: text-top;text-align: left;"><?php echo $detailRes[$x]['TXD_AMT'];?></th>
                               <?php }else { ?>
                                <th style="vertical-align: text-top;text-align: left;">&nbsp;</th>
                               <?php } ?>
                           </tr>

                           <?php } ?>
                          
                           <tr style="">
                             
                               <td style="padding-left: 170px;vertical-align: text-top;padding-top: 32px;" colspan="2"> <?php echo $marathinumber->getIndianCurrency($printdataRes[0]['NETT_AMT']); ?> फक्त </td>
                               <td style="text-align:right;" colspan="2" class="opacity0"> एकूण वजावट ::</td>
                              <td style="text-align:left;text-top;padding-top: 32px;"><span style="vertical-align: 14px;"><?php echo $printdataRes[0]['DEDUCTION']; ?></span></td>
                             

                           </tr>
                           <tr style="">
                              <td style="padding-left: 30px;vertical-align: text-top;padding-top: 10px;" colspan="2"><span class="opacity0">बँक शाखा:</span> <span style="padding-left: 0px;padding-top: 3px;"><?php echo $printdataRes[0]['BANK_BRANCH_NAME']; ?></span></td>
                              <td style="text-align:right;" colspan="2" class="opacity0"> निव्वळ देणे:</td>
                              <td style="text-align:left;"><span style="vertical-align: 34px;"><?php echo $printdataRes[0]['NETT_AMT']; ?></span></td>
                           </tr>
                           
                           <tr style="">
                              <td style="" colspan="5"><span class="opacity0">खाते नं.: </span><span style="padding-left: 40px;"><?php echo $printdataRes[0]['PRT_ACNO']; ?></span></td>
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
//alert(print);
</script>
</html>
