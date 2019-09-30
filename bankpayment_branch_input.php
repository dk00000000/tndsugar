<?  
     // session_start();
      require_once("curdClass.php");
      
      $curd = new CURD();
      $lgs = new Logs();
      $qryObj = new Query(); 
      $dsbObj = new Dashboard();  

      $menu_code = $_GET['menu_code'];

      $oldLovFilter = array(':PCOMP_CODE',':PMENU_CODE',':PSEASON');
      $newLovFilter = array($_SESSION['COMP_CODE'],$menu_code,$_SESSION['SEASON']);
      $qryPath = "util/readquery/general/bankpayment_branch.ini"; 
	  
	  $seasonres = $dsbObj->getLovQry(28,$oldLovFilter,$newLovFilter);
	  $bankbranchres = $dsbObj->getLovQry(18,$oldLovFilter,$newLovFilter);
	  //$contractTypeRes = $dsbObj->getLovQry(21,$oldLovFilter,$newLovFilter);
      $htbillTypeRes = $dsbObj->getLovQry(73,$oldLovFilter,$newLovFilter);
      $titlename = $dsbObj->getLovQry(138,$oldLovFilter,$newLovFilter);
	  
	  $contractTypeQry = $qryObj->fetchQuery($qryPath,'Q001','COMMON_QRY',$oldLovFilter,$newLovFilter);
	  $contractTypeRes = $dsbObj->getData($contractTypeQry);  
    //for Contract type Query
    if(isset($_POST['getCtype'])){
        $oldFilter = array(':PCOMP_CODE',':PSEASON');
        $newFilter = array($_SESSION['COMP_CODE'],$_POST['season']);

        //$cTypeRes = $dsbObj->getLovQry(21,$oldLovFilter,$newLovFilter);
		$commQry = $qryObj->fetchQuery($qryPath,'Q001','COMMON_QRY',$oldFilter,$newFilter);
		$commRes = $dsbObj->getData($commQry);
        echo json_encode($commRes);
        exit();
    }//if
      
    //for Bill type LOV
   /* if(isset($_POST['getBilltype'])){
       $oldFilter = array(':PCOMP_CODE',':PSEASON',':PCONTRACT_TYPE');
       $newFilter = array($_SESSION['COMP_CODE'],$_POST['season'],$_POST['ctype']);

       $billTypeQry = $qryObj->fetchQuery($qryPath,'Q001','BILL_TYPE',$oldFilter,$newFilter);
       $billTypeRes = $dsbObj->getData($billTypeQry);
       echo json_encode($billTypeRes);
       exit();
    }*///if

    //for Fornight LOV
    /*if(isset($_POST['getFornight'])){
       $oldFilter = array(':PCOMP_CODE',':PSEASON',':PCONTRACT_TYPE',':PBILL_TYPE');
       $newFilter = array($_SESSION['COMP_CODE'],$_POST['season'],$_POST['ctype'],$_POST['bill_type']);

       $ForNightQry = $qryObj->fetchQuery($qryPath,'Q001','FORTNIGHT',$oldFilter,$newFilter);
       $ForNightRes = $dsbObj->getData($ForNightQry);
      
       $JobTypeQry = $qryObj->fetchQuery($qryPath,'Q001','JOBTYPE',$oldFilter,$newFilter);
       $JobTypeRes = $dsbObj->getData($JobTypeQry);

       echo json_encode($ForNightRes);
       echo '*';
       echo json_encode($JobTypeRes);
       exit();
    }*///if
     
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
           <form id="hntbranchwise_form" class="form-horizontal form-label-left" method="POST" action="bankpayment_branch.php" target="_blank">
          
           <span class="section"><? echo $titlename[0]['MENU_NAME']; ?> : Report Input</span>
        
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
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Contract Type - Bill Type - Fortnight</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="contract_type" name="contract_type[]" class="form-control col-md-7 col-xs-12" multiple="multiple">
                  <!--<option value="">Select</option>-->
                <? for($i=0;$i<sizeof($contractTypeRes);$i++){?>
                  <option value="<?=$contractTypeRes[$i]['PKEY']." = ".$contractTypeRes[$i]['DKEY']?>"><?=$contractTypeRes[$i]['DKEY']?></option>
                <? } ?> 
                </select>
              </div>
            </div>      

            <!--<div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Bill Type</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="bill_type" name="bill_type" class="form-control col-md-7 col-xs-12">
                  <option value="">Select</option>
                </select>
              </div>
            </div>-->     

            <!--<div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Fortnight</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select id ="fortnight" name="fortnight" class="form-control col-md-7 col-xs-12">
                   <option value="">Select</option>
                  </select>
                </div>
            </div>-->   

           
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Bank Branch Name</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="bank_branch" name="bank_branch[]" class="form-control col-md-7 col-xs-12" multiple="multiple">
                <? for($i=0;$i<sizeof($bankbranchres);$i++){?>
                  <option value="<?=$bankbranchres[$i]['BR_CODE']."||".$bankbranchres[$i]['BR_NAME']?>"><?=$bankbranchres[$i]['BR_CODE']." ".$bankbranchres[$i]['BR_NAME']?></option>
                <? } ?> 
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

  $("#bank_branch").multiselect(
  {
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
  });

  $("#contract_type").multiselect(
  {
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
  });
   $('#season').change(function(){

      $("#contract_type").empty();
      /*$("#bill_type").empty();
      $("#fortnight").empty();*/

      var season = $('#season').val();

      jQuery.ajax({ 
        type: "POST",
        datatype: "json",
        async: false,
        url: "bankpayment_branch_input.php",
        data:({getCtype:'Y',season:season}),
        success:function(res)
        {
          data = $.parseJSON(res);
          $('#contract_type').append($('<option>').text('Select').attr('value',''));
             $.each(data, function(i, value) {
             $('#contract_type').append($('<option>').text(value['DKEY']).attr('value', value['PKEY']+' = '+value['DKEY']));
          });   
        }
      });//ajax 
    });
   
    /*$('#contract_type').change(function(){

        $("#bill_type").empty();
        $("#fortnight").empty();

        var season = $('#season').val();
        var ctype = $('#contract_type').val().split('||');

        jQuery.ajax({ 
            type: "POST",
            datatype: "json",
            async: false,
            url: "bankpayment_branch_input.php",
            data:({getBilltype:'Y',season:season,ctype:ctype[0]}),
            success:function(res)
            {
              $("#bill_type").empty();
              data = $.parseJSON(res);
              $('#bill_type').append($('<option>').text('Select').attr('value',''));
               $.each(data, function(i, value) {
                $('#bill_type').append($('<option>').text(value['HPD_BTYPE']+' || '+value['HT_MNAME']).attr('value', value['HPD_BTYPE']+'||'+value['HT_MNAME']));
              });   

           }//success
        }); //ajax
    }); //function  */

    /*$('#bill_type').change(function(){

        $("#fortnight").empty();

        var season = $('#season').val();
        var ctype = $('#contract_type').val().split('||');
        var bill_type = $('#bill_type').val().split('||');

        jQuery.ajax({ 
            type: "POST",
            datatype: "json",
            async: false,
            url: "bankpayment_branch_input.php",
            data:({getFornight:'Y',season:season,ctype:ctype[0],bill_type:bill_type[0]}),
            success:function(res)
            {
              var data = res.split('*');
              //set fornight LOV
               $("#fortnight").empty();
               data1 = $.parseJSON(data[0]);
               $('#fortnight').append($('<option>').text('Select').attr('value',''));
               $.each(data1, function(i, value) {
                $('#fortnight').append($('<option>').text(value['FORTNIGHT']).attr('value', value['FNNO']));
               });   
            }//success
        }); //ajax
    }); //function  */
 
 }); //ready function
</script>

