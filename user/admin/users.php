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
                  <h4 class="card-title text-center">All Users</h4>
                    <button  href="#change-password" data-toggle="modal" type="button" class="btn btn-primary my-3 float-right">Add User</button>
                   <!--  <a  href="account_requests.php"  class="float-left">Account Requests</a> -->
                  
                  <div class="table-responsive">
                    <?php
                      if ($countUsers > 0) {
                        ?>
                        <table class="table table-hover" id="user_tbl">
                          <thead>
                            <tr>
                              <th>User Name</th>
                              <th>Role</th>
                              <th>Status</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                                foreach ($getUsers as $row) {
                                  $user_idc = $row['user_id'];
                                  $company_id = $row['company_id'];

                                  $company = $operation->retrieveSingle("SELECT *FROM companies WHERE company_id = '$company_id'");
                                    

                                    $Status = '<label class="badge badge-danger">Inactive</label>';

                                    $check = "";
                                  if ($row['account_status'] == 1) {
                                    $Status = '<label class="badge badge-success">Active</label>';
                                    $check = "checked";
                                  }


                                   $status = 2;
                                                
                                  if($row['account_status'] == 1){
                                    $status = 1;
                                    $check = "checked";
                                  }

                                  $user_role = '';

                                  if ($row['user_role'] == "supplier") {
                                    $user_role = '   
                                    <select class="form-control" data-bv-field="fullName" id="euser_role'.$user_idc.'" name="euser_role" required>
                                        
                                        <option selected value="supplier">Supplier</option>
                                        <option value="accountant">Accountant</option>
                                        <option value="secretary">Secretary</option>
                                     </select>';
                                  }elseif ($row['user_role'] == "accountant") {
                                    $user_role = '   
                                    <select class="form-control" data-bv-field="fullName" id="euser_role'.$user_idc.'" name="euser_role" required>
                                        <option value="admin">Admin</option>
                                        <option value="supplier">Supplier</option>
                                        <option selected value="accountant">Accountant</option>
                                        <option value="secretary">Secretary</option>
                                     </select>';
                                  }elseif ($row['user_role'] == 'secretary') {
                                    $user_role = '   
                                    <select class="form-control" data-bv-field="fullName" id="euser_role'.$user_idc.'" name="euser_role" required>
                                        <option value="admin">Admin</option>
                                        <option value="supplier">Supplier</option>
                                        <option value="accountant">Accountant</option>
                                        <option selected value="secretary">Secretary</option>
                                     </select>';
                                  }


                                  $selected1 = "selected";
                                  $btn_del = '<div class="col-12 col-sm-6">
                                                <button data-dismiss="modal" onclick="getDeleteUser(\''.$row['user_id'].'\')"  id="btn_e_del'.$row['user_id'].'" class="btn btn-danger btn-block mt-2" type="button">Delete User</button>
                                        </div>';
                                  $btn_sus = '<div class="col-12 col-sm-6">
                                           <div class="form-group">
                                            <label for="firstName">Account Status</label>
                                              <p class="demo" data-bv-field="firstName">
                                              <input  type="checkbox" name="acc_status" onchange="getSuspendUser(\''.$row['user_id'].'\', \''.$status.'\')" id="acc_status" '.$check.' data-toggle="toggle" data-onstyle="default">
                                              </p>  
                                          </div>   
                                      </div> ';

                                  ?>
                                    <tr>
                                      <td><?=$row['fullname']?></td>
                                      <td><?=$row['user_role']?> - <?=$company['company_name']?></td>
                                      <td><?=$Status?></td>
                                      <td > <a href="#viewUser<?=$user_idc?>" data-toggle="modal" class="btn btn-default"><i class="mdi mdi-eye"></i> View</a></td>
                                    </tr>

                                    <div id="viewUser<?=$user_idc?>" class="modal fade " role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title font-weight-400">VIEW USER</h5>
                                              <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            </div>
                                            <div class="modal-body p-4">

                                               <form id="editUserForm<?=$row['user_id']?>" method="post">
                                                <div class="row">
                                                  <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                      <label for="firstName">Fullname</label>
                                                      <input type="text"  class="form-control" data-bv-field="firstName" name="efullname" id="efullname" required placeholder="Fullname" value="<?=$row['fullname']?>" />
                                                    </div>
                                                  </div>
                                                  <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                      <label for="fullName">Email</label>
                                                      <input value="<?=$row['email']?>" type="text" class="form-control" data-bv-field="fullName" id="eemail<?=$user_idc?>" name="eemail" required placeholder="Email" />
                                                    </div>
                                                  </div>
                                                  <div class="col-12 col-sm-6">
                                                     <div class="form-group">
                                                      <label for="fullName">User role</label>
                                                      <?=$user_role?>
                                                    </div>
                                                  </div>    
                                                 <div class="col-12 col-sm-6">
                                                     <div class="form-group">
                                                      <label for="fullName">Company</label>
                                                      <br/><br/>
                                                       <select style="width:100%;" class="companies form-control " data-bv-field="fullName" id="euser_company<?=$user_idc?>" name="euser_company" required>
                                                          
                                                          <?php
                                                            foreach ($getAllCompanies as $rrow) {
                                                              if ($company_id == $rrow['company_id']) {
                                                                # code...
                                                                echo '<option selected value="'.$rrow['company_id'].'">'.$rrow['company_name'].'</option>';
                                                              }else{
                                                                echo '<option value="'.$rrow['company_id'].'">'.$rrow['company_name'].'</option>';
                                                              }
                                                              
                                                            }
                                                          ?>
                                                       </select>
                                                    </div>
                                                  </div>

                                                   <div class="col-12 col-sm-6">
                                                     <div class="form-group">
                                                      <label for="fullName">Password</label>
                                                      <input type="password" required="" value="password" class="form-control" data-bv-field="fullName" id="epassword" name="epassword" placeholder="Password" />

                                                    </div>
                                                  </div>    
                                              <?php echo $btn_sus; ?>
                                                 </div>


                                                    
                                                         <input type="hidden" id="e_user_id<?=$row['user_id']?>" name="e_user_id" value="<?=$row['user_id']?>"/>
                                                     
                                                         <div class="row">
                                                          <div class="col-12 col-sm-6">
                                                                  <button onclick="editUser('<?=$row['user_id']?>')"  id="editUserBtn<?=$row['user_id']?>" class="btn btn-warning btn-block mt-2" type="submit">Update User</button>
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

           <form id="addUserForm" method="post">
              <div class="row">
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="firstName">Fullname</label>
                    <input type="text"  class="form-control" data-bv-field="firstName" name="fullname" id="fullname" required placeholder="Fullname" />
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="fullName">Email</label>
                    <input type="text" class="form-control" data-bv-field="fullName" id="email" name="email" required placeholder="Email" />
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                   <div class="form-group">
                    <label for="fullName">User role</label>
                     <select class="form-control" data-bv-field="fullName" id="user_role" name="user_role" required>
                        <option selected disabled>-select role-</option>
                        <option value="supplier">Supplier</option>
                         <option value="accountant">Accountant</option>
                          <option value="secretary">Secretary</option>
                     </select>

                  </div>
                </div>    
               <div class="col-12 col-sm-6">
                   <div class="form-group">
                    <label for="fullName">Company</label>
                    <br/><br/>
                     <select style="width:100%;" class="companies form-control " data-bv-field="fullName" id="user_company" name="user_company" required>
                        <option selected disabled>-select company-</option>
                        <?php
                          foreach ($getAllCompanies as $row) {
                            echo '<option value="'.$row['company_id'].'">'.$row['company_name'].'</option>';
                          }
                        ?>
                     </select>
                  </div>
                </div>

                 <div class="col-12 col-sm-6">
                   <div class="form-group">
                    <label for="fullName">Password</label>
                    <input type="password"  class="form-control" data-bv-field="fullName" id="password1" name="password1" required placeholder="Password" />

                  </div>
                </div>    
               <div class="col-12 col-sm-6">
                   <div class="form-group">
                    <label for="fullName">Confirm Password</label>
                    <input type="password"  class="form-control" data-bv-field="fullName" id="password2" name="password2" required placeholder="Confirm Password" />
                  </div>
                </div>

               </div>
              <button  id="addUserBtn" class="btn btn-primary btn-block mt-2" type="submit">Save </button>
            </form>
        </div>
      </div>
    </div>
  </div>

  <div id="suspendUserModal" class="modal fade " role="dialog" aria-hidden="true">
      <div class="modal-dialog " role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title font-weight-400" id="title">SUSPEND USER</h5>
            <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
          </div>
          <div class="modal-body p-4">
            
            <form id="suspendUserForm" method="post">
              <input type="hidden" id="susUser" name="susUser"/>
              <input type="hidden" id="susStatus" name="susStatus"/>
              <p id="msg">Are you sure you want to suspend this user?</p>
                <div class="row">
                  
                  <div class="col-6 col-sm-3">
                    <div class="form-group">
                     <button type="submit" id="suspendBtn" class="btn btn-danger" > Yes </button>
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

      <div id="deleteUserModal" class="modal fade " role="dialog" aria-hidden="true">
      <div class="modal-dialog " role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title font-weight-400" id="title">DELETE USER</h5>
            <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
          </div>
          <div class="modal-body p-4">
            
            <form id="deleteUserForm" method="post">
              <input type="hidden" id="delUser" name="delUser"/>
              <p id="msg">Are you sure you want to delete this user?</p>
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
  <script src="../js/dashboard.js"></script>
  <script src="../js/data-table.js"></script>
  <script src="../js/jquery.dataTables.js"></script>
  <script src="../js/dataTables.bootstrap4.js"></script>
  <script src="../js/select2.min.js"></script>
   <script src="../vendors/alertifyjs/alertify.min.js"></script>
    <script src="js/js.js"></script>
    <script src="../js/js.js"></script>
  <!-- End custom js for this page-->

  <script>
    $(document).ready(function() {
        $('.companies').select2();
    });


  </script>

  <script>
  function getSuspendUser(id,status){
    if(status == 1){
      $("#msg").html("Are you sure to suspend this user?")
      $("#title").html("SUSPEND USER")
      $("#susStatus").val(2)
    }else{
       $("#msg").html("Are you sure to activate this user?")
      $("#title").html("ACTIVATE USER")
      $("#susStatus").val(1)
    }
    
    $("#susUser").val(id);
    $("#view-user"+id).modal('toggle');
    $("#suspendUserModal").modal('toggle');
    
    
  }
  
   function getDeleteUser(id){
    $("#delUser").val(id);
    $("#view-user"+id).modal('toggle');
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

