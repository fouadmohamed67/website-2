<?php

  //functions
  include 'function.php';
  include 'connect.php';
   //my routs

  $header='include/temp/header.php';
  $footer='include/temp/footer.php';
  $english_lang='include/lang/english.php';
  $navar='include/temp/navbar.php';
  //css

  $css='layout/css/';
  $js='layout/js/';
   

 
include $header;
include $english_lang;

if(!isset($no_nav))
{
  include $navar;
}
