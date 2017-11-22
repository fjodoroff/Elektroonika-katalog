<?php
	try {
		session_start();
		require_once('include/functions.php');
		global $db;
		if(!isLoggedIn()){
			header('Location: ./');
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
    <title>Elektroonika e-pood</title>
	
	<!-- Owner style-->
	<!--Font Awesome CDN-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link href="assets/css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
    .marketing{
	
      text-align: center;
      margin-bottom: 20px;
	  padding-top:70px;
	  
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
	.navbar {
		border-radius: 0;
	}
	.categories .category.selected{
		border: 1px solid #ccc;
		height: 300px;
		background: #EEEEEE;		
	}
	.products .product{
		padding: 5px;
		width: 20%;
	}
	.products .product.status-3{
		background: #DDDDDD;
	}
	.products .product.status-2{
		background: #dca7a7;
	}
	.products .product.status-1{
		background: #D2EAC9;
	}
	.ui-autocomplete {
		z-index: 9999;
	}
	/*
	.products {
		padding: 48px;
		position: relative;
		text-align: center;
		margin: 0 auto;
		display: block;
	}
	*/
    </style>
  </head>
  <body>
  <?php printMenu() ?>
	  
<?php
if(isset($_GET['logged'])) {
	?>
		<div class="alert alert-success alert-dismissible fade in" role="alert"> 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> <strong>Success!</strong> You logged as <?php echo $_SESSION['username']; ?></div>
	<?php
}
echo '<div class="row marketing categories">';
foreach($categorys = $db->get_all_categories() as $category) {
	?>
	  <div class="col-md-3 col-sm-4 col-xs-12 category" data-category="<?php echo $category['kauba_kategooria_kood']; ?>">
		 <!-- <img src="assets/images/194211.jpg" alt="marketing01" class="img-polaroid">  -->
		<img src="<?php 
			$img1 = "assets/images/thumb253.jpg";
			if($category['kauba_kategooria_kood']==1) {
				$img1 = "assets/images/watchs.JPG";
			}
			if($category['kauba_kategooria_kood']==2) {
				$img1 = "assets/images/1_26.JPG";
			}
			if($category['kauba_kategooria_kood']==3) {
				$img1 = "assets/images/tv.jpg";
			}
			if($category['kauba_kategooria_kood']==4) {
				$img1 = "assets/images/194211.jpg";
			}
			
						
			echo $img1;
		?>" alt="marketing01" class="img-polaroid">
		<h3><?php echo $category['nimetus']; ?></h3>
		<p><?php echo $category['kirjeldus']; ?></p>
	  </div>		
	<?php	
}
echo '</div>';
echo '<div class="products-holder">';
echo '<div class="products" style="padding:48px;">';
foreach($products = $db->get_all_products() as $product) {		
	?>
	<div class="product status-<?php echo $product['kauba_seisundi_liik_kood']; ?>" data-category="<?php echo $product['kauba_kategooria_kood']; ?>">
	  <div class="thumbnail">
		<img src="<?php 
			$img = "assets/images/thumb253.jpg";
			if(is_file("./uploads/kaup_id_" . $product['kaup_id'] . ".jpg")) {
				$img = "./uploads/kaup_id_" . $product['kaup_id'] . ".jpg";
			}
			if(is_file("./uploads/kaup_id_" . $product['kaup_id'] . ".png")) {
				$img = "./uploads/kaup_id_" . $product['kaup_id'] . ".png";
			}
			echo $img;
		?>" alt="thumb01">
		<div class="caption">
		  <h3><?php echo $product['nimetus']; ?></h3>
		  <p><?php echo $product['hetke_hind'].""; ?> <i class="fa fa-eur"></i></p>
		  <p>
			  <a href="./product.php?pid=<?php echo $product['kaup_id']; ?>&edit" class="btn btn-primary" role="button">Edit</a>
			  <a href="./product.php?pid=<?php echo $product['kaup_id']; ?>&watch" class="btn btn-default" role="button">View</a>
			  <a href="./product.php?pid=<?php echo $product['kaup_id']; ?>&delete" class="btn btn-default" role="button"><i class="fa fa-trash"></i></a>
		  </p>
		</div>
	  </div>
	</div>	
	<?php
}
echo '</div>';
echo '</div>';
?>

    <!-- End Product Thumbnail -->
    <hr class="divider">
	<div class="footer">
		Elektronika e-pood Tallinn University of Technology
		&copy Created by Artur Lipin <b>t120949</b>,Aleksei Fjodorov <b>t131055</b>
		<p class="pull-right"><a href="#">Back To Top</a></p>
						
	</div>
	 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script type="text/javascript" src="assets/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="assets/js/jquery.form.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.ba-hashchange.min.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.2/isotope.pkgd.min.js"></script>
	<script src="assets/js/main.js"></script>
   
  </body>
</html>