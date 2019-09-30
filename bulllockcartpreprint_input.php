<?  
  require_once('dashboard.php');
  include('readfile.php');
  //require_once('header_menu.php');
  
  $lgs = new Logs();
  $qryObj = new Query();
  $dsbObj = new Dashboard(); 
  $rfObj = new ReadFile();
  $lang=strtolower($_SESSION['LANG']);
  $qryPath = "util/readquery/general/bulllockcartpreprint.ini"; 

  $menu_code=$_REQUEST['menu_code'];
  //echo $menu_code;
  $oldLovFilter = array(':PCOMP_CODE',':PMENU_CODE');
  $newLovFilter = array($_SESSION['COMP_CODE'],$menu_code);
  
  //FOR GET FLAG FORM MENUMAST
  $menuQry = $qryObj->fetchQuery($qryPath,'Q001','GET_PARAM',$oldLovFilter,$newLovFilter);
 // echo $menuQry;
  $menuRes = $dsbObj->getData($menuQry);
  $flagarray=explode("=",$menuRes[0]['PARAMLIST_NAME']);
  $flag=$flagarray[1];
  

  //For Getting LOV Result
  $seasonres = $dsbObj->getLovQry(28,$oldLovFilter,$newLovFilter);
  $billTypeRes = $dsbObj->getLovQry(118,$oldLovFilter,$newLovFilter);
  /*$ctypeQry = $qryObj->fetchQuery($qryPath,'Q001','CONTRACTTYPE');
  $ctypeRes = $dsbObj->getData($ctypeQry);*/
  $contractorRes = $dsbObj->getLovQry(40,$oldLovFilter,$newLovFilter);
      
  //for Fornight LOV
  if(isset($_POST['getFornight'])){
         $oldFilter = array(':PCOMP_CODE',':PSN_CODE',':PCTYPE');
         $newFilter = array($_SESSION['COMP_CODE'],$_POST['seasoncode'],$_POST['ctype']);

         $ForNightQry = $qryObj->fetchQuery($qryPath,'Q001','FORNIGHT',$oldFilter,$newFilter);
        // echo"Query". $ForNightQry;
         $ForNightRes = $dsbObj->getData($ForNightQry);

         $JobTypeQry = $qryObj->fetchQuery($qryPath,'Q001','JOBTYPE',$oldFilter,$newFilter);
         $JobTypeRes = $dsbObj->getData($JobTypeQry);

         echo json_encode($ForNightRes);
         echo '*';
       //  echo json_encode($JobTypeRes);
         exit();
  }//if

 
     
require_once("header.php");
require_once("sidebar.php");
  //require_once("footer.php");
?>
 
 <section>  
  <!-- page content --> 
  <div class="right_col" role="main">
    <div class="">
     <div class="clearfix"></div>
     
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
        
          <div class="x_content">
           <form  class="form-horizontal form-label-left" id="bill_gen" action="bulllockcartpreprint.php" target="_blank" method="POST">
           <ul class="contactus-list">
           <span class="section">Bullockcart Bill Print Input </span>
          
          <div class="panel panel-primary">
          <div class="panel-heading" id="addpanel">Bullockcart Bill Print</div>
        <div class="panel-body">
          <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Season <span class="required">*</span></label>
              <li>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select id ="season" name="season" class="form-control col-md-7 col-xs-12" required="required">
                    <!-- <option value="">Select</option> -->
                  <? for($i=0;$i<sizeof($seasonres);$i++){?>
                    <option value="<?=$seasonres[$i]['SN_CODE']?>"><?=$seasonres[$i]['SN_CODE']?></option>
                  <? } ?> 
                  </select>
                  <font color="red"><span id="season_err"></span></font>
                </div>
              </li>
             <input type="hidden" name="flag" id='flag' value="<?php echo $flag; ?>">
          </div>   

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Contract Type <span class="required">*</span></label>
              <li>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select id ="contract_type" name="contract_type" class="form-control col-md-7 col-xs-12" required="required">
                    <option value="CT003">Bullockcart</option>
                    <option value="CT004">Tractor Tayer </option>
                  </select>
                </div>
              </li>
            </div>  

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" >Fortnight No. <span class="required">*</span></label>
            <li>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="fornight" name="fornight" class="form-control col-md-7 col-xs-12" required="required">
                 <option value="">Select</option>
                </select>
              </div>
            </li>
          </div>   
             
          <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Bill Type <span class="required">*</span></label>
              <li><div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="bill_type" name="bill_type" class="form-control col-md-7 col-xs-12" required="required">
                  <option value="">Select</option>
                <? for($i=0;$i<sizeof($billTypeRes);$i++){?>
                  <option value="<?=$billTypeRes[$i]['HT_CODE']?>"><?=$billTypeRes[$i]['HT_NAME']?></option>
                <? } ?> 
                </select>

              </div></li>
            </div>     

            <!-- <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Job Type</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="jobtype" name="jobtype" class="form-control col-md-7 col-xs-12">
                 <option value="">Select</option>
                </select>
              </div>
            </div>    -->

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Contractor</label>
             <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="contractor" name="contractor" class="form-control col-md-7 col-xs-12">
                  <option value="">Select</option>
                <? for($i=0;$i<sizeof($contractorRes);$i++){?>
                  <option value="<?=$contractorRes[$i]['PRT_CODE']?>"><?=$contractorRes[$i]['PRT_CODE'].'||'.$contractorRes[$i]['PRT_NAME']?></option>
                <? } ?> 
                </select>

              </div>
            </div>

                <!-- For Ajax Loader -->
         <div id="wait" style="display:none;width:69px;height:89px;border:1px solid black;position:absolute;top:50%;left:50%;padding:2px;"><img src="images/loader.gif" width="64" height="64" />
              <br>Wait..
            </div>
            <div class="ln_solid"></div>
              <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
               <button id="btn_submit" type="submit" name="submit" class="btn btn-success">Submit</button>
              </div>
              </div>  
              
          </div><!--panel-body-->
          </div><!--panel panel-primary--> 
          </ul>
      </form>     
      </div>
     </div>
    </div>
   </div>
    </div>
   </div>
   <!-- /page content -->
    
</section>
<? include("footer.php");?>
 <script type="text/javascript">
 $(document).ready(function() {
      $('#season').change(function(){
        var seasoncode = $('#season').val();
        var ctype = 'CT003'//.split('||');
        jQuery.ajax({ 
            type: "POST",
            datatype: "json",
            async: false,
            url: "transbillpreprint_input.php",
            data:({getFornight:'Y',seasoncode:seasoncode,ctype:ctype}),
            success:function(res)
            {
             // alert(res);
              var data = res.split('*');
              //alert(data);
              //set fornight LOV 
               $("#fornight").empty();
               data1 = $.parseJSON(data[0]);
               $('#fornight').append($('<option>').text('Select').attr('value',''));
               $.each(data1, function(i, value) {
                $('#fornight').append($('<option>').text(value['DT']).attr('value', value['HPD_FNNO']));
               });   

               /*//set jobtype LOV
               $("#jobtype").empty();
               data2 = $.parseJSON(data[1]);
               $('#jobtype').append($('<option>').text('Select').attr('value',''));
               $.each(data2, function(i, value) {
                $('#jobtype').append($('<option>').text(value['JT_MNAME']).attr('value', value['JT_CODE']));
               });   */
                  
           }//success
        }); //ajax
    }); //function  

    $('#contractor').contractor();
 }); //ready function
</script>
