<?php
include "inc/header.php";
include_once('inc/config.php');

if ($current_user) {
  exit(header("Location: " . $current_user['homepage']));
}
?>

<!DOCETYPE>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> Manage Me </title>
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>

  <header>

    <div class="logo"> <a href = "index.php"><img src="img/logo.png" alt="logo"></a> </div>
      
    
    
    <div class="breadcrumbs">
      <p><a href="index.php">Regesteration</a> &#62; </p>
    </div>
    
    </header>
    

<main>

    <svg viewbox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <style type="text/css">
            .wave {
              animation: wave 8s linear infinite;
            }
      
            .wave1 {
              animation: wave1 10s linear infinite;
            }
      
            .wave2 {
              animation: wave2 12s linear infinite;
            }
      
            @keyframes wave {
              0% {
                transform: translateX(0%);
              }
      
              100% {
                transform: translateX(100%);
              }
            }
      
            @keyframes wave1 {
              0% {
                transform: scaleY(1.2) translateX(0%);
              }
      
              100% {
                transform: scaleY(1.2) translateX(100%);
              }
            }
      
            @keyframes wave2 {
              0% {
                transform: scaleY(.8) translateX(0%);
              }
      
              100% {
                transform: scaleY(.8) translateX(100%);
              }
            }
          </style>
          <path id='sineWave' fill="#54BAB9" fill-opacity="0.2" d="M0,160 C320,300,420,300,740,160 C1060,20,1120,20,1440,160 V0 H0" />
        </defs>
        <use class="wave" href="#sineWave" />
        <use class="wave" x="-100%" href="#sineWave" />
        <use class="wave1" href="#sineWave" />
        <use class="wave1" x="-100%" href="#sineWave" />
        <use class="wave2" href="#sineWave" />
        <use class="wave2" x="-100%" href="#sineWave" />
      </svg>
     
      <div class="loginb">
      <button id='elogin' style="box-shadow: 3px 3px #b3b3b3;" type="button" onclick="" return false;> <a href = "LogIn.php">Employee Log-in </a></button> <button id='mlogin' style="box-shadow: 3px 3px #b3b3b3;" type="button" onclick="" return false;> <a href = "mlogin.php">Manager Log-in </a> </button>
      <br> <div class='newemp'><span class="text-muted">New Employee?</span>
      <span ><a href="sign.php">Sign up</a></span> </div> 
    </div>



    <br><br>

</main>

<footer>
<hr>


<div class="contact">
<div class="email">
<div class="emailicon"><img src="img/emailicon.png" alt="email" ></div>
<div class="emailadd"><a href="mailto:suppurt@manageme.com"> suppurt@manageme.com </a></div>
</div>
</div> 
<br>
<hr>
<div class="copyrights">  &copy;  Manage Me 2021-2022. All rights reserved. </div>

</footer> 


</body>

</html>