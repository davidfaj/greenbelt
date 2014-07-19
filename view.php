<?php
	session_start();
	// if user is not logged, cannot see this page
	if(!isset($_SESSION['user_id'])){
		header('location: index.php');
		die();
	}
	include_once('connection.php');
	$incident_id=$_GET['incident_id'];
	$query="SELECT * FROM incidents WHERE id=" . $incident_id . ";";
	$query_result = fetch_record($query);
	// if logged user got in this page using other id number (non-existent), send back to home.php
	if(is_null($query_result)){
		header('location: home.php');
		die();
	}
?>
<!doctype html>
<html>
<head>
	<title>The Codingdojo Crime Watch</title>
	<meta charset="utf-8">
	<style type="text/css">
		* {font-family: sans-serif;}
		a {padding: 5px; text-align: center; background-color: green; color: white; font-weight: bold;}
		input {background-color: red; color: white; font-weight: bold; padding: 5px; margin-left: 50px;}
	</style>
</head>
<body>
	<h1>Incident name: <?php echo $query_result['name']; ?></h1>
	<h1>Incident date: <?php echo date('F jS Y', strtotime($query_result['created_at'])); ?></h1>
	<h1>Seen by: <?php echo '01'; ?> People</h1>
	<div class="viewers">
		<?php
			include_once('connection.php');
			$query="SELECT users.first_name, users.last_name
	FROM users_has_incidents
	JOIN users ON users.id=users_has_incidents.users_id
	JOIN incidents ON incidents.id=users_has_incidents.incidents_id
	WHERE users_has_incidents.incidents_id = " . $_SESSION['user_id'] . ";";
			$query_results=fetch_all($query);
			foreach($query_results as $row){
				echo '<p>' . $row['first_name'] . " " . $row['last_name'] . '</p>';
			};
		?>
	</div><!-- end of viewers DIV -->
	<form action="process.php" method="post">
		<a href="home.php">Back to home</a>
		<input type="submit" value="DELETE THIS INCIDENT">
		<input type="hidden" name="action" value="delete_incident">
		<input type="hidden" name="incident_id" value=<?php echo '"' . $incident_id; ?>">
	</form>
</body>
</html>