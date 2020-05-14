<?php 
session_start();
$page_title='categories page';
include 'init.php';
if(isset($_SESSION['name']) && $_SESSION['role']==1)
{
    $do=isset($_GET['do'])?$_GET['do']:'manage';
    if($do=='manage')
    {

        $statement=$conn->prepare("SELECT * from categories ");
        $statement->execute();
        $all_categories=$statement->fetchAll();

    ?>
         <div class="container">
          
            <a href="?do=addCategory" class="btn btn-primary mb-2">add new category</a>
            <div class="card d-flex justify-content-center" >
                            <div class="card-header">
                                <div class="h1 d-flex justify-content-center">categories</div>
                            </div>
                            <div class="card-body">
                            <?php
                            foreach($all_categories as $item)
                            {
                               ?>
                                
                              <div class="item" id="item<?php echo $item['id']; ?>">
                               
                                <h3><?php echo $item['name'] ?></h3>
                                <p><?php echo $item['description'] ?></p>
                                <span class="visible"><?php if($item['allow_visible']==1){echo "visible";}else{echo "non visible";} ?></span>
                                <span class="visible"><?php if($item['alllow_comment']==1){echo "comments";}else{echo "no comments";} ?></span>
                                 <form method="POST" action="?do=edit&id=<?php echo $item['id'] ?>">
                                  <button class="btn btn-primary btn-sm">edit</button>
                                 </form>
                                 <form method="POST" action="?do=delete&id=<?php echo $item['id'] ?>">
                                  <button class="btn btn-danger btn-sm">delete</button>
                                 </form>
                              </div>

                                <?php
                            }
                            
                            ?>
                            </div>
            </div>
         </div>

         
    <?php
    }
    else if($do=="addCategory_to_database")
    {
        if($_SERVER['REQUEST_METHOD']=="POST")
        {
            $name=$_REQUEST['name'];
            $description=$_REQUEST['description'];
            $visible=$_REQUEST['allow_visible'];
            $comment=$_REQUEST['alllow_comment'];



           
            $errors_of_adding_category=array();
            if(strlen($name)<3)
            {
              $errors_of_adding_category[0]="the name is short";
            }
            if(category_name_is_used($name,$conn))
            {
                $errors_of_adding_category[1]="there is a category with this name";
            }
            if(strlen($description)<3)
            {
              $errors_of_adding_category[2]="the description is short";
            }
            if(!empty($errors_of_adding_category))
            {
                $_SESSION['category_error']=$errors_of_adding_category;
                header('location: categories.php?do=addCategory');
                exit();

            }
            else
            {
                $statement=$conn->prepare("INSERT INTO categories ( name, description,allow_visible,alllow_comment) VALUES ( :_name,:_description,:_visible,:_comment)");
                $statement->execute(array(
    
                    '_name'=>$name,
                    '_description'=>$description,
                    '_visible'=>$visible,
                    '_comment'=>$comment
                    
    
                ));
                header('location: categories.php');
                 exit();
            }

        }
    }
    else if($do=="addCategory")
    {
    ?>
      <div class="container">
                <div class="card d-flex justify-content-center" >
                        <div class="card-header">
                            <div class="h1 d-flex justify-content-center">add category</div>
                        </div>
                        <div class="card-body">
                            <form action="?do=addCategory_to_database" method="POST"> 

                                        <div class="form-group">
                                            <label for="name">category name : </label>
                                            <input type="text" placeholder="name" class="form-control"   name="name" required="required" >
                                        </div>
                                        <?php
                                        if(isset($_SESSION['category_error'][0]))
                                        {?>
                                            <div class="alert alert-danger">
                                              <?php echo $_SESSION['category_error'][0]; ?>
                                            </div> 
                                            <?php unset($_SESSION['category_error'][0]); ?>
                                       <?php } ?> 
                                       <?php
                                        if(isset($_SESSION['category_error'][1]))
                                        {?>
                                            <div class="alert alert-danger">
                                              <?php echo $_SESSION['category_error'][1];?>
                                            </div> 
                                            <?php unset($_SESSION['category_error'][1]); ?>
                                       <?php } ?> 




                                        <div class="form-group">
                                            <label for="email">category description : </label>
                                            <br>
                                            <textarea name="description"  class="textarea" cols="30" rows="10"></textarea>
                                        </div>
                                        <?php
                                        if(isset($_SESSION['category_error'][2]))
                                        {?>
                                            <div class="alert alert-danger">
                                            <?php echo $_SESSION['category_error'][2]; ?>
                                            </div> 
                                            <?php unset($_SESSION['category_error'][2]); ?>
                                       <?php } ?> 

                                       <div class="form-group">
                                            <label for="visible">category visibility : </label>
                                             <select name="allow_visible" class="form-control">
                                              <option value="1">visible</option>
                                              <option value="0">non visible</option>
                                             </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="alllow_comment">category visibility : </label>
                                             <select name="alllow_comment" class="form-control">
                                              <option value="1">alllow_comment</option>
                                              <option value="0">non alllow_comment</option>
                                             </select>
                                        </div>
                                       
                                      
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary" value="add" name="submit" id="">   
                                        </div>
                            </form>
                            <a href="..\admin\index.php">you have account ?</a>
                        </div>
                </div>
         </div>
    <?php 
    }
   
    else if($do=="delete")
    {
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
           $id_will_del=$_REQUEST['id'];
           $statement=("DELETE FROM categories WHERE id=$id_will_del");
           $conn->query($statement);
           header ('location: categories.php?do=manage');
           exit();
        }
        else
        {
           $message="you are blocked to delete";
            redirect_to_dashbord($message);
        }
    }
    else if($do=="update")
    {
         
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
             
            $id= isset($_GET['id']) && is_numeric($_GET['id'])?intval($_GET['id']):0;
            $name=$_REQUEST['name'];
            $description=$_REQUEST['description'];
            $visible=$_REQUEST['allow_visible'];
            $comment=$_REQUEST['alllow_comment'];

            $statement=$conn->prepare("UPDATE categories SET name=?,description=?,allow_visible=?,alllow_comment=? WHERE id=?");
                $statement->execute(array($name,$description,$visible,$comment,$id));
                 if($statement->rowCount()>0)
                 {
                     
                     header('location: categories.php?do=manage');
                     exit();
                     
                 }



        }
        else
        {
            header ('location: categories.php?do=manage');
           exit();
        }
    }

    else if($do=="edit")
    {
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
            $id=$_REQUEST['id']; 

             
            $category=$conn->prepare("SELECT * FROM categories WHERE id=? ");
            $category->execute(array($id));
            $row_of_category=$category->fetch();

            

            ?>
            <div class="container">
            <div class="card d-flex justify-content-center" >
                    <div class="card-header">
                        <div class="h1 d-flex justify-content-center">edit category</div>
                    </div>
                    <div class="card-body">
                        <form action="?do=update&id=<?php echo $id?>" method="POST"> 

                                    <div class="form-group">
                                        <label for="name">category name : </label>
                                        <input type="text" placeholder="name" class="form-control" value="<?php echo $row_of_category['name']; ?>"   name="name" required="required" >
                                    </div>
                                    <?php
                                    if(isset($_SESSION['category_error'][0]))
                                    {?>
                                        <div class="alert alert-danger">
                                        <?php echo $_SESSION['category_error'][0]; ?>
                                        </div> 
                                        <?php unset($_SESSION['category_error'][0]); ?>
                                <?php } ?> 
                                <?php
                                    if(isset($_SESSION['category_error'][1]))
                                    {?>
                                        <div class="alert alert-danger">
                                        <?php echo $_SESSION['category_error'][1];?>
                                        </div> 
                                        <?php unset($_SESSION['category_error'][1]); ?>
                                <?php } ?> 




                                    <div class="form-group">
                                        <label for="description">category description : </label>
                                        <br>
                                        <textarea name="description"  class="textarea" cols="50" rows="7"><?php echo $row_of_category['description']; ?></textarea>
                                    </div>
                                    <?php
                                    if(isset($_SESSION['category_error'][2]))
                                    {?>
                                        <div class="alert alert-danger">
                                        <?php echo $_SESSION['category_error'][2]; ?>
                                        </div> 
                                        <?php unset($_SESSION['category_error'][2]); ?>
                                <?php } ?> 

                                <div class="form-group">
                                        <label for="visible">category visibility : </label>
                                        <select  name="allow_visible" class="form-control">
                                        <option <?php if ($row_of_category['allow_visible'] == 1 ) echo 'selected' ; ?> value="1">visible</option>
                                        <option <?php if ($row_of_category['allow_visible'] == 0 ) echo 'selected' ; ?> value="0">non visible</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="alllow_comment">category visibility : </label>
                                        <select name="alllow_comment" class="form-control">
                                        <option <?php if ($row_of_category['alllow_comment'] == 1 ) echo 'selected' ; ?> value="1">alllow_comment</option>
                                        <option <?php if ($row_of_category['alllow_comment'] == 0 ) echo 'selected' ; ?>  value="0">non alllow_comment</option>
                                        </select>
                                    </div>
                                
                                
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-primary" value="add" name="submit" id="">   
                                    </div>
                        </form>
                        
                    </div>
                </div>
            </div>
    
        
    <?php
            
 
            
        }
        else
        {
           $message="you are blocked to edit";
            redirect_to_dashbord($message);
        }
       
    }
     







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
 
 