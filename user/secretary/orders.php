<?php
  include("../../connection/Functions.php");
  $operation = new Functions();
  session_start();
  if (!isset($_SESSION['user_secretary'])) {
    # code...
    header("Location:../index.php");
  }

  $user_id = $_SESSION['user_secretary'];
  $getUser = $operation->retrieveSingle("SELECT * FROM `users` WHERE user_id = '$user_id'");

  $company_id = $getUser['company_id'];

  $getOrders = $operation->retrieveMany("SELECT * FROM `item_supplies` 
    INNER JOIN items ON item_supplies.item_id = items.item_id
    INNER JOIN users ON item_supplies.user_id = users.user_id
    WHERE items.company_id = '$company_id' ORDER BY item_supply_id DESC
    ");
  $countOrders = $operation->countAll("SELECT * FROM `item_supplies` 
    INNER JOIN items ON item_supplies.item_id = items.item_id
    INNER JOIN users ON item_supplies.user_id = users.user_id
    WHERE items.company_id = '$company_id'
    ");

  $countReturned = $operation->countAll("SELECT * FROM `item_supplies` 
    INNER JOIN items ON item_supplies.item_id = items.item_id
    INNER JOIN users ON item_supplies.user_id = users.user_id
    WHERE items.company_id = '$company_id' AND status = '6' 
    ");

  $totalSupplied =0;
  $quotations = 0;
  $invoices = 0;
  foreach ($getOrders as $row) {
    $total = $row['quantity']*$row['price'];
    $totalSupplied +=$total; 
    $item = $row['item_supply_id'];

    $countQuotations = $operation->countAll("SELECT * FROM `quotations` WHERE item_supply_id = '$item'");
    $quotations +=$countQuotations; 

    $countInvoices = $operation->countAll("SELECT * FROM `invoices` WHERE item_supply_id = '$item'");
    $invoices +=$countInvoices;

  }


?>

    <!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Henz Supplies Secretary</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../vendors/base/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="../vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../vendors/alertifyjs/css/alertify.min.css" />
<!-- include a theme -->
<link rel="stylesheet" href="../vendors/alertifyjs/css/themes/default.min.css" />
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
            <a class="nav-link" href="orders.php">
              <i class="mdi mdi mdi-cart menu-icon"></i>
              <span class="menu-title">Orders</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="my_orders.php">
              <i class="mdi mdi mdi-cart menu-icon"></i>
              <span class="menu-title">My Orders</span>
            </a>
          </li>
<!--
          <li class="nav-item">
            <a class="nav-link" href="components.html">
              <i class="mdi mdi-grid-large menu-icon"></i>
              <span class="menu-title">My Components</span>
            </a>
          </li>
-->
          
          
        </ul>
      </nav>
      <!-- partial -->
         <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title text-center">All Orders</h4>
<!--                    <button  href="#change-password" data-toggle="modal" type="button" class="btn btn-primary  float-right">Add User</button>-->
                  
                  <div class="table-responsive">
                               <?php
                      if ($countOrders > 0) {
                        ?>
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th>Order Name</th>
                              <th>Quantity</th>
                              <th>Order Status</th>
                              <th>Total Amount (MWK)</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                        <?php
                        foreach ($getOrders as $row) {
                          $total = $row['quantity']*$row['price'];
                          $item_supply_id = $row['item_supply_id'];
                          $user_cust_id = $row['user_id'];

                          $countQuotationAdded = $operation->countAll("SELECT * FROM `quotations` WHERE item_supply_id = '$item_supply_id'"); 
                          

                          $getUserCust = $operation->retrieveSingle("SELECT *FROM users WHERE user_id='$user_cust_id'");
                          $comp_id = $getUserCust['company_id'];
                          $getCompany = $operation->retrieveSingle("SELECT *FROM companies WHERE company_id = '$comp_id'");

                          $status = '';
                          if ($row['status'] == 1) {
                            if ($countQuotationAdded > 0) {
                              $status = '<label class="badge badge-secondary">Quoted </label>'; 
                            }else{
                              $status = '<label class="badge badge-warning">Quotation </label>'; 
                            }
                                                       
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
                          ?>
                          <tr>
                            <td><?=$row['item_name']?></td>
                            <td><?=$row['quantity']?></td>
                            <td><?=$status?></td>
                            <td><?=number_format($total,2)?></td>
                            <td> 
                              <a href="#view-company<?=$row['item_supply_id']?>" data-toggle="modal" class="btn btn-default"><i class="mdi mdi-eye"></i> View</a> 

                              <a href="#view-item<?=$row['item_supply_id']?>" data-toggle="modal" class="btn btn-default"><i class="mdi mdi-file"></i> Quote/Invoice</a> 

                               
                            </td>
                          </tr>
                            <div id="view-item<?=$row['item_supply_id']?>" class="modal fade " role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title font-weight-400">VIEW ORDER</h5>
                                      <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                    <div class="modal-body p-4">

                                      <?php
                                        
                                        if ($countQuotationAdded == 0) {
                                          ?>
                                          <form id="addQuotationForm<?=$row['item_supply_id']?>" method="post" enctype="multipart/form-data">
                                            <div class="row">
                                             
                                             <div class="col-12 col-sm-6">
                                                 <div class="form-group">
                                                  <label for="fullName">Attach Quotation</label>
                                                  <input accept="application/pdf" type="file"  class="form-control" data-bv-field="fullName" name="quotation_file" required />
                                                </div>
                                              </div>


                                             </div>
                                             <input type="hidden" value="<?=$row['item_supply_id']?>" id="quotation_item_id<?=$row['item_supply_id']?>" name="quotation_item_id" required />

                                            <div class="row">
                                            <div class="col-12 ">
                                               <button onclick="AddQuotation('<?=$row['item_supply_id']?>')" id="addUserBtn<?=$row['item_supply_id']?>" class="btn btn-primary btn-block mt-2" type="submit">Save </button>

                                            </div>  
                                            
                                            </div>
                                          </form>
                                          <?php
                                        }else{
                                          //get quotation file for download
                                          $getQuotationAdded = $operation->retrieveSingle("SELECT * FROM `quotations` WHERE item_supply_id = '$item_supply_id'");
                                          ?>
                                          <a class="btn btn-primary  float-left" class="mt-3 mb-3" href="files/<?=$getQuotationAdded['quotation_file']?>"><i class="mdi mdi-pdf"></i>View Quotation</a>
                                          <br/><br/>
                                          <?php
                                          //check if customer has requested for invoice
                                          if ($row['invoice_request'] == 1) {
                                            $countInvoiceAdded = $operation->countAll("SELECT * FROM `invoices` WHERE item_supply_id = '$item_supply_id'"); 
                                            if ($countInvoiceAdded == 0) {
                                              ?>
                                              <form id="addInvoiceForm<?=$row['item_supply_id']?>" method="post" enctype="multipart/form-data">
                                                <div class="row">
                                                 
                                                 <div class="col-12 col-sm-6">
                                                     <div class="form-group">
                                                      <label for="fullName">Attach Invoice</label>
                                                      <input accept="application/pdf" type="file"  class="form-control" data-bv-field="fullName" name="invoice_file" required />
                                                    </div>
                                                  </div>


                                                 </div>
                                                 <input type="hidden" value="<?=$row['item_supply_id']?>" id="invoice_item_id<?=$row['item_supply_id']?>" name="invoice_item_id" required />

                                                <div class="row">
                                                <div class="col-12 ">
                                                   <button onclick="AddInvoice('<?=$row['item_supply_id']?>')" id="addUserBtnInvo<?=$row['item_supply_id']?>" class="btn btn-primary btn-block mt-2" type="submit">Save </button>

                                                </div>  
                                                
                                                </div>
                                              </form>
                                              <?php
                                            }else{
                                              $getInvoiceAdded = $operation->retrieveSingle("SELECT * FROM `invoices` WHERE item_supply_id = '$item_supply_id'");
                                              ?>
                                              <a class="btn btn-primary  float-right" href="files/<?=$getInvoiceAdded['invoice_file']?>"><i class="mdi mdi-pdf"></i>View Invoice</a>
                                              <?php
                                            }
                                            
                                          }
                                        }
                                      ?>

                                      

                                     
                                    </div>
                                  </div>
                                </div>
                              </div>

                            <div id="view-company<?=$row['item_supply_id']?>" class="modal fade " role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title font-weight-400">VIEW COMPANY</h5>
                                      <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                    <div class="modal-body p-4">

                                      <div class="row">
                                        <div class="col-12 mb-3">
                                            <h3>Company Name</h3>
                                            <h6><?=$getCompany['company_name']?></h6>
                                        </div>
                                        <div class="col-12 col-sm-6 mb-3">
                                            <h3>Company Email</h3>
                                            <p><a href="mailto:<?=$getCompany['company_email']?>"><?=$getCompany['company_email']?> </a></p>
                                        </div>
                                         <div class="col-12 col-sm-6 mb-3">
                                            <h3>Company Phone</h3>
                                            <p><a href="tel:<?=$getCompany['company_phone']?>"><?=$getCompany['company_phone']?> </a></p>
                                        </div>
                                         <div class="col-12 ">
                                            <h3>Company Address</h3>
                                            <p><?=$getCompany['company_address']?></p>
                                        </div>
                                      </div>

                                     
                                    </div>
                                  </div>
                                </div>
                              </div>
                          <?php
                        }
                        ?>
                          </tbody>
                        </table>
                        <?php
                      }else{
                        echo '<p class="text-center alert alert-warning mt-3 mb-3">Recent client orders will appear here!</p>';
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
          <h5 class="modal-title font-weight-400">ORDER INFO</h5>
          <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        </div>
        <div class="modal-body p-4">

          <table class="table ">
                     
                      <tbody>
                        <tr>
                          <th>Supplier Name</th>
                          <td>KL Stationery</td>
                        </tr>
                        <tr>
                          <th>Order Name</th>
                          <td>PC Order 1</td>
                        </tr>
                        <tr>
                          <th>Quantity</th>
                          <td>4</td>
                        </tr>
                        <tr>
                          <th>Order Status</th>
                          <td>Pending Payment</td>
                        </tr>
                     
                        
                        <tr>
                          <th>Payment</th>
                          <td><label class="badge badge-warning">Waiting Payment</label></td>
                        </tr>
                        <tr>
                          <th>Quotation</th>
                          <td><a href="#"><label class="badge badge-success">Download<i class="mdi mdi-download"></i></label></a></td>
                        </tr>
                        
                         <tr>
                          <th>Invoice</th>
                          <td>-</td>
                        </tr>
                        
                        <tr>
                          <td><a href="#" class="btn btn-primary">Request Invoice>>></a></td>
                        </tr>
                        
                      </tbody>
                    </table>
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

</html>

