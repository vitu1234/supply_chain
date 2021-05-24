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
$getUsers = $operation->retrieveMany("SELECT * FROM `users` WHERE user_id <> '$user_id'");

$getAllCompanies = $operation->retrieveMany("SELECT *FROM companies");
$countCompanies = $operation->countAll("SELECT *FROM companies");
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
  <link rel="stylesheet" href="../css/style.css">
   <link rel="stylesheet" href="../css/select2.min.css">

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
              <a class="dropdown-item" href="../logout.php">
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
            
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title text-center">All Companies</h4>
                    <button  href="#change-password" data-toggle="modal" type="button" class="btn btn-primary  float-right">Add Company</button>
                    <!-- <a  href="account_requests.html"  class="float-left">Account Requests</a> -->
                  
                  <div class="table-responsive">
                    <?php
                      if ($countCompanies > 0) {
                        ?>
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th>Company Name</th>
                              <th>Company Email</th>
                              <th>Company Phone</th>
                              <th>Company Address</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                                foreach ($getAllCompanies as $row) {
                                  $company_id = $row['company_id'];

                                  
                                  $btn_del = '<div class="col-12 col-sm-6">
                                                <button data-dismiss="modal" onclick="getDeleteCompany(\''.$row['company_id'].'\')"  id="btn_e_del'.$row['company_id'].'" class="btn btn-danger btn-block mt-2" type="button">Delete Company</button>
                                        </div>';
                                  ?>
                                    <tr>
                                      <td><?=$row['company_name']?></td>
                                      <td><?=$row['company_email']?></td>
                                      <td><?=$row['company_phone']?></td>
                                      <td><?=$row['company_address']?></td>
                                      <td > <a href="#viewUser<?=$row['company_id']?>" data-toggle="modal" class="btn btn-default"><i class="mdi mdi-eye"></i> View</a></td>
                                    </tr>

                                    <div id="viewUser<?=$company_id?>" class="modal fade " role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title font-weight-400">VIEW COMPANY</h5>
                                              <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            </div>
                                            <div class="modal-body p-4">

                                               <form id="editCompanyForm<?=$row['company_id']?>" method="post">
                                                <div class="row">
                                                  <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                      <label for="firstName">Company Name</label>
                                                      <input type="text" class="form-control" data-bv-field="firstName" name="ecompany_name" id="ecompany_name<?=$row['company_id']?>" required placeholder="Company Name" value="<?=$row['company_name']?>" />
                                                    </div>
                                                  </div>
                                                  <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                      <label for="fullName">Company Email</label>
                                                      <input value="<?=$row['company_email']?>" type="email" class="form-control" data-bv-field="fullName" id="ecompany_email<?=$company_id?>" name="ecompany_email" required placeholder="Email" />
                                                    </div>
                                                  </div>
                                                  <div class="col-12 col-sm-6">
                                                     <div class="form-group">
                                                      <label for="fullName">Company Phone</label>
                                                      <input value="<?=$row['company_phone']?>" type="tel" class="form-control" data-bv-field="fullName" id="ecompany_phone<?=$company_id?>" name="ecompany_phone" required placeholder="Phone" />
                                                    </div>
                                                  </div>    
                                                   <div class="col-12 col-sm-6">
                                                     <div class="form-group">
                                                      <label for="fullName">Company Address</label>
                                                      <textarea class="form-control" name="ecompany_address" id="ecompany_address<?=$row['company_id']?>" required><?=$row['company_address']?></textarea>
                                                    </div>
                                                  </div> 
    
                                                 </div>


                                                    
                                                         <input type="hidden" id="e_company_id<?=$row['company_id']?>" name="e_company_id" value="<?=$row['company_id']?>"/>
                                                     
                                                         <div class="row">
                                                          <div class="col-12 col-sm-6">
                                                                  <button onclick="editCompany('<?=$row['company_id']?>')"  id="editUserBtn<?=$row['company_id']?>" class="btn btn-warning btn-block mt-2" type="submit">Update Company</button>
                                                          </div>
                                                            
                                                          <?=$btn_del?>
                                              </form>
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
                        echo '<p class="text-center text-warning">Users will appear here!</p>';
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

            <form id="addCompanyForm" method="post">
              <div class="row">
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="firstName">Company Name</label>
                    <input type="text" class="form-control" data-bv-field="firstName" name="company_name" id="company_name" required placeholder="Company Name" />
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="fullName">Company Email</label>
                    <input type="email" class="form-control" data-bv-field="fullName" id="company_email" name="company_email" required placeholder="Email" />
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                   <div class="form-group">
                    <label for="fullName">Company Phone</label>
                    <input type="tel" class="form-control" data-bv-field="fullName" id="company_phone" name="company_phone" required placeholder="Phone" />
                  </div>
                </div>    
                 <div class="col-12 col-sm-6">
                   <div class="form-group">
                    <label for="fullName">Company Address</label>
                    <textarea class="form-control" name="company_address" id="company_address" required></textarea>
                  </div>
                </div> 

               </div>
               <div class="row">
                <div class="col-12 ">
                        <button o  id="addUserBtn" class="btn btn-warning btn-block mt-2" type="submit">Add Company</button>
                </div>
                          
            </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="deleteUserModal" class="modal fade " role="dialog" aria-hidden="true">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-400" id="title">DELETE COMPANY</h5>
        <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body p-4">
        
        <form id="deleteCompanyForm" method="post">
          <input type="hidden" id="delCompany" name="delCompany"/>
          <p id="msg">Are you sure you want to delete this company?</p>
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

  <script>
    $(document).ready(function() {
        $('.companies').select2();
    });


  </script>

  <script>

  
   function getDeleteCompany(id){
    $("#delCompany").val(id);
    $("#viewUser"+id).modal('toggle');
    $("#deleteUserModal").modal('toggle');
  }
  
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
</body>

</html>

