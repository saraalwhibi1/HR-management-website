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




function get_emp_all_requests($id)
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM `request` WHERE emp_id=?;");
    $stmt->bindValue(1, $id);
    $result = $stmt->execute();

    if ($result) {
        return $stmt->fetchAll();
    } else {
        return array();
    }
}


function get_emp_previous_requests($id)
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM `request` WHERE status!='In progress' AND emp_id=?;");
    $stmt->bindValue(1, $id);
    $result = $stmt->execute();

    if ($result) {
        return $stmt->fetchAll();
    } else {
        return array();
    }
}



function get_emp_in_progress_requests($id)
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM `request` WHERE status='In progress' AND emp_id=?;");
    $stmt->bindValue(1, $id);
    $result = $stmt->execute();

    if ($result) {
        return $stmt->fetchAll();
    } else {
        return array();
    }
}

?>



<?php
if ($current_user == false) {
  exit(header("Location: index.php"));
} else if ($current_user['type'] != 'employee') {
  exit(header("Location: " . $current_user['homepage']));
}

$user = get_user_info('employee', $current_user['id']);

$services = get_all_services();
$requests = get_emp_all_requests($current_user['id']);
$previous_requests = get_emp_previous_requests($current_user['id']);
$in_progress_requests = get_emp_in_progress_requests($current_user['id']);

function get_service_type($id, $services)
{
  $service_type = "";
  foreach ($services as $service) {
    if ($service['id'] == $id) {
      $service_type = $service['type'];
      break;
    }
  }
  return $service_type;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="css/style.css">
  <title>Employee</title>
</head>

<body>

  <header>

    <div class="logo"> <a href="index.php"><img src="img/logo.png" alt="logo"></a> </div>

    <nav class="navagation">
      <a href="signout.php" accesskey="h"> Sign out </a>
    </nav>


    <div class="breadcrumbs">
      <p>Home &#62; </p>
    </div>

  </header>

  <main>
    <div class='box'>
      <div class='wave -one'></div>
      <div class='wave -two'></div>
      <div class='wave -three'></div>
      <div style=" text-align: center; color: #109177; font-size: 30px; line-height: 60px;"> Welcome <?= $user['first_name'] ?> <?= $user['last_name'] ?> ! </div>
      <div style="color: #109177; text-align: center;" class='title'>
        Employee's ID: <?= $user['emp_number'] ?><br>
        job Title: <?= $user['job_title'] ?> <br>

      </div>


    </div>



    <table class="tableEmployee">
      <caption style="text-align: left; color: #109177;">
        <h1 class="ReqEmp"> Requests</h1>
      </caption>
      <thead>
        <tr>
          <th>In progress</th>
          <th> </th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($in_progress_requests as $request) {
          $service_type = get_service_type($request['service_id'], $services); ?>
          <tr>
            <td><a href="reqpage.php?id=<?= $request['id'] ?>"> <?= $request['id'] ?> - <?= $service_type ?> </a></td>
            <td><button type="button" onclick="location.href='ePage.php?id=<?= $request['id'] ?>';" return false;>Edit </button></td>
          </tr>
        <?php } ?>
      </tbody>

    </table>

    <div id="addbut"><button type="button" onclick="location.href='AddRequests.php'; return false;">Add Request</button></div>

    <table class="tableEmployee">
      <caption style="text-align: left; color: #109177;">
        <h1 class="ReqEmp"> Previous Requests</h1>
      </caption>

      <thead>
        <tr>
          <th>Requests</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($previous_requests as $request) {
          $service_type = get_service_type($request['service_id'], $services); ?>
          <tr>
            <td><a href="reqpage.php?id=<?= $request['id'] ?>"> <?= $request['id'] ?> - <?= $service_type ?> </a></td>
            <td> <?= $request['status'] ?></td>
            <td><button type="button" onclick="location.href='ePage.php?id=<?= $request['id'] ?>';" return false;>Edit </button></td>
          </tr>
        <?php } ?>

      </tbody>


    </table>



    <style>
      /*This is the waves css for Employee page*/
      .box {
        width: 100%;
        height: 300px;
        border-radius: 5px;
        box-shadow: 0 2px 30px rgba(black, .2);
        background: lighten(#f0f4c3, 10%);
        position: relative;
        overflow: hidden;
        transform: translate3d(0, 0, 0);
        background-color: #cff0f7;
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

      .title {
        position: relative;
        width: 100%;
        z-index: 1;
        line-height: 60px;
        transform: translate3d(0, 0, 0);
        font-family: 'Playfair Display', serif;
        letter-spacing: .2em;
        font-size: 24px;
        text-shadow: 0 1px 0 rgba(black, .1);
        text-indent: .3em;
      }

      @keyframes drift {
        from {
          transform: rotate(0deg);
        }

        from {
          transform: rotate(360deg);
        }
      }

      /*end of the wave css*/


      /*table Employee page and buttons under table*/
      .tableEmployee {
        width: 50%;
        border: 1px;
        margin: 25px 0;
        margin-left: auto;
        margin-right: auto;
        font-size: 0.9em;
        font-family: sans-serif;
        min-width: 400px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
      }

      .tableEmployee thead tr {
        background-color: #54BAB9;
        color: #ffffff;
        text-align: left;
      }

      .tableEmployee th,
      .tableEmployee td {
        padding: 12px 15px;
      }


      .tableEmployee tbody tr {
        border-bottom: 1px solid #dddddd;
      }

      .tableEmployee tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
      }

      .tableEmployee tbody tr:last-of-type {
        border-bottom: 2px solid #54BAB9;
      }

      .tableEmployee tbody tr.active-row {
        font-weight: bold;
        color: #54BAB9;
      }

      .tableEmployee button {
        background-color: #F7ECDE;
        color: Teal;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        opacity: 0.9;
        position: relative;
        display: block;



      }

      .tableEmployee button:hover {
        background: #E9DAC1;
        opacity: 1;
      }

      #addbut button {
        background-color: #F7ECDE;
        color: Teal;
        padding: 14px 20px;
        margin: auto;
        border: none;
        cursor: pointer;
        width: 10%;
        opacity: 0.9;
        position: relative;
        display: block;
      }

      #addbut button:hover {
        background: #E9DAC1;
        opacity: 1;
      }


      /*anker decorartion for all*/
    </style>


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

</body>

</html>