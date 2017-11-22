<?php
	error_reporting(E_ERROR | E_PARSE);
	session_start();
	class DBException extends Exception {
		private $_db_error;
		public function __construct($message, $db_error, $code = 0, Exception $previous = null) {
			$this->_db_error = $db_error;
			parent::__construct($message, $code, $previous);
		}
		public function __toString() {
			return __CLASS__ . "{$this->message}: {$this->_db_error}\n";
		}
	}
	class DB {
		private $_string;
		public $connection;
        /**
         * The Constructor
         */
        public function __construct($string) {
			$this->_string = $string;
			$this->connection = pg_connect($this->_string);
			if(!$this->connection) throw new DBException("Can't connect to database", "");
		}
		
		public function query($query) {
			$result = pg_query($this->connection, $query);
			//print_r($result);
			if(!$result) throw new DBException(pg_last_error($this->connection));
			return $result;
		}
	}
	class DBHelperClass {
		public $_db;
		public function __construct() {
			$this->_db = new DB("host=apex.ttu.ee dbname=db_epood3 user=t120949 password=d63034b");//last DB
			//print_r($this);
		}
		function isAdmin($login, $password) {
			$userID = NULL;
			$login = trim(strip_tags(stripslashes($login)));
			$password = trim(strip_tags(stripslashes($password)));
			//$password = md5($password);
			//SELECT UserID FROM t121088_Usersv2 WHERE Login='axive' AND Password='123';
			$query = "SELECT * FROM f_on_admin('{$login}','{$password}')";
			//die($query);
			if ($result = $this->_db->query($query)) {
				while($row = pg_fetch_assoc($result)) {
					//return $row;
					if($row['f_on_admin'] == 't') return true;
					else return false;
				}
				//$result->close();
			}
		}
		function isWorker($login, $password) {
			$userID = NULL;
			$login = trim(strip_tags(stripslashes($login)));
			$password = trim(strip_tags(stripslashes($password)));
			//$password = md5($password);
			//SELECT UserID FROM t121088_Usersv2 WHERE Login='axive' AND Password='123';
			$query = "SELECT * FROM f_on_tootaja('{$login}','{$password}')";
			//die($query);
			if($result = $this->_db->query($query)) {
				while($row = pg_fetch_assoc($result)) {
					//return $row;
					if($row['f_on_admin'] == 't') return true;
					else return false;
				}
				//$result->close();
			}
		}
		function get_all_products() {
			$products = array();
			$query = 'SELECT * FROM Kaup';
			if ($result = $this->_db->query($query)) {
				while ($row = pg_fetch_assoc($result)) {
					//$photoID = (int)$row['ID'] + 1;
					$products[] = $row;
				}
				//$result->close();
			}
			return $products;
		}
		function get_all_categories(){
			$categorys=array();
			$query = 'SELECT * FROM Kauba_kategooria';
			if ($result = $this->_db->query($query)) {
				while ($row = pg_fetch_assoc($result)) {
					//$photoID = (int)$row['ID'] + 1;
					$categorys[] = $row;
				}
				//$result->close();
			}
			return $categorys;
		}
		// function getPhoto($photoID) {
			// $photoID = trim(strip_tags(stripslashes($photoID)));
			// $query = "
				// SELECT p.*, l.likesCount, c.commentsCount, u.Name AS UserName
				// FROM t121088_Photos p
				// LEFT JOIN
				// (SELECT l.PhotoID, COUNT(1) AS 'likesCount' FROM t121088_Likes l GROUP BY l.PhotoID) AS l
				// ON p.PhotoID = l.PhotoID
				// LEFT JOIN
				// (SELECT c.PhotoID, COUNT(1) AS 'commentsCount' FROM t121088_Comments c GROUP BY c.PhotoID) AS c
				// ON p.PhotoID = c.PhotoID
				// LEFT JOIN
				// (SELECT u.UserID, u.Name FROM t121088_Usersv2 u) AS u
				// ON p.UserID = u.UserID
				// WHERE p.PhotoID = {$photoID};
			// ";
			// //$query = "SELECT * FROM t121088_Photos WHERE PhotoID = {$photoID};";
			// $result = $this->_db->connection->query($query);
			// if(!$result) return array();
			// while ($row = $result->fetch_assoc()) {
				// $row['src'] = getImageSrc($row['Filename'], $row['Description']);
				// return $row;
			// }
		// }
		// function getUserData($userID, $field) {
			// $userID = trim(strip_tags(stripslashes($userID)));
			// $query = "SELECT Name FROM t121088_Usersv2 WHERE UserID = {$userID};";
			// $result = $this->_db->connection->query($query);
			// if(!$result) return array();
			// while ($row = $result->fetch_assoc()) {
				// return $row[$field] ? $row[$field] : $row;
			// }
		// }
		// function deletePhoto($photoID) {
			// $photoID = trim(strip_tags(stripslashes($photoID)));
			// $query = "DELETE FROM t121088_Photos WHERE PhotoID = {$photoID};";
			// if($this->_db->connection->query($query) === TRUE) return true;
			// return false;
		// }
		// function getPhotoLikes($photoID) {
			// $photoID = trim(strip_tags(stripslashes($photoID)));
			// $query = "
				// SELECT PhotoID, COUNT(1) AS likesCount
				// FROM t121088_Likes
				// WHERE PhotoID = {$photoID}
				// GROUP BY PhotoID;";
			// $result = $this->_db->connection->query($query);
			// if(!$result) return false;
			// while ($row = $result->fetch_assoc()) {
				// return $row['likesCount'];
			// }
		// }
		// function isUserLikedPhoto($userID, $photoID) {
			// $userID = trim(strip_tags(stripslashes($userID)));
			// $photoID = trim(strip_tags(stripslashes($photoID)));
			// $query = "SELECT * FROM t121088_Likes WHERE PhotoID = {$photoID} AND UserID = {$userID};";
			// $result = $this->_db->connection->query($query);
			// if(!$result) return false;
			// $rows = array();
			// while ($row = $result->fetch_assoc()) {
				// $rows[] = $row;
			// }
			// if(count($rows) == 0) return false;
			// return true;
		// }
		// function ratePhoto($photoID, $userID) {
			// $userID = trim(strip_tags(stripslashes($userID)));
			// $photoID = trim(strip_tags(stripslashes($photoID)));
			// $rated = null;
			// if(!$this->isUserLikedPhoto($userID, $photoID)) {
				// $query = "INSERT INTO t121088_Likes (UserID, PhotoID, LikeDate) VALUES ({$userID},{$photoID}, '" . date('Y-m-d') ."');";
				// if($this->_db->connection->query($query) === TRUE) $rated = true;
			// } else {
				// $query = "DELETE FROM t121088_Likes WHERE PhotoID = {$photoID} AND UserID = {$userID};";
				// if($this->_db->connection->query($query) === TRUE) $rated = false;				
			// }
			// //$this->_db->connection->query($query);
			// return array(
				// 'rated'	=>	$rated,
				// 'likes'	=>	$this->getPhotoLikes($photoID)
			// );
		// }
		function editProduct($post, $files) {
			$post = array_filter($post);
			$data = array();
			$dic = array(
				'kaup_id'					=>	"%d",
				'kauba_seisundi_liik_kood'	=>	"%d::SMALLINT",
				'kauba_kategooria_kood'		=>	"%d::SMALLINT",
				'tootja_kood'				=>	"%d::SMALLINT",
				'isikukood'					=>	"'39212233718'",
				'nimetus'					=>	"'%s'",
				'hetke_hind'				=>	"%d",
				'kirjeldus'					=>	"'%s'",
				//'loomis_aeg'				=>	"date_trunc('minute',localtimestamp(0))",
				'kogus'						=>	"%d::SMALLINT",
				// 'qty'  				=>  'kogus',
				// 'comments'  		=>  'komentaar',
				// 'rating' 			=>  'hinnang'				
			);
			foreach($dic as $key => &$value) {
				$value = $key . " = " . sprintf($value, $post[$key]);
			}
			//print_r($dic);
			$product_id = is_numeric($post['kaup_id']) ? $post['kaup_id'] : NULL;
			if(!empty($product_id)) {
				unset($dic['kaup_id']);
				$query = "UPDATE kaup
					SET " . implode(',', $dic) . "
					WHERE kaup_id='{$product_id}';";
				//$query = "SELECT * FROM f_kaupade_muutmine(" . implode(',', $dic) . ")";
				//print_r($query);
				//SELECT * FROM f_kaupade_muutmine(78747474,'LLenox',18.00,'vana version','hgjh',3::SMALLINT);
				return $result = $this->_db->query($query);
				// if($result) {
					// echo '!!!';
				// }
				// while ($row = pg_fetch_assoc($result)) {
					// print_r($row);
					// ////555555555555555555555555555
					// //	$dic['kaup_id'] = $row[''];
					// //555555555555555555555555555
				// }
				//return $dic['kaup_id'];
			} return false;
			//print_r();
		}
		function addProduct($post, $files) {
			$post = array_filter($post);
			$data = array();
			//$post['pilt'] = '';
			//$NEXT_ID;
			$dic = array(
				'kauba_seisundi_liik_kood'	=>	"%d::SMALLINT",
				'kauba_kategooria_kood'		=>	"%d::SMALLINT",
				'tootja_kood'				=>	"%d::SMALLINT",
				'isikukood'					=>	"'39212233718'",
				'nimetus'					=>	"'%s'",
				'hetke_hind'				=>	"%d",
				'kirjeldus'					=>	"'%s'",
				'kogus'						=>	"%d::SMALLINT",
				'pilt'  					=>  "'kaup_id_$NEXT_ID.jpg'",
				//'pilt'  					=>  "''",
				// 'comments'  		=>  'komentaar',
				// 'rating' 			=>  'hinnang'				
			);
			foreach($dic as $key => &$value) {
				$value = sprintf($value, $post[$key]);
			}
			$query = "SELECT * FROM f_lisa_kaup1(" . implode(',', $dic) . ")";
			//print_r($query);
			//SELECT * FROM f_lisa_kaup1(
			//	1::SMALLINT,4::SMALLINT,2::SMALLINT,'39212233718','Asus computer S22',1000,'hea arvuti',5::smallint,''
			//);
			
			//$keys = implode(',')
			//$query = "INSERT INTO t121088_Comments (UserID, PhotoID, Text, CommentDate) VALUES ({$userID}, {$photoID}, '{$comment}', '" . date('Y-m-d') ."');";
			//if($this->_db->query($query) === TRUE) return true;
			//return false;
			$result = $this->_db->query($query);
			while ($row = pg_fetch_assoc($result)) {
				//$row['src'] = getImageSrc($row['Filename'], $row['Description']);
				////555555555555555555555555555
				//	$dic['kaup_id'] = $row[''];
				//555555555555555555555555555
			}
			return $dic['kaup_id'];
			//print_r();
		}
		// function createPhoto($userID, $filename, $name, $description) {
			// $userID = strip_tags(stripslashes($userID));
			// $filename = trim(strip_tags(stripslashes($filename)));
			// $name = trim(strip_tags(stripslashes($name)));
			// $description = trim(strip_tags(stripslashes($description)));
			// $query = "INSERT INTO t121088_Photos (UserID, Filename, Name, Description) VALUES ({$userID},'{$filename}', '{$name}', '{$description}');";
			// if($this->_db->connection->query($query) === TRUE) return true;
			// return false;
		// }
		// function trendingPhotos() {
		
		// }
		// function createUser($login, $password, $name, $email) {
			// $login = trim(strip_tags(stripslashes($login)));
			// $password = trim(strip_tags(stripslashes($password)));
			// $name = trim(strip_tags(stripslashes($name)));
			// $email = trim(strip_tags(stripslashes($email)));
			// //$password = md5($password);
			// //INSERT INTO t121088_Users VALUES ('a','a', 'a', 'a');
			// $query = "INSERT INTO t121088_Usersv2 (Login, Password, Name, Email) VALUES ('{$login}','{$password}', '{$name}', '{$email}');";
			// if($this->_db->connection->query($query) === TRUE) return true;
			// return false;
		// }
	}
	$db = new DBHelperClass();
	//Image Functions
	function getImageInfo($img_path) {
		$size = getimagesize($img_path, $info);
		return $size;
	}
	function getImagePath($fileName) {
		return count(explode('.', $fileName)) > 1 ? $fileName : "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjQyIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDI0MiAyMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjxkZWZzLz48cmVjdCB3aWR0aD0iMjQyIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI0VFRUVFRSIvPjxnPjx0ZXh0IHg9IjkzIiB5PSIxMDAiIHN0eWxlPSJmaWxsOiNBQUFBQUE7Zm9udC13ZWlnaHQ6Ym9sZDtmb250LWZhbWlseTpBcmlhbCwgSGVsdmV0aWNhLCBPcGVuIFNhbnMsIHNhbnMtc2VyaWYsIG1vbm9zcGFjZTtmb250LXNpemU6MTFwdDtkb21pbmFudC1iYXNlbGluZTpjZW50cmFsIj4yNDJ4MjAwPC90ZXh0PjwvZz48L3N2Zz4=";
	}
	function getImageSrc($fileName, $alt = "", $styles = false) {
		$path = getImagePath($fileName);
		$image_sizes = getImageInfo($fileName);
		return "<img alt='{$alt}' src='{$path}' style='" . (count($image_sizes) > 0 && $styles === false  ? "max-width:" . $image_sizes[0] . "px" : $styles) . "'>";
	}
	function isLoggedIn() {
		return isset($_SESSION['username']);
	}
	function upload_my_file($fileid) {
		echo "starting";
		$target_dir = "uploads/";
		$target_file = $target_dir . basename($_FILES["photoUploader"]["name"]);

		echo "<p>$target_file " . $target_file;
		$uploadOk = 1;
		$info = pathinfo($_FILES['photoUploader']['name']);
		//die(print_r($_FILES, true));
		$ext = $info['extension'];
		$saved_file = $target_dir . $fileid . "." . $ext;
		$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
			$check = getimagesize($_FILES["photoUploader"]["tmp_name"]);
			if($check !== false) {
				echo "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				echo "File is not an image.";
				$uploadOk = 0;
			}
		}
		echo "all is fine before checks";
		// Check if file already exists
		if (file_exists($target_file)) {
			echo "Sorry, file already exists.";
			$uploadOk = 0;
		}
		// Check file size
		if ($_FILES["photoUploader"]["size"] > 5000000) {
			echo "Sorry, your file is too large.";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file(
			$_FILES["photoUploader"]["tmp_name"], $saved_file)) {
				echo "<p>The file ". basename( $_FILES["photoUploader"]["name"]). " has been uploaded.";
			} else {
				echo "<p>Sorry, there was an error uploading your file.";
			}
		}
		return $saved_file;
	}
	function getRandomColorClass() {
		$colors = array('green', 'red', 'yellow', 'blue');
		return $colors[rand(0, count($colors) - 1)];
	}
	function getUserID() {
		return !empty($_SESSION['ID']) ? $_SESSION['ID'] : $_COOKIE['ID'];
	}
	function getToken() {
		return !empty($_SESSION['token']) ? $_SESSION['token'] : $_COOKIE['token'];
	}
	function checkToken($token, $exceptions = false) {
		if(!empty($token)) {
			if($token == $_SESSION['token']) {
				return true;
			} else {
				if($exceptions) throw new Exception('Token is invalid!');
				else return false;
			}
		} else {
			if($exceptions) throw new Exception('Token is empty!');
			else return false;
		}
	}
	function resetCookies() {
		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach($cookies as $cookie) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', time()-1000);
				setcookie($name, '', time()-1000, '/');
			}
		}
	}
	function namesToColumns() {
		return array(
			'product_id'		=>	'kaup_id',
			'product_status'	=>	'kauba_seisundi_liik_kood',
			'product_category'  =>  'kauba_kategooria_kood',
			'manufacturer'  	=>  'tootja_kood',
			'title'  			=>  'nimetus',
			'qty'  				=>  'kogus',
			'price'  			=>  'hetke_hind',
			'description'  		=>  'kirjeldus',
			'comments'  		=>  'komentaar',
			'rating' 			=>  'hinnang',
			'photoUploader'		=>	'pilt'			
		);
	}
	function printMenu() {
	?>
	<!-- Header -->
     <nav class="navbar navbar-default navbar-inverse top_nav">
        <div class="container">
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
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-product-hunt"></i> Kaubad<span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="./all_products.php#status-0"><i class="fa fa-check-circle"></i> Kõik kaubad</a></li>
                  <li><a href="./all_products.php#status-1"><i class="fa fa-check-circle"></i> Aktiivne kaubad</a></li>
                  <li><a href="./all_products.php#status-2"><i class="fa fa-ban"></i> Mitteaktiivsed kaubad</a></li>
                  <li><a href="./all_products.php#status-3"><i class="fa fa-circle-o"></i> Kustutatud kaubad</a></li>
                  <li role="separator" class="divider"></li>
                  <li class="add-product"><a href="./product.php?add"><i class="fa fa-plus-circle"></i>Lisa kaup</a></li>
                </ul>
              </li>
			  <li><a href="#">Projektidest</a></li>
            </ul>
            <form class="navbar-form navbar-left" role="search">
              <div class="form-group">
                <input id="search" type="text" class="form-control" placeholder="Search">
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
	<?php
	}
	function pAction() {
		if(isset($_GET['add'])) return  'add';
		if(isset($_GET['edit'])) return 'edit';
	}