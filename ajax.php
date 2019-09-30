<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
	
    $(document).ajaxStart(function(){
        $("#wait").css("display", "block");
    });
    $(document).ajaxComplete(function(){
        $("#wait").css("display", "none");
    });
    $("#btn_submit").click(function(){
        //$("#txt").load("demo_ajax_load.asp");
        var url = "ajax_server.php"; // the script where you handle the form input.

	    $.ajax({
	           type: "POST",
	           url: url,
	           data: $("#ajax_form").serialize(), // serializes the form's elements.
	           success: function(data)
	           {
	               //alert(data); // show response from the php script.
	               //location.href = location.href="eprocure/post";
	           }
	         });
    });
});
</script>
</head>
<body>

<form id="ajax_form" name="ajax_form" onsubmit="return false;">
	<input type="text" name="contact_name" id="contact_name">
		<div id="txt"><h2>Let AJAX change this text</h2></div>
	<input type="button" name="btn_submit" id="btn_submit" value="Click">
		<div id="wait" style="display:none;width:69px;height:89px;border:1px solid black;position:absolute;top:50%;left:50%;padding:2px;"><img src='ford_lodding.gif' width="64" height="64" /><br>Loading..</div>
</form>

</body>
</html>
