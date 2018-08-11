<?php

	login();

?>
<!DOCTYPE html 
      PUBLIC "-//W3C//DTD HTML 4.01//EN"
      "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en-US">
	<head profile="http://www.w3.org/2005/10/profile">
		<link rel="icon" type="image/png" href="//swordknight.com/icon.png" />
    
		<meta charset='utf-8'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="theme/swordknight.com/menu.css">
		<link rel="stylesheet" type="text/css" href="theme/swordknight.com/skbc.css">
		<link rel="stylesheet" type="text/css" href="theme/swordknight.com/frontpage.css">
		
	</head>
	<body>
		<header id='header-menu'>
			<h1><a href="."><img src="theme/swordknight.com/img/skbc%20header%20logo.png"></a></h1>
      <div class="topnav" id="myTopnav">
        <a href="." id="home" class="active">SKBC</a>
        <div class="dropdown">
          <button class="dropbtn">My Account
            <i class="fa fa-caret-down"></i>
          </button>
          <div class="dropdown-content">
            <?php if (!logged_in()): ?><a href="register">Register</a><?php endif; ?>
            <?php if (logged_in()): ?><a href="logout">Log out</a><?php else: ?><a href="login">Login</a><?php endif; ?>
            <a href="my-classes">My Classes</a>
            <a href="pre-pay">Pre-Pay</a>
          </div>
        </div> 
        <div class="dropdown">
          <button class="dropbtn">Classes
            <i class="fa fa-caret-down"></i>
          </button>
          <div class="dropdown-content">
            <a href="class-list">List</a>
            <a href="class-descriptions">Descriptions</a>
            <a href="class-registrations">Registrations</a>
          </div>
        </div> 
        <div class="dropdown">
          <button class="dropbtn">Information
            <i class="fa fa-caret-down"></i>
          </button>
          <div class="dropdown-content">
            <a href="for-students">Students</a>
            <a href="for-instructors">Instructors</a>
            <a href="for-fwack">FWACK</a>
            <a href="calendar">Calendar</a>
            <a href="hosts">Hosts</a>
          </div>
        </div>
        <a href="javascript:void(0);" class="icon" onclick="openmenu()"><i class="fa fa-bars"></i></a>
      </div>
      <script>
        function openmenu() {
            var x = document.getElementById("myTopnav");
            if (x.className === "topnav") {
                x.className += " responsive";
            } else {
                x.className = "topnav";
            }
        }
      </script>
		</header>