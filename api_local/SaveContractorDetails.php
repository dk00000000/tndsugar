<?php
// Include all configurations
require_once( "config.php" );

$ReturnArr = array();
$Contractors = array();

//store values received from URL in local variables.
$SequenceNo = $_REQUEST['SequenceNo'];
$contractflag=$_REQUEST['type'];

$ApiURL = $_POST['ApiURL'];
$UploadPath = '../' . $_POST['UploadPath'];
$TargetFile = '';

$ReturnArr['Success'] = 'false';
$ReturnArr['Message'] = "";

if( !file_exists( $UploadPath ) ) {
	$ReturnArr['Success'] = 'false';
    $ReturnArr['Message'] = "Upload path does not exists.";
} else { 
	$Images = $_FILES;
	// $PhotoImage = $_FILES['Photo'];
	// $FingerprintImage = $_FILES['Fingerprint'];
	
	$PhotoImage = $_POST['Photo'];
	$FingerprintImage = $_POST['Fingerprint'];
	// print_r( $Images ); die;

	$UploadPath = trim( $UploadPath, '/' );

	$PhotoUploadedFlag = false;
	$FingerprintUploadedFlag = false;

	$TargetPhotoDir = $UploadPath . '/' . $SequenceNo;

	if( !file_exists( $TargetPhotoDir ) ) {
		mkdir( $TargetPhotoDir . '/', 0777, true );
	} // if( file_exists( $TargetPhotoFile ) )

	$UpdateStr = '';

	if( !empty( $PhotoImage ) ) {

		$TargetPhotoFile = $TargetPhotoDir . '/'.$contractflag.'PHOTO.JPG';

		if( file_exists( $TargetPhotoFile ) ) {
			@unlink( $TargetPhotoFile );
		} // if( file_exists( $TargetPhotoFile ) )

		$str="data:image/jpeg;base64,"; 
	    $data=str_replace($str,"",$PhotoImage); 
	    $data = base64_decode($data);
	    $Flag = file_put_contents($TargetPhotoFile, $data);

	    if( $Flag !== false ) {
	    	$ReturnArr['Message'] .= "\nPhoto has been uploaded.";
	        $PhotoUploadedFlag = true;
	        $UpdateStr .= ', Photo = '.$contractflag.'"PHOTO.JPG"';
	    } else {
	        $ReturnArr['Message'] .= "There was an error uploading photo. Please try again.";
	    }
	} else {
		$ReturnArr['Message'] .= "\nPhoto image was not selected.";
	}

	if( !empty( $FingerprintImage ) ) {

		// $TargetPhotoFile = $UploadPath . '/' . $SequenceNo . '/Fingerprint.jpg';
		$TargetPhotoFile = $TargetPhotoDir . '/'.$contractflag.'FINGERPRINT.JPG';

		if( file_exists( $TargetPhotoFile ) ) {
			@unlink( $TargetPhotoFile );
		} // if( file_exists( $TargetPhotoFile ) )

		$str="data:image/jpeg;base64,"; 
	    $data=str_replace($str,"",$FingerprintImage); 
	    $data = base64_decode($data);
	    $Flag = file_put_contents($TargetPhotoFile, $data);

	    if( $Flag !== false ) {
	    	$ReturnArr['Message'] .= "\nFingerprint image has been uploaded.";
	        $FingerprintUploadedFlag = true;
	        $UpdateStr .= ', Fingerprint = '.$contractflag.'"FINGERPRINT.JPG"';
	    } else {
	        $ReturnArr['Message'] .= "There was an error uploading fingerprint image. Please try again.";
	    }
	} else {
		$ReturnArr['Message'] .= "\nFingerprint image was not selected.";
	}

	/*if( $PhotoUploadedFlag || $FingerprintUploadedFlag ) {
		$Sql = 'UPDATE contractors SET ' . trim( $UpdateStr, ', ' );
		$Conn->query( $Sql );
	}*/

	if( $PhotoUploadedFlag || $FingerprintUploadedFlag ) {
		$ReturnArr['Success'] = 'true';
	} // if( $PhotoUploadedFlag && $FingerprintUploadedFlag )

} // if( !file_exists( '../' . $UploadPath ) )

header('Content-Type: application/json');
echo json_encode( $ReturnArr ); die;

//http://203.127.5.7/tndsugar/api/SaveContractorDetails.php?SequenceNo=DD17A00000276&type=M
?>