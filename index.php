<?php
	try {
		session_start();
	
		require_once('include/functions.php');
		global $db;
		if(isset($_GET['log_out'])) {
			session_destroy();
			resetCookies();
			unset($_SESSION['username']);
			//unset($_SESSION['token']);
			header('Location: ./?logged_out');
		}
		if(!isLoggedIn()) {
			if(isset($_GET['login'])) {
				$isAdmin = $db->isAdmin($_POST['login'], $_POST['password']);
				//print_r($isAdmin);
				if($isAdmin){	
					$_SESSION['username'] = $_POST['login'];
					header("Location: ./all_products.php?logged");	
				} else {
					header("Location: ./?not_logged");
				}
			}
		} else {
			header("Location: ./all_products.php");				
		}
	} catch (Exception $e) {
		print_r($e);
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Elektronika E-pood</title>
	
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
	
       
    <!--Pulling Awesome Font -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<!-- Google Fonts -->
	<link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
	<link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	</head>
	     <style type="text/css">
    .marketing{
      text-align: center;
      margin-bottom: 20px;
    } 
    .divider{
      margin: 80px 0;
    }
    hr{
      border: solid 1px #eee;
    }
    .thumbnail img{
      width: 100%;
    }
    .container {
    padding: 10px;
    
}

    .form-login {
    background-color: #EDEDED;
	margin-top: 15px;
    padding-top: 10px;
    padding-bottom: 20px;
    padding-left: 10px;
    padding-right: 10px;
    border-radius: 15px;
    border-color:#d2d2d2;
    border-width: 5px;
    box-shadow:0 1px 0 #cfcfcf;
}

    h4 { 
     border:0 solid #fff; 
     border-bottom-width:1px;
     padding-bottom:10px;
     text-align: center;
    }

    .form-control {
    border-radius: 10px;
}

    .wrapper {
	padding: 5px;
    text-align: center;
}
    </style>
	 <body style="
    background: url(assets/images/bg3.jpg) no-repeat center center fixed;
    background-size: cover;">
	 
		<div class="container" style="max-width: 55%;">
		<!-- <div class="jumbotron"> -->
				 <!-- Slide Show -->
		  <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style="max-width: 100%;">
			<!-- Indicators -->
			<ol class="carousel-indicators">
			  <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
			  <!-- <li data-target="#carousel-example-generic" data-slide-to="1"></li> -->
			  <li data-target="#carousel-example-generic" data-slide-to="1"></li>
			  <li data-target="#carousel-example-generic" data-slide-to="2"></li>
			</ol>

			<!-- Wrapper for slides -->
			<div class="carousel-inner" role="listbox">
			  <!-- <div class="item active">
				<img src="assets/images/fone/tv-remoe.jpg" alt="img1">
				<div class="carousel-caption">
				  <h3>Title Here 1</h3>
				  <p>span span span span span span span span span</p>
				</div>
			  </div> -->
			  <div class="item active">
				<img src="assets/images/fone/smartwatch.jpg" alt="img1">
				<div class="carousel-caption">
				  <h3 class="katNames">Hi-tech devices</h3>
				  <!-- <p>span span span span span span span span span</p> -->
				</div>
			  </div>
			  <div class="item">
				<img src="assets/images/fone/nvidia-shield-android-tv.jpg" alt="img1">
				<div class="carousel-caption">
				  <h3 class="katNames">Game сonsols</h3>
				  <!-- <p>span span span span span span span span span</p> -->
				</div>
			  </div>
			  <div class="item">
				<img src="assets/images/fone/iphone-4s.jpg" alt="img1">
				<div class="carousel-caption">
				  <h3 class="katNames">Mobile phones</h3>
				  <!-- <p>span span span span span span span span span</p> -->
				</div>
			  </div>
			</div>
			<!-- Controls -->
			<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
			  <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			  <span class="sr-only">Previous</span>
			</a>
			<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
			  <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			  <span class="sr-only">Next</span>
			</a>
		  </div>
		  
			
			  <div class="row">
				<div class="col-md-3 col-lg-3">
				
				</div>
				<div class="col-md-6 col-lg-6">
					<form class="form-login" method="POST" action="./?login">
						<h4>Sign In</h4>
						<?php if(isset($_GET['not_logged'])) { ?>
							<div class="alert alert-danger">
								<strong>Incorrect login information.</strong>
							</div>
						<?php } ?>
						<div class="input-group margin-bottom-sm logInput"> 
							<span class="input-group-addon"><i class="fa fa-user"></i></span> 
							<input class="form-control" name="login" type="text" placeholder="Username">
						</div> 
						<div class="input-group logInput"> 
							<span class="input-group-addon"><i class="fa fa-check-square"></i></span> 
							<input class="form-control" name="password" type="password" placeholder="Password"> 
						</div>
						<div class="wrapper">
							<span class="group-btn">     
								<button type="submit" class="btn btn-primary btn-md">Login <i class="fa fa-sign-in"></i></button>
							</span>
						</div>
						
						</div>
					</form>
				
				</div>
				
				<div class="col-md-3 col-lg-3">
				
				</div>
			</div>
			<div class="footer">
					Elektronika e-pood Tallinn University of Technology
					&copy Created by Artur Lipin <b>t120949</b>,Aleksei Fjodorov <b>t131055</b>
						
			</div>
			<!-- </div> -->
		</div>
	 </body>
	<script type="text/javascript" src="assets/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="assets/js/jquery.form.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="assets/js/bootstrap.min.js"></script>
</html>