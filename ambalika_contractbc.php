<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="icon" href="images/favicon.png" >
<style>
    .sp {
        padding-left:4em;   
    }
    .sp1 {
        padding-left:12em;   
    }
    .sp2 {
        padding-left:6.5em;   
    }
     .page{   
        page-break-before: always; 
        page-break-inside: avoid;
      }

</style>    
</head>
<body>
<?php

    require_once('dashboard.php');
    include('readfile.php');
    
    $lgs = new Logs();
    $qryObj = new Query();
    $dsbObj = new Dashboard(); 
    $rfObj = new ReadFile();
    $lang=strtolower($_SESSION['LANG']);
    $qryPath = "util/readquery/general/contractentry_tran.ini";
    $langPath = "util/language/";
    $menu_code=$_SESSION['MENU_CODE'];
    $langPath = $langPath."general/".$lang.'/'.$menu_code.".txt";
    
    $data = $_GET;
//    print_r($data);
    
    $oldfilter=array(':PPRT_CODE',':PCOMP_CODE');
    $newfilter=array($data['TXN_ACCD'],$_SESSION['COMP_CODE']);
    $getPrtinfoqry = $qryObj->fetchQuery($qryPath,'Q001','GET_PRTINFO',$oldfilter,$newfilter);
    $getPrtinfoRes = $dsbObj->getData($getPrtinfoqry);
//    print_r($getPrtinfoRes);

?>    
    
<div class="container">
<p class="text-center">श्री अंबालिका शुगर प्रा <strong> . </strong> लि <strong> ., </strong> अंबिकानगर ता <strong> . </strong> कर्जत जि <strong> . </strong> अहमदनगर</p>

<div style="width: 99%;">
 <div style="float: left; width: 32.66%;" align="left">करार क्र. १ </div>
 <div style="float: left; width: 33.67%;" align="center"> वाहतूकदार </div>
 <div style="float: left; width: 32.66%;" align="right"> दिनांक : <b><?= $data['TXN_DATE'];?></b></div>
 <br style="clear: left;" />
</div> 
    
<p>प्रति ,</p>
<p class="sp">जनरल मॅनेजर सो <strong> . </strong></p>
<p class="sp">श्री अंबालिका शुगर प्रा <strong> . </strong> लि <strong> . </strong> अंबिकानगर</p>
<p class="sp">ता<strong>. </strong> <b><?= $data['TLK_MNAME'];?></b> <strong>, </strong>जि<strong>. </strong> <b><?= $data['DT_MNAME'];?></b><strong> . </strong></p>
<p class="sp1">यांजकडेस ,</p>
<p>विषय &ndash; ऊस तोडणी वाहतूकीचा करार मिळणे बाबत&hellip; .</p>
<p>अर्जदार &ndash; श्री .<b><?= $data['CONTRACTOR_MNAME'];?>(<?= $data['TXN_ACCD'];?>)</b></p>
<p class="sp">पत्ता. <b>मु. पो.  <?= $data['VL_MNAME'];?>, ता. <?= $data['TLK_MNAME'];?>, जि. <?= $data['DT_MNAME'];?></b></p>
<p class="sp">वय : <b> <?= $data['AGE'];?></b> वर्षे  
    <span style="padding-left:2em;"> मो . नं .: <b><?= $data['PRT_TEL'];?></b></span>
</p>
<p class="sp">    
    आधार नं. :<b> <?= $data['PRT_UID'];?> </b>
    <span style="padding-left:2em;"> पॅन नं. :<b> <?= $data['PRT_PAN'];?></b></span>
</p>    
<p>महोदय <strong> , </strong></p>
    <p style="padding-left:2em;">मी गाळप हंगाम <b><?= $data['TXN_SEASON'];?></b> साठी आपले कारखान्याकडे ऊस तोडणी वाहतूकीचे काम करण्यास तयार आहे . त्यासाठी माझेकडे खालीलप्रमाणे तोडणी वाहतूक यंत्रणा आहे .</p>
<ol class="sp">
	<li>
		<p>टायरबैलगाडी लेबर बैलासह .: <b><?= $data['TXN_NETTFC'];?> </b></p>
	</li>
</ol>
<ol start="2" class="sp">
	<li>
		<p>लेबर .: <b><?= $data['TXN_EXCHRT'];?></b>  &nbsp;&nbsp;&nbsp;  बैल.: <b><?= $data['TXN_AMTFC'];?></b></p>
	</li>
</ol>
<p style="padding-left:2em;">तरी मला तोडणी वाहतुकीचा करार मिळणेस विनंती .</p>
<p style="padding-left:9em;">कळावे ,</p>
<p class="text-right">आपला विश्वासू <strong> , </strong></p>
<p align="right" style="padding-right:8em;">सही :</p>
<p align="right">नाव : <b> <?= $data['CONTRACTOR_MNAME'];?> </b></p>
<p>सदरचा करार अर्ज मंजूरीस शिफारस आहे .</p>
    
<div style="width: 99%;">
 <div style="float: left; width: 49.5%;" align="left"> विभाग प्रमुख    </div>
 <div style="float: left; width: 49.5%;" align="right"> मुख्य शेतकी अधिकारी </div>
 <br style="clear: left;" />
</div> 
    
<br/>
    
<div style="width: 99%;">
 <div style="float: left; width: 49.5%;" align="left"> करार अर्ज मंजूर / नामंजूर   </div>
 <div style="float: left; width: 49.5%;" align="right"> पुर्णवेळ संचालक </div>
 <br style="clear: left;" />
</div>

<?php echo "<p class='page'>  </p>"; ?>     
    
<ul>
	<p class="text-center"><b>टायरगाडी तोडणी वाहतुक करारनामा &ndash;</b></p>
</ul>
<p>लिहुन घेणार &ndash; प्रति ,</p>
<p class="sp2">जनरल मॅनेजर सो <strong> . </strong></p>
<p class="sp2">श्री अंबालिका शुगर प्रा <strong> . </strong> लि <strong> . </strong> अंबिकानगर</p>
<p class="sp2">ता <strong> . </strong> कर्जत <strong> , </strong> जि <strong> . </strong> अहमदनगर <strong> . </strong></p>
<p>लिहुन देणार &ndash; श्री :<b> <?= $data['CONTRACTOR_MNAME'];?> </b>,</p>
<p class="sp2">पत्ता :<b>मु. पो.  <?= $data['VL_MNAME'];?>, ता. <?= $data['TLK_MNAME'];?>, जि. <?= $data['DT_MNAME'];?></b></p>
<p class="sp2">वय : <b> <?= $data['AGE'];?></b> वर्षे  
    <span  class="sp">मो. नं. :<b> <?= $data['PRT_TEL'];?> </b></span>
</p>
<p class="sp2">
    आधार नं. :<b> <?= $data['PRT_UID'];?> </b>
    <span style="padding-left:2em;"> पॅन नं. :<b> <?= $data['PRT_PAN'];?></b></span>
</p>    
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;कारणे करारनामा लिहून देतो की , मी माझे सहभागीदार जातीने ऊस तोडणी वाहतुकीचे काम करीत असतो . सन <b><?= $data['TXN_SEASON'];?></b> चे हंगामात ऊस तोडणी वाहतुकीचे काम करणेसाठी तुमचे कंपनीच्या आणि कारखान्याच्या परीसरात येऊन आम्ही त्या कारखान्यास ऊस पुरविणा - या ऊस उत्पादकाकडुन तोडणी वाहतुकीचे काम मिळविणार आहोत . हे काम मिळवून देणेचे कामी व पार पाडण्याचे कामी तुम्ही आम्हांस मदत व सहकार्य देण्याचे कबुल केलेले आहे त्यासाठी ज्या ऊस उत्पादकाचे तोडणी वाहतूकीचे काम आम्ही करु त्यांचेकडुन आम्हांस मोबदला मिळणार आहे . तुमचेकडुन जी मदत सहकार्य मिळणार आहे त्यासाठी हा करार मी स्वत : व माझे सहभागीदाराचे वतीने तुम्हांस लिहुन देत आहे . सहभागीदाराचे वतीने व साठी हा करार करुन देण्याचा मला अधिकार आहे .</p>
<p class="text-center">कराराच्या शर्ती व अटी <strong> - </strong></p>
<ol>
	<li>
		<p>मी व माझे सहभागीदार बैल &hellip; . लेबर घेऊन तुमच्या कंपनीच्या आणि कारखान्याच्या परीसरातील आणि मिळेल तेथील ऊस उत्पादकाचा ऊस तोडणी वाहतुकीसाठी चालु हंगामात कारखाना कार्यक्षेत्रामध्ये येऊ व ऊस पुरवठादारांकडुन तोडणी वाहतुकीचे काम मिळवू . हंगामभर कामे मिळवत राहु . हंगाम कधी सुरु होणार हे कळविण्याचे कामी आम्हांस मदत करावी .</p>
	</li>
	<li>
		<p>ज्या ऊस उत्पादकाची ऊस तोडणी वाहतुक करावयाची आहे त्याचेबरोबर करार करुन कराराप्रमाणे त्यांचे ऊस तोडणी वाहतुक करुन देऊ . हे काम मिळविण्याचे कामी तुम्ही आम्हांस मदत करावयाची आहे ही बाब तुमचे सदिच्छेतील राहील .</p>
	</li>
	<li>
		<p>आम्ही केलेल्या ऊस तोडणी वाहतुकीचे कामाची बिले तुम्ही त्या त्या ऊस उत्पादकाकडुन घेऊन आम्हांस वेळोवेळी व तुम्ही ठरविलेल्या नियामाप्रमाणे ध्यावीत . याकामी मदत करावी , ही बाब तुमचे सदिच्छेतील राहील या कामसाठी तुम्हांस जो , मोबदला अगर खर्चासाठी रक्कम ध्यावयाची ती रक्कम आम्ही व ऊस उत्पादक मिळुन ठरवू आम्हांस मदत देत असता तुम्हास झळ लागु देणार नाही .</p>
	</li>
	<li>
		<p>आम्ही ऊस उत्पादकाबरोबर संपुर्ण हंगामासाठी त्यांना जसजसी तोड मिळेल त्या त्या वेळी ऊस तोड वाहतुक करण्याचे करार करणार आहोत . त्या करारातील अटीप्रमाणे हंगाम अखेर पर्यंत आमचे कामाचे बिलातुन 10 टक्के रक्कम डिपॉझीट म्हणुन कापुन घेण्याचा अधिकार ऊस उत्पादकांना ठेवलेला आहे त्या प्रमाणे 10 टक्के रक्कम तुम्ही प्रत्येक बिलाचे वेळी ऊस उत्पादकाचे वतीने कापुन घ्यावी व हंगाम अखेर ती आम्हांस ऊस उत्पादकाचे वतीने परत करावी .</p>
	</li>
	<li>
		<p>ऊस उत्पादकाबरोबर ऊस तोडणी वाहतुकीचा जो करारा करु त्या कराराप्रमाणे संबंधीत ऊस उत्पादकाने जो जो कामचा मोबदला कबुल केला असेल तो सर्व त्यांचेकडुन घेऊन तुम्ही आम्हांस मिळवून देण्याचे कामी मदत करावयाची आहे . ऊस उत्पादकाने कामसाठी त्यांचेकडुन टायरबैलगाडी , वायररोप , कोयते , बांबू , चटई , जु , तंबू इत्यादी साहित्य तुम्ही आमचेसाठी घेऊन आम्हांस ध्यावे . हंगाम संपल्यानंतर हे सर्व साहित्य तुम्हांस परत करु . हे साहित्य गहाळ झाल्यास अगर त्याची अफरातफर झाल्यास तुम्ही ठरवाल रेवढी रक्कम नुकसान भरपाई म्हणुन तुम्हांस देऊ ती तुम्ही आमचे कामाचे बिलातुन , डिपॉझीट्मधुन , वाढीव तोडणी वाहतुक खर्चाचे रक्कमेतुन कपात करुन घ्यावी त्यास आम्ही संमती देत आहोत .</p>
	</li>
	<li>
		<p>मी व माझे सहभागीदार यांनी केलेल्या कामाचे बिल माझ्याकडे देण्यात यावे . माझ्या सहभागीदारांनी केलेल्या कामाच्या प्रमाणात याची वाटणी सहभागीदारांना करुन देईल . तुमचेकडे तक्रार येऊ देणार नाही अशी तक्रार आल्यास त्याचे मी परस्पर निवारण करीन . तुम्हांस तोशीस लागल्यास त्यांचे नुकसान भरपाई भरुन देईल . आमच्यात व ज्या ऊस उत्पादकाचे ऊस तोडणी वाहतुकीचे काम आम्ही करीत असु त्याच्या कामाच्या बाबतीत मतभेद अगर वादविवाद झाल्यास याकामी तुम्ही मध्यस्थ् म्हणून दिलेला निर्णय आम्हांस मान्य राहील .</p>
	</li>
	<li>
		<p>आम्ही ज्या ऊस उत्पादकाचे काम करु त्याचेकडुन मिळणारी वाढीव तोडणी वाहतुक खर्चाची , मर्जीतील अगर ईच्छेप्रमाणे आमचेसाठी दिलेली रक्कम तुमचेकादुन हंगाम संपल्यानंतर घेऊ . त्या अगोदर अशा रक्कमा मागण्याचा आम्हांस अधिकार नाही .</p>
	</li>
	<li>
		<p>तुम्ही आम्हास टायरबैलगाडया भाडयाने दिल्यास त्याचे रितसर तुम्ही ठरवाल ते भाडे तुम्हांस देऊ . आमचे होणारे ऊस तोडणी वाहतुकीच्या बिलातुन ते तुम्ही परस्पर कापुन घ्यावे .</p>
	</li>
	<li>
		<p>आम्ही ज्या ऊस उत्पादकाचे ऊस तोडणीचे काम करु त्यांना एकुण निघणा - या ऊसाचे वाढयापैकी 1/3 वाढे ऊस उत्पादकास देऊ .</p>
	</li>
	<li>
		<p>कामावर येणेपुर्वी पुर्व तयारीसाठी तुम्ही आम्हांस वेळोवेळी जरुरीप्रमाणे व तुमच्या मर्जीप्रमाणे ॲडव्हान्स ध्यावा , ही ॲडव्हान्सची रक्कम आम्ही कामावर आल्यानंतर आमचे होणारे कामचे बिलातुन वेळोवेळी कापुन घेण्याचा तुम्हांस अधिकार दिलेला आहे . हंगाम अखेर पर्यंत वेगवेगळ्या ऊस उत्पादकाबरोबर करार करुन ऊस तोडणी वाहतुकीचे काम करुन या कामाचे बिलातुन तुमचेकडून घेतलेल्या ॲडव्हान्सची परतफेड करु ॲडव्हान्स घेऊन कामावर न आल्यास अगर कामावर आल्यान तर ॲडव्हान्सची परतफेड न होताच हंगाम संपण्याचे आत तुम्हांस ऊस पुरवठा करणा - या ऊस ऊत्पादकाकडुन मिळणारे काम सोडुन गेल्यास ती तुमची मी स्वत : केलेली फसवणूक ठरेल . त्यावेळी मी त्यावेळी मी फौजदारी गुन्ह्यास पात्र राहील व होईल . माझे विरुध्द फसवणुकीबाबत फौजदारी फिर्याद करण्याचा तुम्हांस अधिकार तुमचेकडुन का मिळविण्यास येणेसाठी ॲडाव्हान्स घेऊन कामावर न येता अगर कामावर आलेचे दाखुन मध्यंतरीत काम सोडुन देऊन ॲडव्हान्स फेडीबाबत तुमची फसवणूक करणार नाही . ॲडव्हान्स मागण्याचा आम्हांस अधिकार नाही असा ॲडव्हान्स देण्याचे तुमचेवर बंधन नाही .</p>
	</li>
	<li>
		<p>मी जी ॲडव्हान्सची रक्कम तुमच्याकडुन वेळोवेळी घेईल , त्याची परतफेड करण्यास मि माझे करारातील जामीनदार एकत्र तसेच संयुक्तरित्या जबाबदार राहतील . कराराप्रमाणे ॲडव्हान्सचे रक्कमेची परतफेड न कल्यास ॲडव्हान्स घेतलेल्या तारखेपासुन रक्कम तुम्हांस देईपर्यंत कमीतकमी 18% प्रमाणे व्याज देईल . तसेच याखेरीज कराराचा भंग केला म्हणून जनरल स्पेशल व लिक्वीडेटेड डॅमेजेस म्हणून उक्ती रक्कम रु&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip; ../- तुम्हांस अधिक जादा देईल .</p>
	</li>
	<li>
		<p>ऊस तोडणी वाहतुक करुन कारखान्याच्या गव्हाणीपर्यंत पोहचविण्यासाठी जबाबदारी ऊस उत्पादकाची आहे . असा ऊस उत्पादक कारखान्याने ठरविलेल्या दराप्रमाणे ऊस तोडणी वाहतुकीचा खर्च ऊसाची किंमत म्हणून मिळण्यास पात्र होईल . या मिळणा - या खर्चापेक्षा जादा रक्कम ऊस उत्पादकाबरोबर आम्ही केलेल्या करारप्रमाणे झाल्यास अशी जादा रक्कम तुमच्याकडुन आम्हांस हक्क म्हणुन मागता येणार नाही . ही जादा रक्कम आम्ही करार केलेल्या ऊस उत्पादकाकडुन परस्पर वसुल करु ऊस उत्पादकाकडे काम करीत असता त्यांचेबरोबर झालेल्या ऊस उत्पादकाकडुन परस्पर वसुल करु ऊस उत्पादकाकडे कामकरीत असता त्यांचेबरोबर झालेल्या करारप्रमाणे काही नुकसान भरपाई त्यास देण्याचा प्रसंग आल्यास अशी नुकसान भरपाईची रक्कम तुम्ही आमचे बिलातुन , वाढीव तोडणी वाहतुक खर्चामधुन संबंधित ऊस उत्पादकासाठी कापुन घ्यावी तसा तुम्हांस पुर्ण अधिकार व हक्क दिलेला आहे .</p>
	</li>
</ol>
<p class="sp2">येणे प्रमाणे करार स्वखुशीने लिहुन दिला असे <strong> . </strong></p>
<p>ठिकाण :- अंबिकानगर </p>
<p>दिनांक :- <b><?= $data['TXN_DATE'];?></b></p>
<p class="text-center">करारनामा लिहुन देणार</p>
    
    
<div style="width: 99%;">
 <div style="float: left; width: 49.5%;" align="left">
     
    </div>
 <div style="float: right; width: 49.5%;" align="right">
    <p align="right" style="padding-right:3em;">
			फोटो	 
        <span style="padding-left:5em;">
			बोटाचा ठसा </span>
		</p>
</div>
 <br style="clear: left;" />
</div>            
        
<div style="width: 99%;">
 <div style="float: left; width: 49.5%;" align="left">
     <p align="left">
			सही	: 
		</p>
		<p align="left">
			नांव : <b><?= $data['CONTRACTOR_MNAME'];?>(<?= $data['TXN_ACCD'];?>)</b> 
		</p> 
    </div>
 <div style="float: left; width: 49.5%;">
 
	<img src="attach/contracts/<?= $data['TXN_SRNO']?>/MFINGERPRINT.JPG" width="120" height="120" alt="Main Contractor Fingerprint Photo" onerror="this.src = 'images/noimage.png';" class="img-responsive" align="right" />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <span>
        <img src="attach/contracts/<?= $data['TXN_SRNO']?>/MPHOTO.JPG" width="120" height="120" alt="Main Contractor Photo" onerror="this.src = 'images/noimage.png';" class="img-responsive" align="right" />
    </span>	
</div>
 <br style="clear: left;" />
</div>     
    
    
<p>साक्षीदार &ndash;</p>
<p>1)_________________________________ सही : _________________________</p>
<p>2)_________________________________ सही : _________________________</p>
<p><strong> - </strong> लिहुण घेणार &ndash;</p>
<p class="text-center">श्री अंबालिका शुगर प्रा . लि ., अंबिकानगर ,</p>
<p class="text-center">ता . कर्जत , जि . अ . नगर</p>
    
<div style="width: 99%;">
 <div style="float: left; width: 49.5%;" align="left"> मुख्य शेतकी अधिकारी    </div>
 <div style="float: left; width: 49.5%;" align="right">  जनरल मॅनेजर</div>
 <br style="clear: left;" />
</div>     
 <br/>
<div style="width: 99%;">
 <div style="float: left; width: 49.5%;" align="left"> श्री अंबालिका शुगर प्रा.लि.,अंबिकानगर </div>
 <div style="float: left; width: 49.5%;" align="right"> श्री अंबालिका शुगर प्रा.लि.,अंबिकानगर </div>
 <br style="clear: left;" />
</div>
 
<?php echo "<p class='page'>  </p>"; ?>   
    
<p class="text-center"><b> जामीनरोखा </b></p>
<p>मा <strong> . </strong> जनरल मॅनेजर सो <strong> . </strong></p>
<p>श्री <strong> . </strong> अंबालिका शुगर प्रा <strong> . </strong> लि <strong> ., </strong> अंबिकानगर</p>
<p>ता <strong> . </strong> कर्जत जि <strong> . </strong> अहमदनगर</p>
<p>महोदय ,</p>
<p class="sp">श्री .<b> <?= $data['CONTRACTOR_MNAME'];?></b></p>
<p class="sp">पत्ता . :<b>मु. पो. <?= $data['VL_MNAME'];?> , ता.    <?= $data['TLK_MNAME'];?> , जि. <?= $data['DT_MNAME'];?></b></p>
<p>यांनी आपणाशी गळीत हंगाम <b><?= $data['TXN_SEASON'];?></b> साठी तोडणी वाहतुकीचा करार केलेला असुन कारणे जामीनपत्र लिहुन देतो कि ,</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;वरील करारदार हे कराराप्रमाणे वर्तणुक करतील , करार पाळतील . ॲडव्हान्सची सक्कम कबुल केलेप्रमाणे परतफेडकरुन देतील . करारापोटि दिलेली ॲडव्हान्सची रक्कम व पुढे त्यांस जरुरीप्रमाणे तुम्ही जी रक्कम ॲडव्हान्स म्हणुन द्याल त्या सर्व रक्कमेची हमी घेऊन आम्ही जामीनदार राहत आहोत . करारदाराने करार मोडला तर नुकसान भरपाई म्हणुन रक्कम देण्याची जबाबदारी जामीनदार म्हणुन आम्ही स्विकारत आहोत . तसेच ॲडव्हान्सच्या सक्कमेची करारदाराने परतफेड न केल्यास ती रक्कम परतफेड्ण्याची जबाबदारी आम्ही स्विकारत आहोत . वरिल सर्व बाबींसाठी करारदार व आमची जबाबदारी जॉईंट व सेव्हरल राहील . आम्हा सर्वांकडुन अगर तुम्हांस वाटेल त्याचे एकाकडुन अगर काही जणांकडुन ही रक्कम वसुल करण्याचा तुम्हांस अधिकार राहील . आमचे भरवशावर व जामीनकीवर तुम्ही करारदाराकडुन करार करुन त्यास ॲडव्हान्स दिलेला आहे व देणार आहात . करारदारस मिलाफी होऊन तुमचेकडुन केवळ ॲडव्हान्स उपटणेसाठी आम्ही जामीनदार झालेलो नाहीत .</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;करारदार कराराप्रमाणे काम करण्यास सहभागीदारासह आला नाही अगर ॲडव्हान्सची रक्कम न फेड्ताच कराराप्रमाणे काम सोडुन गेला तर तुमची करारदार व आम्ही सर्वांनी मिळुन जाणुनबुजुन केलेली मोठी फसवनूक होईल त्यावेळी करारदारासोबत आम्हीही गुन्ह्यास पात्र राहु .</p>
<p class="sp2">येणेप्रमाणे जामीनपत्र स्वखुशीने लिहुन दिले असे <strong> . </strong></p>
    
<div style="width: 99%;">
 <div style="float: left; width: 49.5%;" align="left">ठिकाण : अंबिकानगर  </div>
 <div style="float: left; width: 49.5%;" align="right">दिनांक : <b><?= $data['TXN_DATE'];?></b> </div>
 <br style="clear: left;" />
</div>   
 <br/>   
  
<?php 
        $oldfilter=array(':PPRT_CODE');
        $newfilter=array($data['TXN_GRNT1']);
        $getAddrqry = $qryObj->fetchQuery($qryPath,'Q001','GET_CONTRACTADD',$oldfilter,$newfilter);
        $getAddrres = $dsbObj->getData($getAddrqry);

        $getPrtinfoqry = $qryObj->fetchQuery($qryPath,'Q001','GET_PRTINFO',$oldfilter,$newfilter);
        $getPrtinfoRes = $dsbObj->getData($getPrtinfoqry);
           // echo "<pre>";
//            print_r($getAddrres);
//            echo "<br>";
//            print_r($getPrtinfoRes);
//            echo "</pre><br>";

?>      
  
<div style="width: 99%;">
 <div style="float: left; width: 49.5%;" align="left">
     <p align="left">
			जामीनदार - 
		 
		</p>
    </div>
     <div style="float: right; width: 49.5%;" align="right">
    <p align="right" style="padding-right:3em;">
        सही 
        <span style="padding-left:6.5em;">       
			फोटो	 </span>
        <span style="padding-left:5em;">
			बोटाचा ठसा </span>
		</p>
    </div>
 <br style="clear: left;" />
</div>              
<div style="width: 99%;">
 <div style="float: left; width: 55.5%;" align="left">
     <p align="left">
			1) नाव : <b><?= $data['FIRSTGNT_MNAME'];?></b>	 
		</p>
		<p align="left">
			पत्ता :<b>मु. पो. <?= $getAddrres[0]['VL_MNAME'];?>,ता.<?= $getAddrres[0]['TLK_MNAME'];?>,जि.<?= $getAddrres[0]['DT_MNAME'];?></b>
		</p> 
        <p align="left">
            वय :<b><?= $getPrtinfoRes[0]['AGE'];?></b>
     </p>
    </div>
 <div style="float: left; width: 44.5%;">
 
    <img src="attach/contracts/<?= $data['TXN_SRNO']?>/FFINGERPRINT.JPG" align="right" width="120" height="120" alt="First Guranter Fingerprint Photo" onerror="this.src = 'images/noimage.png';" class="img-responsive" style="padding-bottom: 25px" />
         
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <span>
        <img src="attach/contracts/<?= $data['TXN_SRNO']?>/FPHOTO.JPG" width="120" height="120"  alt="First Guranter Photo" class="img-responsive" onerror="this.src = 'images/noimage.png';" align="right" style="padding-bottom: 25px"/>
    </span>
</div>
 <br style="clear: left;" />
</div>             
   
        
        
        
<?php 
        $oldfilter=array(':PPRT_CODE');
        $newfilter=array($data['TXN_GRNT2']);
        $getAddrqry = $qryObj->fetchQuery($qryPath,'Q001','GET_CONTRACTADD',$oldfilter,$newfilter);
        $getAddrres = $dsbObj->getData($getAddrqry);

        $getPrtinfoqry = $qryObj->fetchQuery($qryPath,'Q001','GET_PRTINFO',$oldfilter,$newfilter);
        $getPrtinfoRes = $dsbObj->getData($getPrtinfoqry);
           // echo "<pre>";
//            print_r($getAddrres);
//            echo "<br>";
//            print_r($getPrtinfoRes);
//            echo "</pre><br>";

?>   
        
        
<div style="width: 99%;">
 <div style="float: left; width: 55.5%;" align="left">
     <p align="left">
			2) नाव : <b><?= $data['SECONDGNT_MNAME'];?></b>
		</p>
		<p align="left">
			पत्ता :<b>मु. पो. <?= $getAddrres[0]['VL_MNAME'];?>,ता.<?= $getAddrres[0]['TLK_MNAME'];?>,जि.<?= $getAddrres[0]['DT_MNAME'];?></b>
		</p> 
        <p align="left">
            वय :<b><?= $getPrtinfoRes[0]['AGE'];?></b>
     </p>
    </div>
 <div style="float: left; width: 44.5%;" >
    <img src="attach/contracts/<?= $data['TXN_SRNO']?>/SFINGERPRINT.JPG" align="right" width="120" height="120" alt="Second Guranter Fingerprint Photo" onerror="this.src = 'images/noimage.png';" class="img-responsive"  />
    
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <span>
        <img src="attach/contracts/<?= $data['TXN_SRNO']?>/SPHOTO.JPG" width="120" height="120" alt="Second Guranter Photo" class="img-responsive" onerror="this.src = 'images/noimage.png';" align="right"/>
	</span>
</div>
 <br style="clear: left;" />
</div>                  
        
        
<?php 
        $oldfilter=array(':PPRT_CODE');
        $newfilter=array($data['TXN_GRNT3']);
        $getAddrqry = $qryObj->fetchQuery($qryPath,'Q001','GET_CONTRACTADD',$oldfilter,$newfilter);
        $getAddrres = $dsbObj->getData($getAddrqry);

        $getPrtinfoqry = $qryObj->fetchQuery($qryPath,'Q001','GET_PRTINFO',$oldfilter,$newfilter);
        $getPrtinfoRes = $dsbObj->getData($getPrtinfoqry);
           // echo "<pre>";
//            print_r($getAddrres);
//            echo "<br>";
//            print_r($getPrtinfoRes);
//            echo "</pre><br>";
if($data['TXN_GRNT3']!='')
{
?>  
        
<div style="width: 99%;">
 <div style="float: left; width: 55.5%;" align="left">
     <p align="left">
			3) नाव : <b><?= $data['THIRDGNT_MNAME'];?></b>
		</p>
		<p align="left">
			पत्ता :<b>मु. पो. <?= $getAddrres[0]['VL_MNAME'];?>,ता.<?= $getAddrres[0]['TLK_MNAME'];?>,जि.<?= $getAddrres[0]['DT_MNAME'];?></b>
		</p> 
        <p align="left">
            वय :<b><?= $getPrtinfoRes[0]['AGE'];?></b>
     </p>
    </div>
 <div style="float: left; width: 44.5%;">
   
   <img src="attach/contracts/<?= $data['TXN_SRNO']?>/TFINGERPRINT.JPG" align="right" width="120" height="120" alt="Third Guranter Fingerprint Photo" onerror="this.src = 'images/noimage.png';" class="img-responsive" style="padding-top: 25px" />
    
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <span>
	   <img src="attach/contracts/<?= $data['TXN_SRNO']?>/TPHOTO.JPG" width="120" height="120" alt="Third Guranter Photo" class="img-responsive" onerror="this.src = 'images/noimage.png';" align="right" style="padding-top: 25px"/>	
    </span>
   
</div>
 <br style="clear: left;" />
</div>
<?php } ?>    
    
<p>साक्षीदार &ndash;</p>
<p>1) ____________________________ सही : _________________</p>
<p>2) ____________________________ सही : _________________</p>
    
    
<?php echo "<p class='page'>  </p>"; ?> 
    
<p class="text-center"><b> ऊस तोडणी वाहतुकीचा करारनामा </b></p>
<p>लिहून घेणार <strong> - </strong></p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;मी खाली सही करणार ऊस तोडणी वाहतुकीच्या शर्ती व अटी मान्य करुन स्वतःसाठी व माझे सहभागीदारासाठी सदरचा करारनामा लिहुन देत आहे .</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;तुम्ही ऊस उत्पादक शेतकरी आहात . स्न 2018-19 चे गळीत हंगामात तुमचा ऊस शेतात उभा आहे त्यासाठी तोडणी करणे , बैलगाडीमध्ये भरुन कारखान्याचे गव्हानीपर्यंत वाहतुक करणे , ऊस तोडणे व ट्रक ट्रॅक्टर ट्रेलरमध्ये भरुण देणे , तसेच ऊस तोडल्यानंतर ट्रक / ट्रॅक्टर ट्रेलरमध्ये भरणेसाठि एक किमी . पर्यंत वाहुन नेवुन ट्रक ट्रॅक्टर ट्रेलर भरुण देणे हे काम मी व माझे सहभागीदारांना खालील शर्ती व अटी वर पत्कारले आहे . हा करार मी स्वतःसाठी व माझे सहभागीदारांसाठी करुन देत आहे . माझे सहभागीदारा वरही हा करार बंधनकारक आहे व राहील .</p>
<ul>
	<li>
		<p>कराराच्या शर्ती व अटी <strong> - </strong></p>
	</li>
</ul>
<ol>
	<li>
		<p>मी व माझे सहभागीदार चालू हंगामात म्हणजे सन 2018-19 साठी तुमचे गळतीस असलेल्या ऊसाची तोडणी व वाहतूकीचे काम करण्याचे कबुल करीत आहोत .</p>
	</li>
	<li>
		<p>मला व माझे सहभागीदारांना तुम्ही सांगाल तेवढा में . ट्न ऊस दररोज तोडुन त्याची वाहतुक मी व माझे सहभागीदारांच्या बैलगाड्या लावुन करीन मी व माझे सहभागीदार दररोजच्या कोठ्याप्रमाणे ऊस तोडुन ट्रक ट्रॅक्टर ट्रेलरमध्ये भरुण देऊ जरुरीनुसार स्थळापासुन एक किमी . पर्यंत आम्ही तोडलेला ऊस आमचे समधानाने वाहुन नेऊ व ट्रक ट्रॅक्टर ट्रेलरमध्ये भरुण देऊ .</p>
	</li>
	<li>
		<p>तुमचे ऊसाचे प्लॉटची तोडणी सुरु झालेनंतर प्लॉट संपेपर्यंत मी माझे सहभागीदार ऊस तोडणी वाहतुक करुन देऊ दरम्यान काम अर्धवट टाकुन जाणार नाही . तुमचे प्रत्येकाचे ऊस तोडणी वाहतुकीचे काम ज्या ज्या वेळी आम्ही सुरु करु त्यावेळेपासुन काम संपेपावतो त्या त्या संबंधित ऊस उत्पादक करार करुन घेणार यांचे कंत्राटदार म्हणुन राहु व त्याप्रमाणे कामे करु .</p>
	</li>
	<li>
		<p>मी व माझे सहभागीदार हजर राहुन ऊस तोडणीचे काम करु ऊस व्यवस्थित भुईसपाट तोडुन साळुन त्याच्या एका माणसास उचलता येतील अशा मोळ्या आळ्याने बांधुन देऊ बांधताना ऊसास वाढे , पाचट अगर कचरा राहणार याची काळजी घेऊ .</p>
	</li>
	<li>
		<p>ऊसाची तोडणी करीत असताना सर्व पाचट गोळा करुन त्याच्या कट सरीवर ओळी मारु . त्यात ऊसाच्या कांड्या जाणार नाहीत याबद्द्ल दक्षता घेऊ याकामी आमचेकडुन चुक अगर हलगर्जीपणा झालेमूळे तुमचे नुकसान झाल्यास जबाबदारी माझेवर राहील .</p>
	</li>
	<li>
		<p>कराराचे मुदतीत काम करीत असता माझे व माझ्या सहभागीदाराचे गैरवर्तणुकीना आम्ही जबाबदार राहु आमचेपैकी कोणाचेही गैरवर्तन दिसुन आल्यास त्या भागिदारास कामावरुन कमी करण्याची व्यवस्था करु तसे करण्याचे आम्ही नाकारल्यास सदरचा करार रद्द करण्याचा तुम्हांस पुर्ण अधिकार राहिल .</p>
	</li>
</ol>
<ol start="7">
	<li>
		<p>(1) ऊस तोडणी वाहतुकीचे दर खालीलप्रमाणे ठरविलेले आहेत व ते दर मला व माझे सहभागीदारास कबुल .</p>
	</li>
</ol>
<p>आहेत</p>
<ol>
	<li>
		<p>गाडी सेंटर ऊस तोडणे , साळणे , मोळ्या बांधणे व जरुर पडल्यास एक ते दिड किमी . पर्यंत बैलगाडीने</p>
	</li>
</ol>
<p>वाहुन ट्रक ट्रॅक्टर ट्रेलरमध्ये भरणे &ndash; दर मे . टनास रु . &hellip;&hellip;.&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;..</p>
<p>( ब ) डोकी सेंटर ऊस तोडणे , साळणे , मोळ्या बांधणे व डोक्यावर वाहुन प्लॉट्पासुन 300 फुटा पर्यंत ऊस वाहुन ट्रक ट्रॅक्टर ट्रेलरमध्ये भरणे &ndash; दर मे . टनास रु . &hellip;&hellip;.&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;..</p>
<ol>
	<li>
		<p>टायरबैलगाडी - ऊस तोडणे , साळणे , मोळ्या बांधुन बैलगाडीत भरणे - दर मे . टनास रु . &hellip;&hellip;.&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;..</p>
	</li>
</ol>
<p>व टायर बैलगाडीने ऊस वाहतुक पहिल्या किमी . साठी - दर मे . टनास रु . &hellip;&hellip;.&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;..</p>
<p>वरिल कॉलम नं . 7(1) मधील दराखेरीज ज्या ठिकाणी आमची बैलगाडी असेल त्याठिकाणी आम्ही तोडलेल्या ऊसाच्या वाढ्यापैकी 1/3 वाढे रोजचे रोज तुम्हांस बिनतक्रार देऊ व राहिलेले वाढे ऊस तोडणी वाहतुक दराचा भाग म्हणुन आम्हांस तुम्ही नेऊ द्यावयाचे आहे .</p>
<p>7)(2) तुमच्या ऊसाची तोडणी व वाहतूक करण्याची जबाबदारीस पुर्णपणे तुमची आहे . याची अम्हास पुर्णजाणिव व माहीती आहे . तुम्ही केलेल्या तुमच्या ऊस तोडणी वाहुतीकी साठी श्री अंबालिका शुगर प्रा . लि . अंबिकानगर साखर कारखाना हा ठरावीक दराणे तुम्हास ऊसाची किंमत म्हणून खर्च देतो याची अम्हास माहीती आहे .</p>
<p>कॅलम नं 7)(1) मध्ये आमच्याशी ठरलेल्या दरांपेक्षा तुम्हास जादा दराने वरील कारखान्याने ऊस तोडणी वाहतुकीची रक्क्म दिल्यास या जादा दराने तुमचे कडून अम्हांस ऊस तोडणी वाहतुक मिळावी म्हणून आगर त्यासाठी अम्हास कोणताही हक्क्&zwj; ठेवलेला नाही . अम्हांस कबूल केलेल्या दरापेक्षा कमी दराने वरील कारखान्याकडून तूम्हांस ऊस तोडणी वाहतुकीचा खर्च मिळाल्यास कमी असलेल्या रक्क्मेची भरपाई तुम्ही स्वह : करावयाची आहे .</p>
<p>7)(3) तुमचे ऊस तोडणी वाहतुकीचे कारखान्याचे नियमाप्रमाणे होणारे बिल तुमचेसाठी आधीकारपत्र कारखान्यास लिहून दिलेले आहेत . तसेच कारखान्याकडून हि रक्क्म श्री . --------------------------------------------------- यांस तुमचे वतीने मिळावी त्यांनी ती घ्यावी व तुमचा ऊस तोडणी वाहतूक कराणारांना तुमचेसाठी व तुमचे वतीने दयावी म्हणून तुम्ही श्री . ----------------------------------------------------- यांना अलाहिदा अधिकार पत्र असलेला करार लिहून दिलेला आहे . चा योजनेप्रमाणे आम्ही तोडणी वाहतूक केलेल्या कामचाचे जे बिल करारात नमूद केलेल्या दारप्रमाणे होईल ते तमच्याकडून घेऊ .</p>
<p>7)(4) तुम्ही ऊस तोडणी वाहतुक केल्यामुळे देखरेखीसाठी प्लॉटमधील रस्तो दुरूस्ती व्रैरे कामासाठी कारखान्याकडून तुम्हांस दर मे . टनामागे काही रक्क्म मिळण्याची शक्यता आहे . त्यापैकी काही भाग तुमचे मर्जीप्रमाणे तुम्ही आम्हांस बक्षीस म्हणुन दयावा . बक्षीस मागण्याचा आम्हांस या कराराने हक्क्&zwj; निर्माण केलेला नाही . बक्षीस देणे आगर ना देणे तुमचे इच्छेवर अवलंबून राहील . ज्या ज्या ऊस उत्पादकाची अशी रक्क्म येईल तोच विचार करूण हंगाम आखेर प्रत्येक गाडीमागे अगर कोयत्यामागे अगर ऊस तोडणी वाहतुक केलेल्या प्रमाणात किती रक्क्म बक्षिस दयावयाची हे ठरविण्यात यावे . अशी बक्षीसाची जी रक्क्म ठरेल तीच घेण्यास आम्ही पात्र राहू . ठरवीण्याचा अधिकार संपर्ण तुचा ठेवलेला आहे .</p>
<p>7)(5) मी माझे सहभागीदार तुमचे ऊस तोडणी व वाहतुक करणार आहेत या कामची व्यवस्था लावणे ते सुरळभ्त चालू ठेवणे , कामाची बिले घेऊन सहभागीदारांना त्यांनी केलेल्या कामाच्या प्रमाणाचे बिलाची वाटणी करणे वगैरे जादा कामे मला करावी लागणार आहेत . त्यासाठी तुमच्या होणा - या ऊस तोडणी वाहतूकीचे बिलामधून तुम्ही मला ----- अक्क्े प्राणे माबदला अगर वाढीव तोडणी वाहतुक खर्च दयावयाचे आहे .</p>
<p>7)(6) तुमचे ऊस तोडणी सर्वसाधारणपणे अधुन मधून अशी हंगाम संपेपर्यंत चालणार आहे . कराराची हंगाम आखेर पावेतो ज्या ज्या वेळी तुम्हांस तो मिळेल त्या त्या वेळी आम्ही पुर्तता करू त्यासठी आमचे कामाचे पंधवडा बिलातून 10 टक्के प्रमाणे डिपॉझिट म्हणून रक्क्म तुम्ही कापून ठेवावी . हंगाम अखेर कराराची पूर्तता केल्यानंतर ही रक्क्म मिळणेस आम्ही पात्र होऊ हंगाम संपण्यापुर्वी अशी रक्क्म परत मिळण्यास आम्ही पात्र राहणार नाहीत .</p>
<p>7)(7) आम्हास ऊस तोडणी वाहतुकीसाठी लोगणारे टायरबैलगाडी , वायरोप , कोयते , जू तसेच राहण्यासाठी चटई , बांबू व इतर साहित्य हे आम्ही तुमच्या वतीने श्री अंबालिका शुगर प्रा . लि ., अंबिकानगर यांचेकडून लोनवर घ्यावयाचे आहे व काम संपलेनेतर त्यांना तुमच्यासाठी परत करावयाचे आहे असे न केल्यास श्री अंबालिका शुगर प्रा . लि ., अंबिकानगर ठरवील त्याप्रमाणे टायरबैलगाडी , कोयते , जू , वायरोप , चटई , बांबू इ . साहित्याचे नूकसान भरपाई आमचे बिलातून अगर डिपॉझिट रक्क्&zwj;मेतून वसूल करण्याचा अधिकार श्री अंबालिका शुगर प्रा . लि . अंबिकानगर यांना राहील , तशी अम्ही संमती देत आहेत . तुमचे वतीने ते नुकसान भरपाई आमचेकडून वसूल करतील .</p>
<p>8) माझे व भागीदाराचे होणारे बिलाचे पैसे वेळेवार बिनचूक आम्ही आपसात वाठून घेऊ त्याचा तपशील ठेऊ त्याबाबत कोणतीही तक्रार तुमचकडे येऊ देणार नाही . अशा तक्रारारीचे परस्प्र निवरन करु बिल घेतल्यानंतर परत पैसे देण्याची जबाबदारी तुमचेवर राहणार नाही त्यास मी एकटा जबाबदार राहील .</p>
<p>9) कराररची मुदत संपण्यापूर्वी मी व माझे सहभागीदार मध्येच काम सोडून गेल्याने तुमची होणारी नुकसान भरून देऊ आम्ही मध्येच काम टाकून दिल्यामुळे उशीरा होणारी ऊस तोडणी व गैरसोय , अन्य नुकसान यासाठी लिक्वीडेटेड डॅमेजेस म्हणून रक्क्म रू .------------------/- नुकसान भरपाई प्रत्यकास देऊ ती आपणास मला व माझे सहभागीदाराचे वाढीव तोडणी वाहतूक खर्च , डिपॉझीट व बिलाचे रक्कमेतुन परस्पर वसूल करून घेता येईल . तसेच वाढीव तोडणी वाहतूक खर्च व डिपॉझीटची रक्क्मही जप्त करता येईल . या</p>
<p>रक्कमा मला व अम्हाला मगण्याचा अधिकार राहणार नाही . तुमचेसाठी व वतीने श्री .-------------------------------------- यांना हक्क्&zwj; व अधिकार राहील .</p>
<p>10) आम्ही कराराचे मुदतीत तोडणी वाहतुक सुरू असताना प्लॉटमध्ये कायम हजर राहून देखरेख करू आमच्या देखरेखीखाली प्लॉटमधून नियमित टायरबैलगाडया , ट्रक , ट्रॅक्टर ट्रेलर ऊसाने भरून कारखन्याकडे पाठवून देऊ .</p>
<p>11) ऊसाची वाहतूक करीत असताना माझे व माझे सहभागीदारचे हलगर्जीपणामूळे तुमचे ऊसाचे नुकसान झाल्यास अगर रस्त्यात गाडया ट्रक ट्रॅक्टर ट्रेलर च्या घोटाळया मुळे ऊसाचे नुकसान झाल्यास त्या ऊसाची नुकसान भरपाई देऊ व नुकसान होणार नाही याची दक्षता घेऊ .</p>
<p>12) आमचे कडून तुमचे ऊसाचे अगर स्थळाचे शेती वरील ईतर उभ्या पिकाचे कोणत्याही प्रकारचे नुकसान झाल्यास ( उदा . बैलास उस पिक खाण्यास देणे , खाण्यास ऊस घरी नेणे वगैरे ) त्याची नुकसान भरपाई भरून देण्याची जबाबदारी अमचे वर राहील .</p>
<p>13) कराराच्या मुदतीत मला अगर सहभागीदारास अगर अमचे बरोबर काम करणारास काही अपघात वगैरे झाल्यास त्याची जबाबदारी संपुर्ण पणे अमचेवर राहील . त्याबाबत कोणत्याही त - हेची तोशीस तुम्हांस लागु देणार नाही . अशी नुकसान भरपाई मागण्याचा हक्क्&zwj; ठेवलेला नाही . अशी नुकसान भरपाई तुम्हांस यदाकदाचीत कायदयाने दयावी लागलीतर त्या सर्व रक्कमेची खर्चासह भरपाई तुम्हांस मी व माझे सहभागीदार भरून देऊ या कामाची आमची जबाबदारी जॉईंट व सेव्हरल रहिल .</p>
<p>14) वरील अटींवर दररोज&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;मे . टन ऊसाची तोड्णी वाहतुक करणेसाठी अ ) गाडी सेंटर&hellip;&hellip;&hellip; .. लेबर &hellip;&hellip;&hellip;&hellip;बैल ब ) डोकी सेंटर&hellip;&hellip;&hellip;&hellip;&hellip; .. लेबर क ) टायर बैलगाडी &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;लेबर&hellip;&hellip;&hellip;&hellip;&hellip;&hellip; . बैल हे कामावर हजर करणेसाठी व कामकरणेसाठी हा करारनाम मी माझे साठी व माझे सहभागीदारासाठी लिहूण देत आहे .</p>
<p>15) सदर करार माझेसाठी व माझे सहभागीदारांसाठी लिहूण दिलेला आहे . सहभागीदारांसाठी करार करून देण्याचा मला अधीकार आहे . तुम्हांस मिळालेल्या कोठयाप्रमाणे ऊसाची तोडणी करूण देऊ ऊस पोहचविण्याची जी वेळ तुम्ही ठरवून दयाल त्या वेळी ऊस कारखाण्यावर पोहचवून देऊ .</p>
<p>16) अचानक व अनपेक्षीत कराणाने कारखाना बंद राहिल्यास ज्या ज्या वेळी तुमचा ऊस पुरवठयाचा कोटा तहकूब होईल त्या त्या वेळी व तशा प्रसंगी आम्ही आमचे ऊस तोडणी वाहतूकीचे काम थांबवू . तुम्ही सांगाल त्यावेळी ऊस तोडणी वाहतूक परत चालू करू . काम बंद पडले म्हणून नुकसान भरपाई मागणार नाही . अशा प्रसंगी तुमचेकडून नुकसान घेण्याचा आम्हांस मुळीच हक्क्&zwj; ठेवलेला नाही .</p>
<p>17) कारखाण्याचे भूईकाटयावर भरतीचे व रिकामे वजन कारखान्याचे नियमाप्रमाणे करू वजनाच्या स्लिपा घेऊ त्यावर दाखविलेले वजन अखेरचे समजण्यात येईल . वजना संबंधी तक्रार करण्याचा अधीकार ठेवलेला नाही .</p>
<p>18) मी व माझे सहभागीदार तुमचे ऊसाची तोडणी चालू झाल्यानंतर मध्येच बंद करणार नाही . कोणत्याही कारणामुळे व सबबीखाली तुमची ऊस तोडणी वाहतूक आम्ही बंद केल्यास हा करार आम्ही मोडला असे समजण्यात येईल व ताबडतोब संपुष्टात येईल . अशा प्रसंगी तुम्हांस स्वता : अगर दुस - या करवी तुमच्या ऊसाची तोडणी वाहतुककरून घेता येईल . तसेच करारत नमूद केल्याप्रमाणे लिक्वीडेटेड डॅमेजेस वसूल करण्याचा तुम्हांस अधिकार राहील ते आमचे बिलातून जाम होणा - या डिपॉझीटचे व वाढीव तोडणी वाहतूक खर्चाचे होण - या रक्कमेमधुन तुम्ही परस्पर वसूल करावेत .</p>
<p>19) गळीत हंगामामध्ये आमचे वाहनाकडून ट्रक ट्रॅक्टर ट्रेलरमार्फत ऊस पुरवढा जादा झाल्यास आपण आम्हांस ऊस पुरवठा कमी करण्यास जे खाडे दयाल त्याबद्दल आम्ही आपणाकडे कोणत्याही प्रकारची तक्रार करणार नाही .</p>
<p class="sp2">येणे प्रमाणे करार स्वखुशीने लिहून दिला असे .</p>
<p>ठिकाण : अंबिकानगर </p>
<p>दिनांक :<b><?= $data['TXN_DATE'];?></b></p>
<p class="text-center">करार लिहून देणार</p>
    
    
<?php
		$oldfilter=array(':PPRT_CODE',':PCOMP_CODE');
		$newfilter=array($data['TXN_CONS'],$_SESSION['COMP_CODE']);
		$getAddrqry = $qryObj->fetchQuery($qryPath,'Q001','GET_CONTRACTADD',$oldfilter,$newfilter);
		$getAddrres = $dsbObj->getData($getAddrqry);

    
			//echo "<pre>";
//			print_r($getAddrres);
//			echo "<br>";
	?>	        

        
<div style="width: 99%;">
 <div style="float: left; width: 49.5%;" align="left">
     
    </div>
 <div style="float: right; width: 49.5%;" align="right">
    <p align="right" style="padding-right:3em;">
			फोटो	 
        <span style="padding-left:5em;">
			बोटाचा ठसा </span>
		</p>
</div>
 <br style="clear: left;" />
</div>            
        
<div style="width: 99%;">
 <div style="float: left; width: 49.5%;" align="left">
     <p align="left">
			सही	: 
		</p>
		<p align="left">
			नांव :  <b><?= $data['SUBCONTRACTOR_MNAME'];?></b>
		</p> 
        <p align="left">
            पत्ता<strong>: मु. पो. <?= $getAddrres[0]['VL_MNAME'];?>,  ता.<?= $getAddrres[0]['TLK_MNAME'];?>, जि.    <?= $getAddrres[0]['DT_MNAME'];?></strong>
        </p>
    </div>
 <div style="float: left; width: 49.5%;">
 <img src="attach/contracts/<?= $data['TXN_SRNO']?>/BFINGERPRINT.JPG" width="120" height="120" alt="Mukadam Fingerprint Photo" onerror="this.src = 'images/noimage.png';" class="img-responsive" align="right" />  
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 <span>
    <img src="attach/contracts/<?= $data['TXN_SRNO']?>/BPHOTO.JPG" width="120" height="120" alt="Mukadam Photo" onerror="this.src = 'images/noimage.png';" class="img-responsive" align="right"  />
 </span>
		
</div>
 <br style="clear: left;" />
</div>     
        
        
    
<p>साक्षीदार <strong> - </strong></p>
<p><strong> 1 &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;.. </strong> सही <strong> :&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip; </strong></p>
<p><strong> 2&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;.. </strong> सही <strong> :&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip; </strong></p>
    </div>    
    </body>
</html>