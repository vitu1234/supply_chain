<?php
  include("../../connection/Functions.php");
  $operation = new Functions();
  session_start();
  if (!isset($_SESSION['user_accountant'])) {
    # code...
    header("Location:../index.php");
  }

  $user_id = $_SESSION['user_accountant'];
  $getUser = $operation->retrieveSingle("SELECT * FROM `users` WHERE user_id = '$user_id'");

  $company_id = $getUser['company_id'];

  $AllitemsSupply = $operation->retrieveMany("SELECT * FROM `item_supplies` INNER JOIN items ON item_supplies.item_id = items.item_id WHERE items.company_id = '$company_id' LIMIT 5"); 

  $countAllitemsSupply = $operation->countAll("SELECT * FROM `item_supplies` INNER JOIN items ON item_supplies.item_id = items.item_id WHERE items.company_id = '$company_id'"); 


  $year = date("Y");
  $months = array(1,2,3,4,5,6,7,8,9,10,11,12);
  $arrayReturned = array();
  $arrayDelivered = array();
  $arrayLabelsMonths = array();

  foreach ($months as $value){
    $month = $value;
    if ($value == 1) {
      array_push($arrayLabelsMonths, "Jan");
    }elseif ($value == 2) {
      array_push($arrayLabelsMonths, "Feb");
    }elseif ($value == 3) {
      array_push($arrayLabelsMonths, "Mar");
    }elseif ($value == 4) {
      array_push($arrayLabelsMonths, "Apr");
    }elseif ($value == 5) {
      array_push($arrayLabelsMonths, "May");
    }elseif ($value == 6) {
      array_push($arrayLabelsMonths, "Jun");
    }elseif ($value == 7) {
      array_push($arrayLabelsMonths, "Jul");
    }elseif ($value == 8) {
      array_push($arrayLabelsMonths, "Aug");
    }elseif ($value == 9) {
      array_push($arrayLabelsMonths, "Sep");
    }elseif ($value == 10) {
      array_push($arrayLabelsMonths, "Oct");
    }elseif ($value == 11) {
      array_push($arrayLabelsMonths, "Nov");
    }elseif ($value == 12) {
      array_push($arrayLabelsMonths, "Dec");
    }

    $countingReturned = $operation->countAll("SELECT * FROM item_supplies 
      INNER JOIN items ON item_supplies.item_id = items.item_id
      WHERE MONTH(item_supplies.date_created) = '$month' AND YEAR(item_supplies.date_created) = '$year' AND status = '6' AND items.company_id = '$company_id' GROUP BY MONTH(item_supplies.date_created) ");
    $countingDelivered = $operation->countAll("SELECT * FROM item_supplies 
      INNER JOIN items ON item_supplies.item_id = items.item_id
      WHERE MONTH(item_supplies.date_created) = '$month' AND YEAR(item_supplies.date_created) = '$year' AND status = '5' AND items.company_id = '$company_id' GROUP BY MONTH(item_supplies.date_created) ");
    array_push($arrayReturned, $countingReturned);
    array_push($arrayDelivered, $countingDelivered);
  }

  $datapointsReturned = json_encode($arrayReturned);
  $labels = json_encode($arrayLabelsMonths);
  $datapointsDelivered = json_encode($arrayDelivered);


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
  foreach ($getOrders as $row) {
    $total = $row['quantity']*$row['price'];
    $totalSupplied +=$total; 
  }

$TotalPaid = 0;
$TotalInvoices = 0;
  foreach ($AllitemsSupply as $row ) {
    $item_supply_ = $row['item_supply_id'];

    //check payment
    $countPayment = $operation->countAll("SELECT * FROM `payments` WHERE item_supply_id = '$item_supply_'");
      if ($countPayment > 0) {
        $getPayment = $operation->retrieveSingle("SELECT * FROM `payments` WHERE item_supply_id = '$item_supply_'");
        $TotalPaid += $getPayment['amount'];
      }
    $TotalInvoices += $operation->countAll("SELECT * FROM `invoices` WHERE item_supply_id = '$item_supply_'");
   
    
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Henz Supplies Accountant</title>
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
              <?php
                if ($getUser['img_url'] == '' || $getUser['img_url'] == NULL) {
                  // code...
                  echo ' <img src="../images/logo-mini.svg" alt="profile"/>';
                }else{
                  echo ' <img src="../images/'.$getUser['img_url'].'" alt="profile"/>';
                }
              ?>
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
            <a class="nav-link" href="payments.php">
              <i class="mdi mdi-square-inc-cash menu-icon"></i>
              <span class="menu-title">Payments</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="paid.php">
              <i class="mdi mdi-square-inc-cash menu-icon"></i>
              <span class="menu-title">Paid</span>
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
                          <i class="mdi mdi-currency-usd mr-3 icon-lg text-danger"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Total Paid</small>
                            <h5 class="mr-2 mb-0">K<?=number_format($TotalPaid,2)?></h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-book mr-3 icon-lg text-success"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Total Invoices</small>
                            <h5 class="mr-2 mb-0"><?=$TotalInvoices?></h5>
                          </div>
                        </div>
                       
                       <!--  <div class="d-flex py-3 border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-clock mr-3 icon-lg text-danger"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Pending Payment</small>
                            <h5 class="mr-2 mb-0">3</h5>
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
            <div class="col-md-12 grid-margin stretch-card">
        
                <div class="card">
                <div class="card-body">
                  <p class="card-title">Recent Transactions</p>
                  <div class="table-responsive">
                    <?php
                      if ($countAllitemsSupply > 0) {
                        ?>
                        <table id="recent-purchases-listing" class="table">
                          <thead>
                            <tr>
                               <th>Supplier Name</th>
                              <th>Item Name</th>
                                <th>Status report</th>
                                <th>Cost(K)</th>
                                <th></th>
                            </tr>
                          </thead>
                          <tbody>
                        <?php
                        foreach ($AllitemsSupply as $row ) {
                          $item_supply_ = $row['item_supply_id'];

                          $status ='';
                          //check payment
                          $countPayment = $operation->countAll("SELECT * FROM `payments` WHERE item_supply_id = '$item_supply_'");
                          if ($row['status'] <= 3) {
                            if ($countPayment > 0) {

                              $status = '<label class="badge badge-success">Paid<i class="mdi mdi-check"></i>';
                            }else{
                              $status = '<label class="badge badge-warning">Waiting Payment<i class="mdi mdi-close"></i></label>';
                            }
                          }else{

                              $status = '<label class="badge badge-success">Paid<i class="mdi mdi-check"></i>';
                          }
                         
                          $item_id = $row['item_id'];
                          $getItemCompany = $operation->retrieveSingle("SELECT * FROM `items` INNER JOIN companies ON items.company_id = companies.company_id WHERE items.item_id = '$item_id'");
                          $Invoice = '';

                          if ($operation->countAll("SELECT * FROM `invoices` WHERE item_supply_id = '$item_supply_'") > 0) {
                            $getInvoice = $operation->retrieveSingle("SELECT * FROM `invoices` WHERE item_supply_id = '$item_supply_'");
                            $Invoice = '
                            <div class="col-12 col-sm-6">
                                <p><a href="../secretary/'.$getInvoice['invoice_file'].'" class="btn btn-info mt-3">View Invoice</a></p>
                            </div>';
                          }else{
                            $Invoice = '
                            <div class="col-12 col-sm-6">
                                <p><a onclick="alert(\'coming soon!\')" class="text-light btn btn-info mt-3">Regenerate Invoice</a></p>
                            </div>';
                          }

                          ?>
                          <tr>
                             <td><?=$getItemCompany['company_name']?></td>
                            <td><?=$getItemCompany['item_name']?></td>
                            <td><?=$status?></td>
                            <td><?=number_format($getItemCompany['price']*$row['quantity'],2)?></td>
                            <td>   <a href="#view-company<?=$row['item_supply_id']?>" data-toggle="modal" class="btn btn-default"><i class="mdi mdi-eye"></i> View</a> </td>
                        </tr>

                            <div id="view-company<?=$row['item_supply_id']?>" class="modal fade " role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title font-weight-400">VIEW COMPANY</h5>
                                      <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                    <div class="modal-body p-4">

                                      <div class="row">
                                        <div class="col-12  mb-3">
                                            <h3>Company Name</h3>
                                            <h6><?=$getItemCompany['company_name']?></h6>
                                        </div>
                                        <div class="col-12 col-sm-6 mb-3">
                                            <h3>Company Email</h3>
                                            <p><a href="mailto:<?=$getItemCompany['company_email']?>"><?=$getItemCompany['company_email']?> </a></p>
                                        </div>
                                        
                                          <div class="col-12 col-sm-6 mb-3">
                                            <h3>Company Phone</h3>
                                            <p><a href="tel:<?=$getItemCompany['company_phone']?>"><?=$getItemCompany['company_phone']?> </a></p>
                                        </div>

                                         <div class="col-12 ">
                                            <h3>Company Address</h3>
                                            <p><?=$getItemCompany['company_address']?></p>
                                        </div>

                                       
                                      </div>
                                      <div class="row">
                                        <?=$Invoice?>
                                          
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
                        echo '<p class="text-center alert alert-warning">No Transactions history found!</p>';
                      }

                    ?>
                  
                      
                       
                    <a href="payments.php" class="float-right">View All</a> 
                  </div>
                </div>
              </div>
            </div>
          <div class="row">
            <div class="col-md-12 stretch-card">
              
            </div>
          </div>
        </div>
       
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
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
            <div class="row">
              <div class="col-12">
                 <?php
                 $btn = '';
                  if ($getUser['img_url'] != '') {
                    echo ' <img height="200px" width="200px" src="../images/'.$getUser['img_url'].'" alt="profile"/>';
                    $btn = '<button id="btnPro" type="submit" class="btn btn-primary my-3"> Add Picture </button>';
                  }else{
                    $btn = '<button id="btnPro" type="submit" class="btn btn-primary my-3"> Change Picture </button>';
                  }
                ?>

              </div>

              <form enctype="multipart/form-data" id="profilePicForm" method="post">
                 <div class="col-12 ">
                   <input type="file" name="profFile" id="profFile" required class="form-control">
                 </div>
                 <input type="hidden" name="uidProf" id="uidProf" required="" value="<?=$user_id?>" >
                  <div class="col-12 ">
                    <?=$btn?>
                  </div>

              </form>
             

            </div>
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
  <!-- <script src="../js/dashboard.js"></script> -->
  <script src="../js/data-table.js"></script>
  <script src="../js/jquery.dataTables.js"></script>
  <script src="../js/dataTables.bootstrap4.js"></script>
    <script src="../js/select2.min.js"></script>
  <script src="../vendors/alertifyjs/alertify.min.js"></script>
  <script src="js/js.js"></script>
  <script src="../js/js.js"></script>
  <!-- End custom js for this page-->
</body>

</html>

