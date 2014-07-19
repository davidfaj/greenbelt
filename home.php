<?php
	session_start();
	// if user id is not set, send user back to index.php
	if(!isset($_SESSION['user_id'])){
		header('location: index.php');
		die();
	}
?>
<!doctype html>
<html lang="en">
<head>
	<title>The Codgindojo Crime Watch</title>
	<meta charset="utf-8">
	<style type="text/css">
		* {font-family: sans-serif;}
		table, th, td, tr {border-collapse: collapse; border: solid 1px black;}
		thead {background-color: #c0c0c0;}
		th {width: 180px;}
		td {width: 180px;}
		.welcome {display: inline-block; width: 840px;}
		.logoff {display: inline-block; width: 100px;}
		.create_incident {background-color: blue; color: white; font-weight: bold;}
		.home_messages {width: 940px;}
		.red {color: red;}
	</style>
</head>
<body>
	<h1 class="welcome">Welcome, <?php echo $_SESSION['first_name']; ?></h1>
	<a href="process.php?action=logoff" class="logoff">LOG OFF</a>
	<table>
		<thead>
			<tr>
				<th>Incident</th>
				<th>Date</th>
				<th>Reported by</th>
				<th>Did you see it?</th>
				<th>Link</th>
			</tr>
		</thead>
		<tbody>
			<form action="process.php" method="post">
			<?php
				include_once('connection.php');
				$query = "SELECT incidents.id, incidents.name, incidents.created_at, users.first_name " .
						"FROM incidents LEFT JOIN users ON incidents.creator_id = users.id ORDER BY created_at DESC;";
				$query_results = fetch_all($query);
				foreach($query_results as $row){
					echo '<tr>
							<td>' . $row['name'] .			  					'</td>
							<td>' . date('F jS Y', strtotime($row['created_at'])) . '</td>
							<td>' . $row['first_name'] .	  					'</td>
							<td>' . 
								'<input type="submit" name="action" value="Yes">
								<input type="hidden" name="incident_id" value="' . $row['id'] . '">'
							. '</td>
							<td><a href="view.php?incident_id=' . $row['id'] . '">GO</a></td>
						  </tr>';
				}
			?>
			</form>
		</tbody>
	</table>
	<h1>Add a new Incident...</h1>
	<form action="process.php" method="post">
		<label>Incident name: <input type="text" name="new_incident_name"></label>
		<label>Incident date: <input type="text" name="new_incident_date"></label>
		<input type="submit" value="CREATE" class="create_incident">
		<input type="hidden" name="action" value="create_incident">
	</form>
	<div class="home_messages">
		<?php
			if(!empty($_SESSION['errors'])){
				echo $_SESSION['errors'];
				unset($_SESSION['errors']);
			}
			else{
				echo '';
				unset($_SESSION['errors']);
			}
		?>
	</div><!-- end of home_messages DIV -->
</body>
</html>