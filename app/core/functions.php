<?php
function send_m($clientEmail, $p, $name, $tracking_number, $alttext = '', $linkToReject)
{
    $mail = new PHPMailer;
    //  $mail->isSendmail();
    $mail->IsSMTP(); // telling the class to use SMTP
    $path = $_SERVER['DOCUMENT_ROOT'];
    $path .= "/credentials.php";
    require_once($path);
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->SMTPAuth = true;                  // enable SMTP authentication
    $mail->Host = MAIL_HOST;    // sets the SMTP server
    $mail->Port = MAIL_PORT;
    $mail->Username = MAIL_USER;    // SMTP account username
    $mail->Password = MAIL_PASS;        // SMTP account password

    $mail->isHTML(true);

    $mail->AddReplyTo("leads@energysmart.com.au", "New Qualified Lead");

    $mail->SetFrom('leads@energysmart.com.au', 'New Qualified Lead');

    $mail->AddAddress($clientEmail, $name);

    $mail->Subject = "New Qualified Lead - Please contact ASAP";

    $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";  // optional, comment out and test

    $content = <<<EOD
  <body>
  <style type="text/css">
  tr: {
    background: #f0f0f0; /* Цвет фона */
   } 
.ReadMsgBody { width: 100%; background-color: #ffffff; }
.ExternalClass { width: 100%; background-color: #ffffff; }
.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
html { width: 100%; }
body { -webkit-text-size-adjust: none; -ms-text-size-adjust: none; margin: 0; padding: 0; font-family: 'Open Sans', Arial, Sans-serif !important; }
table { border-spacing: 0; table-layout: fixed; margin: 0 auto; }
table table table { table-layout: auto; }
img { display: block !important; overflow: hidden !important; }
.yshortcuts a { border-bottom: none !important; }
a { color: #78d2f7; text-decoration: none; }
img:hover { opacity: 0.9 !important; }
.textbutton a { font-family: 'open sans', arial, sans-serif !important;}
.btn-link-1 a { color: #FFFFFF !important; text-decoration: none !important; font-weight: bold !important; }
.btn-link-2 a { color: #4A4A4A !important; text-decoration: none !important; font-weight: bold !important; }
.footer-link a { color:#A9A9A9 !important;}

/*Responsive*/
@media only screen and (max-width: 640px) {
body { width: auto !important; font-family: 'Open Sans', Arial, Sans-serif !important; }
table[class="table-inner"] { width: 90% !important; }
*.table-full { width: 100%!important; max-width: 100%!important; text-align: center !important; } 
/* image */

tr:nth-child(2) {
    background: red;
}
img[class="img1"] { width: 100% !important; height: auto !important; }
}

@media only screen and (max-width: 479px) {
body { width: auto !important; font-family: 'Open Sans', Arial, Sans-serif !important; }
table[class="table-inner"] { width: 90% !important; }
*.table-full { width: 100%!important; max-width: 100%!important; text-align: center !important; } 
td[class="td-hide"] { height: 0px !important; }
/* image */
img[class="img1"] { width: 100% !important; height: auto !important; }
img[class="img-hide"] { max-width: 0px !important; max-height: 0px !important; }
}
	.datagrid table { border-collapse: collapse; text-align: left; width: 100%; } .datagrid {font: background: #fff; overflow: hidden; }.datagrid table td , .datagrid table th { padding: 11px 5px; }.datagrid table tbody td { color: #595959; border-left: 2px solid #FFFFFF;font-size: 15px;font-weight: normal; }.datagrid table tbody .alt td { background: #F7F7F7; color: #595959; }.datagrid table tbody td:first-child { border-left: none; }.datagrid table tbody tr:last-child td { border-bottom: none; }
</style>
<body>  					 					  				 	 	 	 	 	 	 	 	 	 	
    <table data-module="1-1-panel-1" data-bgcolor="Main BG" bgcolor="#F8F8F8" align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
 		<tr>
 		   <td height="25"></td>
 		</tr>
 		<tr>
			<td align="center">
 				<table data-bgcolor="Boxed" bgcolor="#FFFFFF" style="max-width: 650px;" align="center" class="table-full" width="650" border="0" cellspacing="0" cellpadding="0">
 					<!--img-->
  					<tr>
 						<td align="center" style="line-height: 0px;">
 							<img data-crop="false" src="http://energysmart.com.au/email/new-lead/head.jpg" alt="img" width="650" height="175" class="img1" style="display:block; line-height:0px; font-size:0px; border:0px;">
 						</td>
 					</tr>
 					<!--end img-->
  					<tr>
 						<td data-border-left-color="Border" data-border-right-color="Border" data-border-bottom-color="Border" align="center" style="border-left:1px solid #ECECEC;border-right:1px solid #ECECEC;border-bottom:1px solid #ECECEC; border-bottom-right-radius:5px; border-bottom-left-radius:5px;">
 							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
 								<tr>
 									<td align="center">
 										<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
 											<tr>
 												<td height="20"></td>
 											</tr>
 											<!--title-->
  											<tr>
 												<td data-link-style="text-decoration:none; color:#4A4A4A;" data-link-color="Title Link" data-size="Title" data-color="Title" style="font-family: 'Century Gothic', Arial, sans-serif; color:#4A4A4A; font-size:20px;font-weight: bold;">
 													<singleline>Hi $name </singleline>
 												</td>
 											</tr>
 											<!--end title-->
  											<tr>
 												<td height="10"></td>
 											</tr>
 											<!--content-->
  											<tr>
											    <td data-link-style="text-decoration:none; color:#FF6363;" data-link-color="Content Link" data-size="Content" data-color="Content" style="font-family: 'Open Sans', Arial, sans-serif; color: rgb(61, 61, 61); font-size: 15px; font-weight: 400; line-height: 25.5px;" spellcheck="false" data-gramm_id="3ed6e441-9a5e-1b75-b8e6-1787df104970" data-gramm="true" data-gramm_editor="true">
													<p> <multiline>We have a new qualified lead in your area! Please see their information below, and contact them as soon as possible.</multiline></p>
EOD;

    $content .= '<div class="datagrid"><table style="width:100%;"><tbody>';
    $i = true;
    foreach ($p as $v) {

        if ($i) {
            $content .= '
             <tr class="alt" ><td style="width:50%;"><strong>' . $v["field_name"] . '</strong></td><td style="width:50%;">' . $v["val"] . '</td></tr>';
        } else {
            $content .= ' <tr class="alt" style=" background-color:#F7F7F7;" ><td style="width:50%;"><strong>' . $v["field_name"] . '</strong></td><td style="width:50%;">' . $v["val"] . '</td></tr>';
        }
        $i = !$i;
    }
    $content .= '</tbody></table></div>';
    $tracker = 'http://' . $_SERVER['HTTP_HOST'] . '/api/record?log=true&deliveryn=' . $tracking_number;
    $content .= '<img alt="" src="' . $tracker . '" width="1" height="1" border="0" />';

    $content .= <<<EOD
           <p>Please contact this lead immediately, as this customer is waiting to hear from you! If you can't get onto them, don't worry! Please attempt to call this customer at different times of the day (morning, day, and evening), as most people get home from work at 5:30-6pm! Be sure to make every effort to contact them.</p>
			    <p>If you feel as though this lead requires rejection, please do so via the Energy Smart Portal within the time frame by clicking the button below. </p>
				<p>Please make sure you reject this lead within the time frame, otherwise it will be automatically billable. </p>
				<p>The only way to reject a lead is though our <a href="http://leadpoint.energysmart.com.au/"><strong>Energy Smart portal</strong></a>. We're unable to reject leads for you.</p>
				<p>Good luck!</p></td>
										  </tr>
 										  <!--button-->
  										  <tr>
											  <td align="center">&nbsp;
											      <table data-bgcolor="Button" bgcolor="#2991d6" border="0" align="center" cellpadding="0" cellspacing="0" style="border-radius:2px;">
                                                        <tr>
 															<td data-link-style="text-decoration:none; color:#ffffff;" data-link-color="Button Link" data-size="Button" height="42" align="center" style="font-family: 'Open Sans', Arial, sans-serif; color:#FFFFFF; font-size:14px;font-weight: bold;letter-spacing: 1px;padding-left: 25px;padding-right: 25px;">
																<a href="http://leadpoint.energysmart.com.au"  style="text-decoration:none; color:#ffffff" data-color="Button Link"></a>
 																    <singleline><a href="http://leadpoint.energysmart.com.au" style="text-decoration:none; color:#ffffff"> 	VIEW THIS LEAD IN YOUR PORTAL</a>																	</singleline>
 															</td>
 														</tr>
 												   </table>
														    <p>&nbsp;</p>
											  </td>
 										  </tr>
 														<!--end button-->
									  </table>
								  </td>
 											</tr>
 										</table>
					  </td>
				  </tr>
			  </table>
		  </td>
  </tr>
 				</table>
 			</td>
 		</tr>
 	</table>
  			 		 	  			 		 	  			 		 	  			 		 	  			 		 	  			 		 	  			 		 	  			 		 	  			 		 	  <table data-thumb="http://www.stampready.net/dashboard/editor/user_uploads/zip_uploads/2017/07/25/LOAgERkSsqja14X8vtK7hpHe/StampReady/thumbnails/footer.jpg" data-module="footer" data-bgcolor="Main BG" align="center" width="100%" bgcolor="#F8F8F8" border="0" cellspacing="0" cellpadding="0">
     <tr>
       <td height="25">
</td>
     </tr>
  </body>          
EOD;

    $mail->msgHTML($content);
    $emailSending = "";
    if (!$mail->Send()) {
        echo $emailSending = "Mailer Error: " . $mail->ErrorInfo;
        return FALSE;
    } else {
//    echo $emailSending = "Message sent!";
        return TRUE;
    }
}

// check source, return ID
function getCampaignID($source)
{
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($con->connect_errno) {
        printf("Connect failed: %s\n", $con->connect_error);
        exit();
    }
    $sql = "SELECT * FROM `campaigns` WHERE source='$source'";
    if ($result = $con->query($sql)) {
        $id = $result->fetch_assoc();
        $result->num_rows;
        if ($result) {
            $con->close();
            return $id["id"];
        }
    }
    $con->close();
    return FALSE;
}

//phone number validation
function phone_valid($phone)
{
    $justNums = preg_replace("/[^0-9]/", '', $phone);
    return $justNums;
}

// only for Australian postcodes
function postcodes_valid($postcode)
{
    $valid_post = preg_match("/^(0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2})$/", $postcode);
    if ($valid_post) {
        return $postcode;
    } else {
        return FALSE;
    }
}


function prepareLeadInfo($p)
{
    $campaign_id = getCampaignID($p["source"]);
    $readyLeadInfo = array();
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($con->connect_errno) {
        printf("Connect failed: %s\n", $con->connect_error);
        exit();
    }

    // query to get fields from campaign
    $sql = 'SELECT lead_fields.id, lead_fields.key, lead_fields.name, campaign_lead_fields_rel.is_active, campaign_lead_fields_rel.mandatory';
    $sql .= ' FROM `campaign_lead_fields_rel`';
    $sql .= ' LEFT JOIN `lead_fields` ON lead_fields.id = campaign_lead_fields_rel.field_id';
    $sql .= ' WHERE campaign_lead_fields_rel.campaign_id =' . $campaign_id;
    $res = $con->query($sql);
    if ($res) {
        while ($res->fetch_assoc()) {
            foreach ($res as $r) {
                $key = $r["key"];
                if ($r["is_active"]) {
                    $preArray["key"] = $key;
                    $preArray["val"] = $p["$key"];
                    $preArray["field_name"] = $r["name"];
                    $readyLeadInfo[] = $preArray;
                    unset($preArray);
                }
            }
        }
    } else {
        echo "<br>Something WRONG<br>";
        return FALSE;
    }
    return $readyLeadInfo;
}

function formatReject($d)
{
    switch ($d) {
        case 0:
            return "Reject accepted";
            break;
        case 1:
            return "Approved";
            break;
        case 2:
            return "Requested to Reject";
            break;
        case 3:
            return "Reject not Approved";
            break;
        case 4:
            return "Requested More info";
        case 5:
            return "";
        default:
            return "";
    }
}

function redirect($location = '/')
{
    echo "<script>setTimeout(function() {
  window.location.href = '$location';
},5)</script>";
}

function dd($arg)
{
    echo "<pre>";
    var_dump($arg);
    die;
}