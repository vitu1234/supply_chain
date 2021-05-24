<?php
  include("../../connection/Functions.php");
  $operation = new Functions();
  session_start();
  if (!isset($_SESSION['user_supplier'])) {
    # code...
    header("Location:../index.php");
  }

  $user_id = $_SESSION['user_supplier'];
  $getUser = $operation->retrieveSingle("SELECT * FROM `users` WHERE user_id = '$user_id'");
  $company_id = $getUser['company_id'];

  $getItems = $operation->retrieveMany("SELECT * FROM `items` WHERE company_id = '$company_id'");
  $countItems = $operation->countAll("SELECT * FROM `items` WHERE company_id = '$company_id'");
  $getCategories = $operation->retrieveMany("SELECT * FROM `item_categories` ");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Henz Supplies Supplier</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../vendors/base/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="../vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
       <link rel="stylesheet" href="../vendors/alertifyjs/css/alertify.min.css" />
<!-- include a theme -->
<link rel="stylesheet" href="../vendors/alertifyjs/css/themes/default.min.css" />
  <link rel="stylesheet" href="../css/style.css">
     <link rel="stylesheet" href="../css/select2.min.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../images/favicon.png" />
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">  
          <a class="navbar-brand brand-logo" href="index.php"><img src="../images/logo.svg" alt="logo"/></a>
          <a class="navbar-brand brand-logo-mini" href="index.php"><img src="../images/logo-mini.svg" alt="logo"/></a>
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-sort-variant"></span>
          </button>
        </div>  
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
       
        <ul class="navbar-nav navbar-nav-right">

          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="../images/faces/face5.jpg" alt="profile"/>
              <span class="nav-profile-name"><?=$getUser['fullname']?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a href="#" class="dropdown-item" data-toggle='modal' data-target='#modalUser<?=$user_id?>'>
                <i  class="mdi mdi-settings text-primary"></i>
                Settings
              </a>
              <a href="logout.php" class="dropdown-item">
                <i class="mdi mdi-logout text-primary"></i>
                Logout
              </a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="index.php">
              <i class="mdi mdi-home menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="clients.php">
              <i class="mdi mdi-view-headline menu-icon"></i>
              <span class="menu-title">Clients</span>
            </a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="items.php">
              <i class="mdi mdi-grid-large menu-icon"></i>
              <span class="menu-title">Items</span>
            </a>
          </li>
          
          
        </ul>
      </nav>
      <!-- partial -->
         <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title text-center">Available Items Inventory</h4>
                    <button  href="#change-password" data-toggle="modal" type="button" class="btn btn-primary  float-right">ADD PRODUCTS</button>

                    <a  href="categories.php" class="btn btn-primary  float-left">PRODUCTS CATEGORIES</a>

                  <div class="table-responsive ">

                    <?php
                      if ($countItems > 0) {
                        ?>
                        <table class="table table-hover mt-5">
                          <thead>
                            <tr>
                              <th></th>
                              <th>Product Category</th>
                              <th>Product Name</th>
                              <th>Qty</th>
                              <th>Price (MWK)</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>

                          <?php
                          foreach ($getItems as $row) {
                            $category_id = $row['category_id'];
                            $category = $operation->retrieveSingle("SELECT * FROM `item_categories` WHERE category_id = '$category_id'");
                            $remaining = '';
                            if ($row['quantity_remaining'] <= 5 ) {
                              $remaining = '<i class="text-danger">'.$row['quantity_remaining'].'</i> of '.$row['quantity_total'];
                            }else{
                              $remaining = '<i class="text-success">'.$row['quantity_remaining'].'</i> of '.$row['quantity_total'];
                            }

                            ?>
                            <tr>
                              <td><img style="height: 80px; width: 80px;" src="items/<?=$row['img_url']?>"/></td>
                              <td><?=$category['category_name']?></td>
                              <td><?=$row['item_name']?></td>
                              <td><?=$remaining?></td>
                              <td><?=number_format($row['price'],2)?></td>
                              <td > 
                                <a href="#view-item<?=$row['item_id']?>" data-toggle="modal" class="btn btn-default"><i class="mdi mdi-eye"></i> View</a> 
                                <a href="#view-file<?=$row['item_id']?>" data-toggle="modal" class="btn btn-default"><i class="mdi mdi-file"></i> Change File</a>
                              </td>

                              <div id="view-item<?=$row['item_id']?>" class="modal fade " role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title font-weight-400">VIEW ITEM</h5>
                                      <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                    <div class="modal-body p-4">

                                      <form id="editItemForm<?=$row['item_id']?>" method="post" enctype="multipart/form-data">
                                        <div class="row">
                                 
                                          <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                              <label for="firstName">Item Name</label>
                                              <input type="text"  class="form-control" data-bv-field="firstName" name="eitem_name" id="eitem_name<?=$row['item_id']?>" required placeholder="Product Name" value="<?=$row['item_name']?>" />
                                            </div>
                                          </div>
                                          <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                              <label for="fullName">Amount/piece(MWK)</label>
                                              <input onkeypress="return isNumberKey(event)" type="text" class="form-control" data-bv-field="fullName" id="eitem_price<?=$row['item_id']?>" name="eitem_price" required placeholder="Cost per piece" value="<?=$row['price']?>" />
                                            </div>
                                          </div>
                                          <div class="col-12 col-sm-6">
                                             <div class="form-group">
                                              <label for="fullName">Category</label>
                                               <select style="width: 100%; " class="category_name form-control" data-bv-field="fullName" id="ecategory_name<?=$row['item_id']?>" name="ecategory_name" required>
                                                  
                                                  <?php
                                                    foreach ($getCategories as $drow) {
                                                      $category_id = $row['category_id'];
                                                      if($category_id == $drow['category_id']){

                                                        echo '<option selected value="'.$drow['category_id'].'">'.$drow['category_name'].'</option>';
                                                      }else{
                                                        echo '<option value="'.$drow['category_id'].'">'.$drow['category_name'].'</option>';
                                                      }                                                   
                                                      
                                                    }
                                                  ?>
                                               </select>

                                            </div>
                                          </div>    
                                         <div class="col-12 col-sm-6">
                                             <div class="form-group">
                                              <label for="fullName">Total Products</label>
                                              <input type="text"  class="form-control" data-bv-field="fullName" id="etotal_products<?=$row['item_id']?>" name="etotal_products" required placeholder="Total Number of Products" value="<?=$row['quantity_total']?>"/>
                                            </div>
                                          </div>


                                         </div>
                                          <input type="hidden" value="<?=$company_id?>" id="euser_id<?=$row['item_id']?>" name="euser_id" required />

                                          <input type="hidden" value="<?=$row['item_id']?>" id="eitem_id<?=$row['item_id']?>" name="eitem_id" required />

                                        <div class="row">
                                        <div class="col-12 col-sm-6">
                                           <button onclick="editItem('<?=$row['item_id']?>')" id="addUserBtn<?=$row['item_id']?>" class="btn btn-primary btn-block mt-2" type="submit">Save </button>

                                        </div>  
                                         <div class="col-12 col-sm-6">
                                           <button onclick="getdelItem('<?=$row['item_id']?>')" class="btn btn-danger btn-block mt-2" type="button">Delete </button>
                                          
                                        </div>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>

                               <div id="view-file<?=$row['item_id']?>" class="modal fade " role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title font-weight-400">VIEW ITEM PICTURE</h5>
                                      <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                    <div class="modal-body p-4">
                                      <img style="height: 100px; width: 100px;" src="items/<?=$row['img_url']?>"/>
                                      <form id="editPictureForm<?=$row['item_id']?>" method="post" enctype="multipart/form-data">
                                        <div class="row">
                                 
                                          <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                              <label for="firstName">File</label>
                                              <input type="file"  class="form-control" data-bv-field="firstName" name="efile" id="efile<?=$row['item_id']?>" required placeholder="Product Name" value="<?=$row['item_name']?>" />
                                            </div>
                                          </div>
                                      


                                         </div>
                                          <input type="hidden" value="<?=$company_id?>" id="euser_id<?=$row['item_id']?>" name="euser_id" required />

                                          <input type="hidden" value="<?=$row['item_id']?>" id="eitem_id<?=$row['item_id']?>" name="eitem_id" required />

                                        <button onclick="editFile('<?=$row['item_id']?>')" id="addUserBtn<?=$row['item_id']?>" class="btn btn-primary btn-block mt-2" type="submit">Save </button>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </tr>
                            <?php
                          }
                          ?>
                          </tbody>
                        </table>
                        <?php
                      }else{
                        echo '<p class="text-center alert alert-warning mt-3">Items will appear here!</p>';
                      }
                    ?>
                                          
                  </div>
                </div>
              </div>
            </div>
            

          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->

        <!-- partial -->
      </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  </div>
  
  <div id="change-password" class="modal fade " role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title font-weight-400">NEW</h5>
          <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        </div>
        <div class="modal-body p-4">

          <form id="addItemForm" method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="firstName">Item Sample Picture</label>
                  <input type="file"  class="form-control" data-bv-field="firstName" name="item_file" id="item_file" required placeholder="Product File" />
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label for="firstName">Item Name</label>
                  <input type="text"  class="form-control" data-bv-field="firstName" name="item_name" id="item_name" required placeholder="Product Name" />
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label for="fullName">Amount/piece(MWK)</label>
                  <input onkeypress="return isNumberKey(event)" type="text" class="form-control" data-bv-field="fullName" id="item_price" name="item_price" required placeholder="Cost per piece" />
                </div>
              </div>
              <div class="col-12 col-sm-6">
                 <div class="form-group">
                  <label for="fullName">Category</label>
                   <select style="width: 100%; " class="category_name form-control" data-bv-field="fullName" id="category_name" name="category_name" required>
                      <option selected disabled>-Select Category-</option>
                      <?php
                        foreach ($getCategories as $row) {
                          echo '<option value="'.$row['category_id'].'">'.$row['category_name'].'</option>';
                        }
                      ?>
                   </select>

                </div>
              </div>    
             <div class="col-12 col-sm-6">
                 <div class="form-group">
                  <label for="fullName">Total Products</label>
                  <input type="text"  class="form-control" data-bv-field="fullName" id="total_products" name="total_products" required placeholder="Total Number of Products" />
                </div>
              </div>


             </div>
              <input type="hidden" value="<?=$company_id?>" id="user_id" name="user_id" required />
            <button  id="addUserBtn" class="btn btn-primary btn-block mt-2" type="submit">Save </button>
          </form>
        </div>
      </div>
    </div>
  </div>

<div id="deleteUserModal" class="modal fade " role="dialog" aria-hidden="true">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-400" id="title">DELETE ITEM</h5>
        <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body p-4">
        
        <form id="deleteItemForm" method="post">
          <input type="hidden" id="delItem" name="delItem"/>
          <p id="msg">Are you sure you want to delete this item?</p>
            <div class="row">
              
              <div class="col-6 col-sm-3">
                <div class="form-group">
                 <button type="submit" id="delBtn" class="btn btn-danger" > Yes </button>
                </div>
                </div>
               <div class="col-6 col-sm-3">
                <div class="form-group">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close"> Cancel </button>
                  </div>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

          <!--edit my profile-->
<div class="modal fade" id="modalUser<?=$user_id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title " id="exampleModalLabel">Edit My Profile</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
           <div class="modal-body p-4">

            <form id="editUserProfileForm<?=$user_id?>" method="post">
              <div class="row">
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="firstName">Fullname</label>
                    <input type="text"  class="form-control" data-bv-field="firstName" name="efullname" id="efullname<?=$user_id?>" required placeholder="Fullname" value="<?=$getUser['fullname']?>" />
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="fullName">Email</label>
                    <input value="<?=$getUser['email']?>" type="text" class="form-control" data-bv-field="fullName" id="eemail<?=$user_id?>" name="eemail" required placeholder="Email" />
                  </div>
                </div>
            

                <input type="hidden" name="euser_company" value="-" id="euser_company<?=$user_id?>" />
                <input type="hidden" name="euser_role" id="euser_role<?=$user_id?>" value="<?=$getUser['user_role']?>">
     

                 <div class="col-12 ">
                   <div class="form-group">
                    <label for="fullName">Password</label>
                    <input type="password" required="" value="password" class="form-control" data-bv-field="fullName" id="epassword<?=$user_id?>" name="epassword" placeholder="Password" />

                  </div>
              

                  </div>
                       <input type="hidden" id="e_user_id<?=$user_id?>" name="e_user_id" value="<?=$user_id?>"/>
                   
                       <div class="row">
                        <div class="col-12 ">
                                <button onclick="editUserProfile('<?=$user_id?>')"  id="editUserBtn<?=$user_id?>" class="btn btn-warning btn-block mt-2" type="submit">Update User</button>
                        </div>
                         </div> 
            </form>
        </div>
        </div>
      </div>
    </div>
  <!-- plugins:js -->
  <script src="../vendors/base/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="../vendors/chart.js/Chart.min.js"></script>
  <script src="../vendors/datatables.net/jquery.dataTables.js"></script>
  <script src="../vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="../js/off-canvas.js"></script>
  <script src="../js/hoverable-collapse.js"></script>
  <script src="../js/template.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="../js/dashboard.js"></script>
  <script src="../js/data-table.js"></script>
  <script src="../js/jquery.dataTables.js"></script>
  <script src="../js/dataTables.bootstrap4.js"></script>
    <script src="../js/select2.min.js"></script>
       <script src="../vendors/alertifyjs/alertify.min.js"></script>
         <script src="js/js.js"></script>
  <!-- End custom js for this page-->
</body>

<script type="text/javascript">
  
     $(document).ready(function() {
        $('.category_name').select2();
    });
      function getdelItem(id){
    $("#delItem").val(id);
    $("#view-item"+id).modal('toggle');
    $("#deleteUserModal").modal('toggle');
  }
</script>

</html>

