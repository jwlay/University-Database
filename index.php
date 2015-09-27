<!DOCTYPE html>
<html>
<head>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.9/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

	<style media="screen">
		.btn-circle {
			width: 50px;
		  height: 50px;
		  padding: 10px 16px;
		  font-size: 18px;
		  line-height: 1.33;
		  border-radius: 25px;
			margin-top:40px;
			margin-left: 10px;
		}
	</style>
</head>
<body>

<div class="container">
	<div class="row" style="margin-bottom:20px">
		<div class="col-md-2"></div>
		<div class="col-md-6">
			<h1 class="text-center">University Search<p>
					<small>by Jan Wohlfahrt-Laymann</small></h1>
		</div>
		<div class="col-md-1"></div>
		<div class="col-md-1">
			<button type="button" class="btn btn-primary btn-circle" data-toggle="modal" data-target="#info-modal">
				<i class="fa fa-info"></i>
			</button>
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

<!-- Info Modal -->
<div class="modal fade" id="info-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Info</h4>
      </div>
      <div class="modal-body">
				<h2>University Location Comparison DB<p>
					<small>Cross Cultural and Context Computing - Module 1</small>
				</h2>
				<p>Locational Databases allow the comparison of location and the capture of the spatial distance between points.
				<p>There are many exchange students at the University of Jyväskylä, that might be interested to know the distance between their home University and Jyväskylä and compare the distance they had to travel. Therefore I created a database of Universities and added their coordinates as metadata.
				<p>By using the Formula to calculate the distance between two points on the earth sphere as a function in PostgreSQL it is possible to order Universities by distance from any given University in the Database.
				<p>In order to access the Database a small PhP / Html script is written that allows a User to access and use the database and it’s functionality by entering a University in the Search Box, the Query will retrieve the Universities and order them by distance compared to it.
				<p>The full code can be found on: <a href="https://github.com/jwlay/University-Database">https://github.com/jwlay/University-Database</a>

      </div>
    </div>
  </div>
</div>

</body>
</html>
