[php]
require_once("payment/config.php");
require_once("payment/connect.php");
if (isset($_REQUEST['ref']) && isset($_REQUEST['res'])) {
if ($_REQUEST['res']==1) {
print ("Thank you for your registration.
Your payment is registered (reference: <strong>".$_REQUEST['ref']."</strong>) and a confirmation email has been sent to your address.");
} else {
print ("Your payment has been canceled (reference: <strong>".$_REQUEST['ref']."</strong>).
You will receive an email with the details of your registration: please submit your registration again. You can either try to pay by credit card or choose another means of payment.<br>
If you are experiencing difficulties, please feel free to contact us at: <a href='mailto:".$contact_email."'>".$contact_email."</a>.");
}
}
print("<center><a href='".$website_url_en."'>JdS main page</a></center>");
print("<center><a href='".$current_url_en."'>Registration form</a></center>");
[/php]