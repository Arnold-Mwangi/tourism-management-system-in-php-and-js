<?php
session_start();
error_reporting(0);
include('includes/config.php');
include  'includes/functions.php';
//  $_SESSION['login'];
if(isset($_POST['submit2']))
{
	$pid=intval($_GET['pkgid']);
	$useremail=$_SESSION['login'];
	$fromdate=$_POST['fromdate'];
	$todate=$_POST['todate'];
	$comment=$_POST['comment'];
	$status=0;
	$sql="INSERT INTO tblbooking(PackageId,UserEmail,FromDate,ToDate,Comment,status) VALUES(:pid,:useremail,:fromdate,:todate,:comment,:status)";
	$query = $dbh->prepare($sql);
	$query->bindParam(':pid',$pid,PDO::PARAM_STR);
	$query->bindParam(':useremail',$useremail,PDO::PARAM_STR); 
	$query->bindParam(':fromdate',$fromdate,PDO::PARAM_STR);
	$query->bindParam(':todate',$todate,PDO::PARAM_STR);
	$query->bindParam(':comment',$comment,PDO::PARAM_STR);
	$query->bindParam(':status',$status,PDO::PARAM_STR);
	
	if(functions::checkIfExists()){
	  echo '
		<script>
			alert("It seems you have already booked this package, please choose another one or go to your packages to cancel and try again")
			window.location="package-list.php";
		</script>
	  ';
	}else{
	
		if($query->execute()){
			echo functions::mpesaLogs();
			if(functions::sendEmail()){
				echo functions::emailLogs();
			}
		}
	}
		
	$lastInsertId = $dbh->lastInsertId();
		
			if($lastInsertId)
			{

				$msg="Booked Successfullyc check your email";
			
				
				
			}
			else 
			{
			$error="Something went wrong. Please try again";
			}
		

}
?>
<?php

?>
<!DOCTYPE HTML>
<html>
<head>
<title>TMS | Package Details</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="applijewelleryion/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
<link href="css/font-awesome.css" rel="stylesheet">
<!-- Custom Theme files -->
<script src="js/jquery-1.12.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!--animate-->
<link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
<script src="js/wow.min.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css" />
	<script>
		 new WOW().init();
	</script>

	  <style>
		.errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
		</style>				
</head>
<body>
<!-- top-header -->
<?php include('includes/header.php');?>
<div class="banner-3">
	<div class="container">
		<h1 class="wow zoomIn animated animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;"> TMS -Package Details</h1>
	</div>
</div>
<!--- /banner ---->
<!--- selectroom ---->
<div class="selectroom">
	<div class="container">	
		  <?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
<?php 
$pid=intval($_GET['pkgid']);
$sql = "SELECT * from tbltourpackages where PackageId=:pid";
$query = $dbh->prepare($sql);
$query -> bindParam(':pid', $pid, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{	?>


<form name="book" id="book" method="post">
		<div class="selectroom_top">
			<div class="col-md-4 selectroom_left wow fadeInLeft animated" data-wow-delay=".5s">
				<img src="admin/pacakgeimages/<?php echo htmlentities($result->PackageImage);?>" class="img-responsive" alt="">
			</div>
			<div class="col-md-8 selectroom_right wow fadeInRight animated" data-wow-delay=".5s">
				<h2><?php echo htmlentities($result->PackageName);?></h2>
				<p class="dow">#PKG-<?php echo htmlentities($result->PackageId);?></p>
				<p><b>Package Type :</b> <?php echo htmlentities($result->PackageType);?></p>
				<p><b>Package Location :</b> <?php echo htmlentities($result->PackageLocation);?></p>
					<p><b>Features</b> <?php echo htmlentities($result->PackageFetures);?></p>
					<div class="ban-bottom">
				<div class="bnr-right">
				<label class="inputLabel">From</label>
				<input id="demo" type="date"  max="2022-10-31" name="fromdate" required="">
				
				<label class="inputLabel">Adults <br>(above 12 yrs of Age)</label>
				<input name="tb1"   onkeyup="calculateTotal()"style="width:50%"id="myNumber" max="10" min="1"type="text" placeholder="Enter number of Adults" required="">
			</div>
			<div class="bnr-right">
			<script type="text/javascript">

function calculateTotal() {
	
	var totalAmt = document.book.total.value;
	totalR = eval(totalAmt * document.book.tb1.value);
	
	document.getElementById('update').innerHTML = totalR;
}

</script>
				<label class="inputLabel">To</label>
				<input id="demo1" type="date"  max="2022-10-31"name="todate" required="">
				<label class="inputLabel">Children <br>(below 12 yrs of Age)</label>
				<input style="width:50%" type="text" max="3" min="0"  required placeholder="Enter number of children">
			
			</div>
			</div>
						<div class="clearfix"></div>

				<div class="grand">
				
				<label class="inputLabel">Total(Ksh)</label><br>
				<span id="update"><span>Ksh.</span>20000 </span>
				<input type="hidden" name="total" value="20000" />
				
					
					
				</div>
			</div>
		<h3>Package Details</h3>
				<p style="padding-top: 1%"><?php echo htmlentities($result->PackageDetails);?> </p>	
				<div class="clearfix"></div>
		</div>
		<div class="selectroom_top">
			<h2>Travels</h2>
			<div class="selectroom-info animated wow fadeInUp animated" data-wow-duration="1200ms" data-wow-delay="500ms" style="visibility: visible; animation-duration: 1200ms; animation-delay: 500ms; animation-name: fadeInUp; margin-top: -70px">
				<ul>
				
					<li class="spe">
						<label class="inputLabel">Comment</label>
						<input class="special" type="text" name="comment" required="">
					</li>
					<?php if($_SESSION['login'])
					{?>
						<li class="spe" align="center">
					<button type="submit" name="submit2" class="btn-primary btn">Book</button>
						</li>
						<?php } else {?>
							<li class="sigi" align="center" style="margin-top: 1%">
							<a href="#" data-toggle="modal" data-target="#myModal4" class="btn-primary btn" > Book</a></li>
							<?php } ?>
					<div class="clearfix"></div>
				</ul>
			</div>
			
		</div>
		</form>
<?php }} ?>


	</div>
</div>
<!--- /selectroom ---->
<<!--- /footer-top ---->
<?php include('includes/footer.php');?>
<!-- signup -->
<?php include('includes/signup.php');?>			
<!-- //signu -->
<!-- signin -->
<?php include('includes/signin.php');?>			
<!-- //signin -->
<!-- write us -->
<?php include('includes/write-us.php');?>
<script>

        // Use Javascript
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0 so need to add 1 to make it 1!
var yyyy = today.getFullYear();
if(dd<10){
  dd='0'+dd
} 
if(mm<10){
  mm='0'+mm
} 

today = yyyy+'-'+mm+'-'+dd;
document.getElementById("demo").setAttribute("min", today);
document.getElementById("demo1").setAttribute("min", today);
//total



</script>


</body>
</html>