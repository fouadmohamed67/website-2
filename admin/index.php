<?php

session_start();

$no_nav='';
$page_title='login';
include "init.php";

if(isset($_SESSION['username']))
{
    header('location: dashbord.php');
    exit();
}



//check if user exist
   
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
            $email=$_POST['email'];
            $password=$_POST['password'];
            $hashedPass=sha1($password);
            //validate
            $arr_validator=array();
            if(strlen($email)<4)
            {
                $arr_validator[0]="the email is short";
            }
            if(strlen($password)<6)
            {
                $arr_validator[1]="the password is less than 6 letters";
            }

            //check in database
          
           if(empty($arr_validator))
           {
            $statement=$conn->prepare("SELECT email,name,id,password,role  FROM users WHERE email= ? AND password=? ");
            $statement->execute(array($email,$hashedPass));
             $row=$statement->fetch();
            $count=$statement->rowCount();
             
                if($count>0)
                {
                    $_SESSION['email']=$email;
                    $_SESSION['name']=$row['name'];
                    $_SESSION['id']=$row['id'];
                    $_SESSION['role']=$row['role'];
                    if($row['role']==1)
                    {
                        header('location: dashbord.php');
                        exit();
                    }
                    else
                    {
                        header('location: ../user/dashbord.php');
                        exit();
                    }
                    
                    
                }
           }
                  
              
        }
?>
 <div class="container">
     
    <div class="card d-flex justify-content-center" >
        <div class="card-header">
             <div class="h1 d-flex justify-content-center">login now</div>
        </div>
        <div class="card-body">

            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" class="form">
                <div class="form-group">
                    <label for="email">email : </label>
                    <input type="email" class="form-control" name="email" >
                </div>
                <?php
                if(isset($arr_validator[0]))
                {?>
                    <div class="alert alert-danger">
                    <?php echo $arr_validator[0];?>
                    </div> 
                <?php } ?>


                <div class="form-group">
                    <label for="password">password : </label>
                    <input type="password" class="form-control" name="password" >
                </div>
                <?php
                if(isset($arr_validator[1]))
                {?>
                    <div class="alert alert-danger">
                    <?php echo $arr_validator[1];?>
                    </div> 
                <?php } ?>


                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="submit" id="">   
                </div>
            </form>
            <a href="../user/register.php">register ? </a>

        </div>
    </div>
</div>
 
 </div>
<?php

include $footer;
?>