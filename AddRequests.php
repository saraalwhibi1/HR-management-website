<?php
include "inc/header.php";
include_once('inc/config.php');




if ($current_user == false) {
    exit(header("Location: index.php"));
} else if ($current_user['type'] != 'employee') {
    exit(header("Location: " . $current_user['homepage']));
}


function add_request($emp_id, $service_id, $description)
{
    global $pdo;

    $stmt = $pdo->prepare("INSERT INTO request (emp_id, service_id, description) VALUES (?, ?, ?);");
    $stmt->bindValue(1, $emp_id);
    $stmt->bindValue(2, $service_id);
    $stmt->bindValue(3, $description);
    $result = $stmt->execute();

    if ($result) {
        $_SESSION['success_msg'] = "The request have been added successfully.";
        $request_id = $pdo->lastInsertId();
        return $request_id;
    } else {
        $_SESSION['error_msg'] = "Error while adding the request2.";
        print_r($pdo->errorInfo());
        return false;
    }
}

function uplouad_request_file($request_id, $file_name, $file, $col)
{
    $target_dir = "uploads/" . $request_id . "/";
    $file_url = uplouad_file($target_dir, $file_name, $file);
    
    return edit_request_attachment($request_id, $file_url, $col);
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


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['field4']) && isset($_POST['field3'])) {
		$service_id =  $_POST['field4'];
		$description =  $_POST['field3'];

        $request_id = add_request($current_user['id'], $service_id, $description);

        if ($request_id) {
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


$user = get_user_info('employee', $current_user['id']);

$services = get_all_services();

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
    <script src="js/addreqform.js"></script>
    <title>AddRequest</title>
</head>

<body>
    <header>

        <div class="logo"> <a href="index.php"><img src="img/logo.png" alt="logo"></a> </div>

        <nav class="navagation">
            <a href="signout.php" accesskey="h"> Sign out </a>
        </nav>


        <div class="breadcrumbs">
            <p><a href="index.php">Home</a> &#62; Add Request </p>
        </div>

    </header>

    <div class='boxaddreq'>
        <div class='wave -one'></div>
        <div class='wave -two'></div>
        <div class='wave -three'></div>

        <div class='title'>


            <div class="form-style-5">

                <form id="form" class="form" action="AddRequests.php" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <legend><span class="number">1</span> Request Info</legend>

                        <select id="service" name="field4">
                            <option value="null" id="null">Service Type*</option>
                            <?php foreach ($services as $service) { ?>
                                <option value="<?= $service['id'] ?>"><?= $service['type'] ?></option>
                            <?php } ?>
                        </select>
                    </fieldset>

                    <fieldset>
                        <legend><span class="number">2</span> Additional Info</legend>
                        <textarea name="field3" placeholder="Description*" rows="10"></textarea>
                    </fieldset>

                    <fieldset>

                        <legend><span class="number">3</span> Attachments</legend>
                        <input type="file" name="file1">
                        <input type="file" name="file2">
                    </fieldset>
                    <input type="button" onclick="validation(); return false;" value="Send" />

                </form>
            </div>

            <?php if (isset($_SESSION['error_msg'])) { ?>
				<div class="error_msg" style="display: block" id="error_msg" role="alert"><?= $_SESSION['error_msg'] ?></div>
			<?php } elseif (isset($_SESSION['success_msg'])) { ?>
				<div class="success_msg" style="display: block" id="success_msg" role="alert"><?= $_SESSION['success_msg'] ?></div>
			<?php } ?>

        </div>


    </div>






    <style type="text/css">
        .form-style-5 {
            max-width: 500px;
            padding: 10px 20px;
            background: #f4f7f8;
            margin: 10px auto;
            padding: 20px;
            background: #f4f7f8;
            border-radius: 8px;
            font-family: Georgia, "Times New Roman", Times, serif;
        }

        .form-style-5 fieldset {
            border: none;
        }

        .form-style-5 legend {
            font-size: 1.4em;
            margin-bottom: 10px;
        }

        .form-style-5 label {
            display: block;
            margin-bottom: 8px;
        }

        .form-style-5 input[type="text"],
        .form-style-5 input[type="date"],
        .form-style-5 input[type="datetime"],
        .form-style-5 input[type="email"],
        .form-style-5 input[type="number"],
        .form-style-5 input[type="search"],
        .form-style-5 input[type="time"],
        .form-style-5 input[type="url"],
        .form-style-5 textarea,
        .form-style-5 select {
            font-family: Georgia, "Times New Roman", Times, serif;
            background: rgba(255, 255, 255, .1);
            border: none;
            border-radius: 7px;
            font-size: 15px;
            margin: 0;
            outline: 0;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            background-color: #e8eeef;
            color: #8a97a0;
            -webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.03) inset;
            box-shadow: 0 1px 0 rgba(0, 0, 0, 0.03) inset;
            margin-bottom: 30px;
        }

        .form-style-5 input[type="text"]:focus,
        .form-style-5 input[type="date"]:focus,
        .form-style-5 input[type="datetime"]:focus,
        .form-style-5 input[type="email"]:focus,
        .form-style-5 input[type="number"]:focus,
        .form-style-5 input[type="search"]:focus,
        .form-style-5 input[type="time"]:focus,
        .form-style-5 input[type="url"]:focus,
        .form-style-5 textarea:focus,
        .form-style-5 select:focus {
            background: #d2d9dd;
        }

        .form-style-5 select {
            -webkit-appearance: menulist-button;
            height: 40px;
        }

        .form-style-5 .number {
            background: #1abc9c;
            color: #fff;
            height: 30px;
            width: 30px;
            display: inline-block;
            font-size: 0.8em;
            margin-right: 4px;
            line-height: 30px;
            text-align: center;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.2);
            border-radius: 15px 15px 15px 0px;
        }

        .form-style-5 input[type="submit"],
        .form-style-5 input[type="button"] {
            position: relative;
            display: block;
            padding: 19px 39px 18px 39px;
            color: #FFF;
            margin: 0 auto;
            background: #1abc9c;
            font-size: 18px;
            text-align: center;
            font-style: normal;
            width: 100%;
            border: 1px solid #16a085;
            border-width: 1px 1px 3px;
            margin-bottom: 10px;
        }

        .form-style-5 input[type="submit"]:hover,
        .form-style-5 input[type="button"]:hover {
            background: #109177;
        }

        /*This is the waves css for add requests page the same as employee only title if you want to chnge it  */
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
            background-color: #FBF8F1;
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

<?php
if (isset($_SESSION['error_msg'])) {
	unset($_SESSION['error_msg']);
} elseif (isset($_SESSION['success_msg'])) {
	unset($_SESSION['success_msg']);
} ?>

</html>