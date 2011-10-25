<!-- START: setup/header.tpl -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Flare</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta name="copyright" content="&copy; 2004 Flare Project Team">
		<meta name="Author" content="Flare Project Team">
		<link rel='stylesheet' type='text/css' href='setup/templates/styles.css'>
		<link rel="shortcut icon" href="images/favicon.ico">

		<script language='javascript'>

		{ $SAJAX_JAVASCRIPT }

		{ literal }

		function change_sidebar_cb(result) {
			document.getElementById("navigation").innerHTML = result;
		}

		function welcome_main() {
			x_change_sidebar("welcome", change_sidebar_cb);
			x_welcome_main(welcome_main_cb);
		}

		function welcome_main_cb(result) {
			document.getElementById("content").innerHTML = result;
		}

		function welcome_database() {
			x_change_sidebar("database", change_sidebar_cb);
			x_welcome_database(welcome_database_cb);
		}

		function welcome_database_cb(result) {
			document.getElementById("content").innerHTML = result;
		}

		function welcome_extensions() {
			x_change_sidebar("extensions", change_sidebar_cb);
			x_welcome_extensions(welcome_extensions_cb);
		}

		function welcome_extensions_cb(result) {
			document.getElementById("content").innerHTML = result;
		}

		function welcome_directory() {
			x_change_sidebar("directory", change_sidebar_cb);
			x_welcome_directory(welcome_directory_cb);
		}

		function welcome_directory_cb(result) {
			document.getElementById("content").innerHTML = result;
		}

		function welcome_admin() {
			x_change_sidebar("admin", change_sidebar_cb);
			x_welcome_admin(welcome_admin_cb);
		}

		function welcome_admin_cb(result) {
			document.getElementById("content").innerHTML = result;
		}

		function welcome_finished() {
			x_change_sidebar("finished", change_sidebar_cb);
			x_welcome_finished(welcome_finished_cb);
		}

		function welcome_finished_cb(result) {
			document.getElementById("content").innerHTML = result;
		}

		function setup_database() {
			cdb 		= document.getElementById("create_database").checked;
			cus 		= document.getElementById("create_user").checked;
			root_user	= document.getElementById("priv_username").value;
			root_pass	= document.getElementById("priv_password").value;
			hostname 	= document.getElementById("dbsrvr").value;
			dbname		= document.getElementById("dbname").value;
			dbuser		= document.getElementById("dbuser").value;
			dbpwrd		= document.getElementById("dbpwrd").value;

			if (dbname == "") {
				alert("You must include the name of the database to install Flare in");
				return;
			}

			if (dbuser == "") {
				alert("You must provide a username for Flare");
				return;
			}

			if (dbpwrd == "") {
				alert("You must provide a password for Flare");
				return;
			}

			if (dbuser == "root") {
				alert("Flare does not allow you to use the root account on the database.");
				return;
			}

			if (cdb) {
				if (root_user == '') {
					alert("You must enter a privileged account name in order to create the database");
					return;
				} else if (root_pass == '') {
					alert("You must enter a password for the privileged account");
					return;
				}
			} else if (cus) {
				if (root_user == '') {
					alert("You must enter a privileged account name in order to create the flare user");
					return;
				} else if (root_pass == '') {
					alert("You must enter a password for the privileged account");
					return;
				}
			}

			x_setup_database(root_user,root_pass,dbuser,dbpwrd,dbname,hostname,cdb,cus,setup_database_cb);
		}

		function setup_database_cb(result) {
			document.getElementById("database_exists").innerHTML = "<span style='color: green'>OK</span>";
			document.getElementById("user_exists").innerHTML = "<span style='color: green'>OK</span>";
			document.getElementById("tables_created").innerHTML = "<span style='color: green'>OK</span>";
			document.getElementById("initial_values").innerHTML = "<span style='color: green'>OK</span>";
			fail = 0;

			if (result == "cdbfail") {
				document.getElementById("database_exists").innerHTML = "<span style='color: red'>NOT OK</span>";
				fail = 1;
			} else if (result == "cusfail") {
				document.getElementById("user_exists").innerHTML = "<span style='color: red'>NOT OK</span>";
				fail = 1;
			} else if (result == "tblsfail") {
				document.getElementById("tables_created").innerHTML = "<span style='color: red'>NOT OK</span>";
				fail = 1;
			} else if (result == "valsfail") {
				document.getElementById("initial_values").innerHTML = "<span style='color: red'>NOT OK</span>";
				fail = 1;
			}

			document.getElementById("database_results").style.display = "block";

			if (!fail)
				document.getElementById("next_step").disabled = false;
		}

		function alternate_privileged() {
			cdb = document.getElementById("create_database").checked;
			cus = document.getElementById("create_user").checked;

			if (cdb || cus) {
				document.getElementById("privileged").style.display = "block";
			} else {
				document.getElementById("privileged").style.display = "none";
			}
		}

		function check_writable(section) {
			hdir = document.getElementById("home_dir").value;
			gdir = document.getElementById("group_dir").value;

			if (hdir == '')
				hdir = '--';
			if (gdir == '')
				gdir = '--';

			x_check_writable(hdir, check_writable_home_cb);
			x_check_writable(gdir, check_writable_group_cb);

			document.getElementById("directory_results").style.display = "block";
		}

		function check_writable_home_cb(result) {
			if (result == '1') {
				document.getElementById("home").innerHTML = "<span style='color: green'>YES</span>";
				document.getElementById("home_verified").value = "yes";
			} else {
				document.getElementById("home").innerHTML = "<span style='color: red'>NO</span>";
				document.getElementById("home_verified").value = "no";
			}

			hv = document.getElementById("home_verified").value;
			gv = document.getElementById("group_verified").value;

			if (hv == "yes" && gv == "yes")
				document.getElementById("make_dirs").disabled = false;
			else if (hv == "no" && gv == "yes")
				document.getElementById("make_dirs").disabled = true;
			else if (hv == "yes" && gv == "no")
				document.getElementById("make_dirs").disabled = true;
			else if (hv == "no" && gv == "no")
				document.getElementById("make_dirs").disabled = true;
		}

		function check_writable_group_cb(result) {
			if (result == '1') {
				document.getElementById("group").innerHTML = "<span style='color: green'>YES</span>"
				document.getElementById("group_verified").value = "yes";
			} else {
				document.getElementById("group").innerHTML = "<span style='color: red'>NO</span>"
				document.getElementById("group_verified").value = "no";
			}

			hv = document.getElementById("home_verified").value;
			gv = document.getElementById("group_verified").value;

			if (hv == "yes" && gv == "yes")
				document.getElementById("make_dirs").disabled = false;
			else if (hv == "no" && gv == "yes")
				document.getElementById("make_dirs").disabled = true;
			else if (hv == "yes" && gv == "no")
				document.getElementById("make_dirs").disabled = true;
			else if (hv == "no" && gv == "no")
				document.getElementById("make_dirs").disabled = true;
		}

		function make_directories() {
			hdir = document.getElementById("home_dir").value;
			gdir = document.getElementById("group_dir").value;

			x_make_directories(hdir,gdir,make_directories_cb);
		}

		function make_directories_cb(result) {
			if (result == '1')
				document.getElementById("next_step").disabled = false;
			else
				document.getElementById("next_step").disabled = true;
		}

		function install_extension(extension) {
			x_install_extension(extension, install_extension_cb);
		}

		function install_extension_cb(result) {
			data = result.split(';');

			if (data[0] == '1') {
				document.getElementById(data[1]).disabled = true;
				document.getElementById(data[1]+"_result").innerHTML = "<span style='color: green'>OK</span>"
				document.getElementById(data[1]+"_result").style.display = "block";
			} else {
				document.getElementById(data[1]).disabled = false;
				document.getElementById(data[1]+"_result").innerHTML = "<span style='color: red'>FAIL</span>"
				document.getElementById(data[1]+"_result").style.display = "block";
			}

			x_check_for_necessary_extensions(check_for_necessary_extensions_cb);
		}

		function check_for_necessary_extensions_cb(result) {
			if (result == '1') {
				document.getElementById("next_step").disabled = false;
			} else {
				document.getElementById("next_step").disabled = true;
			}
		}

		function create_admin() {
			fname 		= document.getElementById("fname").value;
			lname 		= document.getElementById("lname").value;
			email 		= document.getElementById("email").value;
			username 	= document.getElementById("username").value;
			password 	= document.getElementById("password").value;
			verify_password	= document.getElementById("verify_password").value;
			auth_type 	= document.getElementById("auth_type").value;

			if (fname == '') {
				alert("Please enter a first name");
				return;
			} else if (lname == '') {
				alert("Please enter a last name");
				return;
			} else if (email == '') {
				alert("Please enter an email address");
				return;
			} else if (username == '') {
				alert("You must specify a username");
				return;
			} else if (password == '') {
				alert("Flare does not allow blank passwords. Please enter a password");
				return;
			} else if (password != verify_password) {
				alert("The passwords you entered do not match");
				return;
			}

			x_create_admin(fname,lname,email,username,password,auth_type,create_admin_cb);
		}

		function create_admin_cb(result) {
			if (result == '1')
				document.getElementById("next_step").disabled = false;
			else
				document.getElementById("next_step").disabled = true;
		}

		{ /literal }
		</script>
	</head>
<!-- END: setup/header.tpl -->
