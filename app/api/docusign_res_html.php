<?
if($_GET['event'] == 'signing_complete'){
	echo '<div><h4 style="color:#000;padding:30px 0px 0px 30px;font-weight:600;font-size:20px;text-align:center;">Terms and condition is successfully signed. Continue registration</h4></div>';
	echo "<script>
	var event = new CustomEvent('custom:event', {bubbles: true, cancelable: true});
	window.parent.document.getElementById('docusFrame').parentNode.dispatchEvent(event);
	</script>";
}
?>