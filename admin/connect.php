<?php

  $dsn='mysql:host=localhost;dbname=MY_M';
  $user='root';
  $pass='';
   

  try{
         $conn=new PDO($dsn,$user,$pass);
         $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
         
  }
  catch(PDOException $e){
    echo 'faild';
  }