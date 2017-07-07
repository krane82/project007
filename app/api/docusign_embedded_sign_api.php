<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
    // DocuSign account credentials & Integrator Key
$username = "safonovam23@gmail.com";
$password = "230186";
$integrator_key = "a2df9b3b-a6ab-493b-8af8-d9902ca0a0a9";
$host = "https://demo.docusign.net/restapi";
    // create a new DocuSign configuration and assign host and header(s)
$config = new DocuSign\eSign\Configuration();
$config->setHost($host);
$config->addDefaultHeader("X-DocuSign-Authentication", "{\"Username\":\"" . $username . "\",\"Password\":\"" . $password . "\",\"IntegratorKey\":\"" . $integrator_key . "\"}");
    /////////////////////////////////////////////////////////////////////////
    // STEP 1:  Login() API
    /////////////////////////////////////////////////////////////////////////
    // instantiate a new docusign api client
$apiClient = new DocuSign\eSign\ApiClient($config);
    // we will first make the Login() call which exists in the AuthenticationApi...
$authenticationApi = new DocuSign\eSign\Api\AuthenticationApi($apiClient);
    // optional login parameters
$options = new \DocuSign\eSign\Api\AuthenticationApi\LoginOptions();
    // call the login() API
$loginInformation = $authenticationApi->login($options);
    // parse the login results
if(isset($loginInformation) && count($loginInformation) > 0)
{
        // note: defaulting to first account found, user might be a 
        // member of multiple accounts
    $loginAccount = $loginInformation->getLoginAccounts()[0];
    if(isset($loginInformation))
    {
        $accountId = $loginAccount->getAccountId();
            // if(!empty($accountId))
            // {
            //     echo "Account ID = $accountId\n";
            // }
    }
}

    // *** This snippet is from file 010.webhook_lib.php ***
// The envelope request includes a signer-recipient and their tabs object,
// and an eventNotification object which sets the parameters for
// webhook notifications to us from the DocuSign platform    
$envelope_events = [
(new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("sent"),
(new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("delivered"),
(new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("completed"),
(new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("declined"),
(new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("voided"),
(new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("sent"),
(new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("sent")
];

$recipient_events = [
(new \DocuSign\eSign\Model\RecipientEvent())->setRecipientEventStatusCode("Sent"),
(new \DocuSign\eSign\Model\RecipientEvent())->setRecipientEventStatusCode("Delivered"),
(new \DocuSign\eSign\Model\RecipientEvent())->setRecipientEventStatusCode("Completed"),
(new \DocuSign\eSign\Model\RecipientEvent())->setRecipientEventStatusCode("Declined"),
(new \DocuSign\eSign\Model\RecipientEvent())->setRecipientEventStatusCode("AuthenticationFailed"),
(new \DocuSign\eSign\Model\RecipientEvent())->setRecipientEventStatusCode("AutoResponded")
];

$event_notification = new \DocuSign\eSign\Model\EventNotification();
$event_notification->setUrl('https://'.$_SERVER['HTTP_HOST'].'/app/api/docusign_res.php');
$event_notification->setLoggingEnabled("true");
$event_notification->setRequireAcknowledgment("true");
$event_notification->setUseSoapInterface("false");
$event_notification->setIncludeCertificateWithSoap("false");
$event_notification->setSignMessageWithX509Cert("false");
$event_notification->setIncludeDocuments("true");
$event_notification->setIncludeEnvelopeVoidReason("true");
$event_notification->setIncludeTimeZone("true");
$event_notification->setIncludeSenderAccountAsCustomField("true");
$event_notification->setIncludeDocumentFields("true");
$event_notification->setIncludeCertificateOfCompletion("true");
$event_notification->setEnvelopeEvents($envelope_events);
$event_notification->setRecipientEvents($recipient_events);


    /////////////////////////////////////////////////////////////////////////
    // STEP 2:  Create & Send Envelope with Embedded Recipient
    /////////////////////////////////////////////////////////////////////////
    // set recipient information
$recipientName = $_POST['recipientName'];
$recipientEmail = $_POST['recipientEmail'];
    // configure the document we want signed
$documentFileName = $_SERVER['DOCUMENT_ROOT']."/docs/terms/terms.pdf";
$documentName = "terms.pdf";
    // instantiate a new envelopeApi object
$envelopeApi = new DocuSign\eSign\Api\EnvelopesApi($apiClient);
    // Add a document to the envelope
$document = new DocuSign\eSign\Model\Document();
$document->setDocumentBase64(base64_encode(file_get_contents($documentFileName)));
$document->setName($documentName);
$document->setDocumentId("1");
    // Create a |SignHere| tab somewhere on the document for the recipient to sign
$signHere = new \DocuSign\eSign\Model\SignHere();
$signHere->setXPosition("500");
$signHere->setYPosition("500");
$signHere->setDocumentId("1");
$signHere->setPageNumber("2");
$signHere->setRecipientId("1");
    // add the signature tab to the envelope's list of tabs
$tabs = new DocuSign\eSign\Model\Tabs();
$tabs->setSignHereTabs(array($signHere));
    // add a signer to the envelope
$signer = new \DocuSign\eSign\Model\Signer();
$signer->setEmail($recipientEmail);
$signer->setName($recipientName);
$signer->setRecipientId("1");
$signer->setTabs($tabs);
$signer->setClientUserId("1234");  // must set this to embed the recipient!
// Add a recipient to sign the document
$recipients = new DocuSign\eSign\Model\Recipients();
$recipients->setSigners(array($signer));
$envelop_definition = new DocuSign\eSign\Model\EnvelopeDefinition();
$envelop_definition->setEmailSubject("[DocuSign PHP SDK] - Please sign this doc");
// set envelope status to "sent" to immediately send the signature request
$envelop_definition->setEventNotification($event_notification);
$envelop_definition->setStatus("sent");
$envelop_definition->setRecipients($recipients);
$envelop_definition->setDocuments(array($document));
// create and send the envelope! (aka signature request)
$envelop_summary = $envelopeApi->createEnvelope($accountId, $envelop_definition, null);
// echo "$envelop_summary\n";
/////////////////////////////////////////////////////////////////////////
// STEP 3:  Request Recipient View (aka signing URL)
/////////////////////////////////////////////////////////////////////////
// instantiate a RecipientViewRequest object
$recipient_view_request = new \DocuSign\eSign\Model\RecipientViewRequest();
// set where the recipient is re-directed once they are done signing
$recipient_view_request->setReturnUrl('http://'.$_SERVER['HTTP_HOST'].'/app/api/docusign_res_html.php');
// configure the embedded signer 
$recipient_view_request->setUserName($recipientName);
$recipient_view_request->setEmail($recipientEmail);
// must reference the same clientUserId that was set for the recipient when they 
// were added to the envelope in step 2
$recipient_view_request->setClientUserId("1234");
// used to indicate on the certificate of completion how the user authenticated
$recipient_view_request->setAuthenticationMethod("email");
print_r($recipient);
// generate the recipient view! (aka embedded signing URL)
$signingView = $envelopeApi->createRecipientView($accountId, $envelop_summary->getEnvelopeId(), $recipient_view_request);
// echo "Signing URL = " . $signingView->getUrl() . "\n";
$signingUrl = $signingView->getUrl();
echo $signingUrl;
    ?>