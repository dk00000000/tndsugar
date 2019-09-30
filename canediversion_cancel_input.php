<?  
  require_once("curdClass.php");
      
  $curd = new CURD();
  $lgs = new Logs();
  $qryObj = new Query();
  $dsbObj = new Dashboard();  
  $action = $_REQUEST['module'];
  $oldLovFilter = array(':PCOMP_CODE',':PMENU_CODE');
  $newLovFilter = array($_SESSION['COMP_CODE'], $_GET['menu_code']);
  
  $seasonres = $dsbObj->getLovQry(28,$oldLovFilter,$newLovFilter);
  $titlename = $dsbObj->getLovQry(138,$oldLovFilter,$newLovFilter);
  $caneres = $dsbObj->getLovQry(38,$oldLovFilter,$newLovFilter);

  
  require_once("header.php");
  require_once("sidebar.php");
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
           <form id="districtform" class="form-horizontal form-label-left" method="POST" action='<?=$action?>' target="_blank">
          
           <span class="section"><?=$titlename[0]['MENU_NAME']?>: Report Input</span>
        
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
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Date<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="date" name="date" class="form-control" type="text" value="" placeholder="Select Date"  required="required"/>
            </div>
          </div> 
            
			<div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Cane Diversion</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="cane" name="cane[]" class="form-control col-md-7 col-xs-12" multiple="multiple">
                <? for($i=0;$i<sizeof($caneres);$i++){?>
                  <option value="<?=$caneres[$i]['CDT_CODE']."||".$caneres[$i]['CDT_NAME']?>"><?=$caneres[$i]['CDT_CODE']." ".$caneres[$i]['CDT_NAME']?></option>
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
 
$("#date").datetimepicker({format: 'DD/MM/YYYY'});

$("#cane").multiselect(
  {
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
  });
 
 }); //ready

 </script> 
