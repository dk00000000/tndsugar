<?php
session_start();
header('Content-type: application/json');
require_once( "../dashboard.php" );
require_once( "../query.php" );
class Postfsinput {
	 
private $lgs,$qryObj,$dsbObj,$rfObj,$xmlObj,$conn,$stid,$prefetch;
   
function __construct() 
	{	
        $filepath = '../util/readquery/general/DBINFO.ini';
        $ini_array = parse_ini_file($filepath, true);
        $dbid='MAIN';
        $host = $ini_array[$dbid]['HOSTIP'];
		$service_name = $ini_array[$dbid]['SERVICENAME'];
		$compCd = $ini_array[$dbid]['COMPCODE'];
		$server = $ini_array[$dbid]['SERVER'];

		define('DATABASE',"(DESCRIPTION =
					(ADDRESS_LIST =
						(ADDRESS = 
							(PROTOCOL = TCP)(HOST = ".$host.")(PORT = 1521))
			   			)
					(CONNECT_DATA =	(SERVER = DEDICATED)
					(SERVICE_NAME = ".$service_name."))				
	  	  		)");		

        $_SESSION['USER'] =strtoupper($_REQUEST['username']);
        $password =strtoupper($_REQUEST['password']);
        $_SESSION['IDTTY'] =$password.strrev($password);            

        if(isset($_SESSION['USER']) && isset($_SESSION['IDTTY'])){
       
        $this->conn = @oci_pconnect($_SESSION['USER'], $_SESSION['IDTTY'], DATABASE, AL32UTF8);
			if (!$this->conn) {
			   $m = oci_error();
			   //echo $m['message'];
			   echo json_encode(array('Success'=>'false','Message'=>'Login Fail'));
			   exit;
			}else {
				//echo json_encode(array('Success'=>'true','Message'=>'Login Success'));
			}
		}
    }

   

    public function postAlltransactionData($data)
	{
		$dsbObj = new Dashboard();
		$qry = new Query();	

		$filepath = 'ini/harvesting_slip.ini';
        $queries = parse_ini_file($filepath, true);

        //FOR JSON TO ARRAY
		$data =json_decode($data, true);			
		
				$response1 = print_r($data, true); 
		        $fp = fopen('oldFilter.txt', 'w');
		        fwrite($fp,  $response1);
		        fclose($fp); 


			foreach ($data as $key => $value) {
                if(gettype($value) == 'array'){
			       $data= $value;			        
                }
			}	    

		    $oldFilter=array();
	        $newFilter=array();

	        $oldDtlFilter=array();
	        $newDtlFilter=array();
	        //FOR INSERT RECORD INTO TABLE 
	         
	        $total_keys=array();
            $total_values=array();

	        for ($i=0; $i<sizeof($data); $i++)
	             { 
	        	   foreach ($data[$i] as $key => $val) 
	        	        {
	        	          if($key == 'TXN_DATE1'){
	        	          	$key = 'LDATE';
	        	          }
		                  $keys=":P".strtoupper($key);
		                  $value=$val;
		                  array_push($oldFilter,$keys);
		                  array_push($newFilter,$value);
		                }//End of for each
	        	

		        //FOR GETTING NEXT SEQ
                $getseqQry = $qry->fetchQuery($filepath,'Q001','GET_SEQ',$oldFilter,$newFilter);
	            $next_seq =$dsbObj->getData($getseqQry);
                $date1 = date('Ymd');
				$date = new DateTime($date1);
				$perd_code = $date->format('Ym');
                array_push($oldFilter,':PTXN_SEQ',":PPERD_CODE",':PUSER_CODE');
                array_push($newFilter,$next_seq[0]['NEXT_TXNSEQ'],$perd_code,$_SESSION['USER']);

                array_push($oldDtlFilter,':PTXN_SEQ',":PPERD_CODE",':PUSER_CODE');
                array_push($newDtlFilter,$next_seq[0]['NEXT_TXNSEQ'],$perd_code,$_SESSION['USER']);

                //HERE WE WRITE INSERT QUERY 
	            $insertQry = $qry->fetchQuery($filepath,'Q001','INSQRY_FSURVEY',$oldFilter,$newFilter);	
	            $stid = oci_parse($this->conn, $insertQry);
	            $res=oci_execute($stid); 
	            $affected_rows = oci_num_rows($stid);
	            $r = oci_commit($this->conn);
	            echo $r;
	            exit(0);
	            if($res==1 && $affected_rows>0)
	            {
	            	/*Set filter to detail qry*/
		            $dtl_data = $data[$i]['SURVEY_REASONS'];
					for ($j=0; $j<sizeof($dtl_data);$j++)
			            {
			            	$oldDtlFilter = array();
			            	$newDtlFilter = array();

			    array_push($oldDtlFilter,':PTXN_SEQ',":PPERD_CODE",':PUSER_CODE');
                array_push($newDtlFilter,$next_seq[0]['NEXT_TXNSEQ'],$perd_code,$_SESSION['USER']);
			            	foreach ($dtl_data[$j] as $new_key=>$res) 
		        	        {
		        	          $dtl_keys=":P".strtoupper($new_key);
			                  $del_value=$res;
			                  array_push($oldDtlFilter,$dtl_keys);
			                  array_push($newDtlFilter,$del_value);
			                }//foreach
			                $insertDtlQry = $qry->fetchQuery($filepath,'Q001','DTL_INSQRY',$oldDtlFilter,$newDtlFilter);	
			                $stid1 = oci_parse($this->conn, $insertDtlQry);
					        $res1=oci_execute($stid1); 
					        $affected_rows1 = oci_num_rows($stid1);
					        $r1 = oci_commit($this->conn);
					     }//for

	            }//if

	            $total_data=array();
	            if($affected_rows > 0){
	           	 $postresponseQry = $qry->fetchQuery($filepath,'Q001','INSQRY_FSURVEYRESPONSE',$oldFilter,$newFilter);
	                 $post_result =$dsbObj->getData($postresponseQry);
	                 $total_data[]=$post_result;
	                 array_push($total_keys, 'txn_srno');
	                 array_push($total_values, $newFilter[0]);
	                 foreach ($post_result as $key => $value) {
	                 	$keys=array_keys($value);
	                 	$values =array_values($value);
	                 	
	                 	for ($k=0; $k < sizeof($value) ; $k++){
                            array_push($total_keys, strtolower($keys[$k]));
                            array_push($total_values, $values[$k]);                            
	                 	}
	                 }	                
	                 $total_insRecord[]=array_combine($total_keys,$total_values);
	            }else{
	            	echo "Query".$insertQry."Probleme in Inserting Field Survey Record";
	            }
		        
		        $oldFilter=array();
		        $newFilter=array();		             
	        }
	       
            // $total_insRecord=array_combine($total_keys, $total_values);

            if(sizeof($post_result) > 0 ){
            	$result['Success']="true";
            }else{
            	$result['Success']="false";
            }
            
	        /*FOR SECMAST MAST*/
	        $result['SURVEYMAST']=$total_insRecord;
	        echo json_encode($result);
	        die;	    

	}

}

$obj = new Postfsinput();
$data=$_REQUEST['data'];
$obj ->postAlltransactionData($data);

//http://203.127.5.7/tndsugar/api/Postfsinput.php?username=kadamkg&password=kadamkg

?>
