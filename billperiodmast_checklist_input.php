<?  
  require_once("curdClass.php");
    
  $curd = new CURD();
  $lgs = new Logs();
  $qryObj = new Query(); 
  $dsbObj = new Dashboard();  

  $menu_code = $_GET['menu_code'];
  $oldLovFilter = array(':PCOMP_CODE',':PMENU_CODE');
  $newLovFilter = array('DS',$menu_code);
  $qryPath = "util/readquery/general/billperiodmast_checklist.ini"; 
  
  $seasonres = $dsbObj->getLovQry(28,$oldLovFilter,$newLovFilter);
  $cTypeRes = $dsbObj->getLovQry(21,$oldLovFilter,$newLovFilter);  

  $menunameRes = $curd->GetSelData($oldLovFilter,$newLovFilter,'billperiodmast_checklist.ini','MENUNAME');
  $menuname = str_replace('&','', $menunameRes[0]['MENU_NAME']);

      //for Contract type LOV
  if(isset($_POST['getCtype'])){
      $oldFilter = array(':PCOMP_CODE',':PSEASON');
      $newFilter = array($_SESSION['COMP_CODE'],$_POST['season']);

      $cTypeRes = $dsbObj->getLovQry(21,$oldLovFilter,$newLovFilter);
      echo json_encode($cTypeRes);
      exit();
  }//if
    
  //for Bill type LOV
  if(isset($_POST['getBilltype'])){
     $oldFilter = array(':PCOMP_CODE',':PSEASON',':PCONTRACT_TYPE');
     $newFilter = array($_SESSION['COMP_CODE'],$_POST['season'],$_POST['ctype']);

     $billTypeQry = $qryObj->fetchQuery($qryPath,'Q001','BILL_TYPE',$oldFilter,$newFilter);
     $billTypeRes = $dsbObj->getData($billTypeQry);
    
     echo json_encode($billTypeRes);
     exit();
  }//if

  //for Fornight LOV
  if(isset($_POST['getFornight'])){
     $oldFilter = array(':PCOMP_CODE',':PSEASON',':PCONTRACT_TYPE',':PBILL_TYPE');
     $newFilter = array($_SESSION['COMP_CODE'],$_POST['season'],$_POST['ctype'],$_POST['bill_type']);

     $ForNightQry = $qryObj->fetchQuery($qryPath,'Q001','FORTNIGHT',$oldFilter,$newFilter);
     $ForNightRes = $dsbObj->getData($ForNightQry);
    
     echo json_encode($ForNightRes);     
     exit();
  }//if
     
  require_once("header.php");
  require_once("sidebar.php");
  //require_once("footer.php");
  ?>
  
   <script type="text/javascript" src="js/jquery.redirect.js"></script>
 <section>  
  <!-- page content --> 
  <div class="right_col" role="main">
    <div class="">
     <div class="clearfix"></div>
     
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
        
          <div class="x_content">
           <form id="billperiodform" class="form-horizontal form-label-left" method="POST" action="billperiodmast_checklist.php" target="_blank">
          
           <span class="section">Bill Period Master Checklist: Report Input</span>
        
          <div class="panel panel-primary"> 
          <div class="panel-heading" id="addpanel">Input</div>
            <div class="panel-body">
      
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" >Season<span class="required">*</span></label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id ="season" name="season" class="form-control col-md-7 col-xs-12" >
                    <? for($i=0;$i<sizeof($seasonres);$i++){?>
                      <option value="<?=$seasonres[$i]['SN_CODE']?>" <? if($_SESSION['SEASON'] == $seasonres[$i]['SN_CODE']) {?> selected="selected" <? }?>><?=$seasonres[$i]['SN_CODE']?></option>
                    <? } ?> 
                    </select>
                  </div>
            </div>   


            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Contract Type <!-- <span class="required">*</span> --></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select id ="contract_type" name="contract_type" class="form-control col-md-7 col-xs-12">
                  <option value="">Select</option>
                  <? for($i=0;$i<sizeof($cTypeRes);$i++){?>
                    <option value="<?=$cTypeRes[$i]['CT_CODE'].'||'.$cTypeRes[$i]['CT_MNAME']?>"><?=$cTypeRes[$i]['CT_CODE'].' || '.$cTypeRes[$i]['CT_MNAME']?></option>
                  <? } ?> 
                  </select>
                </div>
            </div>  

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Bill Type<!-- <span class="required">*</span> --></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="bill_type" name="bill_type" class="form-control col-md-7 col-xs-12">
                  <option value="">Select</option>
                </select>
              </div>
            </div>     

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Fortnight<!-- <span class="required">*</span> --></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select id ="fortnight" name="fortnight" class="form-control col-md-7 col-xs-12">
                   <option value="">Select</option>
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
  $('#season').change(function(){

      $("#contract_type").empty();
      $("#bill_type").empty();
      $("#fortnight").empty();

      var season = $('#season').val();

      jQuery.ajax({ 
        type: "POST",
        datatype: "json",
        async: false,
        url: "billperiodmast_checklist_input.php",
        data:({getCtype:'Y',season:season}),
        success:function(res)
        {
          data = $.parseJSON(res);
          $('#contract_type').append($('<option>').text('Select').attr('value',''));
             $.each(data, function(i, value) {
             $('#contract_type').append($('<option>').text(value['CT_CODE']+' || '+value['CT_MNAME']).attr('value', value['CT_CODE']+'||'+value['CT_MNAME']));
          });   
        }
      });//ajax 
    });
   
    $('#contract_type').change(function(){
        $("#bill_type").empty();
        $("#fortnight").empty();

        var season = $('#season').val();
        var ctype = $('#contract_type').val().split('||');

        jQuery.ajax({ 
            type: "POST",
            datatype: "json",
            async: false,
            url: "billperiodmast_checklist_input.php",
            data:({getBilltype:'Y',season:season,ctype:ctype[0]}),
            success:function(res)
            {
              $("#bill_type").empty();
              data = $.parseJSON(res);
              $('#bill_type').append($('<option>').text('Select').attr('value',''));
               $.each(data, function(i, value) {
                $('#bill_type').append($('<option>').text(value['HPD_BTYPE']+' - '+value['HT_MNAME']).attr('value', value['HPD_BTYPE']+'||'+value['HT_MNAME']));
              });   

           }//success
        }); //ajax
    }); //function  

    $('#bill_type').change(function(){

        $("#fortnight").empty();

        var season = $('#season').val();
        var ctype = $('#contract_type').val().split('||');
        var bill_type = $('#bill_type').val().split('||');

        jQuery.ajax({ 
            type: "POST",
            datatype: "json",
            async: false,
            url: "billperiodmast_checklist_input.php",
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
    }); //function  
 }); //ready function
</script>

