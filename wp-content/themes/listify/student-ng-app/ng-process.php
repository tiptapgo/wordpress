<?php
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$area = $_POST['area'];
if(isset($_POST['q2cat']))
	$q2cat = $_POST['q2cat'];
else
	$q2cat = "";

if(isset($_POST['q2inp']))
	$q2inp = $_POST['q2inp'];
else
	$q2inp = "";

if(isset($_POST['q3inp']))
	$q3inp = $_POST['q3inp'];
else
	$q2cat = "";

if(isset($_POST['q3cat']))
	$q3cat = $_POST['q3cat'];
else
	$q3cat = "";

if(isset($_POST['q4mon']))
	$q4mon = $_POST['q4mon'];
else
	$q2mon = "";

if(isset($_POST['q4tue']))
	$q4tue = $_POST['q4tue'];
else
	$q2tue = "";

if(isset($_POST['q4wed']))
	$q4wed = $_POST['q4wed'];
else
	$q2wed = "";

if(isset($_POST['q4thr']))
	$q4thr = $_POST['q4thr'];
else
	$q2thr = "";

if(isset($_POST['q4fri']))
	$q4fri = $_POST['q4fri'];
else
	$q2fri = "";

if(isset($_POST['q4sat']))
	$q4sat = $_POST['q4sat'];
else
	$q2sat = "";

if(isset($_POST['q4sun']))
	$q4sun = $_POST['q4sun'];
else
	$q4sun = "";

$s8 = $_POST['slider8'];
$s9 = $_POST['slider9'];
$q6 = $_POST['q6'];

if(isset($_POST['q7']))
	$q7 = $_POST['q7'];
else
	$q7 = "";

if(isset($_POST['q7inp']))
	$q7inp = $_POST['q7inp'];
else
	$q7inp = "";

if($q7 !="")
	$ans7 = $q7;
if($q7inp !="")
	$ans7 = $q7inp;

if(isset($_POST['q8inp']))
	$q8inp = $_POST['q8inp'];
else
	$q8inp = "";

$to = "help@tiptapgo.co";

$subject = "Become a Student Request Form";

$txt = "<!doctype html><html><head><title>".$subject."</title><style>body{padding: 30px; background-color: #F0F8FF !important; color: #3396d1; font-size: 18px;} strong{color: #2f3339;}h1{color: #3396d1}h2, h3{color: #72bb46;}</style></head><body style='background-color: #F0F8FF !important; color: #3396d1; font-size: 18px; padding: 30px;' bgcolor='#F0F8FF !important'> <h1 style='color: #3396d1;'>Become a Student Form Response</h1> <h2 style='color: #72bb46;'>1: What is your Basic Info</h2> <div><strong style='color: #2f3339;'>Name: </strong> ".$name."</div><br><div><strong style='color: #2f3339;'>Email: </strong>".$email."</div><br><div><strong style='color: #2f3339;'>Phone No: </strong>".$phone."</div><br><div><strong style='color: #2f3339;'>Area: </strong>".$area."</div><br><h2 style='color: #72bb46'>2: Category</h2> <div><strong style='color: #2f3339;'>Ans: </strong>".$q2cat."</div><h2 style='color: #72bb46'>3: Any specific topics</h2> <div><strong style='color: #2f3339;'>Ans: </strong>".$q2inp."</div><h2 style='color: #72bb46;'>4: Which grade are you in</h2> <div><strong style='color: #2f3339;'>Ans: </strong>".$q3cat."</div><h2 style='color: #72bb46;'>5: Have you done any specialization like Commerce, Computer Science</h2> <div><strong style='color: #2f3339;'>Ans: </strong>".$q3inp."</div><h2 style='color: #72bb46;'>6: When would you like to take classes?</h2> <div><strong style='color: #2f3339;'>Ans: </strong><br>Monday: ".$q4mon."<br>Tuesday: ".$q4tue."<br>Wednesday: ".$q4wed."<br>Thursday: ".$q4thr."<br>Friday: ".$q4fri."<br>Saturday: ".$q4sat."<br>Sunday: ".$q4sun."</div><h2 style='color: #72bb46;'>7: On a scale of 1-10 how much would you rate yourself in ".$q2cat."</h2> <div><strong style='color: #2f3339;'>Ans: </strong>".$s8."</div><h2 style='color: #72bb46;'>8: ... and where would you like to be after taking the classes?</h2><div><strong style='color: #2f3339;'>Ans: </strong>".$s9."</div><h2 style='color: #72bb46;'>9: Where would you like to take classes</h2> <div><strong style='color: #2f3339;'>Ans: </strong>".$q6."</div><h2 style='color: #72bb46;'>10: When would you like to start classes</h2> <div><strong style='color: #2f3339;'>Ans: </strong>".$ans7."</div><h2 style='color: #72bb46;'>11: Anything else you would like your future Tutor to know?</h2> <div><strong style='color: #2f3339;'>Ans: </strong>".$q8inp."</div></body></html>";
$headers = "From: no-reply@tiptapgo.co\r\n";
$headers .= "Reply-To: no-reply@tiptapgo.co\r\n";
$headers .= "CC: shivam@tiptapgo.co\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
mail($to,$subject,$txt,$headers);
?>