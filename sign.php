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


function register_employee($emp_number, $first_name, $last_name, $job_title, $password)
{
    if (is_user_exists("employee", $emp_number)) {
        $_SESSION['error_msg'] = "Email or username already exists.";
        return false;
    }

    global $pdo;

    $stmt = $pdo->prepare("INSERT INTO `employee` (emp_number, first_name, last_name, job_title, password) VALUES (?, ?, ?, ?, ?);");

    $stmt->bindValue(1, $emp_number);
    $stmt->bindValue(2, $first_name);
    $stmt->bindValue(3, $last_name);
    $stmt->bindValue(4, $job_title);
    $stmt->bindValue(5, encrypt_password($password));
    $result = $stmt->execute();

    if ($result) {
        $_SESSION['success_msg'] = "You have signed up successfully.";
        return true;
    } else {
        $_SESSION['error_msg'] = "Error while trying singed up.";
        return false;
    }
}



function is_user_exists($user_type, $username)
{
    global $pdo;

    if ($user_type == "manager")
        $stmt = $pdo->prepare("SELECT * FROM `manager` WHERE username=?;");
    else
        $stmt = $pdo->prepare("SELECT * FROM `employee` WHERE emp_number=?;");

    $stmt->bindValue(1, $username);

    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        return true;
    } else {
        return false;
    }
}

function encrypt_password($password)
{
    return password_hash($password, PASSWORD_BCRYPT);
}

?>
<?php
if ($current_user) {
	exit(header("Location: " . $current_user['homepage']));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['lastname']) && isset($_POST['password']) && isset($_POST['password2'])) {
		$emp_number =  $_POST['username'];
		$first_name =  $_POST['email'];
		$last_name  =  $_POST['lastname'];
		$job_title  =  $_POST['password'];
		$password   =  $_POST['password2'];

		$result = register_employee($emp_number, $first_name, $last_name, $job_title, $password);

		if ($result) {
			login('employee', $emp_number, $password);
		}
	} else {
		$_SESSION['error_msg'] = "Please provide all required data";
	}
}
?>

<html>
<title>sign</title>

<head>
	<link rel="stylesheet" href="css/s.css">
</head>

<body>

	<main>
		<div class='boxaddreq'>
			<div class='wave -one'></div>
			<div class='wave -two'></div>
			<div class='wave -three'></div>

			<div class="container">
				<div class="header">
					<h2>Employee sign up</h2>
				</div>
				<form id="form" class="form" action="sign.php" method="post">
					<div class="form-control">
						<label for="username">ID</label>
						<input type="text" placeholder="enter id" id="username" name="username" />
						<i class="fas fa-check-circle"></i>
						<i class="fas fa-exclamation-circle"></i>
						<small>Error message</small>
					</div>
					<div class="form-control">
						<label for="username">First Name</label>
						<input type="text" placeholder="enter first name" id="email" name="email" />
						<i class="fas fa-check-circle"></i>
						<i class="fas fa-exclamation-circle"></i>
						<small>Error message</small>
					</div>
					<div class="form-control">
						<label for="username">Last Name</label>
						<input type="text" placeholder="enter last name" id="lastname" name="lastname" />
						<i class="fas fa-check-circle"></i>
						<i class="fas fa-exclamation-circle"></i>
						<small>Error message</small>
					</div>
					<div class="form-control">
						<label for="username">Job Title</label>
						<input type="text" placeholder="job title" id="password" name="password" />
						<i class="fas fa-check-circle"></i>
						<i class="fas fa-exclamation-circle"></i>
						<small>Error message</small>
					</div>
					<div class="form-control">
						<label for="username">Password </label>
						<input type="password" placeholder="enter Password " id="password2" name="password2" />
						<i class="fas fa-check-circle"></i>
						<i class="fas fa-exclamation-circle"></i>
						<small>Error message</small>
					</div>
					<button onclick="checkInputs(); return false;">Submit</button>
					<br>
					<span><a href="LogIn.php" style="position: relative; left: 137px; color: #26497c;">log in?</a></span>
				</form>
			</div>
			<?php if (isset($_SESSION['error_msg'])) { ?>
				<div class="error_msg" style="display: block" id="error_msg" role="alert"><?= $_SESSION['error_msg'] ?></div>
			<?php } elseif (isset($_SESSION['success_msg'])) { ?>
				<div class="success_msg" style="display: block" id="success_msg" role="alert"><?= $_SESSION['success_msg'] ?></div>
			<?php } ?>
		</div>
	</main>
	<script src="ja/java.js"></script>
</body>

<?php
if (isset($_SESSION['error_msg'])) {
	unset($_SESSION['error_msg']);
} elseif (isset($_SESSION['success_msg'])) {
	unset($_SESSION['success_msg']);
} ?>

</html>