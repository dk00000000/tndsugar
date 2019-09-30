<?  
     // session_start();
      require_once("curdClass.php");
      
      $curd = new CURD();
      $lgs = new Logs();
      $qryObj = new Query(); 
      $dsbObj = new Dashboard();  

      $oldLovFilter = array(':PCOMP_CODE',':PTXN_DOC');
      $newLovFilter = array('DS','WS');
       //$shiftres = $dsbObj->getLovQry(96,$oldLovFilter,$newLovFilter); 
      $sectionres = $dsbObj->getLovQry(67,$oldLovFilter,$newLovFilter);
      //$seriesres = $dsbObj->getLovQry(95,$oldLovFilter,$newLovFilter);
      $seasonres = $dsbObj->getLovQry(28,$oldLovFilter,$newLovFilter);
      $billTypeRes = $dsbObj->getLovQry(102,$oldLovFilter,$newLovFilter);
	  $farmerres = $dsbObj->getLovQry(94,$oldLovFilter,$newLovFilter);

      //for fornight LOV 
      if(isset($_POST['getFornight'])){
         $oldFilter = array(':PCOMP_CODE',':PSN_CODE');
         $newFilter = array('DS',$_POST['seasoncode']);

         $fornightres = $curd->GetSelData($oldFilter,$newFilter,'canebill_print.ini','FORNIGHT');
         echo json_encode($fornightres);
         exit();
      }
      //$fortnoghtres = $curd->GetSelData($query);

     // echo json_encode($shiftres);
     // echo json_encode($seasonres);
     // echo json_encode($transporterres);

  require_once("header.php");
  require_once("sidebar.php");
  //require_once("footer.php");
  ?>
 


  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->

  
 <section>  
  <!-- page content --> 
  <div class="right_col" role="main">
    <div class="">
     <div class="clearfix"></div>
     
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
        
          <div class="x_content">
           <form id="districtform" class="form-horizontal form-label-left" method="POST" action="canebill_print_austo.php" target="_blank" >
          
           <span class="section">Bill Print </span>
        
          <div class="panel panel-primary">
          <div class="panel-heading" id="addpanel">Input</div>
            <div class="panel-body">
			
			     <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Season</span></label>
             <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="season" name="season" class="form-control col-md-7 col-xs-12" required="required">
                  <option value="">Select</option>
                <? for($i=0;$i<sizeof($seasonres);$i++){?>
                  <option value="<?=$seasonres[$i]['SN_CODE']?>"><?=$seasonres[$i]['SN_CODE']?></option>
                <? } ?> 
                </select>
              </div>
            </div>   

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Fortnight</span></label>
             <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="fornight" name="fornight" class="form-control col-md-7 col-xs-12" required="required">
                 <option value="">Select</option>
                </select>
              </div>
            </div>   
           
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Section</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="section" name="section" class="form-control col-md-7 col-xs-12">
                  <option value="">Select</option>
                <? for($i=0;$i<sizeof($sectionres);$i++){?>
                  <option value="<?=$sectionres[$i]['SC_CODE']?>"><?=$sectionres[$i]['SC_NAME']?></option>
                <? } ?> 
                </select>
              </div>
            </div>     
            
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Bill Type</span></label>
              
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="bill_type" name="bill_type" class="form-control col-md-7 col-xs-12" required="required"  >
                  <option value="">Select</option>
                <? for($i=0;$i<sizeof($billTypeRes);$i++){?>
                  <option value="<?=$billTypeRes[$i]['BT_CODE']?>"><?=$billTypeRes[$i]['BT_NAME']?></option>
                <? } ?> 
                </select>

              </div>
            </div>  
			
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" >Farmer</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
               <select class="form-control" id="farmer" name="farmer" >
                 <option value="">Select</option>
                <? for($i=0;$i<sizeof($farmerres);$i++) {?>
                <option value="<?=$farmerres[$i]['PRT_CODE']?>"><?=$farmerres[$i]['PRT_CODE']." ".$farmerres[$i]['PRT_NAME']." ".$farmerres[$i]['PRT_MNAME']." ".$farmerres[$i]['VL_NAME']?>
                
                </option>
                <? } ?>
                </select>

              </div>
            </div>     


            <div class="ln_solid"></div>
              <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
               <!--  <button type="submit" name="submit" class="btn btn-success" id="btn_submit" onclick="gonext()">Submit</button>  -->
              <input type="submit" name="submit" class="btn btn-success" id="btn_submit" value="Submit">
              </div>
              </div>  
              
          </div><!--panel-body-->
          </div><!--panel panel-primary--> 
         
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
</script>

 <script type="text/javascript">
 $(document).ready(function() {

  $("#farmer").subcontractor();

 }); 
</script>


 <script type="text/javascript">
 $(document).ready(function() {
  

    $('#season').change(function(){
        var seasoncode = $('#season').val();
        jQuery.ajax({ 
            type: "POST",
            datatype: "json",
            async: false,
            url: "canebillprint_input_austo.php",
            data:({getFornight:'Y',seasoncode:seasoncode}),
            success:function(data)
            {
              //setfornight LOV
               $("#fornight").empty();
               data = $.parseJSON(data);
               dl = data.length;
              // $('#fornight').append($('<option>').text('Select').attr('value',''));
               $.each(data, function(i, value) {
                //alert(value+" "+i);
                  $('#fornight').append($('<option selected="selected"> ').text(value['SND_FRDT']+' - '+value['SND_TODT']).attr('value', value['SND_FNNO']));
                
               });    
                  
           }//success
        }); //ajax
    }); //function 


 }); //ready function
</script>
