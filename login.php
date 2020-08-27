<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["email"]) ? $_POST["email"] : false;
	$pass = isset($_POST["pass"]) ? $_POST["pass"] : false;	
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Kyle Proctor-Parker">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass)
			{
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);

				if($row = mysqli_fetch_array($res))
				{
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
				
					echo 	"<form action='login.php' method='POST' enctype='multipart/form-data'>
								<div class='form-group'>
									<input type='hidden' value='".$row['email']."' name='email' />
									<input type='hidden' value='".$row['password']."' name='pass' />
									<input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
								</div>
							  </form>";
				}
				else
				{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			}
			else
			{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		?>
		<?php
			if(isset($_POST['submit']))
			{
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);

				if($row = mysqli_fetch_array($res))
				{
					$userID = $row['user_id'];

					$target_dir= "gallery/";
					$uploadFile= $_FILES["picToUpload"];
					$target_file= $target_dir. basename($uploadFile["name"]);
					$target_file_name= basename($uploadFile["name"]);
					$imageFileType= pathinfo($target_file,PATHINFO_EXTENSION);

					if(($imageFileType == 'jpeg' || $imageFileType == 'jpg') && $_FILES['picToUpload']['size'] < 1048576)
					{
						if($uploadFile["error"] > 0)
						{
							echo "Error: " . $uploadFile["error"] . "<br/>";
						} 
						else 
						{
							$query = "INSERT INTO tbgallery (user_id, filename) VALUES ('$userID', '$target_file_name');";
							$res = mysqli_query($mysqli, $query) == TRUE;

							move_uploaded_file($uploadFile["tmp_name"],
							"gallery/" . $uploadFile["name"]);
						}
					} 
					else 
					{
						echo "Image needs to be a jpeg or jpg and smaller than 1MB";
					}
				}
			}
			
		?>
		<?php
			echo	'<div class="card">
						<div class="card-header">
							Image Gallery
						</div>';
							$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
							$res = $mysqli->query($query);
							$userData = mysqli_fetch_array($res);

							$userID =  $userData['user_id'];

							$query = "SELECT * FROM tbgallery WHERE user_id = '$userID'";
							$result = $mysqli->query($query);
			
							echo '<div class="card-body">';
								if ($result->num_rows > 0) 
								{
									echo '<div class="row imageGallery">';

											while($row = $result->fetch_assoc())
											{
												echo '<div class="col-3" style="background-image: url(gallery/'.$row["filename"].')"></div>';
											}

									echo 	'</div>';
								}
							echo '</div>';

				echo '</div>';
		?>
	</div>
</body>
</html>