<? 	

	require_once('dashboard.php');
	include('readfile.php');
	
	$lgs = new Logs();
	$qryObj = new Query();
	$rfObj = new ReadFile();
	$dsbObj = new Dashboard(); 
	$qryPath = "util/readquery/general/caneratemast.ini";
	$langPath = "util/language/";
	$lang = $_SESSION['LANG'];
	$menu_code = $_SESSION['MENU_CODE'];
	$langPath = $langPath."general/".strtolower($lang).'/'.$menu_code.".txt";
	$action = $_GET['view'];
	$param_name = $_SESSION['PARAM_LIST'];

	$oldLovFilter = array(':PCOMP_CODE');
	$newLovFilter = array($_SESSION['COMP_CODE']);
	$caneLov = $dsbObj->getLovQry(66,$oldLovFilter,$newLovFilter);

	/*For Auto-Incremented code*/
	$oldCodeFilter = array(':PCOMP_CODE', ':PSRNUM',':PTBLNM');
	$newCodeFilter = array($_SESSION['COMP_CODE'],4,'CRATEMAST');
	$crate_code = $dsbObj->getLovQry(4,$oldCodeFilter,$newCodeFilter);

	$master_lov=$dsbObj->getLovQry(61,$oldLovFilter,$newLovFilter);/* use for main section */
	$season_lov=$dsbObj->getLovQry(28,$oldLovFilter,$newLovFilter);
	$billt_lov=$dsbObj->getLovQry(64,$oldLovFilter,$newLovFilter);
	$div_lov=$dsbObj->getLovQry(68,$oldLovFilter,$newLovFilter);
	$gat_lov=$dsbObj->getLovQry(67,$oldLovFilter,$newLovFilter);	
	$factory_lov=$dsbObj->getLovQry(69,$oldLovFilter,$newLovFilter);

	//for fornight LOV
      if(isset($_POST['getFornight'])){
         $oldFilter = array(':PCOMP_CODE',':PSEASON',':PBILL_TYPE');
         $newFilter = array($_SESSION['COMP_CODE'],$_POST['season'],$_POST['billtype_code']);
         $fortnightQry = $qryObj->fetchQuery($qryPath,'Q001','FORTNIGHT',$oldFilter,$newFilter);
         $fortnight_lov = $dsbObj->getData($fortnightQry);
		 echo json_encode($fortnight_lov);
         exit();
      }

	//print_r($snLOVres);
	if($action=='view' || $action=='update')
	{
		$array1 = $_GET['column_names'];
		$colnames = explode(',', $array1);
		$lgs->lg->trace("--Column Names--:".json_encode($colnames));
		$oldfilter = array();
		for($i = 0; $i < sizeof($colnames); $i++)
		{
			$oldfilter[$i] = ":".$colnames[$i];
		}
		$array2 = $_GET['rowdata'];
		//$newfilter = explode(',', $array2);
		$newfilter = json_decode($array2);
		$hb_scode = $newfilter[2];/*get HB_SCODE for selcted drop-down value*/
		$lgs->lg->trace("--Row Data--:".json_encode($newfilter));
	
		$HeaderQry = $qryObj->fetchQuery($qryPath,'Q001','GETHEADER',$oldfilter,$newfilter);
		$HeaderdataRes = $dsbObj->getData($HeaderQry);
		$season = $HeaderdataRes[0]['CR_SCODE'];		

		/* FOR DETAIL DATA */
		$HeaderdataQry = $qryObj->fetchQuery($qryPath,'Q001','GETDATAQRY',$oldfilter,$newfilter);
		$hbData = $dsbObj->getData($HeaderdataQry);
		
		$alldtlArr = array();
		for($i=0;$i<sizeof($hbData);$i++)
	    {
			array_push($alldtlArr,$hbData[$i]['CRD_RUNO']);
		 	$GetDataJsonRes[]=array_values($hbData[$i]);
	    }
	    $_SESSION['oldruno']=$alldtlArr; 
		$GetDataJsonRes=json_encode($GetDataJsonRes,JSON_PRETTY_PRINT.';');

		$cntQry = $qryObj->fetchQuery($qryPath,'Q001','GETCNT',$oldfilter,$newfilter);
		$cntRes = $dsbObj->getData($cntQry);
		//$cnt = $cntRes[0]['CNT'];

		if($cntRes[0]['CNT'] == '')
		{
			$cnt = 1;
		}

		else
		{
			$cnt = $cntRes[0]['CNT'];
		}
	}//END OF VIEW AND UPDATE
	

	$back_link = 'view_browse.php?menu_code='.$menu_code;
    /*use to get server msg file name*/ 
    $server_msg = 'main_msg_'.$lang.'.txt';
    $client_msg = $menu_code.'_msg_'.$lang.'.txt';
	
?>	
<? 	
	require_once("header.php");
	require_once("sidebar.php");
?> 
<style type="text/css">
	.ui-autocomplete{
	z-index:1050;
}
</style>

<div class="right_col">
<div class="col-md-12 col-sm-12 col-xs-12">
<span class="section"><?=$rfObj->readData('CRM',$langPath); ?></span>

<div class="panel panel-primary">
<div class="panel-heading"><?php  echo ucfirst($action); ?></div>
<div class="panel-body">
<form  id="caneratemast" name="caneratemast" class="form-horizontal form-label-left">  
	<div class="x_panel">
	<ul class="contactus-list">
   	   <div class="form-group">
    		<div class="col-md-6 col-sm-6 col-xs-12">
	    		<div class="col-md-5 col-sm-5 col-xs-12">
	    			<label class="control-label"><?php echo $rfObj->readData('SYEAR',$langPath); ?></label>
	    			<input type="hidden" name="crate_code" id="crate_code" value="<?php if($action !='add') { echo $HeaderdataRes[0]['CR_SRNO']; }else{ echo $crate_code[0]['CODE']; } ?>">
				</div>
                
                <li><div class="col-md-7 col-sm-7 col-xs-12">
                      <select  class="form-control" name="season" id="season" valid="required" errmsg="Please Select Season">
					   <option value="">Select Season</option>
						  <?php
						   for($i=0;$i<sizeof($season_lov);$i++) {?>
							<option value="<?=$season_lov[$i]['SN_CODE']?>" 
								<? if($HeaderdataRes[0]['CR_SCODE'] == $season_lov[$i]['SN_CODE'] || $master_lov[0]['TXN_SEASON'] == $season_lov[$i]['SN_CODE'] || $_SESSION['SEASON'] == $season_lov[$i]['SN_CODE']) {?> selected="selected" <? }?>><?=$season_lov[$i]['SN_CODE']?> 
							</option>
								<? } ?>
						</select>
                </div></li>
            </div>	
						    							    	
	    	<div class="col-md-6 col-sm-6 col-xs-12">
	    		<div class="col-md-5 col-sm-5 col-xs-12">
	    			<label class="control-label"><?php echo $rfObj->readData('CVT',$langPath); ?></label>
				</div>
                
                <li><div class="col-md-7 col-sm-7 col-xs-12">
                      <select  class="form-control" name="billtype" id="billtype" valid="required" errmsg="Please Select Bill Type.">
					   <option value="">Select Bill Type</option>
						  <?php
						   for($i=0;$i<sizeof($billt_lov);$i++) {?>
							<option value="<?=$billt_lov[$i]['BT_CODE']?>" 
								<? if($HeaderdataRes[0]['CR_BTYPE'] == $billt_lov[$i]['BT_CODE'])  {?> selected="selected" <? }?>><?=$billt_lov[$i]['BT_NAME']?> 
							</option>
								<? } ?>
						</select>
                </div></li>	
	    	</div>
	    </div>	
	    <!-- END FORM-GROUP -->
	    <div class="form-group">
	    	<div class="col-md-6 col-sm-6 col-xs-12">
	    		<div class="col-md-5 col-sm-5 col-xs-12">
	    			<label class="control-label"><?php echo $rfObj->readData('FN',$langPath); ?></label>
				</div>
                
                <li>
	                <div class="col-md-7 col-sm-7 col-xs-12">
	                      <select id ="fortnight" name="fortnight" class="form-control" valid="required" errmsg="Please Select Fortnight No.">
						  	<option value="">Select</option>
						  </select>
	                </div>
                </li>
            </div>	

            <?php if($param_name == 'Y'){?>
            <div class="col-md-6 col-sm-6 col-xs-12">
	    		<div class="col-md-5 col-sm-5 col-xs-12">
	    			<label class="control-label"><?php echo $rfObj->readData('FC',$langPath); ?></label>
				</div>
                
                <div class="col-md-7 col-sm-7 col-xs-12">
                    <li>
                    	<select id="factory" name="factory" class="form-control" valid="required" errmsg="Please Select Factory."><!-- valid="required" errmsg="Please Select Factory." -->
					  		<option value="">Select Factory</option>
							   <?php
							   for($i=0;$i<sizeof($factory_lov);$i++) { ?>
								 <option value="<?=$factory_lov[$i]['PRT_CODE']?>" 
									<? if($HeaderdataRes[0]['CR_FACTORY'] == $factory_lov[$i]['PRT_CODE'])  {?> selected="selected" <? }?>><?=$factory_lov[$i]['PRT_NAME']?>
							</option>
							<? } ?>
					  </select>
					  </li>
                </div>
            </div>	
            <?php } ?>
    	</div>	
    	<!-- END FORM-GROUP -->

    	<div class="x_title">
		    <div class="row">
		    	<div class="col-md-2">	
				<? if($action=='add'||$action=='update'){?>
			    	<button type="button" class="btn btn-primary glyphicon glyphicon-plus" id="btn_canerate_add" name="btn_canerate_add" data-toggle="modal" data-target=".bs-example-modal-md">
			    	</button>
				<? } ?>
		    	</div>
		    </div>	
		</div>
							    
						 
		<div class="x_content">
				<table id="caneRateTable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th><?php echo $rfObj->readData('SRNO',$langPath); ?> </th>
							<th><?php echo $rfObj->readData('CV',$langPath); ?> </th>
							<th><?php echo $rfObj->readData('VN',$langPath); ?></th>
							<th><?php echo $rfObj->readData('GAT',$langPath); ?></th>
							<th><?php echo $rfObj->readData('GN',$langPath); ?></th>
							<th><?php echo $rfObj->readData('DIV',$langPath); ?></th>
							<th><?php echo $rfObj->readData('DD',$langPath); ?></th>
							<th><?php echo $rfObj->readData('FKM',$langPath); ?></th>
							<th><?php echo $rfObj->readData('TOKM',$langPath); ?></th>
							<th><?php echo $rfObj->readData('RATE',$langPath); ?></th>
							<th>Flag</th>
							<th><?php echo $rfObj->readData('ACTION',$langPath); ?></th>
						</tr>
					</thead>		
				</table>
  		</div>
	</div>
	<!-- X-PANEL END -->
	
		<div id="wait" class="ui-autocomplete" style="display:none;width:69px;height:89px;border:2px solid black;position:absolute;top:70%;left:50%;padding:2px;">
			<img src='images/loader.gif' width="64" height="64" /><br>Loading..
		</div>	
					
		<div class="ln_solid"></div>
		 <div class="form-group">
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
			    <button id="btn_submit" type="button" class="btn btn-success">
			  		<?php
			  		if($action=='add' && $action != 'update')
			  		{  ?>Submit
			  		<?php }else{ ?>Update
			  		<?php } ?>
			  		</button>
			  	<button type="reset" name="reset" class="btn btn-info" id="btn_reset">Reset</button>	
                <button type="button" id="btn_cancel" class="btn btn-danger">
             	 <?php
			  		if($action =='add' || $action == 'update')
			  		{	?>
			  			Cancel
			  		<?php }else{ ?>
			  			Back
			  		<?php } ?>
			  	</button>
			 </div>
		  </div>
		</ul>		  
	</form>			  
	
	</div><!--panel-body-->
</div><!--panel panel-primary--> 	

	  	</div>
	   </div>

<div class="modal fade bs-example-modal-md" id="term_modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
        <div class="modal-content">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
			    </button>
			  	  <h4 class="modal-title" id="myModalLabel"></h4>
			</div>
			
			<div class="modal-body" data-backdrop="false">
		        <form id="pop-up" name="pop-up" class="form-horizontal form-label-left">  
					<ul class="contactus-list">
					<div class="form-group">
						<label class="control-label col-md-3"><?=$rfObj->readData('CV',$langPath); ?></label>
							<div class="col-md-9">
						    	<select name="canev" id="canev" class="form-control">
							   		<option value="">--Select Cane Variety--</option>
						             <?php
								   for($i=0;$i<sizeof($caneLov);$i++)
									{  ?>
								   <option value="<?=$caneLov[$i]['CV_CODE'].'-'.$caneLov[$i]['CV_NAME'] ?>"> <?= $caneLov[$i]['CV_CODE'].'-'.$caneLov[$i]['CV_NAME'] ?></option>
								   <? } ?>
								</select>
							</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3"><?=$rfObj->readData('GAT',$langPath); ?></label>
							<div class="col-md-9">
								<select name="gat" id="gat" class="form-control">
							   		<option value="">--Select Gat--</option>
						             <?php
								   for($i=0;$i<sizeof($gat_lov);$i++)
									{  ?>
								   <option value="<?=$gat_lov[$i]['SC_CODE'].'-'.$gat_lov[$i]['SC_NAME'].'-'.$gat_lov[$i]['SC_DIVISION'] ?>"> <?= $gat_lov[$i]['SC_CODE'].'-'.$gat_lov[$i]['SC_NAME'] ?></option>
								   <? } ?>
								</select>
							</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3"><?=$rfObj->readData('DIV',$langPath); ?></label>
							<li>
								<div class="col-md-9"><!-- valid="required" errmsg="Please Select Division." -->
									<select name="div" id="div" class="form-control" >
								   		<option value="">Select Division</option>
							             <?php
									   for($i=0;$i<sizeof($div_lov);$i++)
										{  ?>
									   <option value="<?=$div_lov[$i]['DV_CODE']?>"> <?= $div_lov[$i]['DV_CODE'].'-'.$div_lov[$i]['DV_NAME'] ?></option>
									   <? } ?>
									</select>
								</div>
							</li>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3"><?=$rfObj->readData('FKM',$langPath); ?></label>
							<li><div class="col-md-9">
								<input type="text" class="form-control amount" name="from_km" id="from_km" valid="required" errmsg="Please Enter From Km.">
							</div></li>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3"><?=$rfObj->readData('TOKM',$langPath); ?></label>
							<li><div class="col-md-9">
								<input type="text" class="form-control amount" name="to_km" id="to_km" valid="required" errmsg="Please Enter To Km.">
							</div></li>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3"><?=$rfObj->readData('RATE',$langPath); ?></label>
							<li>
								<div class="col-md-9">
									<input type="text" class="form-control amount" name="rate" id="rate"  valid="required"  errmsg="Please Enter Rate">
								</div>
							</li>
					</div>
					<span id="err_amount" style="color: red;"></span>	

				</ul>	
			    </form>
			</div>		

			<div class="modal-footer">
			    <button type="submit" class="btn btn-primary" id="btn_canerate" name="btn_canerate" >Save</button>
			   	<button type="reset" class="btn btn-info" data-dismiss="modal">Exit</button>
			</div>
		</div>	
	</div>	
</div>	

   
<script type="text/javascript">
var back_link = '<?php echo $back_link ?>';
var action_flag = "<?php echo $action; ?>";

$(document).ready(function(){
var tnSet = [];
var tnSetNew = [];
var tnDataSet = new Array();
var tnId = '';
var tn_flag = '';
var tn_detail = [];
//var tn_count = '';

/************************************************DETAIL TABLE START***************************************************/
var caneRateTable  = $('#caneRateTable').DataTable({
	<?php 
	 if($action =='view' || $action=='update'){
	 echo '"data":'.$GetDataJsonRes.',';
		  }
	 ?>
			/*"stateSave": true,
			"deferRender": true,*/
			"order": [[ 0, 'asc' ], [ 1, 'asc' ]],
			"aLengthMenu": [[100, 200, 300, -1],[100, 200, 300, "All"]],
            "iDisplayLength": 100,
			 "columnDefs": [ 
        <? if($action == 'add' || $action == 'update' || $action == 'view') { ?>       
          {
            "targets":[11],
            "data": null,

          <? if($action == 'add') { ?>
            "defaultContent":"<a href='' id='delete' class='glyphicon glyphicon-trash' title='Delete'></a>",  
          <? }
            if($action == 'update') {?> 
               "defaultContent":"<a id='delete' class='glyphicon glyphicon-trash' title='Delete'></a>", 
          <? }
		  if($action == 'view') {?> 
               "defaultContent":" ", 
          <? } ?>
          },

          { 
            "responsivePriority": -1,
            "targets": -1
          },  
        <? } ?>
      ],  
});

$("#btn_canerate_add").on('click',function(){
	tn_flag = 'ins';
	$("#canev").val("");
	$("#gat").val("");
	$("#div").val("");
	$("#from_km").val("");
	$("#to_km").val("");
	$("#rate").val("");
	$("#div").prop('disabled',false);
});

if(action_flag=='add'){
 	tn_count = 1;
}else{
	tn_count = '<?php echo $cnt; ?>'
}

//tn_count = '<?php //echo $cnt; ?>';
 
$("#btn_canerate").on('click', function (e) { 
	var res = validKeyInd();
	if(errCOUNT == 0)
	{
		e.preventDefault();
		var value1 = $("#canev").val();
		//alert(value1);
		if(value1 == null || value1 == ""){
			var canev = '';
			var cvariety = '';
		}
		else{
			var res = value1.split("-");
			var canev = res[0];/*split code*/
			var cvariety = res[1];/*get description*/
		}

		//Gat No
		var value2 = $("#gat").val();
		//alert(value2);
		if(value2 == null || value2 == ""){
			var gat_no = '';
			var gat_name = '';
			var div_code = '';
		}
		else{
			var res2 = value2.split("-");
			var gat_no = res2[0];/*split code*/
			var gat_name = res2[1];/*get description*/
			var div_code = res2[2]
		}
		//var value3 = $("#div").text();
		var value3 = $('#div :selected').text();
		var res3 = value3.split("-");
		var div_code = res3[0].trim();
		var div_name = res3[1];/*get description*/
		var from_km = $("#from_km").val();
		var to_km = $("#to_km").val();
		var rate = $("#rate").val();
		//alert('**'+tn_flag);	
		if(tn_flag=='ins'){
			tnSet =  [[tn_count,canev,cvariety,gat_no,gat_name,div_code,div_name,from_km,to_km,rate,tn_flag]];
			caneRateTable.rows.add(tnSet).draw();
			tn_count++;	
			//$("#term_modal").hide();
			$('#term_modal').modal('hide');
		}
		if(action_flag=='add' || tn_flag=='ins'){
			flag_val = 'upd';
		}
		if(action_flag=='update' && tn_flag=='upd'){
			flag_val = '';
		}
        if(tn_flag == 'upd')
        {        
			var tnTemp = new Array();
			/*set updated records in datatable inner html*/
			$('table#caneRateTable tr:eq('+tnId+') td:eq(1)').html(canev);
			$('table#caneRateTable tr:eq('+tnId+') td:eq(2)').html(cvariety);
			$('table#caneRateTable tr:eq('+tnId+') td:eq(3)').html(gat_no);
			$('table#caneRateTable tr:eq('+tnId+') td:eq(4)').html(gat_name);
			$('table#caneRateTable tr:eq('+tnId+') td:eq(5)').html(div_code);
			$('table#caneRateTable tr:eq('+tnId+') td:eq(6)').html(div_name);
			$('table#caneRateTable tr:eq('+tnId+') td:eq(7)').html(from_km);
			$('table#caneRateTable tr:eq('+tnId+') td:eq(8)').html(to_km);
			$('table#caneRateTable tr:eq('+tnId+') td:eq(9)').html(rate);
			$('table#caneRateTable tr:eq('+tnId+') td:eq(10)').html(flag_val);

			/*get newly set records for futher process*/
			var ac_cnt = $('table#caneRateTable tr:eq('+tnId+') td:eq(0)').text();
			var v0 = $('table#caneRateTable tr:eq('+tnId+') td:eq(1)').text();
			var v1 = $('table#caneRateTable tr:eq('+tnId+') td:eq(2)').text();
			var v2 = $('table#caneRateTable tr:eq('+tnId+') td:eq(3)').text();
			var v3 = $('table#caneRateTable tr:eq('+tnId+') td:eq(4)').text();
			var v4 = $('table#caneRateTable tr:eq('+tnId+') td:eq(5)').text();
			var v5 = $('table#caneRateTable tr:eq('+tnId+') td:eq(6)').text();
			var v6 = $('table#caneRateTable tr:eq('+tnId+') td:eq(7)').text();
			var v7 = $('table#caneRateTable tr:eq('+tnId+') td:eq(8)').text();
			var v8 = $('table#caneRateTable tr:eq('+tnId+') td:eq(9)').text();
			var v9 = $('table#caneRateTable tr:eq('+tnId+') td:eq(10)').text();
			
			tnSet =  [[ac_cnt,v0,v1,v2,v3,v4,v5,v6,v7,v8,v9,flag_val]];
			$.each(tnSet, function(key,value) {
				tnTemp[key] = value;
			});
			tnSetNew.push(tnTemp);
			//alert(tnSetNew);
			//$("#term_modal").hide();
			$('#term_modal').modal('hide');
		}
	}//validation if	
		
	});	

/*Tonnage Table Edit Option*/
/*$('#caneRateTable').on('click','#tnedit',function (e) {
	e.preventDefault();
	var tnData = caneRateTable.row($(this).parents('tr')).data();
	var cane = tnData[1]+"-"+tnData[2];
		$("#canev").val(cane);
	var gat = tnData[3]+"-"+tnData[4]+"-"+tnData[5];
		$("#gat").val(gat);
	var div = tnData[5]+"-"+tnData[6];
		$("#div").val(div);
		$("#from_km").val(tnData[7]);
		$("#to_km").val(tnData[8]);
		$("#rate").val(tnData[9]);
	tnId = (caneRateTable.row( $(this).parents('tr')).index()+1);
	//alert(tnId);
	tn_flag = 'upd';
});	*/

/*Tonnage Delete Option*/
$('#caneRateTable').on('click','#delete',function (e) {
	e.preventDefault();
	var data = caneRateTable.row( $(this).parents('tr') ).data();
	caneRateTable.row( $(this).parents('tr')).remove().draw();
});	


var season_code = '<?php echo $season; ?>';
var fortnight = '<?php echo $HeaderdataRes[0]['CR_FNNO']; ?>';
var btype = '<?php echo $HeaderdataRes[0]['CR_BTYPE']; ?>';

	$.ajax({
            url: "caneratemast_server.php",
            method:"POST",
            data:$('#caneratemast').serialize()+'&'+$.param({'action':'fortnight'})+'&'+$.param({'season':season_code,'billtype_code':btype}),
	        datatype: "json",
              success: function(data){
    		  data = $.parseJSON(data);
			  //$('#fortnight').append($('<option>').text('Select').attr('value',''));
			  $.each(data, function(i, value) {
			  	if(value['SND_FNNO'] == fortnight){
			   	$('#fortnight').append($('<option selected="selected">').text(value['FORTNIGHT']).attr('value', value['SND_FNNO']));
              	}else{
              	$('#fortnight').append($('<option>').text(value['FORTNIGHT']).attr('value', value['SND_FNNO']));
              	}
              });
			}  
         });

$('#btn_submit').on('click', function(){ 	
		/*use to get new row data*/	

		caneRateTable.rows().every(function (rowIdx, tableLoop, rowLoop) {
			new_data = this.data();	
			tn_detail.push(new_data);
		});
		
		/*use to get data in edit mode*/   
     		if((tnSetNew.length)>0){
     			for(i=0;i<tnSetNew.length;i++){
					tnDataSet[i]=tnSetNew[i][0];
				}
			}
			//alert('submit'+tn_detail);
	var res = validKeyInd();
	if(errCOUNT == 0)
	{

		$("#wait").show();
		$.ajax({
            url: "caneratemast_server.php",
            data:$('#caneratemast').serialize()+'&'+$.param({'action':'fullform'})+
            '&'+$.param({'flag':action_flag,tn_detail:tn_detail,tnDataSet:tnDataSet}),
			datatype: "json",
             success: function(data)
             {
             	$("#wait").hide();
             	if(data == 1 && action_flag =='add')
				   {
				    swal({
					  title: 'Record Added Successfully',
					  timer: 10000,
					  type: 'success',
					  showConfirmButton: false
					});
				    location.href = back_link;
				   }
				   else if(data == 1 && action_flag =='update')
				   {
				      swal({
					  title: 'Record Updated Successfully',
					  timer: 10000,
					  type: 'success',
					  showConfirmButton: false
					});
				    location.href = back_link;
				   }else
				   {
				      var msg = data.trim();
					  swal(msg,"","error");
				   }

			  	
			}//success
         });//ajax
	}//if
	
	
 	});//submit

/***********************************************Change On Season ******************************************************************/
/*$("#season").change(function(){
	var season_code=$("#season").val();
	$("wait").show();	
	$.ajax({
            url: "caneratemast_server.php",
            method:"POST",
            data:$('#caneratemast').serialize()+'&'+$.param({'action':'fortnight'})+'&'+$.param({'season':season_code}),
	        datatype: "json",
              success: function(data){
              	$("wait").hide();
			  data = $.parseJSON(data);
			  if(data.length ==0)
			  {
			  	swal({
				  title:"Data Not Found !",//call getMsg function with message number and file name
				  timer: 2000,
				  type: 'error',
				  showConfirmButton: false
					});

			  }else{
			  $.each(data, function(i, value) {
               $('#fortnight').append($('<option>').text(value['SND_TODT']+" -To- "+value['SND_DUDT']).attr('value',value['SND_FNNO'])); 
              });
			 }//else	  
            }  
         });
    });*/

/********************************Change On Season **********************************************/

$("#gat").change(function(){

	var gat = $("#gat").val().split('-');
	if(gat == ''){
		$('#div').empty();
		$("#wait").show();
		$("#div").prop('disabled',false);
		$.ajax({
	        url: "caneratemast_server.php",
	        method:"POST",
	        data:$.param({'action':'divi'}),
	        datatype: "json",
	          success: function(data){
	          $("#wait").hide();

	          $('#div').append($('<option>').text('---Select Division---').attr('value',''));
	          data = $.parseJSON(data);
			  $.each(data, function(i, value) {
	           $('#div').append($('<option>').text(value['DV_CODE']+'-'+value['DV_NAME']).attr('value',value['DV_CODE'])); 
	          });			 
	        }  
	     }); //ajax
	}
	else{
		$options = $('#div option');
		$options.filter('[value="'+gat[2]+'"]').prop('selected', true);
		$("#div").prop('disabled',true);
	}	
});

	
/***********************************************Change On Cane Bill Type ******************************************************************/
if(action_flag == 'add' || action_flag == 'update'){
$('#billtype').change(function(){
        var billtype_code = $('#billtype').val();
		var season = $("#season").val();
		//alert(season+'||'+billtype_code);
        jQuery.ajax({ 
            type: "POST",
            datatype: "json",
            async: false,
            url: "caneratemast.php",
            data:({getFornight:'Y',season:season,billtype_code:billtype_code}),
            success:function(data)
            {
              //setfornight LOV
              $("#fortnight").empty();
               data = $.parseJSON(data);
               
               $('#fortnight').append($('<option>').text('Select').attr('value',''));
               $.each(data, function(i, value) {
                //alert(value);
                $('#fortnight').append($('<option>').text(value['FORTNIGHT']).attr('value', value['SND_FNNO']));
               });    
                  
           }//success
        }); //ajax
    }); //function 
} //if

});//ready




</script>	 

<script type="text/javascript">
	/*On Click of Cancel button*/
	$("#btn_cancel").on('click',function(){
		if(action_flag == 'view')
		{
			location.href=back_link;
		}
		else
		{
			swal({
				  title: 'Are you sure?',
				  text: "You won't be able to revert this action!",
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Yes'
				}).then(function () {
					location.href=back_link;
				})
		}
	});	

if(action_flag == 'view' || action_flag =='update')
		{
			$("#code").prop('disabled',true);	
			$("#btn_copy").prop('disabled',true);	
		}
if(action_flag == 'view')
		{
			$("#btn_submit").hide();
			$("#btn_reset").hide();
			$("#season").prop('disabled',true);
			$("#billtype").prop('disabled',true);
			$("#fortnight").prop('disabled',true);

		}		
if(action_flag == 'add')
{
	$("#season").prop("disabled",true);
}

</script>

<? include("footer.php");?>