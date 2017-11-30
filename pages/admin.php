<?php
	require_once 'connect.php';

	//ïf user isnt logged in, sends them to login page
	if(!$user->is_loggedin()){
		$user->redirect('login.php');
	}else if(!$_SESSION['username'] == 'admin'){
		$user->redirect('login.php');
	}
	//gets username from the session
	
	$userID = $_SESSION['username'];
	//changes the username to allways appear in uppercase when printed (and using th e variable)
	$printableUsername = strtoupper($userID);


	//Checks if the "hidden"-delete butten is clicked if it is clicked then deletes the article
	if (isset($_POST['deleteArticle'])){
		$stmt = $db->prepare("DELETE FROM news WHERE newsID='".intval($_POST['deleteArticle'])."'");
		$stmt->execute();
    };

	if (isset($_POST['deleteUser'])){
		$stmt = $db->prepare("DELETE FROM users WHERE username='".$_POST['deleteUser']."'");
		$stmt->execute();
    };

	if (isset($_POST['deleteCategory'])){
		$stmt = $db->prepare("DELETE FROM categories WHERE category='".$_POST['deleteCategory']."'");
		$stmt->execute();
    };

	if (isset($_POST['categorySubmit'])){
		$stmt = $db->prepare("
			INSERT INTO categories (category)
			VALUES ('".htmlentities($_POST['newCategory'])."');
			");
		$stmt->execute();
	};

	function displayRating(PDO $db, $newsID){
		//sets rating to 0
		$rating = 0;
		//crates array to be filled with ratings
		$ratingarr = array();
		
		//looks for ratings connected to a selected article
		$ratingstmt = $db->prepare("
			SELECT *
			FROM ratings r
			WHERE r.newsID = ". $newsID .";
			");
		$ratingstmt->execute();
		//counts number of total ratings (aka how many ratings it has got)(number of rows fetched)
		$numberOfRatings = $ratingstmt->rowCount();
		
		//if numberOfRatings is 0 just returns 0
		if($numberOfRatings == 0){
			return $rating;
		}else{
			while ($ratingrow = $ratingstmt->fetch(PDO::FETCH_ASSOC)){
				//fills the array with values from db
				$ratingarr[] = $ratingrow['rating'];
			}
			
			//gets the sum of all the values in the array and devides it by number of ratings, could aslo use "count($ratingarr)" here, would do the same
			$rating = array_sum($ratingarr) / $numberOfRatings;
			return $rating;
		}
	}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <!--Jquery and UI-->
    <script src="jquery-ui/external/jquery/jquery.js"></script>
    <link rel="stylesheet" href="jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="jquery-ui/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="jquery-ui/jquery-ui.theme.min.css">
    <script src="jquery-ui/jquery-ui.min.js"></script>
    
    <link rel="stylesheet" href="css/profile.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="css/header.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="css/fonts.css?<?php echo time(); ?>">
</head>
<body>
	<!--Menu-->
	<a id="logo" href="index.php">SUPERNEWS</a>
	<header>
			<a id="createnews" href="createnews.php">CREATE ARTICLE</a>
			<a id="logout" href="logout.php?logout=true">LOG OUT</a>
			<a id="profile" href="profile.php">WELCOME, <?php echo $printableUsername; ?></a>
		</header>
		<a class="adminbutton" href="admin.php">ADMIN DASHBOARD</a>
	
	<h1>ADMIN DASHBOARD</h1>
	<div id="main">
		<div class="container">
		
			<!-- Menu to quickly jump to sections(usefull if the lists gets pretty long-->
			<a href="#categoriesTab" class="tabs">Categories</a>
			<a href="#articlesTab"  class="tabs">Articles</a>
			<a href="#usersTab" class="tabs">Users</a>
			
			
			<!--SECTION TO DISPLAY ALL CATEGORIES -->
			<h3 id="categoriesTab">All Categories:</h3>
			
			<?php
				//gets all categories
				$stmt = $db->prepare("SELECT category FROM categories");
				$stmt->execute();
				//Just to check if they dont have any
				$numRows = $stmt->rowCount();
			?>
			
				<table>
					<tr>
						<td><b>Category</b></td>
						<td><b>Number of articles</b></td>
						<td style="text-align: center;"><b>DELETE</b></td>
					</tr>
					<?php
					if($numRows == 0){
						echo '<p style="text-align: center;">No news made!</p>';
					}else{
						while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							echo '<tr>';
							echo '<td>' .$row['category']. '</td>';
							
							//gets all news that has "category"
							$articleStmt = $db->prepare("
							SELECT category 
							FROM news
							WHERE category = '".$row['category']."'");
							$articleStmt->execute();
							//Just to check if they dont have any
							$numberOfArticles = $articleStmt->rowCount();
							
							echo '<td>'.$numberOfArticles.'</td>';
							echo '<td>
								<form method="post">
									<input type="hidden" name="deleteCategory" value="'.$row['category'].'">
									<input class="delbut" type="submit" value="DELETE">
								</form>
							</td>';
							echo '</tr>';
						}
					};?>
				</table>
				
			<br>
			
			<!-- form to create a new category -->
			<form method="post" class="newDetails">
				<label for="newUsername">Create new category:</label>
				<br>
				<input class="field" id="newCategory" type="text" name="newCategory">
				<input class="updateButton" type="submit" name="categorySubmit" value="Create">

			</form>
			
			<br>
			
			<!-- display all articles section -->
			<h3 id="articlesTab">All Articles:</h3>
			
			<?php
				//gets users articles
				$stmt = $db->prepare("SELECT newsID, title, category, uploadDate, rating, authorID FROM news");
				$stmt->execute();
				//Just to check if they dont have any
				$numRows = $stmt->rowCount();
			?>
			
				<table>
					<tr>
						<td><b>Title</b></td>
						<td><b>Category</b></td>
						<td><b>Upload Date</b></td>
						<td><b>Rating</b></td>
						<td><b>AUTHOR</b></td>
						<td style="text-align: center;"><b>DELETE</b></td>
					</tr>
					<?php
					if($numRows == 0){
						echo '<p style="text-align: center;">No news made!</p>';
					}else{
						while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							echo '<tr>';
							echo '<td>' .$row['title']. '</td>';
							echo '<td>'.$row['category'].'</td>';
							echo '<td>'.$row['uploadDate'].'</td>';
							echo '<td>' .displayRating($db, $row['newsID']).'<i> /5</i></td>';
							//checks if username is empty
							if ($row['authorID'] == NULL){
								echo '
								<td>
									<i>[deleted]</i>
								</td>';
							}else{
								echo '
								<td>
									<i>'.$row['authorID'].'</i>
								</td>';
							}
							echo '<td>
								<form method="post">
									<input type="hidden" name="deleteArticle" value="'.intval($row['newsID']).'">
									<input class="delbut" type="submit" value="DELETE">
								</form>
							</td>';
							echo '</tr>';
						}
					};?>
				</table>
			<br>
			
			<!-- Section to display all users -->
			<h3 id="usersTab">All Users:</h3>
			
			<?php
				//gets users created news
				$stmt = $db->prepare("SELECT username FROM users");
				$stmt->execute();
				//Just to check if they dont have any
				$numRows = $stmt->rowCount();
			?>
			
				<table>
					<tr>
						<td><b>USERNAME</b></td>
						<td style="text-align: center;"><b>DELETE</b></td>
					</tr>
					<?php
					if($numRows == 0){
						echo '<p style="text-align: center;">No news made!</p>';
					}else{
						//goes through db to find all users created news
						while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							echo '<tr>';
							echo '<td>' .$row['username']. '</td>';
							if($row['username'] == 'admin'){
								echo '<td>
									<input class="delbut" type="button" value="CANT DELETE">
									</td>';
							}else{
								echo '<td>
								<form method="post">
									<input type="hidden" name="deleteUser" value="'.$row['username'].'">
									<input class="delbut" type="submit" value="DELETE">
								</form>';
							}
							echo '</td>';
							echo '</tr>';
						}
					};?>
				</table>
			
		</div>
	</div>
	
	<script>
	//function to make name to uppercase
	function allCaps(a){
		return a.toUpperCase;
	}
	</script>
</body>
</html>