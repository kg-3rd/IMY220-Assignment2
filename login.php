<?php
	
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;	
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<meta charset="utf-8" />
	<meta name="author" content="Kgothalang Moifo">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
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

					if(isset($_FILES["picToUpload"]))
					{	
						$pic_name = $_FILES["picToUpload"]["name"];
						$file_path = pathinfo($pic_name);
						$file_ext = $file_path["extension"];
						$file_ext = strtolower($file_ext);

						if(($file_ext == "jpeg" || $file_ext == "jpg") && $_FILES["picToUpload"]["size"] < 1000000)
						{
							$directory = "gallery/";
							$file_name = $file_path['filename'];//name without extension 
							$temp_name = $_FILES['picToUpload']['tmp_name'];
							$path_filename_ext = $directory.$file_name.".".$file_ext;
							
							move_uploaded_file($temp_name,$path_filename_ext);
							$_query =  "INSERT INTO tbgallery (filename, user_id)
										VALUES ('$pic_name','$row[user_id]')";
							$_res = $mysqli->query($_query);
							if($_res) 
							{
								echo '<div class="alert alert-success mt-3" role="alert">
											File upload successful!
										</div>';

							}
							else 
							{
								echo "Error: " . $query . "<br>" . $mysqli->error;
							}
							
						}
						else
						{
							echo '<div class="alert alert-danger mt-3" role="alert">
									File too large! Only files less than 1MB allowed!
								</div>';
						}
					}
			

					echo 	"<form action='login.php' enctype='multipart/form-data' method='POST'>
								<div class='form-group'>
									<input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
									<input name='loginEmail' value='".$row['email']."' hidden>
									<input name='loginPass' value='".$row['password']."' hidden>
								</div>
							  </form>";
							  
					$query1 = "SELECT filename FROM tbgallery WHERE user_id = '$row[user_id]'";
					$result = $mysqli->query($query1);
					if($result->num_rows > 0)
					{
						echo 	"<div><h1>Image Gallery</h1>
								<div class='row imageGallery'>";
						while($row =  $result->fetch_assoc())
						{
							// echo $row["filename"];
							echo 	"<div class='col-3' style='background-image: url(gallery/$row[filename])'></div>";
						}
						echo 	"</div></div>";
					}
					else
					{
						echo 	'<div class="alert alert-danger mt-3" role="alert">
									You do not have images uploaded on this site!
								</div>';
					}
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
			
			
		$mysqli->close();
		?>
	</div>
</body>
</html>