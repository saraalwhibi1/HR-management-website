<?php
include "inc/header.php";
include_once('inc/config.php');
?>
<?php


function login($user_type, $username, $password)
{
    global $pdo;

    if ($user_type == "manager")
        $stmt = $pdo->prepare("SELECT * FROM `manager` WHERE username=?;");
    else
        $stmt = $pdo->prepare("SELECT * FROM `employee` WHERE emp_number=?;");

    $stmt->bindValue(1, $username);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_first_name'] = $user['first_name'];
        $_SESSION['user_last_name'] = $user['last_name'];
        $_SESSION['user_type'] = $user_type;
        $_SESSION['user_homepage'] = get_user_homepage($user_type);
        exit(header("Location: " . $_SESSION['user_homepage']));
    } else {
        $_SESSION['error_msg'] = "Username or password not matched.";
        return false;
    }
}



function get_user_homepage($user_type)
{
    if ($user_type == 'manager')
        return 'managerHP.php';
    else
        return 'Employeehomepage.php';
}


?>

<?php
if ($current_user) {
  exit(header("Location: " . $current_user['homepage']));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['login']) && isset($_POST['password'])) {
    $emp_number =  $_POST['login'];
    $password =  $_POST['password'];
    login('manager', $emp_number, $password);
  } else {
    $_SESSION['error_msg'] = "Please provide all required data";
  }
}
?>

<html>
<title>LogIn</title>

<head>
  <link rel="stylesheet" href="css\s.css">
  <style>
    html {
      background-color: #F7ECDE;

    }

    body {
      font-family: "Poppins", sans-serif;
      height: 100vh;
    }


    a {
      color: #92badd;
      display: inline-block;
      text-decoration: none;
      font-weight: 400;
    }

    h2 {
      text-align: center;
      font-size: 16px;
      font-weight: 600;
      text-transform: uppercase;
      display: inline-block;
      margin: 40px 8px 10px 8px;
      color: #cccccc;
    }



    /* STRUCTURE */

    .wrapper {
      display: flex;
      align-items: center;
      flex-direction: column;
      justify-content: center;
      width: 100%;
      min-height: 100%;
      padding: 20px;
    }

    #formContent {
      -webkit-border-radius: 10px 10px 10px 10px;
      border-radius: 10px 10px 10px 10px;
      background: #fff;
      padding: 30px;
      width: 90%;
      max-width: 450px;
      position: relative;
      padding: 0px;
      -webkit-box-shadow: 0 30px 60px 0 rgba(0, 0, 0, 0.3);
      box-shadow: 0 30px 60px 0 rgba(0, 0, 0, 0.3);
      text-align: center;
    }

    #formFooter {
      background-color: #f6f6f6;
      border-top: 1px solid #dce8f1;
      padding: 25px;
      text-align: center;
      -webkit-border-radius: 0 0 10px 10px;
      border-radius: 0 0 10px 10px;
    }



    /* TABS */

    h2.inactive {
      color: #cccccc;
    }

    h2.active {
      color: #0d0d0d;
      border-bottom: 2px solid #5fbae9;
    }



    /* FORM TYPOGRAPHY*/

    input[type=button],
    input[type=submit],
    input[type=reset] {
      background-color: #54BAB9;
      border: none;
      color: white;
      padding: 15px 80px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      text-transform: uppercase;
      font-size: 13px;
      -webkit-box-shadow: 0 10px 30px 0 #aaf8f7;
      box-shadow: 0 10px 30px 0 #aaf8f7;
      -webkit-border-radius: 5px 5px 5px 5px;
      border-radius: 5px 5px 5px 5px;
      margin: 5px 20px 40px 20px;
      -webkit-transition: all 0.3s ease-in-out;
      -moz-transition: all 0.3s ease-in-out;
      -ms-transition: all 0.3s ease-in-out;
      -o-transition: all 0.3s ease-in-out;
      transition: all 0.3s ease-in-out;
    }

    input[type=button]:hover,
    input[type=submit]:hover,
    input[type=reset]:hover {
      background-color: Light;
    }

    input[type=button]:active,
    input[type=submit]:active,
    input[type=reset]:active {
      -moz-transform: scale(0.95);
      -webkit-transform: scale(0.95);
      -o-transform: scale(0.95);
      -ms-transform: scale(0.95);
      transform: scale(0.95);
    }

    input[type=text],
    input[type=password] {
      background-color: Light;
      border: none;
      color: Light;
      padding: 15px 32px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin: 5px;
      width: 85%;
      border: 2px solid #f6f6f6;
      -webkit-transition: all 0.5s ease-in-out;
      -moz-transition: all 0.5s ease-in-out;
      -ms-transition: all 0.5s ease-in-out;
      -o-transition: all 0.5s ease-in-out;
      transition: all 0.5s ease-in-out;
      -webkit-border-radius: 5px 5px 5px 5px;
      border-radius: 5px 5px 5px 5px;
    }

    input[type=text],
    input[type=password]:focus {
      border-bottom: 2px solid Light;
    }

    input[type=text] input[type=password]:placeholder {
      color: teal;
    }



    /* ANIMATIONS */




    /* OTHERS */

    *:focus {
      outline: none;
    }

    #icon {
      width: 60%;
    }

    * {
      box-sizing: border-box;
    }

    .box {
      width: 100%;
      height: 0px;
      border-radius: 5px;
      box-shadow: 0 2px 30px rgba(black, .2);
      background: lighten(#f0f4c3, 10%);
      position: relative;
      overflow: hidden;
      transform: translate3d(0, 0, 0);
      background-color: #cff0f7;
    }

    /*This is the waves css for add requests page the same as employee only title if you want to chnge it  */
    .boxaddreq {

      width: 100%;
      height: px;
      border-radius: 5px;
      box-shadow: 0 2px 30px rgba(black, .2);
      background: lighten(#f0f4c3, 10%);
      position: relative;
      overflow: hidden;
      transform: translate3d(0, 0, 0);
    }

    .title {
      position: relative;
      left: 30px;
      top: 0;
      width: 100%;
      z-index: 1;
      line-height: 60px;
      text-align: left;
      transform: translate3d(0, 0, 0);
      color: #54BAB9;
      font-family: 'Playfair Display', serif;
      letter-spacing: .2em;
      font-size: 24px;
      text-shadow: 0 1px 0 rgba(black, .1);
      text-indent: .3em;
    }


    .wave {
      opacity: .4;
      position: absolute;
      top: 100%;
      left: 50%;
      background: #54BAB9;
      width: 100%;
      height: 500px;
      margin-left: -250px;
      margin-top: -250px;
      transform-origin: 50% 48%;
      border-radius: 43%;
      animation: drift 3000ms infinite linear;
    }

    .wave.-three {
      animation: drift 5000ms infinite linear;
    }

    .wave.-two {
      animation: drift 7000ms infinite linear;
      opacity: .1;
      background: brown;
    }

    .box:after {
      content: '';
      display: block;
      left: 0;
      top: 60%;
      width: 100%;
      height: 100%;
      background: linear-gradient(to bottom, rgba(#e8a, 1), rgba(#def, 0) 80%, rgba(white, .5));
      z-index: 11;
      transform: translate3d(0, 0, 0);
    }

    @keyframes drift {
      from {
        transform: rotate(0deg);
      }

      from {
        transform: rotate(360deg);
      }
    }
  </style>
</head>


<body>

  <div class='boxaddreq'>
    <div class='wave -one'></div>
    <div class='wave -two'></div>
    <div class='wave -three'></div>


    <div class="wrapper fadeInDown">
      <div id="formContent">
        <!-- Tabs Titles -->
        <h2 class="active"> Log In Manger </h2>



        <!-- Login Form -->
        <form id="mloginForm" action="mlogin.php" method="post">
          <div>
            <input type="text" id="mlogin" class="fadeIn second" name="login" placeholder="username"">
      <p></p>
      </div>
      <div>
      <input type="password" id="mpassword" class="fadeIn third" name="password" placeholder="password">
            <p></p>
          </div>
          <input type="submit" onclick="checkMLoginInputs(); return false;" class="fadeIn fourth" value="Log In">
        </form>

        <?php if (isset($_SESSION['error_msg'])) { ?>
          <div class="error_msg" style="display: block" id="error_msg" role="alert"><?= $_SESSION['error_msg'] ?></div>
        <?php } elseif (isset($_SESSION['success_msg'])) { ?>
          <div class="success_msg" style="display: block" id="success_msg" role="alert"><?= $_SESSION['success_msg'] ?></div>
        <?php } ?>

        <!-- Remind Passowrd -->
        <div id="formFooter">
          <a class="underlineHover" href="#">Forgot Password?</a>
        </div>

      </div>
    </div>

    <script src="js/LJ.js"> </script>
</body>

<?php
if (isset($_SESSION['error_msg'])) {
  unset($_SESSION['error_msg']);
} elseif (isset($_SESSION['success_msg'])) {
  unset($_SESSION['success_msg']);
} ?>

</html>