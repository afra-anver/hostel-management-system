<?php
include 'config.php';

if(isset($_GET['id'])){
    
    $id = (int)$_GET['id']; 
    
    $sql = "DELETE FROM entries WHERE id=$id";
    if($conn->query($sql) === TRUE){
        header("Location: view.php?msg=deleted"); 
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>