<?php 
	require_once('dashboard.php');
	require_once('readfile.php');

	$qryObj = new Query();
	$dsbObj = new Dashboard();
	$rfObj = new ReadFile();
	$lgs = new Logs();
	$qryPath = "util/readquery/general/assign_section_browse.ini";
	$langPath = "util/language/";
	$menu_code=$_SESSION['MENU_CODE'];
	$lang=$_SESSION['LANG'];
	$langPath = $langPath."general/".strtolower($lang).'/'.$menu_code.".txt";
	$back_link = $_SERVER['HTTP_REFERER'];
	
	$rfidQry = $qryObj->fetchQuery($qryPath,'Q001','GET_RFID');
	$rfidRes = $dsbObj->getData($rfidQry);
	
	$oldfilter = array(':PCOMP_CODE',':PSEASON');
	$newfilter = array($_SESSION['COMP_CODE'],$_SESSION['SEASON']);
	
	$getdataQry = $qryObj->fetchQuery($qryPath,'Q001','GETDATAQRY',$oldfilter,$newfilter);
	$getdataRes = $dsbObj->getData($getdataQry);
	
	$section = $qryObj->fetchQuery($qryPath,'Q001','SEC_LOV',$oldfilter,$newfilter);
	//echo "Qury".$getLov;
	$sectionRes = $dsbObj->getData($section);
    //print_r($sectionRes);
   
	
	

	/*SET NEW RFID*/
	if(isset($_POST['setSection']))
	{
		$oldfilter = array(':PCOMP_CODE',':PTXN_SEQ',':PSECTION');
		$newfilter = array($_SESSION['COMP_CODE'],$_POST['seq'],$_POST['section']); 
		$updateQry = $qryObj->fetchQuery($qryPath,'Q001','UPDATE',$oldfilter,$newfilter);
		$lgs->lg->trace("Update Query: ".json_encode($updateQry));
		$updateRes = $dsbObj->updateData($updateQry);
		$lgs->lg->trace("Update Query Res: ".json_encode($updateRes));
		echo json_encode($updateRes);
		exit();
	}

	/*RESET RFID'S*/

	
require_once("header.php");
include("sidebar.php");
?>
 
<div class="right_col" role="main" style="min-height: 788px;">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="panel panel-primary">
					<div class="panel-heading">Contract Entry - Assign Section </div>
						<div class="panel-body" style="overflow: scroll;">
							<table id="rfidTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
								     <th width="5%">Section</th>
									<th width="5%">Action</th>
									<th width="12%">Number</th>
									<th width="8%">MCon. Code</th>
									<th width="14%">MCon. Name</th>
									<th width="5%">SCon. Code</th>
									<th width="14%">SCon. Name</th>
									<th width="8%">Vehicle Type</th>
									<th width="5%">Vehicle No</th>
									<th width="8%">Harv Type</th>
									<th width="8%">Trans Type</th>
								</tr>

							</thead>
								<tbody>
									<?php 
                                    foreach ($getdataRes as $key => $data) {
                                    
									
									?>
									<tr>
				<td><select id ="section" name="section" class="form-control col-md-7 col-xs-12 section">
                  <option value="">Select</option>
                <? for($i=0;$i<sizeof($sectionRes);$i++){?>
                  <option value="<?=$sectionRes[$i]['SC_CODE']?>"<? if($sectionRes[$i]['SC_CODE'] == $getdataRes[$key]['TXN_SECTION'])  {?> selected="selected" <? } ?>><?=$sectionRes[$i]['SC_NAME']?></option>
                <? } ?> 
                </select></td>	
											<td><button type="button" name="update" class="btn btn-info btn-sm btn_reset" data-value="<?=$data['TXN_STKT']?>" value="<?=$data['TXN_SEQ']?>">Update</button></td>
											
											<td><?=$data['TXN_SRNO'] ?></td>
											<td><?=$data['MCON_CODE'] ?></td>
											<td><?=$data['MCON_NAME'] ?></td>
											<td><?=$data['SCON_CODE'] ?></td>
											<td><?=$data['SCON_NAME'] ?></td>
											<td><?=$data['VH_DESC'] ?></td>
											<td><?=$data['VHNO'] ?></td>
											<td><?=$data['HT_DESC'] ?></td>
											<td><?=$data['TT_DESC'] ?></td>
											
									</tr>
										<?php } ?>
								</tbody>		
							</table>
							<div id="wait" class="ui-autocomplete" style="display:none;width:69px;height:89px;border:0px solid black;position:absolute;top:70%;left:50%;padding:2px;">
					   				<img src='images/loading.gif' width="64" height="64" /><br>Loading..</div>
						</div>
					</div>
				</div>
		</div>
	<?php include("footer.php");?>
	</div>
</div>


<script type="text/javascript">
$(document).ready(function(){
var back_link = '<?php echo $back_link ?>';
var rfidTable  = $('#rfidTable').DataTable();

var section;
$('#rfidTable').on('change','.section', function(e1) {
e1.preventDefault();
  section  = $(this).val();

});

/*RESET RFID*/
$('#rfidTable').on('click', '.btn_reset', function (e1) {
        e1.preventDefault();
        var seq = $(this).val();
		
	   	 jQuery.ajax({ 
			type: "POST",
			datatype: "json",
			async: false,
			url: "assign_section_browse.php",
			data:({setSection:'Y',seq:seq,section:section}),
			success:function(res)
			{
			if(res==1)
			{
			   var msg="Section Updated Successfully";
			   swal({
					 title: msg,
					 type: 'success',
				   })
			  }
		   }
		});//End of Ajax
	   
   });        

});//ready

</script>

