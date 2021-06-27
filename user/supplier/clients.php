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
                  <h4 class="card-title text-center">All Clients</h4>
            
                  
                  <div class="table-responsive">
                    <?php
                      if ($countOrders > 0) {
                        ?>
                        <table class="table table-hover" id="user_tbl">
                          <thead>
                            <tr>
                              <th>Client Name</th>
                              <th>Item Name</th>
                               <th>Qty</th>
                               <th>Total Amount</th>
                              <th>Status</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                        <?php
                        foreach ($getOrders as $row) {

                          $client_company_id = $row['company_id'];
                          $getCompany = $operation->retrieveSingle("SELECT *FROM companies WHERE company_id = '$client_company_id'");

                          $total = $row['quantity']*$row['price'];
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
                          ?>
                          <tr>
                            <td><?=$row['fullname'].' - '.$getCompany['company_name']?></td>
                            <td><?=$row['item_name']?></td>
                            <td><?=$row['quantity']?></td>
                            <td><?=number_format($total,2)?></td>
                            <td><?=$status?></td>
                            <td>
                              <a href="#view-company<?=$row['item_supply_id']?>" data-toggle="modal" class="btn btn-default"><i class="mdi mdi-eye"></i> View</a>
                            </td> 
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
                        echo '<p class="text-center alert alert-warning mt-3 mb-3">Client orders will appear here!</p>';
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
  


          <!--edit my profile-->
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
         <script src="../js/js.js"></script>
</body>
  <script type="text/javascript">
       $(document).ready(function(){
    var table = $('#user_tbl').DataTable({
      columnDefs: [
        {bSortable: false, targets: [2]} 
      ] ,
       "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      dom: 'Bfrtip',
      buttons: [
          'colvis',
          'csv',
          'pdf'
      ]


    });
});

  </script>
</html>

