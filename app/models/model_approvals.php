<?php

class Model_Approvals extends Model
{

    public function getLeadSources()
    {
        $con = $this->db();
        $LeadSources = array();
        $sql = "SELECT `source`, `name` FROM `campaigns`";
        $res = $con->query($sql);
        while ($row = $res->fetch_assoc()) {
            $LeadSources[] = $row;
        }
        return $LeadSources;
    }

    public function deleteFile()
    {
        $con = $this->db();
        $filepath = $_POST['path'];
        //return var_dump($_POST);
        $sql = "UPDATE leads_rejection SET audiofile=NULL WHERE audiofile='$filepath'";
        if (is_file($filepath)) {
            unlink($filepath);
        }
        $res = $con->query($sql);
        return $sql;
    }

    public function selectAllDataOfDeclinedLead($leadId, $clientId)
    {
        $con = $this->db();
        $sql = "SELECT ler.address, ler.full_name as 'cliName', ler.email as 'cliEmail', ler.phone, rej.reason, rej.note, rej.decline_reason, rej.audiofile, cli.full_name as 'usName', cli.email as 'usEmail' from leads_rejection rej left join leads_lead_fields_rel ler on rej.lead_id=ler.id left join clients cli on rej.client_id=cli.id where rej.lead_id='" . $leadId . "' and rej.client_id='" . $clientId . "'";
        $res = $con->query($sql);
        if ($res) {
            $result = $res->fetch_assoc();
            return $result;
        }
        return false;
    }

    public function sendEmail($data)
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

        $mail->AddReplyTo("leads@energysmart.com.au", "Leadpoint CRM");

        $mail->SetFrom('leads@energysmart.com.au', 'Leadpoint CRM');

        $mail->AddAddress($data["usEmail"], $data["usName"]);

        $mail->Subject = "One of your rejection leads is declined by Leadpoint admin";

        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";  // optional, comment out and test


        $content .= '<style type="text/css">
.ReadMsgBody { width: 100%; background-color: #ffffff; }
.ExternalClass { width: 100%; background-color: #ffffff; }
.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
html { width: 100%; }
body { -webkit-text-size-adjust: none; -ms-text-size-adjust: none; margin: 0; padding: 0; font-family: \'Open Sans\', Arial, Sans-serif !important; }
table { border-spacing: 0; table-layout: fixed; margin: 0 auto; }
table table table { table-layout: auto; }
img { display: block !important; overflow: hidden !important; }
.yshortcuts a { border-bottom: none !important; }
a { color: #78d2f7; text-decoration: none; }
img:hover { opacity: 0.9 !important; }
.textbutton a { font-family: \'open sans\', arial, sans-serif !important;}
.btn-link-1 a { color: #FFFFFF !important; text-decoration: none !important; font-weight: bold !important; }
.btn-link-2 a { color: #4A4A4A !important; text-decoration: none !important; font-weight: bold !important; }
.footer-link a { color:#A9A9A9 !important;}

/*Responsive*/
@media only screen and (max-width: 640px) {
body { width: auto !important; font-family: \'Open Sans\', Arial, Sans-serif !important; }
table[class="table-inner"] { width: 90% !important; }
*.table-full { width: 100%!important; max-width: 100%!important; text-align: center !important; } 
/* image */
img[class="img1"] { width: 100% !important; height: auto !important; }
}

@media only screen and (max-width: 479px) {
body { width: auto !important; font-family: \'Open Sans\', Arial, Sans-serif !important; }
table[class="table-inner"] { width: 90% !important; }
*.table-full { width: 100%!important; max-width: 100%!important; text-align: center !important; } 
td[class="td-hide"] { height: 0px !important; }
/* image */
img[class="img1"] { width: 100% !important; height: auto !important; }
img[class="img-hide"] { max-width: 0px !important; max-height: 0px !important; }
}
	.datagrid table { border-collapse: collapse; text-align: left; width: 100%; } .datagrid {font: background: #fff; overflow: hidden; }.datagrid table td , .datagrid table th { padding: 11px 5px; }.datagrid table tbody td { color: #595959; border-left: 2px solid #FFFFFF;font-size: 15px;font-weight: normal; }.datagrid table tbody .alt td { background: #F7F7F7; color: #595959; }.datagrid table tbody td:first-child { border-left: none; }.datagrid table tbody tr:last-child td { border-bottom: none; }
       </style>
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
 												<td height="20">
 												</td>
 											</tr>
 											<!--title-->
  											<tr>
 												<td data-link-style="text-decoration:none; color:#4A4A4A;" data-link-color="Title Link" data-size="Title" data-color="Title" style="font-family: \'Century Gothic\', Arial, sans-serif; color:#4A4A4A; font-size:20px;font-weight: bold;">
 													<singleline>Hi John.</singleline>
 												</td>
 											</tr>
 											<!--end title-->
  											<tr>
 												<td height="10"></td>
 											</tr>
 											<!--content-->
  											<tr>
											  <td data-link-style="text-decoration:none; color:#FF6363;" data-link-color="Content Link" data-size="Content" data-color="Content" style="font-family: \'Open Sans\', Arial, sans-serif; color: rgb(61, 61, 61); font-size: 15px; font-weight: 400; line-height: 25.5px;" spellcheck="false" data-gramm_id="3ed6e441-9a5e-1b75-b8e6-1787df104970" data-gramm="true" data-gramm_editor="true">
													<p> <multiline>We had to decline your rejection request. We\'ve spoken with the customer and here is the information they have provided us with.</multiline></p>
													<div class="datagrid">
													    <table>
															<tbody>
																<tr style = "height:40px;">
																	<td style = "width: 21%; height:30px;"><strong>Address</strong></td>
																	<td style = "width: 79%;">' . $data['address'] . '</td>
																</tr>
																<tr class="alt" style = "background-color:#F7F7F7;" >
																	<td style = "width: 21%;"><p><strong>Client\'s name<u></u><u></u></strong></p></td>
																	<td style = "width: 79%;">' . $data['cliName'] . '</td>
																</tr>
																<tr >
																	<td style = "width: 21%;"><p><strong>Client\'s email<u></u><u></u></strong></p></td>
																	<td style = "width: 79%;">' . $data['cliEmail'] . '</td>
																</tr>
																<tr class="alt" style = "background-color:#F7F7F7;">
																	<td  style = "width: 21%;"><p><strong>Client\'s phone<u></u><u></u></strong></p></td>
																	<td style = "width: 79%;">' . $data['phone'] . '</td>
																</tr>
																<tr >
																	<td style = "width: 21%;"><strong>Declining reason</strong></td>
																	<td style = "width: 79%;">  ' . $data['note'] . ' </td>
																</tr>
																<tr class="alt" style = "background-color:#F7F7F7;">
																	<td style = "width: 21%;"><strong>Details of rejection <u></u><u></u></strong></td>
																	<td style = "width: 79%;">' . $data['reason'] . '</td>
																</tr>
																<tr >
																	<td><strong>  Rejection disapproval reason</strong></td>
																	<td>   ' . $data['decline_reason'] . '    </td>
																<!--<td><p><strong>Date and time:</strong> 21/7/17, 11:18 AM. </p>
																	<p><strong>What happened? </strong>Customer advised they are not in a caravan park but a home park, they all my details and i want a quote. </p>
																	<p><strong>Why is this?</strong> Customer wants to find out his facts first</p>
																	<p><strong>Interested in the next 6 months?</strong> Yes</p>
																	<p><strong>Interested in an installer calling you?:</strong> yes. </p>
																	<p><strong>If yes, what time of day is best for a call?:</strong> Customer could say a specific time.</p>
																	<p><strong>If no, why?:</strong> Yes</p></td>-->
																	</tr>
															</tbody>
														</table>
													</div>
												    <p>&nbsp;</p>
													</td>
										    </tr>
 														<!--button-->
  											<tr>
												<td align="center">&nbsp;
													<table data-bgcolor="Button" bgcolor="#2991d6" border="0" align="center" cellpadding="0" cellspacing="0" style="border-radius:2px; cellpadding:0; cellspacing:0; border:0; ">
														<tr \'Open Sans\', Arial, sans-serif; color:#FFFFFF; font-size:14px;font-weight: bold;letter-spacing: 1px;padding-left: 25px;padding-right: 25px;">
																 <singleline>	<a href="http://leadpoint.energysmart.com.au" style="text-decoration:none; color:#ffffff; padding:10px; height:20px; display:block;">VIEW THIS LEAD IN YOUR PORTAL</a></singleline>
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
     </tr>';

        if ($data['audiofile']) {
            $content .= '<h3>You also have an audio attachment for this lead</h3>';
        }
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

    public function getApprovals()
    {
        //$memcache = new Memcache;
        //$memcache->connect(__HOST__, 11211) or die ("Не могу подключиться");
        $con = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8;', DB_USER, DB_PASS);
        $stamp = strtotime('-1 month');
        $sql = "select `a`.`lead_id`, `c`.`campaign_name`,
 DATE_FORMAT(FROM_UNIXTIME(`ld`.`timedate`), \"%e.%m.%Y %h:%i:%s %p\" ),
 DATE_FORMAT(FROM_UNIXTIME(`a`.`date`), \"%e.%m.%Y %h:%i:%s %p\" ),
  `a`.`reason`,`a`.`note`, `a`.`decline_reason`,`a`.`approval`,
  `a`.`audiofile`, `a`.`id`,`a`.`client_id`,
   (select seen from lead_conversations where lead_id=a.id limit 1) as seen
    FROM `leads_rejection` AS `a` INNER JOIN `leads_delivery` as `ld` ON
     (`a`.`id`=`ld`.`id` ) INNER JOIN clients as c ON a.client_id=c.id
      where `a`.`approval` != 1 AND `ld`.`timedate`>'" . $stamp . "'";
        $statement = $con->prepare($sql);
        if ($statement->execute()) {
            $result = $statement->fetchAll(PDO::FETCH_NUM);
            return $result;
        }
        return false;
    }
}