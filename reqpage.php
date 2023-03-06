<?php
include "inc/header.php";
include_once('inc/config.php');

?>
<?php

function get_user_info($user_type, $id)
{
    global $pdo;

    if ($user_type == "manager")
        $stmt = $pdo->prepare("SELECT * FROM `manager` WHERE id=?;");
    else
        $stmt = $pdo->prepare("SELECT * FROM `employee` WHERE id=?;");

    $stmt->bindValue(1, $id);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        return $user;
    } else {
        $_SESSION['error_msg'] = "User not found.";
        return false;
    }
}



function get_service($id)
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM `service` WHERE id=?;");
    $stmt->bindValue(1, $id);
    $stmt->execute();
    $service = $stmt->fetch();

    if ($service) {
        return $service;
    } else {
        $_SESSION['error_msg'] = "Service not found.";
        return false;
    }
}



function get_request_info($id)
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM request WHERE id=?;");
    $stmt->bindValue(1, $id);
    $stmt->execute();
    $request = $stmt->fetch();

    if ($request) {
        return $request;
    } else {
        return false;
    }
}


function approve_request($id)
{
    global $pdo;

    $stmt = $pdo->prepare("UPDATE request SET status = ? WHERE id = ?;");
    $stmt->bindValue(1, 'Approved');
    $stmt->bindValue(2, $id);
    $result = $stmt->execute();

    if ($result) {
        $_SESSION['success_msg'] = "The request have been approved successfully.";
        return true;
    } else {
        $_SESSION['error_msg'] = "Error.";
        return false;
    }
}

function decline_request($id)
{
    global $pdo;

    $stmt = $pdo->prepare("UPDATE request SET status = ? WHERE id = ?;");
    $stmt->bindValue(1, 'Declined');
    $stmt->bindValue(2, $id);
    $result = $stmt->execute();

    if ($result) {
        $_SESSION['success_msg'] = "The request have been declined successfully.";
        return true;
    } else {
        $_SESSION['error_msg'] = "Error.";
        return false;
    }
}



?>
<?php
if ($current_user == false) {
  exit(header("Location: index.php"));
} else if (!isset($_GET['id'])) {
  exit(header("Location: " . $current_user['homepage']));
}

$request = get_request_info($_GET['id']);

if (!$request)
  exit(header("Location: " . $current_user['homepage']));

$employee = get_user_info('employee', $request['emp_id']);
$service = get_service($request['service_id']);


if (isset($_GET['action']) && isset($_GET['id'])) {
  $action = $_GET['action'];
  $result = false;

  if ($action == 'approve')
    $result = approve_request($_GET['id']);
  else if ($action == 'decline')
    $result = decline_request($_GET['id']);

  if ($result)
    exit(header("Location: reqpage.php?id=" . $_GET['id']));
}
?>

<!DOCTYPE html>
<html>

<head>

  <title>Request information</title>
  <link rel="stylesheet" href="css/style.css" />

  <style>
    main {

      background-color: #FBF8F1;
      text-align: center;
      list-style-position: outside;
      margin: 0 15%;

    }

    h1,
    h2,
    h3 {
      text-align: center;
      color: #54bab9;
    }

    #reqInfo {
      font-size: 2.5em;

    }


    hr.lead {
      border: 3px solid midnightblue;
      border-radius: 5px;
    }

    hr.line {
      border: 1px solid midnightblue;
      border-radius: 5px;
      height: 2px;
      width: 50em;
    }


    #Status {
      color: blue;
    }

    span {
      text-decoration-line: underline;
      color: #2e6b91;
    }

    img {
      display: block;
      margin-left: auto;
      margin-right: auto;
      width: 50%;
    }


    #reqDes {
      text-align: left;
      border: 2px solid #333333;
      font-size: 10pt;
      border-radius: 2% 6% 5% 4% / 1% 1% 2% 4%;
      text-transform: uppercase;
      letter-spacing: 0.2ch;
      background: #ffffff;
      position: relative;
      padding-left: 20px;
    }

    #reqDes ::before {
      content: '';
      border: 2px solid #353535;
      display: block;
      width: 100%;
      height: 100%;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate3d(-50%, -50%, 0) scale(1.015) rotate(0.5deg);
      border-radius: 1% 1% 2% 4% / 2% 6% 5% 4%;
    }

    button {
      background-color: rgb(134, 194, 157);
      color: #F7ECDE;
      padding: 14px 20px;
      margin: 8px 0;
      border: none;
      cursor: pointer;
      width: 20%;
      opacity: 0.9;
      font-size: 0.6em;
    }

    button:hover {
      opacity: 1;
    }

    /* Extra styles for the cancel button */
    .editbtn {
      padding: 14px 20px;
      background-color: gray;

    }

    /* Float cancel and signup buttons and add an equal width */
    .editbtn,
    .signupbtn {

      width: 20%;
    }

    .Declinebtn {
      background-color: rgb(202, 95, 95);
    }


    .box {

      background-color: #F7ECDE;
    }

    .wave {
      z-index: -1;
      opacity: .4;
      position: absolute;
      top: 80%;
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

    .wave1 {
      z-index: -1;
      opacity: .4;
      position: absolute;
      top: 40%;
      left: 2%;
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
      z-index: -1;
      animation: drift 5000ms infinite linear;
    }

    .wave.-two {
      z-index: -1;
      animation: drift 7000ms infinite linear;
      opacity: .1;
      background: brown;
    }

    .wave1.-three {
      z-index: -1;
      animation: drift 5000ms infinite linear;
    }

    .wave1.-two {
      z-index: -1;
      animation: drift 7000ms infinite linear;
      opacity: .1;
      background: brown;
    }

    .boxaddreq {

      width: 100%;
      height: px;
      border-radius: 5px;
      box-shadow: 0 2px 30px rgba(black, .2);
      background: lighten(#f0f4c3, 10%);
      position: relative;
      overflow: hidden;
      transform: translate3d(0, 0, 0);
      background-color: #FBF8F1;
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

  <header>

    <div class="logo"> <a href="index.php"><img src="img/logo.png" alt="logo"></a> </div>

    <nav class="navagation">
      <a href="signout.php" accesskey="h"> Sign out </a>
    </nav>


    <div class="breadcrumbs">
      <p><a href="index.php">Home</a> &#62; Request Information </p>
    </div>

  </header>



  <div class='boxaddreq'>
    <div class='wave -one'></div>
    <div class='wave -two'></div>
    <div class='wave -three'></div>
    <div class='wave1 -one'></div>
    <div class='wave1 -two'></div>
    <div class='wave1 -three'></div>


    <main>

      <div class='box'>

        <?php if (isset($_SESSION['error_msg'])) { ?>
          <div class="error_msg" style="display: block" id="error_msg" role="alert"><?= $_SESSION['error_msg'] ?></div>
        <?php } elseif (isset($_SESSION['success_msg'])) { ?>
          <div class="success_msg" style="display: block" id="success_msg" role="alert"><?= $_SESSION['success_msg'] ?></div>
        <?php } ?>


        <h1 id="reqInfo"><span>REQUEST INFORMATION</span></h1>
        <hr class="lead">
        <h2><span>Employee Name:</span> <?= $employee['first_name'] . ' ' . $employee['last_name'] ?></h2>
        <hr class="line">
        <h2 id="reqType"><span>Request Type:</span> <?= $service['type'] ?></h2>
        <hr class="line">
        <h2 id="Status"><span>Request Status:</span> <?= $request['status'] ?></h2>
        <hr class="line">
        <h2><span>Request Description:</span><br></h2>

        <pre id="reqDes">
<?= $request['description'] ?>
</pre>
        <hr class="line">
        <h2><span>Attachments:</span></h2>

        <?php if ($request['attachment1'] != '' && @is_array(getimagesize($request['attachment1']))) { ?>
          <h3>Attached image:</h3>
          <img src="<?= $request['attachment1'] ?>" alt="img certificate" width="400" height="300">
        <?php } else if ($request['attachment1'] != '') { ?>
          <h3>Attached file:</h3>
          <a href="<?= $request['attachment1'] ?>"> &#128206;file</a><br>
        <?php } ?>

        <?php if ($request['attachment2'] != '' && @is_array(getimagesize($request['attachment2']))) { ?>
          <h3>Attached image:</h3>
          <img src="<?= $request['attachment2'] ?>" alt="img certificate" width="400" height="300">
        <?php } else if ($request['attachment2'] != '') { ?>
          <h3>Attached file:</h3>
          <a href="<?= $request['attachment2'] ?>"> &#128206;file</a><br>
        <?php } ?>

        <hr class="line">
        <br>
        <h2>
          <?php if ($current_user['type'] == 'manager') { ?>
            <?php if ($request['status'] == 'Declined') { ?>
              <button type="button" onclick="location.href='reqpage.php?action=approve&id=<?= $request['id'] ?>';">Approval</button>
            <?php } else if ($request['status'] == 'Approved') { ?>
              <button class="Declinebtn" type="button" onclick="location.href='reqpage.php?action=decline&id=<?= $request['id'] ?>';">Decline</button>
            <?php } else { ?>
              <button type="button" onclick="location.href='reqpage.php?action=approve&id=<?= $request['id'] ?>';">Approval</button> | <button class="Declinebtn" type="button" onclick="location.href='reqpage.php?action=decline&id=<?= $request['id'] ?>';">Decline</button>
            <?php } ?>
          <?php } else { ?>
            <button class="editbtn" type="button" onclick="window.location='ePage.php?id=<?= $request['id'] ?>'">Edit</button>
          <?php } ?>
        </h2>

      </div>

    </main>

    <footer>



      <div class="contact">
        <div class="email">
          <div class="emailicon"><img src="img/emailicon.png" alt="email"></div>
          <div class="emailadd"><a href="mailto:suppurt@manageme.com"> suppurt@manageme.com </a></div>
        </div>
      </div>
      <hr>
      <div class="copyrights"> &copy; Manage Me 2021-2022. All rights reserved. </div>

    </footer>

</body>

<?php
if (isset($_SESSION['error_msg'])) {
  unset($_SESSION['error_msg']);
} elseif (isset($_SESSION['success_msg'])) {
  unset($_SESSION['success_msg']);
} ?>

</html>