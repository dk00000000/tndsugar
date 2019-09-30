  <?  
   // session_start();
    require_once("curdClass.php");
    
    $curd = new CURD();
    $lgs = new Logs();
    $qryObj = new Query();
    $dsbObj = new Dashboard();  

    $oldLovFilter = array(':PCOMP_CODE',':PTXN_DOC',':PSN_CODE');
    $newLovFilter = array('DS','WS', $_SESSION['SEASON']);

    $sectionres = $dsbObj->getLovQry(67,$oldLovFilter,$newLovFilter);
    $farmerres = $dsbObj->getLovQry(94,$oldLovFilter,$newLovFilter);
    $seasonres = $dsbObj->getLovQry(28,$oldLovFilter,$newLovFilter);

    $dateqry = $curd->GetSelData($oldLovFilter,$newLovFilter,'canereceipt_register.ini','DATEQRY');

    if(isset($_POST['getDateData']))
    {
      $oldFilter = array(':PCOMP_CODE',':PSN_CODE');
      $newFilter = array('DS',$_POST['season']);
      $dateqry = $curd->GetSelData($oldFilter,$newFilter,'canereceipt_register.ini','DATEQRY');
      $lgs->lg->trace("Date value= ".json_encode($dateqry));
      echo json_encode($dateqry);
      exit();
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
           <form id="districtform" class="form-horizontal form-label-left" method="POST" action="canereceipt_register.php" target="_blank">
          
           <span class="section">Farmer Wise Cane Receipt Register-Detail: Report Input</span>
        
          <div class="panel panel-primary">
          <div class="panel-heading" id="addpanel">Input</div>
            <div class="panel-body">

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Season<span class="required">*</span></label>
             <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="season" name="season" class="form-control col-md-7 col-xs-12" required="required">
                  <option value="">Select</option>
                  <? for($i=0;$i<sizeof($seasonres);$i++){?>
                  <option value="<?=$seasonres[$i]['SN_CODE']?>" <? if($_SESSION['SEASON'] == $seasonres[$i]['SN_CODE']){?> selected="selected" <? } ?>><?=$seasonres[$i]['SN_CODE']?></option>
                  <? } ?> 
                </select>
              </div>
            </div>   
           
           <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >From Date<span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" name="fromdate" id="fromdate" required="required" value="<?= $dateqry[0]['SN_STDT'] ?>">
              </div>
            </div>    
            
           <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >To Date<span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" name="todate" id="todate" required="required" value="<?=$dateqry[0]['SN_EDDT']?>">
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
   //$("#farmer").subcontractor();

  
  $('#season').on('change', function(){
    var season = $('#season').val();
    //alert(season);
    jQuery.ajax({
      type: "POST",
      datatype: "json",
      async: false,
      url: "canereceipt_reginput.php",
      data: ({getDateData:'Y',season:season}),
      success: function(data)
      {
        data = $.parseJSON(data);
        //alert(data);
        if(data==''){
        $('#fromdate').val('');
        $('#todate').val('');
        }
        $.each(data, function(i, value){
        $('input#fromdate').val(value['SN_STDT']);
        $('input#todate').val(value['SN_EDDT']);
        }); 
      }//success
    }); //ajax
  });//change function

    $("#fromdate").datetimepicker({format : 'DD/MM/YYYY'});
    $("#todate").datetimepicker({format : 'DD/MM/YYYY'}); 
    
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
 }); //ready

 $(document).click(function() {
 $('.farmer-list').hide();
});

 function Farmer(val)
 {
    var farmer_desc = val.split('-');
    var farmer = farmer_desc[0];//use to set farmer code in
    $("#farmer").val(farmer);
    $("#fr_code").val(farmer_desc[0]+'-'+farmer_desc[1]);
    $("#suggesstion-box").hide();
 }
</script>

 
