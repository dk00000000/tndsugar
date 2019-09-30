<?  
     // session_start();
      require_once("curdClass.php");
      
      $curd = new CURD();
      $lgs = new Logs();
      $qryObj = new Query(); 
      $dsbObj = new Dashboard();  

     $menu_code=$_GET['menu_code'];

//$comp_code = 'DS';
	  $oldLovFilter = array(':PCOMP_CODE',':PMENU_CODE');
	  $newLovFilter = array($_SESSION['COMP_CODE'],$menu_code);
		
	   $titleRes = $dsbObj->getLovQry(138,$oldLovFilter,$newLovFilter);
     $bankres = $dsbObj->getLovQry(6,$oldLovFilter,$newLovFilter);
     $seasonres = $dsbObj->getLovQry(28,$oldLovFilter,$newLovFilter);
     $sectionres = $dsbObj->getLovQry(67,$oldLovFilter,$newLovFilter);
     $billTypeRes = $dsbObj->getLovQry(102,$oldLovFilter,$newLovFilter);


      //for fornight LOV
      if(isset($_POST['getFornight'])){
         $oldFilter = array(':PCOMP_CODE',':PSEASON',':PBTYPE');
         $newFilter = array($_SESSION['COMP_CODE'],$_POST['seasoncode'],$_POST['bill_type']);  
         //FOR HEADER DATA
		 $FortnightRes = $dsbObj->getLovQry(137,$oldFilter,$newFilter);
         echo json_encode($FortnightRes);
         exit();
     }
     

  require_once("header.php");
  require_once("sidebar.php");
  //require_once("footer.php");
  ?>
  <style type='text/css'>
  .multiselect-container {
    height: 200px;  
    width: 500px;
    overflow-x: hidden;
    overflow-y: scroll;  
  }
  
  .multiselect-container > li > a label.radio{
    display: none;
  }
  
  .multiselect-container > li > a > label.checkbox
  {
    white-space: normal;
  }
</style>
 <section>  
  <!-- page content --> 
  <div class="right_col" role="main">
    <div class="">
     <div class="clearfix"></div>
     
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
        
          <div class="x_content">
           <form id="districtform" class="form-horizontal form-label-left" method="POST" action=" cb_bank_ack.php" target="_blank">
          
           <span class="section"><? echo $titleRes[0]['MENU_NAME'];?>: Report Input</span>
        
          <div class="panel panel-primary"> 
          <div class="panel-heading" id="addpanel">Input</div>
            <div class="panel-body">
			
			     <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Season</span></label>
             <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="season" name="season" class="form-control col-md-7 col-xs-12" >
                  <option value="">Select</option>
                <? for($i=0;$i<sizeof($seasonres);$i++){?>
                  <option value="<?=$seasonres[$i]['SN_CODE']?>" <? if($_SESSION['SEASON']==$seasonres[$i]['SN_CODE']){?> selected="selected" <? } ?>><?=$seasonres[$i]['SN_CODE']?></option>
                <? } ?> 
                </select>
              </div>
            </div>   
			
			<div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Bill Type</span></label>
              
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="bill_type" name="bill_type" class="form-control col-md-7 col-xs-12" required="required">
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
                <select id ="fornight" name="fornight" class="form-control col-md-7 col-xs-12" >
                 <option value="">Select</option>
                </select>
              </div>
            </div>   
           
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Bank</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="bank" name="bank[]" class="form-control col-md-7 col-xs-12" multiple="multiple">
                  <option value="">Select</option>
                <? for($i=0;$i<sizeof($bankres);$i++){?>
                  <option value="<?=$bankres[$i]['BK_CODE']?>"><?=$bankres[$i]['BK_NAME']?></option>
                <? } ?> 
                </select>
              </div>
            </div>       

            <? /*?> <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Section</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="section" name="section" class="form-control col-md-7 col-xs-12">
                  <option value="">Select</option>
                <? for($i=0;$i<sizeof($sectionres);$i++){?>
                  <option value="<?=$sectionres[$i]['SC_CODE']?>"><?=$sectionres[$i]['SC_NAME']?></option>
                <? } ?> 
                </select>
              </div>
            </div> <? */ ?>      
            
            

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
 <script type="text/javascript">
 $(document).ready(function() {
   $("#bank").change(function()
    {
      multiple: false;
    });

  $("#bank").multiselect(
  {
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    uncheckAll: function()
    {
          snglSelCnt = 0;
      //$callback.text("Uncheck all clicked!");
      },
    open: function()
    {
          multiple: false;
      //alert("List Open "+snglSelCnt);
      if(snglSelCnt >= 1 )
      {
        //alert("You can select only one item");
        return false;
      }
      },
    click: function(event, ui)
    { 
      if(isDflVal == 'Y')
      {
        if(snglSelCnt >= 1 )
        {
          alert("You Can Select Only One Item ");
          return false;
        } 
        else
        {
          snglSelCnt++;
        }
      }
      else
      {
        if(snglSelCnt >= 1 )
        {
          alert("You Can Select Only One Item ");
          return false;
        }
        else
        {
          snglSelCnt++;
        } 
      } 
    }
  });
  
     $('#season').on('change',function(){
      $('#fornight').empty();
     });
	$('#bill_type').on('change',function(){

		var seasoncode = $('#season').val();  
		//var bill_type = $('#billtype').val().split('-');
		var bill_type = $('#bill_type').val().split('-');
		//alert(bill_type[0]);

		$('#fornight').empty();

		 $.ajax(
				{
					url: "farmerledger_input.php",
					data: ({getFornight:'Y',seasoncode:seasoncode,bill_type:bill_type[0]}),
					type: "POST",
					datatype: "json",
					async: false,
					success: function(response)
					{
						console.log(response);
						result = $.parseJSON(response);//for fortnight lov
						$('#fornight').append($('<option>').text('Select'));
						$.each(result, function(i, value) 
						{
							$('#fornight').append($('<option>').text(value['FORTNIGHT']).attr('value', value['SND_FNNO']));						
						});
					}
				
				} ); //End of ajax
	});  //function  
 }); //ready function
</script>

