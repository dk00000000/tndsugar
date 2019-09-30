<?php 

	require_once('dashboard.php');
	require_once('readfile.php');
	
	$lgs = new Logs();
	$qryObj = new Query();
	$rfObj = new ReadFile();
	$dsbObj = new Dashboard(); 
	
	$qryPath = $_SESSION['QRYPATH']."/general/fileupload.ini";
	$langPath = "util/language/";
	$lang = $_SESSION['LANG'];
	$menu_code = $_SESSION['MENU_CODE'];
	$langPath = $langPath."general/".strtolower($lang).'/'.$menu_code.".txt";
	
	if(isset($_GET['PK']) && $_GET['PK']!=""){
		$pk = $_GET['PK'];
	}
	else{
		$pk = '';
	}
	if(isset($_GET['COMM_MENU'])){
		$comm_menu=$_GET['COMM_MENU'];
	}
	$type = $_GET['TYPE'];
	
	//GET ALL RECORDS FROM QUERY
	if($pk=="" && $type=='all'){
		$query = 'DISPQRY1';	
		$title = 'View Messages';
		$subtitle = 'All unread Messages';
	}
	else{
		$query = 'DISPQRY';
	}
	
	if($type == 'attach'){
		$title = 'Attach';
		$subtitle = 'Attachments';
		$flag = 'A';
	}
	if($type == 'msg'){
		$title = 'Message';
		$subtitle = 'Messages';
		$flag = 'M';
	}
	
	$oldfilter = array(":PCOMM_PKVAL", ":PCOMP_CODE", ":PCOMM_REPLY", ":PUSER_CODE");
	$newfilter = array($pk, $_SESSION['COMP_CODE'], $flag, $_SESSION['USER']);
	
	$dispQry = $qryObj->fetchQuery($qryPath,'Q001',$query,$oldfilter,$newfilter);
	$getdataRes = $dsbObj->getData($dispQry);
	
	for($i=0;$i<sizeof($getdataRes);$i++)
	{
		$GetDataJsonRes[]=array_values($getdataRes[$i]);
	}
	
	$GetDataJsonRes=json_encode($GetDataJsonRes,JSON_PRETTY_PRINT.';');
	
	require_once("header.php");

 	require_once("sidebar.php");?>   
 

<script type="text/javascript">
	var type = "<?php echo $type; ?>";
	var pk = "<?php echo $pk; ?>";
	var comm_menu = "<?php echo $comm_menu; ?>";
	
	$(document).ready(function() {
		
		var table  = $('#example').DataTable({
		 <? echo '"data":'.$GetDataJsonRes.','; ?>
		 
			"columnDefs": [									
				
					{
							"targets": [8],
							"render": function (data, type, row, meta) {
				
								if(row[4] != null){
									return "<a href='' id='view' class='glyphicon glyphicon-eye-open' title='View'></a> &nbsp; <a href='' id='download' class='glyphicon glyphicon-save' title='Download'></a> &nbsp; <a href='' id='delete' class='glyphicon glyphicon-trash' title='Delete'></a>";
								}
								else{
									return "<a href='' id='delete' class='glyphicon glyphicon-trash' title='Delete'></a>";
								}
							},//render													 
						},
					{ 
						"responsivePriority": -1,
						"targets": -1
					},
					
					{
						"targets": [0],
						"render": function (data, type1, row, meta) {				
							var url = 'add_attach.php?action=view&srno='+data+'&COMM_MENU='+comm_menu+'&TYPE='+type;
							return "<a href="+url+">"+data+"</a>";
							
						},//render													 
					},
					
			],	
			
		});//Datatable
		
		$('#btn_add').on('click',function(){
			window.location= "add_attach.php?action=add&PK="+pk+"&COMM_MENU="+comm_menu+"&TYPE="+type;	
			
		});//Add
		
		$('#example').on( 'click', '#view', function (e) {
			e.preventDefault();
			var data = table.row( $(this).parents('tr') ).data();
			var file = data[7];
			var file=file.substring(17, file.length);
			window.open(file, '_blank');
		});
		
		$('#example').on( 'click', '#download', function (e) {
			e.preventDefault();
			var data = table.row( $(this).parents('tr') ).data();
			var filename = data[4];
			var file = data[7];
			var file=file.substring(17, file.length);
			downloadURI(file,data[4]);
		});
	
		function downloadURI(uri, name) 
		{
			var link = document.createElement("a");
			link.download = name;
			link.href = uri;
			link.click();
		}
		
		$('#btn_back').on('click',function(){
			location.href = 'view_browse.php?menu_code='+comm_menu;
		});//back
	
	});//function
		
</script>
<section>	
	<!-- page content --> 
	<div class="right_col" role="main">
	  <div class="">
	   <div class="clearfix"></div>
	  
	
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		
		  <div class="x_content">
		   <form id="attachform" class="form-horizontal form-label-left">
		   <span class="section"><?=$title?></span>

			 <div class="panel panel-primary">
			  <div class="panel-heading"><?=$subtitle?></div>
			  <div class="panel-body">
				<!--Data table-->
				<div class="row">
				  <div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
					<? if($pk!=""){ ?>
					 <button type="button" class="btn btn-primary glyphicon glyphicon-plus" id="btn_add"></button>
					 <? } ?> 
					  <div class="x_content">
						<table id="example" class="table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Priority</th>
									<th>From</th>
									<th>Subject</th>
									<th>Date</th>
									<th>File Name</th>
									<th>Remark</th>
									<th>Type</th>
									<th>File Path</th>
									<th>Action</th>
								</tr>
							</thead>		
						</table>	  
					  </div><!--x_content-->
					  
					</div><!--x_panel-->
				  </div><!--col-md-12 col-sm-12 col-xs-12-->
				</div><!--row-->
				<!--End of Data Table--> 
				
				 <div class="ln_solid"></div>
				  <div class="form-group">
					<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
						<button type="button" name="back" class="btn btn-danger" id="btn_back">Back</button>	
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