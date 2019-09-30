<? 	
	require_once('dashboard.php');
	include('readfile.php');
	
	$lgs = new Logs();
	$qryObj = new Query();
	$rfObj = new ReadFile();
	$dsbObj = new Dashboard(); 
	$qryPath = "util/readquery/general/cb_deductiondtl.ini";
	$langPath = "util/language/";
	$lang = $_SESSION['LANG'];
	$menu_code = $_SESSION['MENU_CODE'];
	$langPath = $langPath."general/".strtolower($lang).'/'.$menu_code.".txt";
	$action = $_GET['view'];
	$param_name = $_SESSION['PARAM_LIST'];
	$more_option = $_GET['OPTN'];
	$temp_flag = false;

	$oldLovFilter = array(':PCOMP_CODE',':PAD_DOC');
	$newLovFilter = array($_SESSION['COMP_CODE'],'CANE');
	$caneLov = $dsbObj->getLovQry(66,$oldLovFilter,$newLovFilter);

	/*For Auto-Incremented code*/
	$oldCodeFilter = array(':PCOMP_CODE', ':PSRNUM',':PTBLNM');
	$newCodeFilter = array($_SESSION['COMP_CODE'],4,'CDEDMAST');
	$crate_code = $dsbObj->getLovQry(4,$oldCodeFilter,$newCodeFilter);

	$master_lov=$dsbObj->getLovQry(61,$oldLovFilter,$newLovFilter);/* use for main section */
	$season_lov=$dsbObj->getLovQry(28,$oldLovFilter,$newLovFilter);
	$billt_lov=$dsbObj->getLovQry(64,$oldLovFilter,$newLovFilter);
	/*$ded_type=$dsbObj->getLovQry(70,$oldLovFilter,$newLovFilter);*/
	$ded_type=$dsbObj->getLovQry(100,$oldLovFilter,$newLovFilter);
	
	//for fornight LOV
      if(isset($_POST['getFornight'])){
         $oldFilter = array(':PCOMP_CODE',':PSEASON',':PBTYPE');
         $newFilter = array($_SESSION['COMP_CODE'],$_POST['season'],$_POST['billtype_code']);
		 $fortnight_lov=$dsbObj->getLovQry(137,$oldFilter,$newFilter);
         echo json_encode($fortnight_lov);
         exit();
      }
	
	if($action=='view' || $action=='update' || $more_option=='copy')
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
		array_push($oldfilter, ':PCOMP_CODE');
		array_push($newfilter, $_SESSION['COMP_CODE']);
		$hb_scode = $newfilter[2];/*get HB_SCODE for selcted drop-down value*/
		$lgs->lg->trace("--Row Data--:".json_encode($newfilter));
	}//END OF VIEW AND UPDATE

	if($more_option=='copy')
	{
		/*Code For Copy Option*/
		$oldProcFilter = array(':PCOMP_CODE',':PCD_SRNO', ':PCD_SCODE', ':PCD_BTYPE',':PCD_FNNO');
		$newProcFilter = array($_SESSION['COMP_CODE'],$HeaderdataRes[0]['CD_SRNO'],$season,$HeaderdataRes[0]['CD_BTYPE'],$HeaderdataRes[0]['CD_FNNO']);

		$cpyAmndProc = $qryObj->fetchQuery($qryPath,'Q001','CPYDD_PROC',$oldfilter,$newfilter);
		$lgs->lg->trace("--Copy Proc--".$cpyAmndProc);
		$cpyAmndProcRes = $dsbObj->getOutProcData($cpyAmndProc,$aOutPara);
		$lgs->lg->trace("--Copy Proc Res--".json_encode($cpyAmndProcRes));
		//echo "Proc Res".$cpyAmndProcRes;
		$oldFilterC = array(':PCOMP_CODE', ':PCD_SRNO');
		$newFilterC = array($comp_code, $cpyAmndProcRes);
		$HeaderQry = $qryObj->fetchQuery($qryPath,'Q001','GETHEADER',$oldFilterC,$newFilterC);
		$HeaderdataRes = $dsbObj->getData($HeaderQry);
		//print_r($HeaderdataRes);
		//echo sizeof($HeaderdataRes);
		if(sizeof($HeaderdataRes) > 0)
		{
			$temp_flag = false;
			$season = $HeaderdataRes[0]['CD_SCODE'];		
			/* FOR DETAIL DATA */
			$HeaderdataQry = $qryObj->fetchQuery($qryPath,'Q001','GETDATAQRY',$oldfilter,$newfilter);
			$hbData = $dsbObj->getData($HeaderdataQry);
			
			$alldtlArr = array();
			for($i=0;$i<sizeof($hbData);$i++)
		    {
				array_push($alldtlArr,$hbData[$i]['CDD_RUNO']);
			 	$GetDataJsonRes[]=array_values($hbData[$i]);
		    }
		    $_SESSION['oldruno']=$alldtlArr; 
			$GetDataJsonRes=json_encode($GetDataJsonRes,JSON_PRETTY_PRINT.';');

			$cntQry = $qryObj->fetchQuery($qryPath,'Q001','GETCNT',$oldfilter,$newfilter);
			$cntRes = $dsbObj->getData($cntQry);
			$cnt = $cntRes[0]['CNT'];
		}
		else
			$temp_flag = true;
	}

	if($action=='view' || $action=='update')
	{
		$HeaderQry = $qryObj->fetchQuery($qryPath,'Q001','GETHEADER',$oldfilter,$newfilter);
		$HeaderdataRes = $dsbObj->getData($HeaderQry);
		$season = $HeaderdataRes[0]['CD_SCODE'];		
		/* FOR DETAIL DATA */
		$HeaderdataQry = $qryObj->fetchQuery($qryPath,'Q001','GETDATAQRY',$oldfilter,$newfilter);
		$hbData = $dsbObj->getData($HeaderdataQry);
		
		$alldtlArr = array();
		for($i=0;$i<sizeof($hbData);$i++)
	    {
			array_push($alldtlArr,$hbData[$i]['CDD_RUNO']);
		 	$GetDataJsonRes[]=array_values($hbData[$i]);
	    }
	    $_SESSION['oldruno']=$alldtlArr; 
		$GetDataJsonRes=json_encode($GetDataJsonRes,JSON_PRETTY_PRINT.';');

		$cntQry = $qryObj->fetchQuery($qryPath,'Q001','GETCNT',$oldfilter,$newfilter);
		$cntRes = $dsbObj->getData($cntQry);
		$cnt = $cntRes[0]['CNT'];
	}

	$back_link = 'view_browse.php?menu_code='.$menu_code;
    /*use to get server msg file name*/ 
    $server_msg = 'main_msg_'.$lang.'.txt';
    $client_msg = $menu_code.'_msg_'.$lang.'.txt';
	
?>	
<? 	
	require_once("header.php");
	require_once("sidebar.php");
?> 
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
	    			<input type="hidden" name="crate_code" id="crate_code" value="<?php if($action !='add') { echo $HeaderdataRes[0]['CD_SRNO']; }else{ echo $crate_code[0]['CODE']; } ?>">
				</div>
                
                <li><div class="col-md-7 col-sm-7 col-xs-12">
                      <select  class="form-control" name="season" id="season" valid="required" errmsg="Please Select Season">
					   <option value="">Select Season</option>
						  <?php
						   for($i=0;$i<sizeof($season_lov);$i++) {?>
							<option value="<?=$season_lov[$i]['SN_CODE']?>" 
								<? if($HeaderdataRes[0]['CD_SCODE'] == $season_lov[$i]['SN_CODE'] || $master_lov[0]['TXN_SEASON'] == $season_lov[$i]['SN_CODE'] || $_SESSION['SEASON'] == $season_lov[$i]['SN_CODE'])  {?> selected="selected" <? }?>><?=$season_lov[$i]['SN_CODE']?> 
							</option>
								<? } ?>
						</select>
                </div></li>
            </div>	
						    							    	
	    	<div class="col-md-6 col-sm-6 col-xs-12">
	    		<div class="col-md-5 col-sm-5 col-xs-12">
	    			<label class="control-label"><?php echo $rfObj->readData('CVT',$langPath); ?>*</label>
				</div>
                
                <li><div class="col-md-7 col-sm-7 col-xs-12">
                      <select  class="form-control" name="billtype" id="billtype" valid="required" errmsg="Please Select Bill Type.">
					   <option value="">Select Bill Type</option>
						  <?php
						   for($i=0;$i<sizeof($billt_lov);$i++) {?>
							<option value="<?=$billt_lov[$i]['BT_CODE']?>" 
								<? if($HeaderdataRes[0]['CD_BTYPE'] == $billt_lov[$i]['BT_CODE'])  {?> selected="selected" <? }?>><?=$billt_lov[$i]['BT_NAME']?> 
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

            
    	</div>	
    	<!-- END FORM-GROUP -->

    	<div class="x_title">
		    <div class="row">
		    	<div class="col-md-2">	
			    	<button type="button" class="btn btn-primary glyphicon glyphicon-plus" id="btn_canerate_add" name="btn_canerate_add" data-toggle="modal" data-target=".bs-example-modal-md">
			    	</button>
		    	</div>
		    </div>	
		</div>
							    
						 
		<div class="x_content">
				<table id="caneRateTable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th><?php echo $rfObj->readData('SRNO',$langPath); ?> </th>
							<th><?php echo $rfObj->readData('SO',$langPath); ?> </th>
							<th><?php echo $rfObj->readData('CD',$langPath); ?> </th>
							<th><?php echo $rfObj->readData('DD',$langPath); ?></th>
							<th><?php echo $rfObj->readData('TYPE',$langPath); ?></th>
							<th><?php echo $rfObj->readData('CAT',$langPath); ?></th>
							<th><?php echo $rfObj->readData('RULE',$langPath); ?></th>
							<th><?php echo $rfObj->readData('VALUE',$langPath); ?></th>
							<th><?php echo $rfObj->readData('FLAG',$langPath); ?></th>
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
		        <form  class="form-horizontal form-label-left">  
					<ul class="contactus-list">
					<div class="form-group">
						<div class="col-md-5 col-sm-5 col-xs-12">	
							<label class="control-label"><?=$rfObj->readData('SO',$langPath); ?></label>
						</div>	
							<li>
								<div class="col-md-7 col-sm-7 col-xs-12">
									<input type="text" class="form-control" name="sort_order" id="sort_order" valid="required" errmsg="Please Enter Value.">
								</div>
							</li>
					</div>	

					<div class="form-group">
						<div class="col-md-5 col-sm-5 col-xs-12">	
							<label class="control-label"><?=$rfObj->readData('D',$langPath); ?></label>
						</div>	
							<div class="col-md-7 col-sm-7 col-xs-12"><!-- valid="required" errmsg="Please Select ." -->
						    	<select class="form-control" id="ad_type" name="ad_type">
				                  <option value="">Select</option>
				                  <? for($i=0;$i<sizeof($ded_type);$i++) {?>
				                  <option value="<?=$ded_type[$i]['AD_CODE'].'-'.$ded_type[$i]['AD_NAME'].'-'.$ded_type[$i]['AD_TYPE']?>" <? if($HeaderdataRes[0]['AD_TYPE'] == $ded_type[$i]['AD_CODE'] ) {?> selected="selected" <? }?>><?=$ded_type[$i]['AD_CODE']
				                  .'-'. $ded_type[$i]['AD_NAME'].'-'. $ded_type[$i]['AD_TYPE']?>
				                  </option>
				                  <? } ?>
						  </select>
							</div>
					</div>

					<div class="form-group">
						<div class="col-md-5 col-sm-5 col-xs-12">	
							<label class="control-label"><?=$rfObj->readData('CAT',$langPath); ?></label>
						</div>	
							<div class="col-md-7 col-sm-7 col-xs-12">
						    	<select class="form-control" id="cat" name="cat" valid="required" errmsg="Please Select .">
				                  <option value="B">Bal Based</option>
				                  <option value="C">Compulsory</option>
						  </select>
							</div>
					</div>

					<div class="form-group">
						<div class="col-md-5 col-sm-5 col-xs-12">	
							<label class="control-label"><?=$rfObj->readData('RULE',$langPath); ?></label>
						</div>	
							<div class="col-md-7 col-sm-7 col-xs-12">
						    	<select class="form-control" id="rule" name="rule" valid="required" errmsg="Please Select .">
				                  <option value="P">Percent(%)</option>
				                  <option value="A">Amount</option>
				                  <option value="R">Rate Per MT</option>
						  </select>
							</div>
					</div>

					<div class="form-group">
						<div class="col-md-5 col-sm-5 col-xs-12">	
							<label class="control-label"><?=$rfObj->readData('VALUE',$langPath); ?></label>
						</div>	
							<li>
								<div class="col-md-7 col-sm-7 col-xs-12">
									<input type="text" class="form-control amount" name="value" id="value" valid="required" errmsg="Please Enter Value.">
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
<? include("footer.php");?>
   
<script type="text/javascript">
var back_link = '<?php echo $back_link ?>';
var action_flag = "<?php echo $action; ?>";
var more_option = "<?php echo $more_option; ?>";

$(document).ready(function(){
var tnSet = [];
var tnSetNew = [];
var tnDataSet = new Array();
var tnId = '';
var tn_flag = '';
var tn_detail = [];
var tn_count = '';

/************************************************DETAIL TABLE START***************************************************/
var caneRateTable  = $('#caneRateTable').DataTable({
	<?php 
	 //if($action =='view' || $action=='update' || ($more_option == 'copy' && $temp_flag == false){
	if($action != 'add' && $temp_flag == false){
	 echo '"data":'.$GetDataJsonRes.',';
		  }
	 ?>
			"stateSave": true,
			"deferRender": true,
			"columnDefs": [	
					{
                    "targets": [4],
                    "visible": true
                     },									
					{
					"targets": [9],
					"data": null,
					"defaultContent":"<a id='tnedit' class='glyphicon glyphicon-edit' title='Edit' data-toggle='modal' data-target='.bs-example-modal-md'></a>&nbsp;&nbsp;&nbsp;&nbsp;<a id='delete' class='glyphicon glyphicon-trash' title='Delete'></a>",				
					},
					{ 
						"responsivePriority": -1,
						  "targets": -1
					},
			],	
});

$("#btn_canerate_add").on('click',function(){
	tn_flag = 'ins';
	$("#sort_order").val("");
	$("#ad_type").val("");
	$("#cat").val("");
	$("#rule").val("");
	$("#value").val("");
});

if(action_flag=='add')
{
 	tn_count = 1;
}else{
	tn_count = '<?php echo $cnt; ?>';
	
}
 
$("#btn_canerate").on('click', function (e) { 
	var res = validKeyInd();
	if(errCOUNT == 0)
	{
		e.preventDefault();
		var sort_order = $("#sort_order").val();
		var value1 = $("#ad_type").val();
		var res = value1.split("-");
		var code = res[0];/*split code*/
		var name = res[1];/*get description*/
		var type = res[2];/*get description*/

		var test = $( "#cat option:selected").text();

		var value2 = $("#cat").val();
		
		var cat_name = '';
		if(value2 == 'B'){
			cat_name = 'Bal Based';
		}else{
			cat_name = 'Compulsory';
		}

		var value3 = $("#rule").val();
		var rule = '';
		if(value3 == 'P'){
			rule = 'Percentage';
		}else if(value3 == 'A'){
			rule = 'Amount';
		}else{
			rule = 'Rate Per MT';
		}
		var value = $("#value").val();

		if(tn_flag=='ins'){
			tnSet =  [[tn_count,sort_order,code,name,type,cat_name,rule,value,tn_flag]];
			caneRateTable.rows.add(tnSet).draw();
			tn_count++;	
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
				//alert("id in upd"+tnId);
				var tnTemp = new Array();
				/*set updated records in datatable inner html*/
   				$('table#caneRateTable tr:eq('+tnId+') td:eq(1)').html(sort_order);
   				$('table#caneRateTable tr:eq('+tnId+') td:eq(2)').html(code);
   				$('table#caneRateTable tr:eq('+tnId+') td:eq(3)').html(name);
   				$('table#caneRateTable tr:eq('+tnId+') td:eq(4)').html(type);
   				$('table#caneRateTable tr:eq('+tnId+') td:eq(5)').html(cat_name);
   				$('table#caneRateTable tr:eq('+tnId+') td:eq(6)').html(rule);
   				$('table#caneRateTable tr:eq('+tnId+') td:eq(7)').html(value);
   				$('table#caneRateTable tr:eq('+tnId+') td:eq(8)').html(flag_val);
   				$('table#caneRateTable tr:eq('+tnId+') td:eq(10)').html();
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
				tnSet =  [[ac_cnt,v0,v1,v2,v3,v4,v5,v6,v7,v8,flag_val]];
				//alert("updated row:"+tnSet);
				$.each(tnSet, function(key,value) {
					tnTemp[key] = value;
				tnSetNew.push(tnTemp);
				});
				//alert("updated data:"+tnSetNew);
				$('#term_modal').modal('hide');
			}
	}//validation if	
		
	});	

/*Tonnage Table Edit Option*/
$('#caneRateTable').on('click','#tnedit',function (e) {
			e.preventDefault();
			var tnData = caneRateTable.row($(this).parents('tr')).data();
			$("#sort_order").val(tnData[1]);
			var dd = tnData[2]+"-"+tnData[3]+"-"+tnData[4];
			$("#ad_type").val(dd);
			$("#cat option:selected").text(tnData[5]);
			$("#rule option:selected").text(tnData[6]);
			$("#value").val(tnData[7]);
			tnId = (caneRateTable.row( $(this).parents('tr')).index()+1);
			tn_flag = 'upd';
			//alert("Id:"+tnId);
		});	

/*Tonnage Delete Option*/
$('#caneRateTable').on('click','#delete',function (e) {
			e.preventDefault();
			var data = caneRateTable.row( $(this).parents('tr') ).data();
			caneRateTable.row( $(this).parents('tr')).remove().draw();
		});	

//I changed 
var season_code = '<?php echo $season; ?>';
var fortnight = '<?php echo $HeaderdataRes[0]['CD_FNNO']; ?>';
var btype = '<?php echo $HeaderdataRes[0]['CD_BTYPE']; ?>';

	$.ajax({
            url: "cb_deductiondtl_server.php",
            method:"POST",
            data:$('#caneratemast').serialize()+'&'+$.param({'action':'fortnight'})+'&'+$.param({'season':season_code,'billtype_code':btype}),
	        datatype: "json",
              success: function(data){
    		  data = $.parseJSON(data);
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
		caneRateTable.rows().every(function (rowIdx,tableLoop,rowLoop) {
		    		new_data = this.data();	
					tn_detail.push(new_data);
					});

		/*use to get date in edit mode*/   
     		if((tnSetNew.length)>0){
     			for(i=0;i<tnSetNew.length;i++){
					tnDataSet[i]=tnSetNew[i][0];
				}
			}
	var res = validKeyInd();
	if(errCOUNT == 0)
	{
		//$("#wait").show();
		$.ajax({
            url: "cb_deductiondtl_server.php",
            data:$('#caneratemast').serialize()+'&'+$.param({'action':'fullform'})+
            '&'+$.param({'flag':action_flag,tn_detail:tn_detail,tnDataSet:tnDataSet}),
			datatype: "json",
             success: function(data)
             {
             	//$("#wait").hide();
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

// I changed
/********************************Change On Season *****************************************/
/*$("#season").change(function(){
	var season_code=$("#season").val();
	$("#wait").show();	
	$.ajax({
            url: "cb_deductiondtl_server.php",
            method:"POST",
            data:$('#caneratemast').serialize()+'&'+$.param({'action':'fortnight'})+'&'+$.param({'season':season_code}),
	        datatype: "json",
              success: function(data){
              	$("#wait").hide();
			  data = $.parseJSON(data);
			  //$('#fortnight').append($('<option>').text('Select').attr('value',''));
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
               $('#fortnight').append($('<option>').text(value['DT']).attr('value',value['SND_FNNO'])); 
              });
			 }//else	  
            }  
         });
    });*/

/******************************Change On Season ************************************************/
/***********************************************Change On Cane Bill Type ******************************************************************/
if(action_flag == 'add' || action_flag == 'update'){
$('#billtype').change(function(){
        var billtype_code = $('#billtype').val();
		var season = $("#season").val();
       
        jQuery.ajax({ 
            type: "POST",
            datatype: "json",
            async: false,
            url: "cb_deductiondtl.php",
            data:({getFornight:'Y',season:season,billtype_code:billtype_code}),
            success:function(data)
            {
              //setfornight LOV
               $("#fortnight").empty();
               data = $.parseJSON(data);
               
               $('#fortnight').append($('<option>').text('Select').attr('value',''));
               $.each(data, function(i, value) {
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

var temp ="<?php echo $temp_flag; ?>";
//alert('temp_flag : '+temp);
if(temp == 1)
{
	var cpyAmndProcRes ="<?php echo $cpyAmndProcRes; ?>";
	swal({
		  title: cpyAmndProcRes,
		  type: 'error',
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Ok'
		}).then(function () {
			//location.reload();
			location.href = back_link;
		})
	//swal(cpyAmndProcRes, "", "error");	
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
