<?php
  include("../../connection/Functions.php");
  $operation = new Functions();
  session_start();

  if (!isset($_SESSION['user'])) {
    # code...
    header("Location:../index.php");
  }

  $user_id = $_SESSION['user'];

$getUser = $operation->retrieveSingle("SELECT * FROM `users` WHERE user_id = '$user_id'");
$countUsers = $operation->countAll("SELECT * FROM `users` WHERE user_id <> '$user_id'");
$countOrdersSupplied = $operation->countAll("SELECT * FROM `item_supplies` WHERE status = '5'");
$countAllOrders = $operation->countAll("SELECT * FROM `item_supplies`");
$countAvailableItems = $operation->countAll("SELECT * FROM `items` WHERE quantity_total > 0");

$getSupplies = $operation->retrieveMany("SELECT * FROM `item_supplies`
  INNER JOIN users ON item_supplies.user_id = users.user_id
 LIMIT 8");
$countSupplies = $operation->countAll("SELECT * FROM `item_supplies`
  INNER JOIN users ON item_supplies.user_id = users.user_id
 LIMIT 8");

$countCompanies = $operation->countAll("SELECT * FROM `companies`");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Henz Supplies Admin</title>
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
  <!-- endinject -->
  <link rel="shortcut icon" href="../images/favicon.png" />
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">  
          <a class="navbar-brand brand-logo" href="index.html"><img src="../images/logo.svg" alt="logo"/></a>
          <a class="navbar-brand brand-logo-mini" href="index.html"><img src="../images/logo-mini.svg" alt="logo"/></a>
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
              <a href="../logout.php" class="dropdown-item">
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
            <a class="nav-link" href="users.php">
              <i class="mdi mdi-account menu-icon"></i>
              <span class="menu-title">Users</span>
            </a>
          </li>
          
     <li class="nav-item">
            <a class="nav-link" href="companies.php">
              <i class="mdi mdi-grid-large menu-icon"></i>
              <span class="menu-title">Companies</span>
            </a>
          </li>
          
          
        </ul>
      </nav>
      <!-- partial -->
           <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                  <div class="mr-md-3 mr-xl-5">
                    <h2>Welcome back,</h2>
                    <p class="mb-md-0">Your analytics dashboard.</p>
                  </div>
                  <div class="d-flex">
                    <i class="mdi mdi-home text-muted hover-cursor"></i>
                    <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;Dashboard&nbsp;/&nbsp;</p>
                    <p class="text-primary mb-0 hover-cursor">Analytics</p>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body dashboard-tabs p-0">
                  <ul class="nav nav-tabs px-4" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                    </li>
                    
                  </ul>
                  <div class="tab-content py-0 px-0">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                      <div class="d-flex flex-wrap justify-content-xl-between">
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-grid mr-3 icon-lg text-info"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Companies</small>
                            <h5 class="mr-2 mb-0"><?=$countCompanies?></h5>
                          </div>
                        </div>
                       
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-account mr-3 icon-lg text-success"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Total Users</small>
                            <h5 class="mr-2 mb-0"><?=$countUsers?></h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-account mr-3 icon-lg text-warning"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Supplied Items</small>
                            <h5 class="mr-2 mb-0"><?=$countOrdersSupplied."/".$countAllOrders?></h5>
                          </div>
                        </div>
                        <div class="d-flex py-3 border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-sitemap  mr-3 icon-lg text-danger"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Available Items</small>
                            <h5 class="mr-2 mb-0"><?=$countAvailableItems?></h5>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="sales-tab">
                      <div class="d-flex flex-wrap justify-content-xl-between">
                        
                      <!--   <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-currency-usd mr-3 icon-lg text-danger"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Revenue</small>
                            <h5 class="mr-2 mb-0">K5,775,445</h5>
                          </div>
                        </div> -->
                     <!--    <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-account mr-3 icon-lg text-success"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Total Users</small>
                            <h5 class="mr-2 mb-0">50</h5>
                          </div>
                        </div> -->
                   <!--      <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-account mr-3 icon-lg text-warning"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Suppliers</small>
                            <h5 class="mr-2 mb-0">33</h5>
                          </div>
                        </div> -->
                   <!--      <div class="d-flex py-3 border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-sitemap  mr-3 icon-lg text-danger"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Items</small>
                            <h5 class="mr-2 mb-0">3497843</h5>
                          </div>
                        </div> -->
                      </div>
                    </div>
                    <div class="tab-pane fade" id="purchases" role="tabpanel" aria-labelledby="purchases-tab">
      <div class="d-flex flex-wrap justify-content-xl-between">
                        
                        <!-- <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-currency-usd mr-3 icon-lg text-danger"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Revenue</small>
                            <h5 class="mr-2 mb-0">K5,775,445</h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-account mr-3 icon-lg text-success"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Total Users</small>
                            <h5 class="mr-2 mb-0">50</h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-account mr-3 icon-lg text-warning"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Suppliers</small>
                            <h5 class="mr-2 mb-0">33</h5>
                          </div>
                        </div>
                        <div class="d-flex py-3 border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-sitemap  mr-3 icon-lg text-danger"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Items</small>
                            <h5 class="mr-2 mb-0">3497843</h5>
                          </div>
                        </div> -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12 stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title">Recent Supplies</p>
                  <div class="table-responsive">
             

                        <?php
                        if ($countSupplies > 0) {
                          ?>
                          <table id="recent-purchases-listing" class="table">
                          <thead>
                            <tr>
                              <th>Status report</th>
                                <th>Supplier Name</th>
                                <th>Client</th>
                                <th>Item</th>
                                <th>Cost(K)</th>
                                <th>Quantity</th>
                                <th>Date</th>
                            </tr>
                          </thead>
                          <tbody>

                          <?php


                            foreach ($getSupplies as $row) {
                            # code...
                            $to_company_id =$row['company_id']; 
                            $item_id = $row['item_id'];

                            $getToCompany = $operation->retrieveSingle("SELECT * FROM `companies` WHERE company_id = '$to_company_id'");

                            $getItem = $operation->retrieveSingle("SELECT * FROM `items` WHERE item_id = '$item_id'");

                            $from_company_id = $getItem['company_id'];
                            $from_Company = $operation->retrieveSingle("SELECT * FROM `companies` WHERE company_id = '$from_company_id'");
                        $status = '';
                          if ($row['status'] == 1) {
                            $status = '<label class="badge badge-secondary">Quotation </label>';
                          }elseif ($row['status'] == 2) {
                            $status = '<label class="badge badge-info">Invoice </label>';
                          }elseif ($row['status'] == 3) {
                            $status = '<label class="badge badge-primary">Payment </label>';
                          }elseif ($row['status'] == 4) {
                            $status = '<label class="badge badge-warning">Ordered/Intransit </label>';
                          }elseif ($row['status'] == 5) {
                            $status = '<label class="badge badge-success">Delivery/Delivered</label>';
                          }elseif ($row['status'] == 6) {
                            $status = '<label class="badge badge-danger">Returned</label>';
                          }

                              echo '
                              <tr>
                              <td>'.$status.'</td>
                                <td>'.$from_Company['company_name'].'</td>
                                <td>'.$getToCompany['company_name'].'</td>
                                <td>'.$getItem['item_name'].'</td>
                                <td>'.$getItem['price'].'</td>
                                <td>'.$row['quantity'].'</td>
                                <td>'.$row['date_created'].'</td>
                              </tr>';
                          }

                          ?>
                                      </tbody>
                          </table>
                          <?php
                        }else{
                          echo '<p class="text-center text-warning">Recent supplies will appear here</p>';
                        }
                        
                        ?>
                       
                  
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
       
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
  </div>
  <!-- container-scroller -->
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
     <script src="../vendors/alertifyjs/alertify.min.js"></script>
         <script src="js/js.js"></script>
  <!-- End custom js for this page-->
</body>

</html>

