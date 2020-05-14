<?php 
session_start();
$page_title='manage Users';
include 'init.php';
 
if(isset($_SESSION['name']) && $_SESSION['role']==1)
{
     
    $do=isset($_GET['do'])?$_GET['do']:'manage';
    if($do=='manage')
    {
        $statement=$conn->prepare("SELECT * from users");
        $statement->execute();
        $all_users=$statement->fetchAll();
        ?> 
        <div class="text-center h1">mange members</div> 
        <div class="container">
          <div class="table-responsive">
           <table class="table ">
            <thead>
                <tr>
                    <th>id</th>
                    <th>name</th>
                    <th>email</th>
                    <th>role</th>
                    <th>RegisteredAt</th>
                    <th>action</th>
                </tr>
            </thead>
            <tbody>
                
                    <?php foreach($all_users as $user)
                    {?>
                    <tr>
                        <td><?php echo $user['id'] ?></td>
                        <td><?php echo $user['name'] ?></td>
                        <td><?php echo $user['email'] ?></td>
                        <td><?php echo $user['RegisteredAt']?></td>
                        <td><?php
                         if($user['role']==1 )
                         {
                             echo "admin";
                         }
                         else
                         {
                            echo "user";
                         }
                
                        
                        ?></td>
                         <td>
                             <form   method="POST" action="?do=delete&id=<?php echo $user['id']?>">
                                    <button class="btn btn-danger btn-sm">delete</button>
                             </form> 
                             <form   method="POST" action="?do=edit&id=<?php echo $user['id']?>">
                                    <button class="btn btn-primary btn-sm">
                                          edit
                                    </button>
                             </form>
                        </td>
                    </tr> 
                    <?php }?>
                    
                
            </tbody>
           </table>
          </div>
        </div>

<?php
    }
    elseif($do=='delete')
    {
         if($_SERVER['REQUEST_METHOD']=='POST')
         {
            $id_will_del=$_REQUEST['id'];
            $statement=("DELETE FROM users WHERE id=$id_will_del");
            $conn->query($statement);
            header ('location: manageUsers.php?do=manage');
            exit();
         }
         else
         {
            $message="you are blocked to delete";
             redirect_to_dashbord($message);
         }
    }
    elseif($do=='update')
    {
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
            $user_id=$_REQUEST['id'];
            $name=$_REQUEST['name'];
            $email=$_REQUEST['email'];
            if(empty($_REQUEST['password']))
            {
                $hashed_pass=$_REQUEST['oldpass'];
            }
            else
            {
                $pass=$_REQUEST['password'];
                $hashed_pass=sha1($pass);
            }
            $role=$_REQUEST['role'];
  
            
            //validation

            $array_of_errors=array();
            if(strlen($name)<4)
            {
                 $array_of_errors[]="the name should be greater than 4 letters";
            }
            if(strlen($email)<4)
            {
                 $array_of_errors[]="the email required";
            }
            //get all emails from database
            $emails_id=$conn->prepare("SELECT email,id FROM users WHERE email=? AND id!=? ");
            $emails_id->execute(array($email,$user_id));
            $row_of_email=$emails_id->fetch();
            $count_of_the_same_email=$emails_id->rowCount();
            if($count_of_the_same_email>0)
            {
                $array_of_errors[]="sorry this email in use";
            }
            
           foreach($array_of_errors as $error)
           {
               ?>
                  <div class="alert alert-danger m-4  ">
                   <?php echo $error ?>
                  </div>
               <?php
           }
             
             if(empty($array_of_errors))
             {
                $statement=$conn->prepare("UPDATE users SET name=?,email=?,password=?,role=? WHERE id=?");
                $statement->execute(array($name,$email,$hashed_pass,$role,$user_id));
                 if($statement->rowCount()>0)
                 {
                     
                     header('location: manageUsers.php?do=manage');
                     exit();
                     
                 }
             }

         

        }
        else
        {
            $message="you are blocked to update";
             redirect_to_dashbord($message);
        }
    }
    elseif($do=='edit')
    {  
        $user_id= isset($_GET['id']) && is_numeric($_GET['id'])?intval($_GET['id']):0;
        $statement=$conn->prepare("SELECT  *  FROM users WHERE id=?");
        $statement->execute(array($user_id));
        $row=$statement->fetch();
        $count=$statement->rowCount();
        if($count>0)
        {?>
         <div class="container">
                <div class="card d-flex justify-content-center" >
                        <div class="card-header">
                            <div class="h1 d-flex justify-content-center">edit your profile</div>
                        </div>
                        <div class="card-body">
                            <form action="?do=update" method="POST">
                             <input type="hidden" name="id" value=<?php echo $row['id'] ?>>
                             <input type="hidden" name="oldpass" value=<?php echo $row['password'] ?>>
                             
                                        <div class="form-group">
                                            <label for="name">your name : </label>
                                            <input type="text" class="form-control" value="<?php echo $row['name']?>" name="name" required="required" >
                                        </div>
                                        <div class="form-group">
                                            <label for="email">email : </label>
                                            <input type="email" class="form-control" value="<?php echo $row['email']?>" name="email" required="required" >
                                        </div>
                                        <div class="form-group">
                                            <label for="password">password : </label>
                                            <input type="password" class="form-control"   name="password" >
                                        </div>
                                        <div class="form-group">
                                            <label for="role">role : </label>
                                            <select name="role" class="form-control">
                                                <option value="1">admin</option>
                                                <option value="0">user</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary" name="submit" id="">   
                                        </div>
                            </form>
                        </div>
                </div>
         </div>


 <?php 
        }
         
        else
        {
            $message="sorry you are blocked";
             redirect_to_dashbord($message);
        }

        ?>
 
     
    
    <?php }
}
else if($_SESSION['role']==0)
{
    header('location: ../user/dashbord.php');
    exit();
}
else
{
    header('location: index.php');
    exit();
}
 