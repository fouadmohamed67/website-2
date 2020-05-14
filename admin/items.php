<?php 
session_start();
$page_title='items page';
include 'init.php';
if(isset($_SESSION['name']) && $_SESSION['role']==1)
{
    $do=isset($_GET['do'])?$_GET['do']:'manage';
    if($do=='manage')
    {

        $statement=$conn->prepare("SELECT 
        items.*,
        categories.name As category_name,
        users.name As user_name
          FROM
          items
          INNER JOIN categories ON categories.id=items.category_id
          INNER JOIN users ON users.id=items.user_id");
        $statement->execute();
        $all_items=$statement->fetchAll();

    ?>
         <div class="container">
            <a href="?do=additem" class="btn btn-primary mb-2">add new item</a>
            <div class="card d-flex justify-content-center" >
                            <div class="card-header">
                                <div class="h1 d-flex justify-content-center">items</div>
                            </div>
                            <div class="card-body">
                            <?php
                            foreach($all_items as $item)
                            {
                               ?>
                                
                              <div class="item">
                               
                                <h3><?php echo $item['name'] ?></h3>
                                <p><?php echo $item['description'] ?></p>
                                <span class="price"><?php echo $item['price']; ?></span><br>
                                <span class="date">category name: <?php echo $item['category_name'] ; ?></span><br>
                                <span class="date"><?php echo $item['user_name'] ; ?></span><br>
                                <span class="date"><?php echo $item['date'] ; ?></span>
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
    else if($do=="additemtodata")
    {
        if($_SERVER['REQUEST_METHOD']=="POST")
        {
            $name=$_REQUEST['name'];
            $description=$_REQUEST['description'];
            $price=$_REQUEST['price'];
            $category_id=$_REQUEST['category_id'];
            $user_id=$_SESSION['id'];
            



           
            $errors_of_adding_items=array();
             
            if(empty($description))
            {
              $errors_of_adding_items[1]="the description is required";
            }

            if(empty($price))
            {
              $errors_of_adding_items[2]="the price is required";
            }


            if(!empty($errors_of_adding_items))
            {
                $_SESSION['items_error']=$errors_of_adding_items;
                header('location: items.php?do=additem');
                exit();

            }
            else
            {
                $statement=$conn->prepare("INSERT INTO items ( name, description,price,date,category_id,user_id) VALUES ( :_name,:_description,:_price,now(),:_category_id,:_user_id)");
                $statement->execute(array(
    
                    '_name'=>$name,
                    '_description'=>$description,
                    '_price'=>$price,
                    '_user_id'=>$user_id,
                    '_category_id'=>$category_id
                    
                    
                    
                    
    
                ));
                header('location: items.php');
                 exit();
            }

        }
        else
        {
            header("location: items.php");
            exit();
        }
    }
    else if($do=="additem")
    {
    ?>
      <div class="container">
                <div class="card d-flex justify-content-center" >
                        <div class="card-header">
                            <div class="h1 d-flex justify-content-center">add item</div>
                        </div>
                        <div class="card-body">
                        
                       
                            <form action="?do=additemtodata" method="POST"> 

                                        <div class="form-group">
                                            <label for="name">item name : </label>
                                            <input type="text" placeholder="name" class="form-control"   name="name" required="required" >
                                        </div>
                                      



                                        <div class="form-group">
                                            <label for="description">item description : </label>
                                            <br>
                                            <textarea name="description"  class="textarea" cols="30" rows="10"></textarea>
                                        </div>
                                        <?php
                                        if(isset($_SESSION['items_error'][1]))
                                        { 
                                            ?> 
                                            <div class="alert alert-danger">
                                            <?php echo $_SESSION['items_error'][1]; ?>
                                            </div> 
                                            <?php unset($_SESSION['items_error'][1]); ?>
                                       <?php } ?> 

                                       <div class="form-group">
                                            <label for="price">item price : </label>
                                             <input type="text" name="price">
                                       </div>
                                       <?php
                                        if(isset($_SESSION['items_error'][2]))
                                        {?>
                                            <div class="alert alert-danger">
                                            <?php echo $_SESSION['items_error'][2]; ?>
                                            </div> 
                                            <?php unset($_SESSION['items_error'][2]); ?>
                                       <?php } ?> 


                                       <div class="form-group">
                                            <label for="visible">item price : </label>
                                             <select name="category_id" class="form-control" id="">
                                                 <?php
                                                 $statement=$conn->prepare("SELECT * FROM categories");
                                                 $statement->execute();
                                                 $all_categories=$statement->fetchAll();


                                                 foreach($all_categories as $category)
                                                 {
                                                     ?>
                                                                <option value="<?php echo $category['id'];?>"><?php echo $category['name'] ;?> </option>
                                                     <?php
                                                 }

                                                 ?>
                                             </select>
                                       </div>

                                         
                                       
                                      
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary" value="add the item" name="submit" id="">   
                                        </div>
                            </form>
                             
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
           $statement=("DELETE FROM items WHERE id=$id_will_del");
           $conn->query($statement);
           header ('location: items.php?do=manage');
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
 
 