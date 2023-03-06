<?php
    $con = mysqli_connect("localhost", "root", "root", "manage_me");
    if($error = mysqli_connect_error() != null){
        exit($error);
    }
    $rId = $_POST['rid'];
    $sql = "SELECT description FROM request WHERE id= '".$rId."'";
    if($re = mysqli_query($con, $sql)){
        while ($row= mysqli_fetch_assoc($re)){
            $json['des']= $row['description'];
        }
    }
    $json = json_encode($json);
    header("Content-Type: text/plain");
    print $json;
?>