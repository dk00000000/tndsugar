<?php

require('curdClass.php');
require_once("header.php");
require_once('readfile.php');
include("sidebar.php");

$lgs = new Logs();
$rfObj = new ReadFile();
$qryObj = new Query();
$dsbObj = new Dashboard();
$curd = new CURD();

$lgs->lg->trace("--START BILLPRDMAST FORM--");

$langPath = $_SESSION['LANGPATH'];
$lang = $_SESSION['LANG'];
$menu_code = $_SESSION['MENU_CODE'];
/*langPath for labels*/
$langPath = $langPath."general/".strtolower($lang)."/".$menu_code."_lbl.txt";
$lgs->lg->trace("LANG PATH :".$langPath);

/*langPath for messages*/
$langPathMsg = strtolower($lang)."/".$menu_code."_msg.txt";
$lgs->lg->trace("LANG PATH :".$langPathMsg);
$langPathMsg1 = strtolower($lang)."/main_msg.txt";
$lgs->lg->trace("LANG PATH :".$langPathMsg1);
//echo $langPathMsg1;
/*back link*/
$backlnk = 'view_browse.php?menu_code='.$menu_code;

$action = $_GET['view'];/*get the action from comman browse*/

if($action == 'update' || $action == 'view')
{
	$array1 = $_GET['column_names'];
	$colnames = explode(',', $array1);
	$lgs->lg->trace("--Column Names--:".json_encode($colnames));
	$oldfilter = array();
	for($i = 0; $i < sizeof($colnames); $i++)
	{
		$oldfilter[$i] = ":".$colnames[$i];
	}
	$lgs->lg->trace("--Old Filter--:".json_encode($oldfilter));
	//$count = sizeof($oldfilter);

	$array2 = $_GET['rowdata'];
	$lgs->lg->trace("--Row Data Before --:".$array2);
	//$array2 = utf8_decode(rawurldecode($_GET['rowdata']));
	//$newfilter = explode(',', $array2);
	$newfilter = json_decode($array2);
	$season = $newfilter[2];
	$ctype = $newfilter[3];
	
	$lgs->lg->trace("--Row Data--:".json_encode($newfilter));
	
	$lgs->lg->trace("--I'm in update--".$action."Comp Code".$_SESSION['COMP_CODE']);
	//$mainRes = $scmastObj->getScmastData('SELECT_QRY',$oldfilter, $newfilter);
	array_push($oldfilter,':PCOMP_CODE',':PHP_CTYPE',':PSEASON');
	array_push($newfilter,$_SESSION['COMP_CODE'],$ctype,$season);
	$mainRes = $curd->GetSelData($oldfilter, $newfilter, 'HTPERDMAST.ini', 'GETQRY');
	$lgs->lg->trace("--BILLPRDMAST RESULT OF SELECTED RECORD--".json_encode($mainRes));
	
	$detailRes = $curd->GetSelData($oldfilter, $newfilter, 'HTPERDMAST.ini', 'DTL_GETQRY');
	$lgs->lg->trace("--BILLPRDDETAIL RESULT OF SELECTED RECORD--".json_encode($detailRes));
	
	for($i=0;$i<sizeof($detailRes);$i++)
	{
		$temp[]=array_values($detailRes[$i]);
	}
	$temp=json_encode($temp,JSON_PRETTY_PRINT.';');
	
	$cntRes = $curd->GetSelData($oldfilter, $newfilter, 'HTPERDMAST.ini', 'GET_CNT');
	$cnt = $cntRes[0]['FNNO'];
	//$cnt = $cnt+1;

	


}

$oldLovFilter = array(':PCOMP_CODE', ':PTABLENAME', ':PCOLUMNNAME');
$newLovFilter = array($_SESSION['COMP_CODE'], 'HTPERDMAST', 'HP_SCODE');
//To get LOV from dashbord's getLov function
//$SsnLOVres = $dsbObj->getLovQry(16,$oldLovFilter,$newLovFilter);
$SsnLOVres = $dsbObj->getLovQry(28,$oldLovFilter,$newLovFilter);
$lgs->lg->trace("Season LOV in HT period:".$SsnLOVres);
$ContLOVres = $dsbObj->getLovQry(21,$oldLovFilter,$newLovFilter);
$billType = $dsbObj->getLovQry(73,$oldLovFilter,$newLovFilter);

//langPath for color details
$langPath1 = $_SESSION['LANGPATH'];
$langPath1 = trim($langPath1."general/colorscheme.txt");
$headingColor = trim(preg_replace("/[\\n\\r]+/", " ", $rfObj->readData('BRWSHDRCLR',$langPath1)));
$textColor = trim(preg_replace("/[\\n\\r]+/", " ", $rfObj->readData('BRWSTXTCLR',$langPath1)));
//End langPath for color details



?>

<script>

$(document).ready(function() {
var action_flag = "<?php echo $action; ?>";

$("#frdt").datetimepicker({format : 'DD/MM/YYYY'});
$("#todt").datetimepicker({format : 'DD/MM/YYYY'});
$("#dudt").datetimepicker({format : 'DD/MM/YYYY'});


<?php /*?>var count = <?php echo $count; ?>;<?php */?>
var dataset = [];
var upddataset = [];
var deldataset = [];
//var counter = 1;
var flag;
var flag1;
var rowId='';

var msg_FileName = '<?=$langPathMsg1; ?>';
var gs_valFileName = '<?=$langPathMsg; ?>';// USE GET VALIDATION MESSAGES BY PASSING HARDCODED NUMBERS
var backlnk = '<?=$backlnk; ?>';

$("#hp_scode").prop("readonly", true); 
$("#hp_scode").hide();
if(action_flag == 'update')
{
	console.log("in update mode");
	$("#hpscode").prop("disabled", true);
	$("#btn_submit").text('Update');
	$("#btn_reset").hide();
	$(".panel-heading").text('Edit');
	$(".modal-title").text('Edit');
	$("#hp_scode").show();
	$("#hpscode").hide();	
}

if(action_flag == 'view')
{
	console.log("in view mode");
	$("#hpscode").prop("disabled", true);
	$("#contype").prop("disabled", true);
	$("#btn_submit").hide();
	$("#btn_reset").hide();
	$("#btn_cancel").text('Back');
	$(".panel-heading").text('View');
	$('#btn_add').hide();
	$("#hp_scode").show();
	$("#hpscode").hide();
}

$("#btn_submit").click(function(event){
		event.preventDefault();
		var data = $("#htprdd_tbl").dataTable().fnGetData();
		for(var i=0;i<data.length;i++){
			if(data[i][7] == 'ins')
			{
				dataset.push(data[i]);
			}
		}
		dataset = dataset.concat(upddataset);
		//alert(insdataset.length);
		/*for(var j=0;j<dataset.length;j++)
		{
			//alert(insdataset[j]);
			alert(dataset[j][0]+dataset[j][1]+dataset[j][2]+dataset[j][3]+dataset[j][4]+dataset[j][5]);
		}*/
		/*for(var j=0;j<deldataset.length;j++)
		{
			//alert(insdataset[j]);
			alert(deldataset[j][0]);
		}*/
	
	var res = validKeyInd();
	if(errCOUNT == 0)
	{
		$.ajax({
              url: "billprdmastserver.php",
			  data:$('#form_htprdmast').serialize()+'&'+$.param(
			  	   											  {'action':action_flag,
															   'dataset':dataset,
															   'deldataset':deldataset
															  }),
              datatype: "json",
              success: function(response){
              var ls_result = response;
				  if(ls_result == 1) 
				  { 
					console.log("Record Inserted Successfully !!"+ls_result);
					if(action_flag == 'update')
					{
						var msg = getMsg(2,msg_FileName);
						//var msg = 'Record Updated Successfully !';
					}
					else
					{
						var msg = getMsg(1,msg_FileName);
						//var msg = 'Record Inserted Successfully !';
					}
					swal(msg, "", "success");
					location.href = backlnk;
				  }
				  else
				  {
					var ls_result = ls_result.trim();
					swal(ls_result, "", "error");
				  }
              }  
            });//ajax
		}//if
                
});//btn_submit

/*Code on Cancle Button*/
  $('#btn_cancel').on('click',function(){
      $(function() {
        location.href = backlnk;
          });
    });//cancel
	
var table  = $('#htprdd_tbl').DataTable({
	
	<?php 
		 if($action == 'update' || $action == 'view')
		 {
			echo '"data":'.$temp.',';
		 }
	?>
	"dom": 'rf',
	"pageLength": -1,
	"columnDefs": [									
					{
						"targets": [8],
						"data": null,
						"defaultContent":
						"<a href='' id='edit' class='glyphicon glyphicon-edit' title='Edit' data-toggle='modal' data-target='.bs-example-modal-md'></a> &nbsp;&nbsp;&nbsp;&nbsp; <a href='' id='delete' class='glyphicon glyphicon-trash' title='Delete'></a>",				
							
					},
					<?php if($action == 'view') 
						  {
					?>
					{
						"targets": [8],
						"visible": false
					},
					<?php  }	?>
					
					{ 
						"responsivePriority": -1,
						"targets": -1
					},
					/*{
						"targets": [5],
						"visible": false
					},*/
					/*{
						 "orderable": false, 
						 "targets": 0
					}*/
				  ],

});//End of datatable


$('#htprdd_tbl').on( 'click', '#delete', function (e) {
	e.preventDefault();
	var data = table.row( $(this).parents('tr') ).data();
	if(flag != 'ins')
	{
		deldataset.push(data);
	}
	table.row( $(this).parents('tr')).remove().draw();
});	//Delete
		
$('#htprdd_tbl').on( 'click','#edit', function (e) {
	
	e.preventDefault();
	rowId = (table.row( $(this).parents('tr')).index())+1;
	
	var data = table.row($(this).parents('tr')).data();
	//rowId = data[0];
	console.log("Data: "+data);
	//alert("Row Id: "+rowId)
	flag = 'upd';
	if(data[7] == null)
	{
		flag = 'upd';
		$("#fnno").prop("readonly", true);
	}
	/*if(data[7] == null)
	{
		flag = 'upd';
		$("#fnno").prop("readonly", true);
	}
	else if(data[7] == 'ins')
	{
		flag1 = 'upd';
		$("#fnno").prop("readonly", true);
	}*/
	
	var val0 = $('#htprdd_tbl tr:eq('+rowId+') td:eq(1)').text();
	var bill_type = $('#htprdd_tbl tr:eq('+rowId+') td:eq(2)').text();
	var val1 = $('#htprdd_tbl tr:eq('+rowId+') td:eq(3)').text();
	var val2 = $('#htprdd_tbl tr:eq('+rowId+') td:eq(4)').text();
	var val3 = $('#htprdd_tbl tr:eq('+rowId+') td:eq(5)').text();
	var val4 = $('#htprdd_tbl tr:eq('+rowId+') td:eq(6)').text();
	
	var bill_code = data[2].split('-'); 
	//alert(bill_code[0]);
	$("#fnno").val(val0);
	$("#bill_type").val(bill_code[0]);
	$("#frdt").val(val1);
	$("#todt").val(val2);	
	$("#dudt").val(val3);
	$("input:radio[name=lck]").filter("[value="+val4+"]").prop('checked', true);
	//alert("Row Id: "+rowId)
});	//Edit
		
$('#btn_add').on('click', function () { 
	//$('#htprdmstdtl').show();
	flag = 'ins';
	flag1 = 'ins';
	$("#fnno").prop("readonly", false);
	$("#fnno").val('');
	$("#frdt").val('');			
	$("#todt").val('');
	$("#dudt").val('');
	//$('input[name=lck]').attr('checked', false);
	$("input:radio[name=lck]").filter("[value=N]").prop('checked', true);
});//Add

var counter = 0;
if(action_flag=='add'){
 	counter = 1;
}else{
	counter = '<?php echo $cnt; ?>'
}

$("#btn_save").click(function(event){
	var res = validKeyInd();
	if(errCOUNT == 0)/*Apply validation on 05-02-2018, Issue came from Mangesh Sir.*/
	{
		
		$("#htprdmstdtl .close").click()
		var btype = $("#bill_type").val()+'-'+$("#bill_type option:selected").text();/*Newly added field*/
		var sncode = $("#hpscode").val();
		var value0 = $("#fnno").val();
		var value1 = $("#frdt").val();
		var value2 = $("#todt").val();
		var value3 = $("#dudt").val();
		var value4 = $("input[name=lck]:checked()").val();
		
		if(flag == 'upd')
		{		
			//alert("Flag: "+flag);
			$('#htprdd_tbl tr:eq('+rowId+') td:eq(1)').html(value0);
			$('#htprdd_tbl tr:eq('+rowId+') td:eq(2)').html(btype);
			$('#htprdd_tbl tr:eq('+rowId+') td:eq(3)').html(value1);
			$('#htprdd_tbl tr:eq('+rowId+') td:eq(4)').html(value2);
			$('#htprdd_tbl tr:eq('+rowId+') td:eq(5)').html(value3);
			$('#htprdd_tbl tr:eq('+rowId+') td:eq(6)').html(value4);
			$('#htprdd_tbl tr:eq('+rowId+') td:eq(7)').html(flag);
			
			var val0 = $('#htprdd_tbl tr:eq('+rowId+') td:eq(0)').text();
			var val1 = $('#htprdd_tbl tr:eq('+rowId+') td:eq(1)').text();
			var val2 = $('#htprdd_tbl tr:eq('+rowId+') td:eq(2)').text();
			var val3 = $('#htprdd_tbl tr:eq('+rowId+') td:eq(3)').text();
			var val4 = $('#htprdd_tbl tr:eq('+rowId+') td:eq(4)').text();
			var val5 = $('#htprdd_tbl tr:eq('+rowId+') td:eq(5)').text();
			var val6 = $('#htprdd_tbl tr:eq('+rowId+') td:eq(6)').text();

			var upddataset1 = [val0, val1, val2, val3, val4, val5,val6,flag];
			console.log("Updated data: "+upddataset1);
			upddataset.push(upddataset1);
		}
		else
		{
			
			dataSet1 =  [[counter,value0,btype,value1,value2,value3,value4,flag]];
			table.rows.add(dataSet1).draw();
			counter++;
		}
	}
});//btn_save

}); //End of ready 

</script>



<!-- page content -->
<div class="right_col" role="main">
<div class="">
	<?php //print_r($mainRes); ?>
	<div class="clearfix"></div>
	<form id="form_htprddmast" name="form_htprddmast" class="form-horizontal form-label-left" onsubmit="return false;">  
	<ul class="contactus-list">
	<div class="modal bs-example-modal-md" tabindex="-1" role="dialog" aria-hidden="true" id="htprdmstdtl">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close cancel" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?=$rfObj->readData('ADD',$langPath); ?></h4>
      </div>
      <div class="modal-body" data-backdrop="false">
      		
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-12 col-xs-12"><?=$rfObj->readData('BT',$langPath); ?></label>
				<li>
					<div class="col-md-9 col-sm-12 col-xs-12">
						<select class="form-control" name="bill_type" id="bill_type">
						<? for($i=0;$i<sizeof($billType);$i++)
						{
						?>
						<option value="<?=$billType[$i]['HT_CODE']?>">
							<?=$billType[$i]['HT_NAME']?></option>
						<? 
						}
						?>	
						</select>
					</div>
				</li>
			</div><!--//form-group-->				

			<div class="form-group">
				<label class="control-label col-md-3 col-sm-12 col-xs-12"><?=$rfObj->readData('FNNO',$langPath); ?></label>
				<li>
					<div class="col-md-9 col-sm-12 col-xs-12">
						<input type="text" id="fnno" name="fnno" class="form-control col-md-7 col-xs-12" valid="required" errmsg="Please Enter Fortnight Number." value="">
					</div>
				</li>
			</div><!--//form-group-->
			

			<div class="form-group">
				<label class="control-label col-md-3 col-sm-12 col-xs-12"><?=$rfObj->readData('FRDT',$langPath); ?></label>
				<li>
					<div class="col-md-9 col-sm-12 col-xs-12">
						<input type="text" id="frdt" name="frdt" valid="required" errmsg="Please Select From Date." class="form-control col-md-7 col-xs-12" value="">
					</div>
				</li>
			</div><!--//form-group-->
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-12 col-xs-12"><?=$rfObj->readData('TODT',$langPath); ?></label>
				<li>
					<div class="col-md-9 col-sm-12 col-xs-12">
						<input type="text" id="todt" name="todt" valid="required" errmsg="Please Select To Date." class="form-control col-md-7 col-xs-12" value="">
					</div>
				</li>
			</div><!--//form-group-->
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-12 col-xs-12"><?=$rfObj->readData('DUDT',$langPath); ?></label>
				<div class="col-md-9 col-sm-12 col-xs-12">
					<input type="text" id="dudt" name="dudt" class="form-control col-md-7 col-xs-12" value="">
				</div>
			</div><!--//form-group-->	
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-12 col-xs-12"><?=$rfObj->readData('LCK',$langPath); ?></label>
				
				<div class="btn-group" data-toggle="buttons">
				<label><input type="radio" id="lcky" name="lck" value="Y"> &nbsp; Yes &nbsp;</label>
				<label><input type="radio" id="lckn" name="lck" value="N" checked="checked"> No</label>
			  	</div>				
			</div><!--//form-group-->	
		
		
		
      </div><!--//Panel-body-->
	  
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn_save">Add</button>
		<button type="button" class="btn btn-default cancel" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</ul>
</form>
	
	
	
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
				<form id="form_htprdmast" name="form_htprdmast" class="form-horizontal form-label-left" onsubmit="return false;">
				<ul class="contactus-list">
				<span class="section"><?=$rfObj->readData('HTPRDMST',$langPath); ?></span>
				<div class="panel panel-primary">
				<div class="panel-heading"><?=$rfObj->readData('ADD',$langPath); ?></div>
					<div class="panel-body">
						
						<div class="form-group row">
							<label class="control-label col-md-3 col-sm-6 col-xs-6"><?=$rfObj->readData('CD',$langPath); ?></label>
							<li><div class="col-md-6 col-sm-6 col-xs-6">
									<? if($action == 'add') { ?>
										<select id ="hpscode" name="hpscode" class="form-control col-md-7 col-xs-12" valid="required" errmsg="Please Select Season Year." disabled="disabled">
										<? for($i=0;$i<sizeof($SsnLOVres);$i++)
										{
										?><option <? if($_SESSION['SEASON']==$SsnLOVres[$i]['SN_CODE']) {?> selected="selected" <? } ?> value="<?=$SsnLOVres[$i]['SN_CODE']?>"><?=$SsnLOVres[$i]['SN_CODE']?></option>
										<? 
											}
										?>	
									    </select>	
								 	<? }//if 
								 		else { ?>
								 	    <input type="text" id="hp_scode" name="hp_scode" class="form-control col-md-7 col-xs-12" value="<?=$mainRes[0]['HP_SCODE']; ?>" />
								 	<? }//else ?>
									<span id="hpscode_error" style="color: red;"></span>
							</div></li>
						</div><!--//form-group-->						
						
						<div class="form-group row">
							<label class="control-label col-md-3 col-sm-6 col-xs-6"><?=$rfObj->readData('CONTP',$langPath); ?></label>
							<li><div class="col-md-6 col-sm-6 col-xs-6">
									<select id ="contype" name="contype" class="form-control col-md-7 col-xs-12" valid="required" errmsg="Please Select Contractor Type.">
									
									<? for($i=0;$i<sizeof($ContLOVres);$i++)
									{
									?>
									<option value="<?=$ContLOVres[$i]['CT_CODE']?>" <? if($mainRes[0]['HP_CTYPE'] == $ContLOVres[$i]['CT_CODE']) {?> selected="selected" <? }?>><?=$ContLOVres[$i]['CT_NAME']?></option>
									<? 
									}
									?>	
									</select>	
									<span id="contype_error" style="color: red;"></span>
							</div></li>
						</div><!--//form-group-->
						
					</div><!--//panel-body-->
				</div><!--//panel-->
						
				<div class="panel panel-primary">
	   			<div class="panel-heading"><?=$rfObj->readData('POSVAL',$langPath); ?></div>
		  			<div class="panel-body">
						<!--Data table-->
						<div class="row">
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
								<div class="x_title">
								<button type="button" class="btn btn-primary glyphicon glyphicon-plus" id="btn_add" data-toggle="modal" data-target=".bs-example-modal-md"></button>
								<div class="clearfix" ></div>
								</div><!--//x_title-->
								<div class="x_content">
<table id="htprdd_tbl" class="table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
	<thead>
		<tr>
		<th style="background-color:<?=$headingColor; ?> ; color:<?=$textColor; ?>" width="10"><?=$rfObj->readData('SR',$langPath); ?>
		</th>
		<th style="background-color:<?=$headingColor; ?> ; color:<?=$textColor; ?>" width="10"><?=$rfObj->readData('FNNO',$langPath); ?>
		</th>
		<th style="background-color:<?=$headingColor; ?> ; color:<?=$textColor; ?>" width="15"><?=$rfObj->readData('BT',$langPath); ?>
		</th>
		<th style="background-color:<?=$headingColor; ?> ; color:<?=$textColor; ?>" width="15"><?=$rfObj->readData('FRDT',$langPath); ?>
		</th>
		<th style="background-color:<?=$headingColor; ?> ; color:<?=$textColor; ?>" width="15"><?=$rfObj->readData('TODT',$langPath); ?>
		</th>
		<th style="background-color:<?=$headingColor; ?> ; color:<?=$textColor; ?>" width="15"><?=$rfObj->readData('DUDT',$langPath); ?>
		</th>
		<th style="background-color:<?=$headingColor; ?> ; color:<?=$textColor; ?>" width="5"><?=$rfObj->readData('LCK',$langPath); ?>
		</th>
		<th style="background-color:<?=$headingColor; ?> ; color:<?=$textColor; ?>" width="5">Flag
		</th>
		<th style="background-color:<?=$headingColor; ?> ; color:<?=$textColor; ?>" width="10"><?=$rfObj->readData('ACT',$langPath); ?>
		</th>
			
		</tr>
	</thead>		
</table>	 
								</div><!--x_content-->
							</div><!--x_panel-->
						  </div>
						 </div>
					</div><!--//panel-body-->
				</div><!--//panel-->				
						
						<div class="ln_solid"></div>
						<div class="form-group">
						<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
						<button class="btn btn-success" type="submit" id="btn_submit">Submit</button>
						<button class="btn btn-info" type="reset" id="btn_reset">Reset</button>
						<button class="btn btn-danger" type="button" id="btn_cancel">Cancel</button>
						</div>
						</div>
				</ul>		
				</form>
				
				</div>
			</div>
		</div>
	</div>
</div>
</div>
	
	
<? include("footer.php");?>		