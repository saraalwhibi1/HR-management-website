<?php
include "inc/header.php";
include_once('inc/config.php');


?>
<?php

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






function get_all_services()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM `service`;");
    $result = $stmt->execute();

    if ($result) {
        return $stmt->fetchAll();
    } else {
        return array();
    }
}



function uplouad_request_file($request_id, $file_name, $file, $col)
{
    $target_dir = "uploads/" . $request_id . "/";
    $file_url = uplouad_file($target_dir, $file_name, $file);
    
    return edit_request_attachment($request_id, $file_url, $col);
}

function edit_request($request_id, $service_id, $description)
{
    global $pdo;

    $stmt = $pdo->prepare("UPDATE request SET service_id = ?, description = ? WHERE id = ?;");
    $stmt->bindValue(1, $service_id);
    $stmt->bindValue(2, $description);
    $stmt->bindValue(3, $request_id);
    $result = $stmt->execute();

    if ($result) {
        $_SESSION['success_msg'] = "The request have been updated successfully.";
        return true;
    } else {
        $_SESSION['error_msg'] = "Error while updating request.";
        return false;
    }
}



function uplouad_file($target_dir, $file_name, $file)
{
    global $msg;
    $target_file = strtolower($target_dir . basename($file_name));

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    } else {
        $msg = "Sorry, there was an error uploading your file.";
        return false;
    }
}



function edit_request_attachment($request_id, $file_url, $col)
{
    global $pdo;

    $stmt = $pdo->prepare("UPDATE request SET $col = ? WHERE id = ?;");
    $stmt->bindValue(1, $file_url);
    $stmt->bindValue(2, $request_id);
    $result = $stmt->execute();

    if ($result) {
        $_SESSION['success_msg'] = "The request have been updated successfully.";
        return true;
    } else {
        $_SESSION['error_msg'] = "Error while updating request.";
        return false;
    }
}






?>
<?php
if ($current_user == false) {
  exit(header("Location: index.php"));
} else if (!isset($_REQUEST['id'])) {
  exit(header("Location: " . $current_user['homepage']));
}

$request = get_request_info($_REQUEST['id']);

if (!$request)
  exit(header("Location: " . $current_user['homepage']));

$employee = get_user_info('employee', $request['emp_id']);
$service = get_service($request['service_id']);

$services = get_all_services();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['id']) && isset($_POST['field4']) && isset($_POST['field3'])) {
    $request_id =  $_POST['id'];
    $service_id =  $_POST['field4'];
    $description =  $_POST['field3'];

    $result = edit_request($request_id, $service_id, $description);

    if ($result) {
      if (isset($_FILES["file1"]["name"]) && $_FILES["file1"]["tmp_name"] != "") {
        uplouad_request_file($request_id, $_FILES["file1"]["name"], $_FILES["file1"], "attachment1");
      }

      if (isset($_FILES["file2"]["name"]) && $_FILES["file2"]["tmp_name"] != "") {
        uplouad_request_file($request_id, $_FILES["file2"]["name"], $_FILES["file2"], "attachment2");
      }

      exit(header("Location: reqpage.php?id=" . $request_id));
    }
  } else {
    $_SESSION['error_msg'] = "Please provide all required data";
  }
}
?>


<!DOCTYPE html>
<html>

<head>
  <title>Edit request</title>
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
      color: #54bab9
    }


    #reqInfo {
      font-size: 2.5em;
      color: midnightblue;
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
      color: black;
      color: #2e6b91;
    }

    textarea {
      width: 65%;
      height: 200px;
      padding: 12px 20px;
      box-sizing: border-box;
      border: 2px solid #ccc;
      border-radius: 4px;
      background-color: #f8f8f8;
      font-size: 16px;
      resize: none;
    }



    select {
      font-size: 12px;
      width: 15em;
      height: 2em;
      margin-bottom: 1em;
      padding: .25em;
      border: 0;
      border-bottom: 2px solid #2e6b91;
      font-weight: bold;
      letter-spacing: .11em;
      border-radius: 0;
    }

    select :focus,
    select :active {
      outline: 0;
    }

    .select-items {
      position: absolute;
      background-color: red;
      top: 100%;
      left: 0;
      right: 0;
      z-index: 99;
    }



    input[type=file]::file-selector-button {
      border: 2px solid #6c5ce7;
      padding: .2em .4em;
      border-radius: .2em;
      background-color: #a29bfe;
      transition: 1s;
    }

    input[type=file]::file-selector-button:hover {
      background-color: #109177;
      border: 2px solid #00cec9;
    }


    .box {
      background-color: #F7ECDE;
    }




    .wave {
      z-index: -1;
      opacity: .4;
      position: absolute;
      top: 70%;
      left: -20%;
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
      left: 30%;
      background: #109177;
      width: 100%;
      height: 500px;
      margin-left: -250px;
      margin-top: -250px;
      transform-origin: 50% 48%;
      border-radius: 43%;
      animation: drift 3000ms infinite linear;
      opacity: .1;
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
      font-size: 1em;
    }

    button:hover {
      opacity: 1;
    }
  </style>
</head>

<body>

  <header>

    <div class="logo"> <a href="index.php?id=<?= $request['id'] ?>"><img src="img/logo.png" alt="logo"></a> </div>

    <nav class="navagation">
      <a href="signout.php" accesskey="h"> Sign out </a>
    </nav>


    <div class="breadcrumbs">
      <p><a href="index.php">Home</a> &#62; Edit Request </p>
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
        <h1 id="reqInfo"><span>REQUEST INFORMATION</span></h1>
        <hr class="lead">
        <form id="form" class="form" action="ePage.php" method="post" enctype="multipart/form-data">
          <input type="hidden" id="id" name="id" value="<?= $request['id'] ?>">
          <h2><span>Request Type:</span>
            <select class="custom-select" name="field4">
              <?php foreach ($services as $service) { ?>
                <?php if ($service['id'] == $request['service_id']) { ?>
                  <option value="<?= $service['id'] ?>" selected><?= $service['type'] ?></option>
                <?php } else { ?>
                  <option value="<?= $service['id'] ?>"><?= $service['type'] ?></option>
                <?php } ?>
              <?php } ?>
            </select>
            </select>
          </h2>
          <hr class="line">
          <h2><span>Request Description:</span><br><textarea name="field3"><?= $request['description'] ?></textarea>
          </h2>
          <hr class="line">

          <h2><span>Attachments:</span></h2>

           <?php if ($request['attachment1'] != '' && @is_array(getimagesize($request['attachment1']))) { ?>
            <h3>Attached image:</h3>
            <img src="<?= $request['attachment1'] ?>" alt="image" width="400" height="300">
          <?php } else if ($request['attachment1'] != '') { ?>
            <h3>Attached file:</h3>
            <a href="<?= $request['attachment1'] ?>"> &#128206;file</a><br>
          <?php } ?>

          <input type="file" id="myfile" name="file1"><br><br>

           <?php if ($request['attachment2'] != '' && @is_array(getimagesize($request['attachment2']))) { ?>
            <h3>Attached image:</h3>
            <img src="<?= $request['attachment2'] ?>" alt="image" width="400" height="300">
          <?php } else if ($request['attachment2'] != '') { ?>
            <h3>Attached file:</h3>
            <a href="<?= $request['attachment2'] ?>"> &#128206;file</a><br>
          <?php } ?>

          <input type="file" id="myfile" name="file2">

          <br>
          <br>
          <hr class="line">
          <button type="button" onclick="validation(); return false;">Save</button> </h2>


        </form>

        <?php if (isset($_SESSION['error_msg'])) { ?>
          <div class="error_msg" style="display: block" id="error_msg" role="alert"><?= $_SESSION['error_msg'] ?></div>
        <?php } elseif (isset($_SESSION['success_msg'])) { ?>
          <div class="success_msg" style="display: block" id="success_msg" role="alert"><?= $_SESSION['success_msg'] ?></div>
        <?php } ?>

      </div>

    </main>

    <footer>
      <hr>


      <div class="contact">
        <div class="email">
          <div class="emailicon"><img src="img/emailicon.png" alt="email"></div>
          <div class="emailadd"><a href="mailto:suppurt@manageme.com"> suppurt@manageme.com </a></div>
        </div>
      </div>
      <br>
      <hr>
      <div class="copyrights"> &copy; Manage Me 2021-2022. All rights reserved. </div>

    </footer>


  </div>
  <script src="js/EditJs.js"> </script>
</body>

<?php
if (isset($_SESSION['error_msg'])) {
	unset($_SESSION['error_msg']);
} elseif (isset($_SESSION['success_msg'])) {
	unset($_SESSION['success_msg']);
} ?>

</html>