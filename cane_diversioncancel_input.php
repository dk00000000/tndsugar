   <?  
     // session_start();
      require_once("curdClass.php");
      
      $curd = new CURD();
      $lgs = new Logs();
      $qryObj = new Query();
      $dsbObj = new Dashboard();  

      $oldLovFilter = array(':PCOMP_CODE',':PTXN_DOC',':PMENU_CODE');
      $newLovFilter = array('DS','WS',$_GET['menu_code']);
      $titleRes = $dsbObj->getLovQry(138,$oldLovFilter,$newLovFilter);
      $sectionres = $dsbObj->getLovQry(67,$oldLovFilter,$newLovFilter);
      $seasonres = $dsbObj->getLovQry(28,$oldLovFilter,$newLovFilter);
      
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
           <form id="districtform" class="form-horizontal form-label-left" method="POST" action="cane_diversioncancel.php" target="_blank">
          
           <span class="section"><? echo $titleRes[0]['MENU_NAME'];?>: Report Input</span>
        
          <div class="panel panel-primary">
          <div class="panel-heading" id="addpanel">Input</div>
            <div class="panel-body">

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Season</label>
             <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="season" name="season" class="form-control col-md-7 col-xs-12" >
                  <option value="">Select</option>
                <? for($i=0;$i<sizeof($seasonres);$i++){?>
                  <option value="<?=$seasonres[$i]['SN_CODE']?>"><?=$seasonres[$i]['SN_CODE']?></option>
                <? } ?> 
                </select>
              </div>
            </div>   
           
           <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >From Date</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control col-md-7 col-xs-12" name="fromdate" id="fromdate" />
              </div>
            </div>    
            
           <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >To Date</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control col-md-7 col-xs-12" name="todate" id="todate" />
              </div>
            </div>    
           
             <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Section</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="section" name="section" class="form-control col-md-7 col-xs-12">
                  <option value="">Select</option>
                <? for($i=0;$i<sizeof($sectionres);$i++){?>
                  <option value="<?=$sectionres[$i]['SC_CODE'].'||'.$sectionres[$i]['SC_MNAME']?>"><?=$sectionres[$i]['SC_CODE'].'-'.$sectionres[$i]['SC_MNAME']?></option>
                <? } ?> 
                </select>
              </div>
            </div>    
            
                     
            <div class="ln_solid"></div>
              <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
              <input type="submit" name="submit" class="btn btn-success" id="btn_submit" value="Submit" />
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
  $("#fromdate").datetimepicker({format : 'DD/MM/YYYY'});
  $("#todate").datetimepicker({format : 'DD/MM/YYYY'}); 
 }); //ready function
</script>