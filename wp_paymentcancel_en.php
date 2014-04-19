[php]
require_once("payment/config.php");
require_once("payment/connect.php");
if (isset($_REQUEST['ref'])) {
print ("The payment of your registration fees has been canceled (reference: <strong>".$_REQUEST['ref']."</strong>).
You are nevertheless registered for the conference: you can either complete your registration and payment later or choose another mean of payment.
If you are experiencing difficulties, please, fell free to contact: <a href='mailto:".$contact_email."'>".$contact_email."</a>.");
}
print("<center><a href='".$website_url_en."'>JdS main page</a></center>");
print("<center><a href='".$current_url_en."'>Registration form</a></center>");
[/php]