<?php
	require_once('functions.php');
	global $db;
	$data = array();
	$html = false;
	$unauthorizedActions = array("getPhotos", "getPhoto", "getUsers", "getComments");
	try {
		if(isset($_REQUEST['token']) && !in_array($_REQUEST['action'], $unauthorizedActions)) {
			if(!isLoggedIn()) throw new Exception('Log in first!');
			if(checkToken($_REQUEST['token'], true)) {
				switch ($_REQUEST['action']) {
					case "rate":
						if(empty($_REQUEST['photoID'])) throw new Exception("photoID parameter must be defined!");
						if(!is_numeric($_REQUEST['photoID'])) throw new Exception("photoID parameter must be a number!");
						if(empty($_REQUEST['userID'])) throw new Exception("userID parameter must be defined!");
						if(!is_numeric($_REQUEST['userID'])) throw new Exception("userID parameter must be a number!");
						
						$result = $db->ratePhoto($_REQUEST['photoID'], $_REQUEST['userID']);
						if($result['rated'] === null) throw new Exception('Database answer is empty!');
						$data = array(
							'success'	=>	true,
							'class'		=>	$result['rated'] == false ? 'fa-heart-o' : 'fa-heart',
							'likes'		=>	$result['likes'] == false ? 0 : $result['likes']
						);		
						break;
					case "addComment":
						if(empty($_REQUEST['photoID'])) throw new Exception("photoID parameter must be defined!");
						if(!is_numeric($_REQUEST['photoID'])) throw new Exception("photoID parameter must be a number!");
						if(empty($_REQUEST['userID'])) throw new Exception("userID parameter must be defined!");
						if(!is_numeric($_REQUEST['userID'])) throw new Exception("userID parameter must be a number!");
						if(empty($_REQUEST['comment'])) throw new Exception("comment parameter must be defined!");
						
						$result = $db->addComment($_REQUEST['photoID'], $_REQUEST['userID'], $_REQUEST['comment']);
						if(!$result) throw new Exception('Result empty!');
						$data = array(
							'success'	=>	true
						);
						break;
					default:
						throw new Exception('No action parameter set');
						break;
				}
			} else {
				throw new Exception('Invalid token!');
			}
		} else { //Methods without authorization token
			switch ($_REQUEST['action']) {
				case "login":
					if(empty($_POST['login'])) throw new Exception('Login field empty!');
					if(empty($_POST['password'])) throw new Exception('Password field empty!');
					
					$id = $db->logIn($_POST['login'], $_POST['password']);
					if($id != NULL) {
						$data['success'] = "Successfully logged in!";
						$data['profile_img'] = "uploads/8.jpg";
						session_start();
						$_SESSION['ID'] = $_COOKIE['ID'] = $id;
						$data['token'] = $_SESSION['token'] = base64_encode($id . uniqid());
						setcookie("token", $_SESSION['token']);
					} else {
						throw new Exception('Username or password is incorrect!');
					}			
					break;
				case "getComments":
					if(empty($_REQUEST['photoID'])) throw new Exception("photoID parameter must be defined!");
					if(!is_numeric($_REQUEST['photoID'])) throw new Exception("photoID parameter must be a number!");
					$html = true;
					
					header('Content-Type: text/html');
					$result = $db->_db->connection->query("
						SELECT 
							c.*, u.Name 
						FROM 
							t121088_Comments AS c, t121088_Usersv2 AS u
						WHERE 
							PhotoID = {$_REQUEST['photoID']} AND
							u.UserID = c.UserID
						ORDER BY CommentID DESC;
					");
					if(!$result) throw new Exception('Result empty!');
					if(mysqli_num_rows($result) == 0) {
						echo '<h2>No comments found!</h2>';
						echo '<p><a href=#">Be the first one!</a></p>';
						die();
					}
					echo '<ul class="media-list">';
					while ($row = $result->fetch_assoc()) {
						?>
							<li class="media">
								<a class="pull-left" href="#">
									<img class="media-object img-circle" src="img/usr_default.png" alt="profile" style="height: 93px;">
								</a>
								<div class="media-body">
									<div class="well well-lg">
										<h4 class="media-heading text-uppercase reviews"><?php echo $row['Name']; ?></h4>
										<ul class="media-date text-uppercase reviews list-inline">
										<?php
											$date = explode('-', $row['CommentDate']);
										?>
											<li class="dd"><?php echo $date[2]; ?></li>
											<li class="mm"><?php echo $date[1]; ?></li>
											<li class="aaaa"><?php echo $date[0]; ?></li>
										</ul>
										<p class="media-comment"><?php echo $row['Text']; ?></p>
									</div>              
								</div>
							</li>   
						<?php
					}
					echo "</ul>";
					die();
					break;
				case "getPhotos":
					if(isset($_REQUEST['photos']) && !empty($_POST['photos'])) {
						header('Content-Type: text/html');
						$html = true;
						$heading = "";
						if($_REQUEST['photos'] == "all") {
							$result = $db->_db->connection->query("
								SELECT p.*, l.likesCount, c.commentsCount, u.Name AS UserName
								FROM t121088_Photos p
								LEFT JOIN
								(SELECT l.PhotoID, COUNT(1) AS 'likesCount' FROM t121088_Likes l GROUP BY l.PhotoID) AS l
								ON p.PhotoID = l.PhotoID
								LEFT JOIN
								(SELECT c.PhotoID, COUNT(1) AS 'commentsCount' FROM t121088_Comments c GROUP BY c.PhotoID) AS c
								ON p.PhotoID = c.PhotoID
								LEFT JOIN
								(SELECT u.UserID, u.Name FROM t121088_Usersv2 u) AS u
								ON p.UserID = u.UserID
								ORDER BY p.PhotoID DESC;
							");
							if(!$result) throw new Exception('Result empty!');
							$heading = "All Photos";
						} else if($_REQUEST['photos'] == 'commentable') {
							$result = $db->_db->connection->query("
								SELECT p.*, l.likesCount, c.commentsCount, u.Name AS UserName
								FROM t121088_Photos p
								LEFT JOIN
								(SELECT l.PhotoID, COUNT(1) AS 'likesCount' FROM t121088_Likes l GROUP BY l.PhotoID) AS l
								ON p.PhotoID = l.PhotoID
								LEFT JOIN
								(SELECT c.PhotoID, COUNT(1) AS 'commentsCount' FROM t121088_Comments c GROUP BY c.PhotoID) AS c
								ON p.PhotoID = c.PhotoID
								LEFT JOIN
								(SELECT u.UserID, u.Name FROM t121088_Usersv2 u) AS u
								ON p.UserID = u.UserID
								WHERE c.commentsCount IS NOT NULL
								ORDER BY c.commentsCount DESC;
							");
							if(!$result) throw new Exception('Result empty!');
							$heading = "Most commented photos";							
						} else if($_REQUEST['photos'] == 'liked') {
							$result = $db->_db->connection->query("
								SELECT p.*, l.likesCount, c.commentsCount, u.Name AS UserName
								FROM t121088_Photos p
								LEFT JOIN
								(SELECT l.PhotoID, COUNT(1) AS 'likesCount' FROM t121088_Likes l GROUP BY l.PhotoID) AS l
								ON p.PhotoID = l.PhotoID
								LEFT JOIN
								(SELECT c.PhotoID, COUNT(1) AS 'commentsCount' FROM t121088_Comments c GROUP BY c.PhotoID) AS c
								ON p.PhotoID = c.PhotoID
								LEFT JOIN
								(SELECT u.UserID, u.Name FROM t121088_Usersv2 u) AS u
								ON p.UserID = u.UserID
								WHERE l.likesCount IS NOT NULL
								ORDER BY l.likesCount DESC;
							");
							if(!$result) throw new Exception('Result empty!');
							$heading = "Most liked photos";						
						} else if($_REQUEST['photos'] == 'my') {
							if(checkToken($_REQUEST['token'], true)) {
								$result = $db->_db->connection->query("
									SELECT p.*, l.likesCount, c.commentsCount, u.Name AS UserName
									FROM t121088_Photos p
									LEFT JOIN
									(SELECT l.PhotoID, COUNT(1) AS 'likesCount' FROM t121088_Likes l GROUP BY l.PhotoID) AS l
									ON p.PhotoID = l.PhotoID
									LEFT JOIN
									(SELECT c.PhotoID, COUNT(1) AS 'commentsCount' FROM t121088_Comments c GROUP BY c.PhotoID) AS c
									ON p.PhotoID = c.PhotoID
									LEFT JOIN
									(SELECT u.UserID, u.Name FROM t121088_Usersv2 u) AS u
									ON p.UserID = u.UserID
									WHERE p.UserID = {$_SESSION['ID']};
								");
								if(!$result) throw new Exception('Result empty!');
								$heading = "My Photos";
							}
						} else if(strpos($_REQUEST['photos'], 'user-') !== FALSE) {
							$userID = trim(strip_tags(stripslashes(substr($_REQUEST['photos'], strpos($_REQUEST['photos'], 'user-') + 5))));
							if(!is_numeric($userID)) throw new Exception("user id must be numeric!");
							$result = $db->_db->connection->query("
								SELECT p.*, l.likesCount, c.commentsCount, u.Name AS UserName
								FROM t121088_Photos p
								LEFT JOIN
								(SELECT l.PhotoID, COUNT(1) AS 'likesCount' FROM t121088_Likes l GROUP BY l.PhotoID) AS l
								ON p.PhotoID = l.PhotoID
								LEFT JOIN
								(SELECT c.PhotoID, COUNT(1) AS 'commentsCount' FROM t121088_Comments c GROUP BY c.PhotoID) AS c
								ON p.PhotoID = c.PhotoID
								LEFT JOIN
								(SELECT u.UserID, u.Name FROM t121088_Usersv2 u) AS u
								ON p.UserID = u.UserID
								WHERE p.UserID = {$userID};
							");
							if(!$result) throw new Exception('Result empty!');
							$userName = $db->getUserData($userID, 'Name');
							$heading = "Photos of {$userName}";
						} else throw new Exception('Photos parameter undefined');
						
						$counter = 1;
						echo "<div>";
						echo "<h1 class='answer-title' style='margin-bottom:48px'>{$heading}</h1>";
						while ($row = $result->fetch_assoc()) {
							if($counter % 3 == 0) echo '<div class="row">';
								?>	
						  <div class="col-sm-6 col-md-4 photo photo-<?php echo $row['PhotoID']; ?>">
							<figure class="effect-sadie <?php echo getRandomColorClass(); ?>">
							  <?php echo getImageSrc($row['Filename'], $row['Name'], ""); ?>
							  <figcaption>
								<h2><?php echo $row['Name']; ?> <span></span></h2>
								<div class="icon-wrapper">
									<div class="heart">
										<span><?php echo !empty($row['likesCount']) ? $row['likesCount'] : 0 ?></span> 
										<a href="#" class="fa <?php echo isLoggedIn() ? ($db->isUserLikedPhoto($_SESSION['ID'], $row['PhotoID']) ? "fa-heart" : "fa-heart-o") : "fa-heart-o" ?>"></a>
									</div>| 
									<div class="comment"><?php echo !empty($row['commentsCount']) ? $row['commentsCount'] : 0 ?> <a href="#" class="fa fa-comment"></a> </div>|
									<div class="expand"><a href="#photo-<?php echo $row['PhotoID']; ?>" class="fa fa-expand"></a></div>
									<?php 
										if($_SESSION['ID'] == $row['UserID']) { 
									?>
										| <div class="remove"><a href="?deleteImage=<?php echo $row['PhotoID']; ?>" class="fa fa-remove"></a></div>
									<?php 
										} if($_REQUEST['photos'] == "all") { 
									?>
										<br/>
										<a href="#user-photos-<?php echo $row['UserID'] ?>" class="user"><i class="fa fa-user"></i> <?php echo $row['UserName'] ?></a>
									<?php } ?>
								</div>
								<p><?php echo $row['Description']; ?></p>
							  </figcaption>
							  </figure>
						  </div>
							<?php 
								if($counter % 3 == 0) echo '</div>';
								$counter++;
							}
							die("</div>");
					} else throw new Exception('Photos parameter empty!');
					break;
				case "register":
					if(!isLoggedIn()) {
						$supported_names = array("login", "password", "name", "email");
						foreach($supported_names as $name) {
							if(!array_key_exists($name, $_POST)) throw new Exception('Not all parameters set!');
							if(empty($_POST[$name])) throw new Exception('One of the parameter is empty!');
						}
						
						if($result = $db->createUser($_POST['login'], $_POST['password'], $_POST['name'], $_POST['email'])) {
							//print_r($result);
							$data = array("success"	=>	"Successfully registered!");
						} else throw new Exception("User with this data already exist!");
						
					} else throw new Exception('You has been already signed in!');
					break;
				case "getPhoto":
					if(empty($_REQUEST['photoID'])) throw new Exception("photoID parameter must be defined!");
					if(!is_numeric($_REQUEST['photoID'])) throw new Exception("photoID parameter must be a number!");
					
					$result = $db->getPhoto($_REQUEST['photoID']);
					if(empty($result)) throw new Exception('Result empty!');
					$data = $result;
					break;
				case "getUsers":
					if(isset($_REQUEST['users']) && !empty($_POST['users'])) {
						header('Content-Type: text/html');
						$html = true;
						$heading = "";
						if($_REQUEST['users'] == "all") {
							$result = $db->_db->connection->query("
								SELECT u.*, i.imagesCount, c.commentsCount, l.likesCount
								FROM t121088_Usersv2 u
								LEFT JOIN
								(SELECT p.UserID, COUNT(1) AS 'imagesCount' FROM t121088_Photos p GROUP BY p.UserID) AS i
								ON u.UserID = i.UserID
								LEFT JOIN
								(SELECT c.UserID, COUNT(1) AS 'commentsCount' FROM t121088_Comments c GROUP BY c.UserID) AS c
								ON u.UserID = c.UserID
								LEFT JOIN
								(SELECT l.UserID, COUNT(1) AS 'likesCount' FROM t121088_Likes l GROUP BY l.UserID) AS l
								ON u.UserID = l.UserID;
							");
							if(!$result) throw new Exception('Result empty!');
							$heading = "All Users";
						} else throw new Exception('users parameter undefined');
						$counter = 1;
						
						echo "<div>";
						echo "<h1 class='answer-title' style='margin-bottom:48px'>{$heading}</h1>";
						while ($row = $result->fetch_assoc()) {
							if($counter % 3 == 0) echo '<div class="row">';
								?>	
						  <div class="col-sm-6 col-md-4 user user-<?php echo $row['UserID']; ?>">
							<figure class="effect-sadie <?php echo getRandomColorClass(); ?>">
							  <img alt="<?php echo $row['Name']; ?>" src="img/no_user.jpg" data-holder-rendered="true">
							  <figcaption>
								<h2><?php echo $row['Name']; ?> <span></span></h2>
								<div class="icon-wrapper">
									<div class="heart"><?php echo !empty($row['imagesCount']) ? $row['imagesCount'] : 0 ?> <a href="#photos-user-<?php echo $row['UserID']; ?>" class="fa fa-file-image-o"></a> </div>| 
									<div class="comment"><?php echo !empty($row['commentsCount']) ? $row['commentsCount'] : 0 ?> <a href="#" class="fa fa-comment"></a> </div>|
									<div class="heart" title="Number of user likes"><?php echo !empty($row['likesCount']) ? $row['likesCount'] : 0 ?> <a href="#" class="fa fa-heart"></a> </div>| 
									<div class="expand"><a href="#users-user-<?php echo $row['UserID']; ?>" class="fa fa-expand"></a></div>
								</div>
								<p><?php echo $row['Description']; ?></p>
							  </figcaption>
							  </figure>
						  </div>
						<?php 
							if($counter % 3 == 0) echo '</div>';
								$counter++;
							}
							die("</div>");
					} else throw new Exception('users parameter empty!');
					break;
				default:
					throw new Exception('No action parameter set');
					break;
			}
		}
	} catch (Exception $e) {
		if(!$html) {
			$data = array(
				'error'	=>	$e->getMessage()
			);
		} else {
			$data = "<h2 class='text-danger'>Error: " . $e->getMessage() . "</h2>";
		}
	} finally {
		if(!$html) {
			header('Content-Type: application/json');
			echo json_encode($data);
		} else {
			header('Content-Type: text/html');
			echo $data;
		}
	}
	