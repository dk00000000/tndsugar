<?  
  require_once("curdClass.php");
      
  $curd = new CURD();
  $lgs = new Logs();
  $qryObj = new Query();
  $dsbObj = new Dashboard();  
  $action = $_REQUEST['module'];
  $oldLovFilter = array(':PCOMP_CODE');
  $newLovFilter = array('DS');

  $sectionres = $dsbObj->getLovQry(67,$oldLovFilter,$newLovFilter);
  $seasonres = $dsbObj->getLovQry(28,$oldLovFilter,$newLovFilter);
  $Villageres = $dsbObj->getLovQry(5,$oldLovFilter,$newLovFilter);
  $canetyperes = $dsbObj->getLovQry(33,$oldLovFilter,$newLovFilter);
  $canevarietyres = $dsbObj->getLovQry(34,$oldLovFilter,$newLovFilter);

  //for Village LOV
  if(isset($_POST['getVillage'])){
    $oldFilter = array(':PCOMP_CODE',':PSECTION');
    $newFilter = array('DS',$_POST['section']);

    $villageRes = $curd->GetSelData($oldFilter,$newFilter,'annexure.ini','VILLAGE');
    echo json_encode($villageRes);
    exit();
  }
  
  if($_REQUEST['menu_code'] == 'L06202'){
    $reportname = 'Annexure 2A';
  }
  
  if($_REQUEST['menu_code'] == 'L06203'){
    $reportname = 'Annexure 2B';
  }
 
  if($_REQUEST['menu_code'] == 'L06205'){
    $reportname = 'Annexure 2C';
  }
  else if($_REQUEST['menu_code'] == 'L06207'){
    $reportname = 'Annexure 2D'; 
  }

  require_once("header.php");
  require_once("sidebar.php");
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
           <form id="annexureform" class="form-horizontal form-label-left" method="POST" action='<?=$action?>?menu_code=<?=$_REQUEST['menu_code']?>' target="_blank">
          
           <span class="section"><?=$reportname?>: Report Input</span>
        
          <div class="panel panel-primary">
          <div class="panel-heading" id="addpanel">Input</div>
            <div class="panel-body">

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Season<span class="required">*</span></label>
             <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="season" name="season" class="form-control col-md-7 col-xs-12" required="required">
                  <option value="">Select</option>
               <? for($i=0;$i<sizeof($seasonres);$i++){?>
                  <option value="<?=$seasonres[$i]['SN_CODE']?>" <? if($_SESSION['SEASON'] == $seasonres[$i]['SN_CODE']) {?> selected="selected" <? }?>><?=$seasonres[$i]['SN_CODE']?></option>
                <? } ?> 
                </select>
              </div>
            </div>   
                            
             <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Section</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="section" name="section" class="form-control col-md-7 col-xs-12">
                  <option value="">Select</option>
                <? for($i=0;$i<sizeof($sectionres);$i++){?>
                  <option value="<?=$sectionres[$i]['SC_CODE'].'-'.$sectionres[$i]['SC_MNAME']?>"><?=$sectionres[$i]['SC_CODE'].'-'.$sectionres[$i]['SC_MNAME']?></option>
                <? } ?> 
                </select>
              </div>
            </div>  

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Village</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="village" name="village" class="form-control col-md-7 col-xs-12">
                  <option value="">Select</option>
                <? for($i=0;$i<sizeof($Villageres);$i++){?>
                  <option value="<?=$Villageres[$i]['VL_CODE'].'-'.$Villageres[$i]['VL_MNAME']?>"><?=$Villageres[$i]['VL_CODE'].'-'.$Villageres[$i]['VL_MNAME']?></option>
                <? } ?> 
                </select>
              </div>
            </div>  
			 
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Cane Type</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="canetype" name="canetype" class="form-control col-md-7 col-xs-12">
                  <option value="">Select</option>
                <? for($i=0;$i<sizeof($canetyperes);$i++){?>
                  <option value="<?=$canetyperes[$i]['CT_CODE'].'-'.$canetyperes[$i]['CT_MNAME']?>"><?=$canetyperes[$i]['CT_CODE'].'-'.$canetyperes[$i]['CT_MNAME']?></option>
                <? } ?> 
                </select>
              </div>
            </div>  

            
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Cane Variety</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="canevariety" name="canevariety" class="form-control col-md-7 col-xs-12">
                  <option value="">Select</option>
                <? for($i=0;$i<sizeof($canevarietyres);$i++){?>
                  <option value="<?=$canevarietyres[$i]['CV_CODE'].'-'.$canevarietyres[$i]['CV_NAME']?>"><?=$canevarietyres[$i]['CV_CODE'].'-'.$canevarietyres[$i]['CV_NAME']?></option>
                <? } ?> 
                </select>
              </div>
            </div>  
          
             <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Farmer</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="fr_code" id="fr_code" size="80" class="form-control" placeholder="Type Name...">  
                <div id="suggesstion-box"></div>
               <input type="hidden" name="farmer" id="farmer" value="">
              </div>
            </div> 

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Area</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="area" name="area" class="form-control col-md-7 col-xs-12">
                  <option value="R">Registered</option>
                  <option value="B">Balance</option>
                </select>
              </div>
            </div>  
            
            <div class="ln_solid"></div>
              <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
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
<script type="text/javascript">
 $(document).ready(function() {
    
    $("#fr_code").keyup(function()
    {
       $("#farmer").val('');
      if($("#fr_code").val().length > 3)
      {
        var key = $(this).val();
        var sn = $("#season").val();
        $.ajax({
        type: "POST",
        url: "individual_fieldreg_server.php",
        data:'type_string='+key+'&season='+sn+'&action=farmer_rpt',
        success: function(data){
          $("#suggesstion-box").show();
          $("#suggesstion-box").html(data);
          $("#farmer_code").css("background","#FFF");
        }
        });
      }//if
    });

  $('#section').change(function(){
    $("#village").empty();
    var section = $('#section').val();
    var sectioncode = section.split('-');
    //alert(section);
    jQuery.ajax({ 
        type: "POST",
        datatype: "json",
        async: false,
        url: "annexure_input.php",
        data:({getVillage:'Y',section:sectioncode[0]}),
        success:function(data)
        {
          $("#village").empty();
          data = $.parseJSON(data);
           
          $('#village').append($('<option>').text('Select').attr('value',''));
          $.each(data, function(i, value) {
            //alert(value);
            $('#village').append($('<option>').text(value['VL_CODE']+'-'+value['VL_MNAME']).attr('value', value['VL_CODE']+'-'+value['VL_MNAME']));
          });    
              
        }//success
      }); //ajax
  }); //function

 }); //ready

 $(document).click(function() {
 $('.farmer-list').hide();
});

 function Farmer(val)
 {
    var farmer_desc = val.split('-');
    var farmer = farmer_desc[0]+'-'+farmer_desc[2];//use to set farmer code in
    $("#farmer").val(farmer);
    $("#fr_code").val(farmer_desc[0]+'-'+farmer_desc[1]);
    $("#suggesstion-box").hide();
 }
</script> 
