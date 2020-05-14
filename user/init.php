<?php
 
   
  //functions
  include '../admin/function.php';
  include 'connect.php';


  $header='temp/header.php';
  $footer='temp/footer.php';
  $navar='temp/navbar.php';
  $css='layout/css/';


    include $header;


    if(!isset($no_nav))
    {
    include $navar;
    }

