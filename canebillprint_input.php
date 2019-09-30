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
           <form id="districtform" class="form-horizontal form-label-left" method="POST" action="canebill_print.php" target="_blank" >
           <!-- <form id="districtform" class="form-horizontal form-label-left" method="POST" action="bill_jsml.php" target="_blank" > -->
          
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
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Farmer</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="fr_code" id="fr_code" size="80" class="form-control" placeholder="Type Name...">  
                <div id="suggesstion-box"></div>
               <input type="hidden" name="farmer" id="farmer">

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

  //$("#farmer").subcontractor();

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
            url: "billprint_input.php",
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

$("#fr_code").keyup(function()
{
  if($("#fr_code").val().length > 3)
  {
    var key = $(this).val();
    var sn = $("#season").val();
    $.ajax({
    type: "POST",
    url: "farmerledger_server.php",
    data:'type_string='+key+'&season='+sn+'&action=farmer_rpt',
    success: function(data){
      $("#suggesstion-box").show();
      $("#suggesstion-box").html(data);
      $("#farmer_code").css("background","#FFF");
    }
    });
  }//if
});

 }); //ready function
 
 function Farmer(val)
 {
    var farmer_desc = val.split('-');
    var farmer = farmer_desc[0];//use to set farmer code in
    $("#farmer").val(farmer);
    $("#fr_code").val(farmer_desc[0]+'-'+farmer_desc[1]);
    $("#suggesstion-box").hide();
 }
</script>
