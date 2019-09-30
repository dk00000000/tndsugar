<?  
  require_once('dashboard.php');
  include('readfile.php');
  //require_once('header_menu.php');
  
  $lgs = new Logs();
  $qryObj = new Query();
  $dsbObj = new Dashboard(); 
  $rfObj = new ReadFile();
  $lang=strtolower($_SESSION['LANG']);
  $qryPath = "util/readquery/general/cane_biilgenration.ini"; 

  $menu_code=$_REQUEST['menu_code'];
  //echo $menu_code;
  $oldLovFilter = array(':PCOMP_CODE',':PMENU_CODE');
  $newLovFilter = array($_SESSION['COMP_CODE'],$menu_code);
  
  //FOR GET FLAG FORM MENUMAST
  $menuQry = $qryObj->fetchQuery($qryPath,'Q001','GET_PARAM',$oldLovFilter,$newLovFilter);
 // echo $menuQry;
  $menuRes = $dsbObj->getData($menuQry);
  $flagarray=explode("=",$menuRes[0]['PARAMLIST_NAME']);
  $flag=$flagarray[1];
  

  //For Getting LOV Result
  $seasonres = $dsbObj->getLovQry(28,$oldLovFilter,$newLovFilter);
  $billTypeRes = $dsbObj->getLovQry(102,$oldLovFilter,$newLovFilter);
      
  //for Fornight LOV
  if(isset($_POST['getFornight'])){
         $oldFilter = array(':PCOMP_CODE',':PSEASON',':PBTYPE');
         $newFilter = array($_SESSION['COMP_CODE'],$_POST['season'],$_POST['bill_type']);  
         //FOR HEADER DATA
		 $FortnightRes = $dsbObj->getLovQry(137,$oldFilter,$newFilter);
         echo json_encode($FortnightRes);
         exit();
  }//if

  //for Fornight LOV
  if(isset($_REQUEST['action'])){
         $action=$_REQUEST['action'];
         $flag=$_REQUEST['flag'];
		 if($action == 'procedure')
        {
         $aOutPara="";
         $oldFilter = array(':PCOMP_CODE',':PSN_CODE',':PFOR_NIGHT',':PBILL_TYPE',':PFLAG');
         $newFilter = array($_SESSION['COMP_CODE'],$_SESSION['SEASON'],$_REQUEST['fornight'],$_REQUEST['bill_type'],$flag);
        
         //FOR HEADER DATA
         $bllGenProc = $qryObj->fetchQuery($qryPath,'Q001','PROCEDURE',$oldFilter,$newFilter);
         $bllGenRes = $dsbObj->getOutProcData($bllGenProc,$aOutPara);
         $data = json_encode($bllGenRes);
         echo $data;
         exit();
  }else
       {
        $aOutPara="";
         $oldFilter = array(':PCOMP_CODE',':PSN_CODE',':PFOR_NIGHT',':PBILL_TYPE');
         $newFilter = array($_SESSION['COMP_CODE'],$_SESSION['SEASON'],$_REQUEST['fornight'],$_REQUEST['bill_type']);
        //FOR HEADER DATA
         $DelbllGenProc = $qryObj->fetchQuery($qryPath,'Q001','DEL',$oldFilter,$newFilter);
		 $DelbllGenRes = $dsbObj->getOutProcData($DelbllGenProc,$aOutPara);
         echo $DelbllGenRes;
         exit();
       }//else
         
  }//if
     
require_once("header.php");
require_once("sidebar.php");
  //require_once("footer.php");
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
           <form  class="form-horizontal form-label-left" id="bill_gen" onsubmit="return false;">
           <ul class="contactus-list">
           <span class="section"><?php 
                  if($flag =='A')
                       { 
                        echo 'Cane Bill Generation';
                       }
                       else
                       {
                        echo 'Journal Voucher Generation ';
                       }  ?>
                       </span>
        
          <div class="panel panel-primary">
          <div class="panel-heading" id="addpanel"><?php 
                  if($flag =='A')
                       { 
                        echo 'Cane Bill Generation';
                       }
                       else
                       {
                        echo 'Journal Voucher Generation ';
                       }  ?></div>
            <div class="panel-body">
			
			     <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Season <span class="required">*</span></label>
              <li>
             <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="season" name="season" class="form-control col-md-7 col-xs-12" valid="required" errmsg="Please Select Season." disabled="disabled">
                  <option value="">Select</option>
                <? for($i=0;$i<sizeof($seasonres);$i++){?>
                  <option value="<?=$seasonres[$i]['SN_CODE']?>" <? if($_SESSION['SEASON'] == $seasonres[$i]['SN_CODE']){ echo 'selected="selected"'; }?>><?=$seasonres[$i]['SN_CODE']?></option>
                <? } ?> 
                </select>

                  <font color="red"><span id="season_err"></span></font>
              </div>
            </li>
             <input type="hidden" name="flag" id='flag' value="<?php echo $flag; ?>">
            </div>   
         
		 <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Bill Type <span class="required">*</span></label>
              <li><div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="bill_type" name="bill_type" class="form-control col-md-7 col-xs-12" valid="required" errmsg="Please Select Bill Type."  >
                  <option value="">Select</option>
                <? for($i=0;$i<sizeof($billTypeRes);$i++){?>
                  <option value="<?=$billTypeRes[$i]['BT_CODE']?>"><?=$billTypeRes[$i]['BT_NAME']?></option>
                <? } ?> 
                </select>

              </div></li>
            </div> 
		 
		 
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Fornight No <span class="required">*</span></label>
              <li>
             <div class="col-md-6 col-sm-6 col-xs-12">
                <select id ="fornight" name="fornight" class="form-control col-md-7 col-xs-12" valid="required" errmsg="Please Select Fornight Number.">
                 <option value="">Select</option>
                </select>
              </div>
            </li>
            </div>   
           
                
                <!-- For Ajax Loader -->
         <div id="wait" style="display:none;width:69px;height:89px;border:1px solid black;position:absolute;top:50%;left:50%;padding:2px;"><img src="images/loader.gif" width="64" height="64" />
              <br>Wait..
            </div>
            <div class="ln_solid"></div>
              <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
               <button id="btn_submit" type="submit" name="submit" class="btn btn-success">Submit</button>
			   <button id="btn_delete" type="button" name="delete" class="btn btn-danger">Delete</button>
              </div>
              </div>  
              
          </div><!--panel-body-->
          </div><!--panel panel-primary--> 
          </ul>
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
   $('#bill_type').change(function(){
   
       $("#fortnight").empty();

        var season = $('#season').val();
        var bill_type = $('#bill_type').val();
		
        jQuery.ajax({ 
            type: "POST",
            datatype: "json",
            async: false,
            url: "cane_biilgenration.php",
            data:({getFornight:'Y',season:season,bill_type:bill_type}),
            success:function(data)
            {		
               //setfornight LOV
               $("#fornight").empty();
               data = $.parseJSON(data);
               
               $('#fornight').append($('<option>').text('Select').attr('value',''));
               $.each(data, function(i, value) {
                //alert(value);
                  $('#fornight').append($('<option>').text(value['FORTNIGHT']).attr('value', value['SND_FNNO']));
               });    
                  
           }//success
        }); //ajax
    }); //function  

    //For Night Validation
    $("#fornight").click(function (e) {
      var seasoncode = $('#season').val();
      if(seasoncode =="")
       {
        $("#season_err").html("Please Select Season");
       }else{
         $("#season_err").html("");  
       }
    })


//Submit data and insert into tabl
$('#btn_submit').on('click', function() {
$("#wait").show();
var res = validKeyInd();
     if(errCOUNT == 0)
       {
        $.ajax({
              url: "cane_biilgenration.php",
              data:$('#bill_gen').serialize()+'&'+$.param({'action':'procedure'}),
              datatype: "json",
              success: function(data){
              $("#wait").hide();
              swal(data.trim(), "", "");
             }//success


         });//End Of Ajax Call
      
      }//if 

}); //End OF Submit 

$('#btn_delete').on('click', function() {
//alert('delete');

var res = validKeyInd();
     if(errCOUNT == 0)
       {
	   $("#wait").show();
        $.ajax({
              url: "cane_biilgenration.php",
              data:$('#bill_gen').serialize()+'&'+$.param({'action':'delete'}),
              datatype: "json",
              success: function(data){
               //alert("Res :"+data);
             // console.log("Ajax Call success"+data);
              $("#wait").hide();
              swal(data.trim(), "", "success");
             }//success
         });//End Of Ajax Call
      }//if 
}); //End OF Delete 

 }); //ready function
</script>
