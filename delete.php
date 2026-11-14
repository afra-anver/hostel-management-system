<?php
include 'config.php';

if(isset($_GET['id'])){
    // SQL Injection thadukka (int) use panrom
    $id = (int)$_GET['id']; 
    
    $sql = "DELETE FROM entries WHERE id=$id";
    if($conn->query($sql) === TRUE){
        header("Location: view.php"); // Delete aana piragu view page-ku thirumbi pōgum
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>