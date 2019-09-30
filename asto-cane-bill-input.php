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


	  //Get Fortnight Lov
	  if(isset($_POST['getFortnight']))
	  {
	  	$oldFilter = array(':PCOMP_CODE',':PSEASON',':PBTYPE');
		$newFilter = array($_SESSION['COMP_CODE'],$_POST['season'],$_POST['billtype']);
		$fortnight_lov = $dsbObj->getLovQry(137,$oldFilter,$newFilter);
		echo json_encode($fortnight_lov);
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
           <form id="districtform" class="form-horizontal form-label-left" method="POST" action="asto-cane-bill.php" target="_blank" >
          
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
                  <option value="<?=$seasonres[$i]['SN_CODE']?>" <? if($_SESSION['SEASON'] == $seasonres[$i]['SN_CODE']){?> selected = "selected" <? } ?>><?=$seasonres[$i]['SN_CODE']?></option>
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

$("#season").change(function(){
	$("#fornight").empty();
});

$("#bill_type").change(function(){
var bill_type = $("#bill_type").val();
var season = $("#season").val();

jQuery.ajax({
			type: "POST",
			dataype: "json",
			async: false,
			url: "asto-cane-bill-input.php",
			data: ({getFortnight:'Y',season:season,billtype:bill_type}),
			success: function(data)
			{
				$("#fornight").empty();
				data = $.parseJSON(data);
				$("#fornight").append($('<option>').text('Select').attr('value',''));
				$.each(data, function(i, value){
					$("#fornight").append($('<option>').text(value['FORTNIGHT']).attr('value',value['SND_FNNO']));
				});
			}
});//ajax

}); //change


 }); //ready function
</script>
