<?php
session_start();
session_unset();
session_destroy();
$no_nav="";
$page_title="Register";
include "init.php";


    //check if info is right
    if($_SERVER['REQUEST_METHOD']=="POST")
    {
           
        $user_id=$_REQUEST['id'];
        $name=$_REQUEST['name'];
        $email=$_REQUEST['email'];
        $pass=$_REQUEST['password'];
        $role=$_REQUEST['role'];
        $hashed_pass=sha1($pass);

         

       
            $statement=$conn->prepare("INSERT INTO users ( name, email, password,RegisteredAt) VALUES ( :_name,:_email,:_hashpass,now() )");
            $statement->execute(array(

                '_name'=>$name,
                '_email'=>$email,
                '_hashpass'=>$hashed_pass
                

            ));
            session_start();
            $_SESSION['email']=$email;
            $_SESSION['name']=$name;
            $_SESSION['id']=$user_id;
            $_SESSION['role']=$role;
            header('location: dashbord.php');
            exit();
        

    }
    else
    {
        header ('location register.php');
    }

  ?>
  
  <div class="container">
                <div class="card d-flex justify-content-center" >
                        <div class="card-header">
                            <div class="h1 d-flex justify-content-center">register</div>
                        </div>
                        <div class="card-body">
                            <form action="" method="POST">
                             <input type="hidden" name="id" value=>
                             <input type="hidden" name="oldpass" value=>
                             
                                        <div class="form-group">
                                            <label for="name">your name : </label>
                                            <input type="text" class="form-control"   name="name" required="required" >
                                        </div>
                                        <?php
                                        if(isset($errors_of_register[0]))
                                        {?>
                                            <div class="alert alert-danger">
                                              <?php echo $errors_of_register[0];?>
                                            </div> 
                                       <?php } ?>



                                        <div class="form-group">
                                            <label for="email">email : </label>
                                            <input type="email" class="form-control"   name="email" required="required" >
                                        </div>
                                        <?php
                                        if(isset($errors_of_register[1]))
                                        {?>
                                            <div class="alert alert-danger">
                                              <?php echo $errors_of_register[1];?>
                                            </div> 
                                       <?php } ?>
                                       <?php
                                       if(isset($errors_of_register[3]))
                                        {?>
                                            <div class="alert alert-danger">
                                              <?php echo $errors_of_register[3];?>
                                            </div> 
                                       <?php } ?>



                                        <div class="form-group">
                                            <label for="password">password : </label>
                                            <input type="password" class="form-control"   name="password" >
                                        </div>
                                        <?php
                                       if(isset($errors_of_register[2]))
                                        {?>
                                            <div class="alert alert-danger">
                                              <?php echo $errors_of_register[2];?>
                                            </div> 
                                       <?php } ?>


                                        <div class="form-group">
                                            <input name="role" class="form-control" type="hidden" value="0">
                                        </div>

                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary" value="register" name="submit" id="">   
                                        </div>
                            </form>
                            <a href="..\admin\index.php">you have account ?</a>
                        </div>
                </div>
         </div>


<?php
include "temp/footer.php";
?>