<?php
include "inc/header.php";
include_once('inc/config.php');


if ($current_user == false) {
    exit(header("Location: index.php"));
} else if ($current_user['type'] != 'manager') {
    exit(header("Location: " . $current_user['homepage']));
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



function get_all_requests()
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM `request`;");
    $result = $stmt->execute();

    if ($result) {
        return $stmt->fetchAll();
    } else {
        return array();
    }
}


function get_all_employee()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM `employee`;");
    $result = $stmt->execute();

    if ($result) {
        return $stmt->fetchAll();
    } else {
        return array();
    }
}


function get_previous_requests()
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM `request` WHERE status!='In progress';");
    $result = $stmt->execute();

    if ($result) {
        return $stmt->fetchAll();
    } else {
        return array();
    }
}


function get_in_progress_requests()
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM `request` WHERE status='In progress';");
    $result = $stmt->execute();

    if ($result) {
        return $stmt->fetchAll();
    } else {
        return array();
    }
}


$user = get_user_info('manager', $current_user['id']);

$services = get_all_services();
$employee = get_all_employee();
$requests = get_all_requests();

$previous_requests = get_previous_requests();
$in_progress_requests = get_in_progress_requests();

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

function get_employee_name($id, $employee)
{
    $employee_name = "";
    foreach ($employee as $emp) {
        if ($emp['id'] == $id) {
            $employee_name = $emp['first_name'] . ' ' . $emp['last_name'];
            break;
        }
    }
    return $employee_name;
}

?>
<!DOCETYPE>
    <html>

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title> Manage Me </title>
        <link rel="stylesheet" href="css/style.css" />
        <script
            src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js">
        </script>
                <script>
            $(document).ready(function () {
                $(".reqlink").mouseover(function () {
                    var val=$(this).attr("href");
                    var reqID = val.substring(15,);
                    $.ajax({
                        type: "POST",
                        url: "txtbox.php",
                        data: "rid=" + reqID,
                        success: function (e) {
                            var obj = $.parseJSON(e);
                            $(".reqlink").attr('title',obj.des);
                        }
                    });
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                $(".decline").click(function () {
                    var reqID=$(this).attr("value");
                    $.get("Decline.php",
                           {action:"decline",request_id:reqID},
                           function(){
                              window.location.reload();
                           }
                           );
                });
            });
        </script>
         <script>
            $(document).ready(function () {
                $(".approve").click(function () {
                    var reqID=$(this).attr("value");
                    $.get("approve.php",
                           {action:"approve",request_id:reqID},
                           function(){
                              window.location.reload();
                           }
                           );
                });
            });
        </script>
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

            <div class="title">
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

                <h1> Welcome <?= $user['first_name'] ?> <?= $user['last_name'] ?> !</h1>
            </div>

            <div class="requests">
                <h2 class="Reqtitle"> Requests</h2>
                <div class="reqbox">
                    <?php for ($i = 0; $i < count($services); $i++) { ?>
                        <?php if ($i % 2 == 0) echo '<div class="reqcol">'; ?>
                        <table class="managertable">
                            <thead>
                                <tr>
                                    <th colspan="3"><?= $services[$i]['type'] ?></th>
                                </tr>
                                <tr>
                                    <td> Requests </td>
                                    <td> Status </td>
                                    <td> Decision </td>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($in_progress_requests as $request) { ?>
                                    <?php if ($request['service_id'] == $services[$i]['id']) { $employee_name = get_employee_name($request['emp_id'], $employee);?>
                                        <tr style="background: #ccc;">
                                            <td><div class="result"></div><a class="reqlink" href="reqpage.php?id=<?= $request['id'] ?>"> <?= $request['id'] ?>-<?= $employee_name ?></a></td>
                                            <td> <?= $request['status'] ?> </td>
                                            <td><button class='approve' id='approve' type="button" value="<?= $request['id'] ?>" >Approve</button> <button class='decline' id='decline' type="button" value="<?= $request['id'] ?>">Decline</button></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>

                                <?php foreach ($previous_requests as $request) { ?>
                                    <?php if ($request['service_id'] == $services[$i]['id']) { $employee_name = get_employee_name($request['emp_id'], $employee);?>
                                        <tr>
                                            <td><div class="result"></div><a class="reqlink" href="reqpage.php?id=<?= $request['id'] ?>"> <?= $request['id'] ?>-<?= $employee_name ?> </a></td>
                                            <td> <?= $request['status'] ?> </td>
                                            <?php if ($request['status'] == 'Approved') { ?>
                                                <td> <button class='decline' id='decline' type="button" value="<?= $request['id'] ?>">Decline</button></td>
                                                <?php } else { ?>
                                                    <td><button class='approve' id='approve' type="button" value="<?= $request['id'] ?>">Approve</button> </td>
                                                    <?php } ?>
                                           
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php if ($i % 2 != 0) echo '</div>'; ?>
                    <?php } ?>
                </div>
            </div>

            <br><br>

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