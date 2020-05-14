<?php 
session_start();
$page_title='dashbord';
include 'init.php';

if(isset($_SESSION['name']) &&$_SESSION['role']==1)
{
             
            $statement=$conn->prepare("SELECT * from categories ");
            $statement->execute();
            $row_of_category=$statement->fetchAll();



            
            $no_all_users=count_of_Users('users',0,$conn);
            $no_all_admins=count_of_Users('users',1,$conn);
            $no_all_categories=count_of_things('categories',$conn);
            $no_all_items=count_of_things('items',$conn);
  ?>

    <div class="container less-mar">
            <div class="row justify-content-center row-counter">
               <div class="col-md-2  users text-center counter">
                  
                   <h3>users</h3>
                   <span><?php echo $no_all_users; ?></span>
                 </div>
   
               <div class="col-md-3   admins text-center counter">
                
                   <h3>admins</h3>
                   <span><?php echo $no_all_admins; ?></span>
                 </div>
                
               <div class="col-md-3   categories text-center counter">
                 
                   <h3>categories</h3>
                   <span><?php echo  $no_all_categories ; ?></span>
                 </div> 
               <div class="col-md-2   items text-center counter">
                   <h3>items</h3>
                   <span> <?php echo $no_all_items; ?></span>
               </div>
                
            </div>
          <div class="row">
            <div class="col-md-4">
               <div class="card">
                 <div class="card-header">all categories</div>
                 <div class="card-body in-card-body">
                   <div class="col">
                     <?php
                     if($no_all_categories <=0)
                     {
                       ?>
                            <h3>no categories</h3>
                       <?php
                     }
                     else
                     {
                      foreach($row_of_category as $category)
                      {?>
 
                          <div class="ele ">
                           <a href=""><?php echo $category['name'];?></a>
                          </div>
                      <?php }
                     }
                     ?>
                    </div>
                 </div>
               </div>
                     



            </div>
            <div class="col-md-8"> 
              <div class="card">
                  <div class="card-header">header</div>
                  <div class="card-body">
                             body
                  </div>    
              </div>
            </div>
          </div>               
    </div>
<?php
  }
else
{
  header ('location: index.php');
  exit();
}
 