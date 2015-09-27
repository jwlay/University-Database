<!DOCTYPE html>
<html>
<head>
	<style media="screen">

	</style>
</head>
<body>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.9/css/dataTables.bootstrap.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<div class="container">
	<div class="row" style="margin-bottom:20px">
		<div class="col-md-6 col-md-offset-2">
			<h1 class="text-center">University Search<p>
					<small>by Jan Wohlfahrt-Laymann</small></h1>
		</div>
	</div>
	<div class="row" style="margin-bottom:10px">
		<div class="col-md-3"></div>
		<div class="col-md-4">
			<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
			<input type="text" name="searchQuery" style="width:100%; margin-bottom:5px" placeholder="University name">
			<input class="button btn btn-primary" id="searchbutton" type="submit" Value="Search" name="Search" style="width:100%">
		</div>
	</div>
	<div class="row">
		<div class="col-md-8 col-md-offset-1">
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
				$result = pg_query_params($conn, 'SELECT c2.*, gis_distance(c1.location, c2.location) AS distance FROM university c1, university c2 WHERE c1.name = $1 ORDER BY distance ASC', array("$query")) or die('query did not work');

				$str = pg_escape_string("$query");
				$search = "SELECT c2.*, gis_distance(c1.location, c2.location) AS distance FROM university c1, university c2 WHERE c1.name = '{$str}' ORDER BY distance ASC;";
				$result = pg_query($search) or die('query did not work');

				echo "<table style='width:100%' class='table table-striped table-bordered' cellspacing='0'>";
					echo "<thead>";
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
					echo "</thead>";
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
	</div>
</div>

</body>
</html>
