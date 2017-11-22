<?php
	// try {
		// session_start();
	
		// require_once('include/functions.php');
		// global $db;
		// if(isset($_GET['log_out'])) {
			// session_destroy();
			// resetCookies();
			// unset($_SESSION['username']);
			// //unset($_SESSION['token']);
			// header('Location: ./?logged_out');
		// }
		// if(!isLoggedIn()) {
			// if(isset($_GET['login'])) {
				// $isAdmin = $db->isAdmin($_POST['login'], $_POST['password']);
				// //print_r($isAdmin);
				// if($isAdmin){				
					// $_SESSION['username'] = $_POST['login'];
					// header("Location: ./all_products.php?logged");	
				// } else {
					// header("Location: ./?not_logged");
				// }
			// }
		// } else {
			// header("Location: ./all_products.php");				
		// }
	// } catch (Exception $e) {
		// print_r($e);
	// }
	function printInput($type = 'text', $name) {
		global $product, $db;
		$dic = namesToColumns();
		$value = isset($_POST[$name]) && !empty($_POST[$name]) ? $_POST[$name] : "";
		$value = isset($product[$dic[$name]]) ? $product[$dic[$name]] : $value;
		if(!isset($_GET['added'])) {
			if($type == "select") {
				echo '<select class="form-control" name="' . $name . '"' . (isset($_GET['watch']) ? (" disabled") : "") . '>';
				$id = '';
				$query = '';
				if($name == 'product_status') {
					$query = 'SELECT * FROM Kauba_seisundi_liik;';
					$id = 'kauba_seisundi_liik_kood';											//$photoID = (int)$row['ID'] + 1;
				} elseif($name == 'manufacturer') {
					$query = 'SELECT * FROM Tootja;';
					$id = 'tootja_kood';					
				} elseif($name == 'product_category') {
					$query = 'SELECT * FROM Kauba_kategooria;';
					$id = 'kauba_kategooria_kood';	
				}
				if ($result = $db->_db->query($query)) {
					$i = 0;
					while ($row = pg_fetch_assoc($result)) {
						//print_r($row);	
						echo "<option value='{$row[$id]}'" . ($value == $row[$id] ? " selected" : "") . ">{$row['nimetus']}</option>";													//$photoID = (int)$row['ID'] + 1;
					}
				}
				echo '</select>';
			}
			if($type == "text") {
				echo '<input type="text" class="form-control" name="' . $name . '" required' . 
					($value ? (" value='" . $value . "'") : "") .
					(isset($_GET['watch']) ? (" disabled") : "") 
				. '>';
			}			
			if($type == "textarea") {
				echo '<textarea type="text" class="form-control" rows="5" name="' . $name . '" required' . 
					(isset($_GET['watch']) ? (" disabled") : "") 
				. '>' . ($value ? $value : "") . '</textarea>';
			}
		}
	}
	$product = array();
	try {
		session_start();
		require_once('include/functions.php');
		global $db;
		$product_id = NULL;
		//print_r($_POST);
		$isAdmin = $db->isAdmin($_POST['login'], $_POST['password']);
		if(isset($_GET['pid']) && is_numeric($_GET['pid'])) $product_id = $_GET['pid'];
		if(empty($product_id) && isLoggedIn()) throw new Exception("product id empty");
		if(isset($_POST['product_id']) && is_numeric($_POST['product_id'])) $product_id = $_POST['product_id'];
		$query = 'SELECT * FROM Kaup WHERE kaup_id = ' . $product_id . ';';
		if(isset($_GET['watch']) || isset($_GET['edit'])) {
			if ($result = $db->_db->query($query)) {
				$i = 0;
				while ($row = pg_fetch_assoc($result)) {
					//print_r($row);	
					$product = $row;												//$photoID = (int)$row['ID'] + 1;
				}
			}				
		}
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
    <title>Add Kaup</title>
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
	
		.rating span.star:before {
			content: "\f006";
			padding-right: 5px;
			color: #999;
		}
		.rating span.star {
			font-family: FontAwesome;
			font-weight: normal;
			font-style: normal;
			display: inline-block;
		}
		.rating {
			unicode-bidi: bidi-override;
			direction: rtl;
			font-size: 30px;
		}
		.rating span.star:hover {
			cursor: pointer;
		}
		.rating span.star:hover:before, .rating span.star:hover ~ span.star:before {
			content: "\f005";
			color: #e3cf7a;
		}
		.rating span.star:before {
			content: "\f006";
			padding-right: 5px;
			color: #999;
		}
		.form-group label span {
			padding-left: 3px;
			vertical-align: top;
			color: #FB4646;
			font-size: 13px;
		}
		.product-id {
			font-size: 15px;
			float: right;
		}		
	</style>
  </head>
  <body>
	<?php printMenu(); 
	?>
	<!-- Content -->
	<div class="container">
	
		<div class="row" style="padding: 62px;">
			<div class="col-md-4 col-sm-4 col-xs-4">
				<div class="list-group">
				  <a class="list-group-item" href="javascript:history.back()"><i class="fa fa-home"></i>&nbsp; Back</a>
				  <a class="list-group-item" href="#"><i class="fa fa-list"></i>&nbsp; List</a>
				  <a class="list-group-item" href="#"><i class="fa fa-pencil-square-o"></i>&nbsp; Edit</a>
				</div>
			</div>
			<div class="col-md-8 col-sm-8 col-xs-8">
			<!-- Adress panel -->
			<div class="panel panel-primary">
				<div class="panel-heading"><?php
					if(isset($_GET['edit'])) {
						echo "Muuda kaup";
					}
					if(isset($_GET['watch'])) {
						echo "Vaata kaup";
					}
					echo !empty(product_id) ? "<span class='label label-default product-id'>#{$product_id}</span>" : "";
				?></div>
					<div class="panel-body">
				<?php 
					try {
						//print_r($product);
						if(isset($_GET['edited'])) {
						?>
						<div class="alert alert-success">
							<strong>Kaup on muutunud.</strong>
						</div>	
						<?php	
						}						
						if(isset($_GET['added'])) { 
				?>
						<div class="alert alert-success">
							<strong>Kaup on lisatud.</strong>
						</div>					
					<?php 
						}
						if(isset($_POST['add'])) {
							echo '<div class="alert alert-info">';
							$data = array();
							$dic = namesToColumns();
							foreach($_POST as $key => $value) {
								if(isset($dic[$key])) $data[$dic[$key]] = $value;	
							}
							//print_r($data);
							$kaup_id = $db->addProduct($data);
							if($kaup_id !== FALSE) {
								$saved_file = upload_my_file('kaup_id_' . $kaup_id);
								//var_dump($saved_file);
								if($saved_file !== FALSE) {
									//successfully added product with image
									?>
									<script>
										location.search = "?edit&pid=" + $kaup_id;
									</script>
									<?php
								}
							}
							echo '</div>';
						} if(isset($_POST['edit'])) {
							echo '<div class="alert alert-info">';
							$data = array();
							$dic = namesToColumns();
							foreach($_POST as $key => $value) {
								if(isset($dic[$key])) $data[$dic[$key]] = $value;	
							}
							$updated = $db->editProduct($data);
							if($updated) {
								?>
								<script>
									location.search = "?edit&pid=<?php echo $data['kaup_id']; ?>&edited"
								</script>	
								<?php								
							}
							echo '</div>';
						}
					} catch (Exception $e) {
					?>
						<div class="alert alert-danger">
							<pre><?php print_r($e); ?></pre>
						</div>	
					<?php
					}
						?>
						<form method="POST" action="./product.php<?php echo '?edit&pid=' . $product_id; ?>" enctype="multipart/form-data">
						<div class="row">
						<?php
							if(isset($_GET['watch'])) {
							?>
								<div class="col-md-7 col-sm-7 col-xs-12">
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<img src="<?php 
												$img = "assets/images/thumb253.jpg";
												if(is_file("./uploads/kaup_id_" . $product['kaup_id'] . ".jpg")) {
													$img = "./uploads/kaup_id_" . $product['kaup_id'] . ".jpg";
												}
												if(is_file("./uploads/kaup_id_" . $product['kaup_id'] . ".png")) {
													$img = "./uploads/kaup_id_" . $product['kaup_id'] . ".png";
												}
												echo $img;
											?>" class="thumbnail" style="width: 100%;">
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Komentaar</label>
												<textarea class="form-control" rows="5" id="comments" name="comments"></textarea>
											</div>
											<span class="rating">
												<span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span>
											</span>
										</div>
									</div>
								</div>
								<div class="col-md-5 col-sm-5 col-xs-12">
							<?php								
							} else {
							?>
							<div class="col-md-12 col-sm-12 col-xs-12">						
							<?php } ?>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
								  <div class="form-group">
									<label>Kauba Seisundi Liik Kood<span>*</span></label>
									<?php printInput('select', 'product_status') ?>
								  </div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
								  <div class="form-group">
									<label>Kauba Kategooria Kood<span>*</span></label>
									<?php printInput('select', 'product_category') ?>
								  </div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
								  <div class="form-group">
									<label>Tootja Kood<span>*</span></label>
									<?php printInput('select', 'manufacturer'); ?>
								  </div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
								  <div class="form-group">
									<label>Nimetus<span>*</span></label>
									<?php printInput('text', 'title'); ?>
								  </div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<label for="qty">Kogus<span>*</span></label>
										<div class="input-group">
											<?php printInput('text', 'qty'); ?>
											<span class="input-group-addon">tk.</span>
										  </div>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="form-group price">
										<label for="price">Hind<span>*</span></label>
										<div class="input-group">
											<?php printInput('text', 'price'); ?>
											<span class="input-group-addon"><i class="fa fa-eur"></i></span>
										  </div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
										<label for="comment">Kirjeldus<span>*</span></label>
										<?php printInput('textarea', 'description'); ?>
									</div>
								</div>
							</div>	
							<?php if(!isset($_GET['watch'])): ?>
														
							<div class="row">
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<label for="image">Pilt</label>
										<input type="file" id="image" name="photoUploader">
										<p class="help-block">Valige toote pilt</p>
									</div>
								</div>					
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<label>Komentaar</label>
										<textarea class="form-control" rows="5" id="comments" name="comments"></textarea>
									</div>
									<span class="rating">
										<span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span>
									</span>
								</div>
							</div>
							<?php endif; ?>
							<div class="form-actions" style="padding: 10px; text-align: right;">
								<button type="submit" name="submit" class="btn btn-primary">Salvesta</button>
								<button type="reset" class="btn btn-danger">Tühista</button>
							</div>
							<input type="text" id="product-id" name="product_id" value="<?php echo $product['kaup_id']; ?>">
							<?php if(pAction() == 'edit'): ?>
							<input type="hidden" name="edit">
							<?php else: ?>
							<input type="hidden" name="add">
							<?php endif; ?>							
						</form>
					</div>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Content -->
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