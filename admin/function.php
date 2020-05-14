<?php
  include "connect.php";
   function getTitle()
   {
       global $page_title;
       if(isset($page_title))
       {
         echo($page_title);
       }
       else
       {
        echo("default");
       
       }
   }
  //check if category name is used
   function category_name_is_used($name,$conn)
   {
    $state=$conn->prepare("SELECT name FROM categories WHERE name=?  ");
    $state->execute(array($name));
    $row=$state->fetch();
    $count_of_the_same_name=$state->rowCount();
    if($count_of_the_same_name>0)
    {
       return true;
    }
    else
    {
      return false;
    }
   }
   //check if email is used
   function email_in_use($email,$conn)
   {
  
    $emails_id=$conn->prepare("SELECT email FROM users WHERE email=?  ");
    $emails_id->execute(array($email));
    $row_of_email=$emails_id->fetch();
    $count_of_the_same_email=$emails_id->rowCount();
    if($count_of_the_same_email>0)
    {
       return true;
    }
    else
    {
      return false;
    }

   } 
   //redirect to dashbord
   function redirect_to_dashbord($error_message,$time=3)
   {
      echo "<div class='h1 text-center alert alert-danger'>$error_message</div>";
      header("refresh:$time,url=dashbord.php");
      exit();

   }
   //redirect to login
   function redirect_to_login($error_message,$time=3)
   {
      echo "<div class='text-center alert alert-danger'>$error_message</div>";
      header("refresh:$time,url=index.php");
      exit();
   }
   function count_of_things($table,$conn)
   {
      $all=$conn->prepare("SELECT COUNT(*) FROM $table");
      $all->execute();
      return  $all->fetchColumn();
   }
   //return count of
   function count_of_Users($table,$role,$conn)
   {
      $all=$conn->prepare("SELECT COUNT(*) FROM $table WHERE role=$role");
      $all->execute();
      return  $all->fetchColumn();
   }

