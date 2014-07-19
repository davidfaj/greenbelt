<?php
session_start();
// if user clicked on register
if(isset($_POST['action']) && $_POST['action']=='register'){
	// gets inputs values to session
	$_SESSION['first_name']=$_POST['first_name'];
	$_SESSION['last_name']=$_POST['last_name'];
	$_SESSION['email']=$_POST['email'];
	$_SESSION['password']=$_POST['password'];
	$_SESSION['confirm_password']=$_POST['confirm_password'];
	$_SESSION['errors']='';
	//validate inputs
	if(empty($_SESSION['first_name'])){
		$_SESSION['errors']=$_SESSION['errors'] . '<p class="red">first name is required</p>';
	}
	if(empty($_SESSION['last_name'])){
		$_SESSION['errors']=$_SESSION['errors'] . '<p class="red">last name is required</p>';
	}
	if(empty($_SESSION['email'])){
		$_SESSION['errors']=$_SESSION['errors'] . '<p class="red">email is required</p>';
	}
	elseif(!filter_var($_SESSION['email'], FILTER_VALIDATE_EMAIL)){
		$_SESSION['errors']=$_SESSION['errors'] . '<p class="red">this email address is not valid</p>';
	}
	if(empty($_SESSION['password'])){
		$_SESSION['errors']=$_SESSION['errors'] . '<p class="red">password is required</p>';
	}
	if(empty($_SESSION['confirm_password'])){
		$_SESSION['errors']=$_SESSION['errors'] . '<p class="red">confirm password is required</p>';
	}
	if($_SESSION['password']!=$_SESSION['confirm_password']){
		$_SESSION['errors']=$_SESSION['errors'] . '<p class="red">confirm password is not matching password</p>';
	}
	// if inputs are not valid, go back to index.php
	if(!empty($_SESSION['errors'])){
		header('location: index.php');
		die();
	}
	// if inputs are valid, proceed
	// connect to database
	include_once('connection.php');
	// treat data before inserting in the table
	$first_name=escape_this_string($_SESSION['first_name']);
	$last_name=escape_this_string($_SESSION['last_name']);
	$email=escape_this_string($_SESSION['email']);
	$password=escape_this_string($_SESSION['password']);
	// insert new user data into table users
	$query="INSERT INTO users (first_name, last_name, email, password, created_at, updated_at) " .
		"VALUES ('" . $first_name . "', '" .$last_name . "', '" . $email . "', '" . $password . "', NOW(), NOW());";
	run_mysql_query($query);
	$_SESSION['success_message']='<p class="green">' . $_SESSION['first_name'] . ', you have succesfully registered!</p>';
	unset($_SESSION['first_name']);
	unset($_SESSION['last_name']);
	unset($_SESSION['email']);
	unset($_SESSION['password']);
	header('location: index.php');
	die();
}

// if user clicked on login
if(isset($_POST['action']) && $_POST['action']=='login'){
	// connect to database
	include_once('connection.php');
	// get and treat inputs to variables
	$_SESSION['email']=$_POST['email'];
	$email = escape_this_string($_POST['email']);
	$password = escape_this_string($_POST['password']);
	$query="SELECT * FROM users WHERE email = '" . $email . "';";
	$query_result = fetch_record($query);
	// if query brings no results, write message and die
	if(is_null($query_result['id'])){
		$_SESSION['errors_login'] = '<p class="red">This email is not registered yet. Please register.</p>';
		header('location: index.php');
		die();
	}
	// if query bring a result, compare the passwords (input vs db)
	if($password!=$query_result['password']){
		$_SESSION['errors_login'] = '<p class="red">This is not the correct password. Please try again.</p>';
		header('location: index.php');
		die();
	}
	// if password is correct, proceed
	$_SESSION['user_id'] = $query_result['id'];
	$_SESSION['first_name']=$query_result['first_name'];
	$_SESSION['last_name']=$query_result['last_name'];
	$_SESSION['email']=$query_result['email'];
	header('location: home.php');
	die();
}

// if user clicked on logoff
if(isset($_GET['action']) && $_GET['action']=='logoff'){
	session_destroy();
	header('location: index.php');
	die();
}

// if user clicked on create_incident
if(isset($_POST['action']) && ($_POST['action']=='create_incident')){
	// gets inputs values to session
	$_SESSION['new_incident_name']=$_POST['new_incident_name'];
	$_SESSION['new_incident_date']=$_POST['new_incident_date'];
	$_SESSION['errors']='';
	//validate inputs
	if(empty($_SESSION['new_incident_name'])){
		$_SESSION['errors']=$_SESSION['errors'] . '<p class="red">incident name is required</p>';
	}elseif(strlen($_SESSION['new_incident_name'])<10){
		$_SESSION['errors']=$_SESSION['errors'] . '<p class="red">incident name must have at least 10 characters</p>';
	}
	if(empty($_SESSION['new_incident_date'])){
		$_SESSION['errors']=$_SESSION['errors'] . '<p class="red">incident date is required</p>';
	}
	elseif(count(explode("/", $_SESSION['new_incident_date']))!=3){
		$_SESSION['errors']=$_SESSION['errors'] . '<p class="red">incident date is not valid, use the format "yyyy/mm/dd"</p>';
	}
	// if inputs are not valid, display error messages
	if(!empty($_SESSION['errors'])){
		header('location: home.php');
		die();
	}
	// if inputs are valid, proceed
	// connect to database
	include_once('connection.php');
	// get user data to variable
	$new_incident_name = escape_this_string($_POST['new_incident_name']);
	$new_incident_date = escape_this_string($_POST['new_incident_date']);
	$user_id = $_SESSION['user_id'];
	// insert user data into database
	$query = "INSERT INTO incidents (name, created_at, updated_at, creator_id) VALUES ('" .
		 $new_incident_name . "', '" . $new_incident_date . "', NOW(), " . $user_id . ");";
	run_mysql_query($query);
	header('location: home.php');
	die();
}

// if user clicked on delete_message
if(isset($_POST['action']) && ($_POST['action']=='delete_incident')){
	// connect to database
	include_once('connection.php');
	// get user data to variable
	$incident_id = $_POST['incident_id'];
	// delete data from database
	$query = "DELETE FROM incidents WHERE (id=" . $incident_id . ");";
	run_mysql_query($query);
	header('location: home.php');
	die();
};





// if user clicked on Yes
if(isset($_POST['action']) && ($_POST['action']=='Yes')){
	// connect to database
	include_once('connection.php');
	// get user data to variable
	$incident_id=$_POST['incident_id'];
	$user_id = $_SESSION['user_id'];
	// insert user data into database
	$query = "INSERT INTO users_has_incidents (users_id, incidents_id) VALUES (" . $user_id . ", " . $incident_id . ");";
	run_mysql_query($query);
	header('location: home.php');
	die();
}
?>