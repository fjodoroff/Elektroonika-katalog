<?php
require_once('include/functions.php');
/*
	try {
		session_start();
		require_once('include/functions.php');
		global $db;
		if(!isLoggedIn()){
			header('Location: ./');
		}
	} catch (Exception $e) {
		print_r($e);
	}*/
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
	<link href="assets/css/style.css" rel="stylesheet">
	<!--Font Awesome CDN-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script type="text/javascript" src="assets/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="assets/js/jquery.form.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/main.js"></script>
	
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <!-- Header -->
     <nav class="navbar navbar-default navbar-inverse top_nav">
        <div class="container-fluid">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
              <span class="sr-only">Toggle navigation</span>
              
            </button>
            <a class="navbar-brand" href="index.php">Elektroonika e-pood</a>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
              <li class="active"><a href="#"><i class="fa fa-home"></i><span class="sr-only">(current)</span></a></li>      
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-product-hunt"></i>Products<span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="#"><i class="fa fa-check-circle"></i>Aktiivne tooted</a></li>
                  <li><a href="#"><i class="fa fa-ban"></i>Mitteaktiivsed tooted</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i>Kustutatud tooted</a></li>
                  <li role="separator" class="divider"></li>
                  <li><a href="#">Separated link</a></li>
                  <li role="separator" class="divider"></li>
                  <li><a href="#">One more separated link</a></li>
                </ul>
              </li>
			  <li><a href="#">About</a></li>
            </ul>
            <form class="navbar-form navbar-left" role="search">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Search">
              </div>
              <button type="submit" class="btn btn-default">Submit</button>
            </form>
            <ul class="nav navbar-nav navbar-right">
				<?php if(isLoggedIn()): ?>
					<li><a href="#"><i class="fa fa-user"></i><?php echo $_SESSION['username']; ?></a></li>
					<a href="./?log_out" class="btn btn-danger" style="margin-top:7px;">Log out</a>
				<?php endif; ?>
            </ul>
		</div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
     </nav>
	 
	 <!--Navigation Panel-->
	

	
	<!-- Content -->
	<div class="container">
		<div class="row" style="padding: 62px;">
		<div class="col-md-4 col-sm-4 col-xs-4">
			<div class="list-group">
			  <a class="list-group-item" href="#"><i class="fa fa-home fa-fw"></i>&nbsp; Home</a>
			  <a class="list-group-item" href="#"><i class="fa fa-book fa-fw"></i>&nbsp; List</a>
			  <a class="list-group-item" href="#"><i class="fa fa-cog fa-fw"></i>&nbsp; Settings</a>
			</div>
		</div>
			<div class="col-md-8 col-sm-8 col-xs-8">
			<!-- Adress panel -->
				<div class="panel panel-primary">
				  <div class="panel-heading">Add Product</div>
				  <div class="panel-body">
					<form>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
							  <div class="form-group">
								<label>Kauba Seisundi Liik Kood*</label>
									<select class="form-control">
										<option>Aktiivne</option>
										<option>Mitteaktiivne</option>
										<option>Kustatud</option>
									</select>
							  </div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
							  <div class="form-group">
								<label>Kauba Kategooria Kood*</label>
									<select class="form-control">
										<option>Arvutid</option>
										<option>Full HD TV</option>
										<option>Mobiiltelefonid</option>
										<option>Kaekellad</option>
									</select>
							  </div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
							  <div class="form-group">
								<label>Tootja Kood*</label>
									<select class="form-control">
										<option>SAMSUNG Group</option>
										<option>APPLE Inc.</option>
										<option>HTC Corporation</option>
										<option>Atari</option>
										<option>Acer</option>
										<option>Alienware</option>
										<option>MSI</option>
										<option>Lenovo</option>
										<option>Fujitsu</option>
										<option>Toshiba</option>
									</select>
							  </div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
							  <div class="form-group">
								<label>Isiku seisundi Liik</label>
									<select class="form-control">
										<option>1</option>
										<option>2</option>
									</select>
							  </div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
							  <div class="form-group">
								<label>Nimetus</label>
								<input type="text" class="form-control">
							  </div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
							  <div class="form-group">
								<label>Hetke Hind</label>
								<input type="text" class="form-control">
							  </div>
							</div>
						</div>
						<div class="form-group">
							<label for="comment">Kirjeldus</label>
							<textarea class="form-control" rows="5" id="kirjeldus"></textarea>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<label for="exampleInputFile">Pilt</label>
							<input type="file" id="fileIn">
							<p class="help-block">Valige toote pilt</p>
						  </div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="form-group">
								<label>Kogus</label>
									<input type="text" class="form-control">
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="form-actions" style="padding: 10px; text-align: right;">
				  <button type="submit" class="btn btn-primary">Salvesta</button>
				  <button type="button" class="btn">Tühista</button>
				</div>
				</div>
			</div>
		</div>
		
		<?php
	echo '<table class="table table-hover">';
	echo '<thead>';
    echo '<tr class="active">';
    echo '<td>#</td>';
    echo '<td>Nimetus</td>';
    echo '<td>Hind</td>';
	echo '<td>Kirjeldus</td>';
	echo '<td>Loomise aeg</td>';
	echo '<td>Kogus</td>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
	foreach($products = $db->get_all_products() as $product)
	if(!empty($products)) {
	
	echo '<tr>';
    echo '<td>' . $product['kaup_id'] . '</td>';
    echo '<td>' . $product['nimetus'] . '</td>';
    echo '<td>' . $product['hetke_hind'] . '</td>';
	echo '<td>' . $product['kirjeldus'] . '</td>';
	echo '<td>' . $product['loomise_aeg'] . '</td>';
	echo '<td>' . $product['kogus'] . '</td>';
    echo '</tr>';
	}
	echo '</tbody>';
	echo '<table>';
	
	?>
	</div>
	<!-- End Content -->
  </body>
</html>