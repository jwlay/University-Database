<!DOCTYPE html>
<html>
<head>
</head>
<body>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<div class="container">
<div class="row">
<div class="col-md-2"></div>
<div class="col-md-6">
<h1>University Search</h1>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
<input type="text" name="searchQuery">
<input class="button btn btn-default" type="submit" Value="Search" name="Search">
<?php

	header('Content-Type: text/html; charset=utf-8');
	$servername = "localhost";
	$port = '5432';
	$username = "Jan";
	$password = "";
	$dbname = "Jan";

	$conn = pg_connect( "host=$servername port=$port user=$username password=$password dbname=$dbname");

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}


	if (isset($_POST['searchQuery'])) {
		$query = trim ($_POST['searchQuery']);
		$search = "SELECT c2.*, gis_distance(c1.location, c2.location) AS distance FROM university c1, university c2 WHERE c1.name = '$query' ORDER BY distance ASC;";
		$result = pg_query($search) or die('query did not work');

		echo "<table style='width:100%' class='table-striped'>";
			echo "<thread>";
				echo "<tr>";
				echo "<th>";
				echo "Name";
				echo "</th>";
				echo "<th>";
				echo "Address"; 
				echo "</th>";
				echo "<th>";
				echo "Distance (in km)"; 
				echo "</th>";
				echo "</tr>";
			echo "</thread>";
			echo "<tbody>";
		while($result_arr = pg_fetch_array( $result )) 
			{ 
			echo "<tr>";
			echo "<td>";
			echo $result_arr['name'];
			echo "</td>";
			echo "<td>";
			echo $result_arr['address']; 
			echo "</td>";
			echo "<td>";
			echo $result_arr['distance']; 
			echo "</td>";
			echo "</tr>";
			}
		echo "</tbody>";
		echo "</table>";

		$anymatches=pg_num_rows($result); 
			if ($anymatches == 0) 
			{ 
			   echo "Nothing was found that matched your query.<br><br>"; 
			}
	}

?>
</form>
</div>
<div class="col-md-2"></div>
</div>
</div>

</body>
</html>