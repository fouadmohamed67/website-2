<?php
session_start();
$page_title="home user";
include "init.php";

if(isset($_SESSION['name']) &&$_SESSION['role']==0)
{
  echo $_SESSION['name'];
    echo "welcome in user " . $_SESSION['name'].$_SESSION['id'];
}
else
{
  header ('location: ../admin/index.php');
  exit();
}
 