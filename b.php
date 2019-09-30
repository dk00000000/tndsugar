  <html>
  <head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker-standalone.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker-standalone.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  </head>
  <body>
  
  <!-- page content --> 
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
        
          <div class="x_content">
           <form id="districtform" class="form-horizontal form-label-left" method="POST" action="trans_summary.php" target="_blank">
          
           
          <div class="panel panel-primary">
          <div class="panel-heading" id="addpanel">Input</div>
            <div class="panel-body">

          
           <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >From Date <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" name="fromdate" id="fromdate" required="required" placeholder="Select From Date">
              </div>
            </div>    
            
           <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >To Date <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" name="todate" id="todate" required="required" placeholder="Select From Date">
              </div>
            </div>  

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >No. of  Day <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" name="days" id="days">
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
    
   <!-- /page content -->
<script type="text/javascript">
 $(document).ready(function() {

  $("#fromdate").datetimepicker({format : 'DD/MM/YYYY'}); 
  $("#todate").datetimepicker({format : 'DD/MM/YYYY'}); 



  $("#fromdate,#todate").change(function(){



    var start = $("#fromdate").datetimepicker("getDate");
    var end = $("#todate").datetimepicker("getDate");


    alert(start);

  
    //inputTime = new Date(start).getTime();


    //alert(inputTime/(1000*60*60));

    // if(start>end)
    // {
    //     $("#frdt").val("From Date is less than To Date");
    // }
    // else if(frmTime>toTime)
    // {
    //    $("#totm").val("From Time is less than To Time");
    // }
    // else
    // {


    days = ((end - start) / (1000 * 60 * 60 * 24));

    //tot_time_diff = ((toTime - frmTime)/(1000*60*60));

    //tot_time_diff_hour=tot_time_diff/24;

    //day = days+tot_time_diff;
  
    //alert(days)
    $("#days").val(days+" Days");

    //$("#hour").val(tot_time_diff+" hour");

  // }

});





 }); 
</script>

 
