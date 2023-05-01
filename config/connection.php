<?php 
  global $con;
  $con=mysqli_connect("127.0.0.1","root","","barcodes");
  if(!$con) {
    die(" Connection Error "); 
} 
?>