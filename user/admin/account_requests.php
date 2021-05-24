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
              <span class="nav-profile-name">Henry Kamwaza</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item">
                <i class="mdi mdi-settings text-primary"></i>
                Settings
              </a>
              <a class="dropdown-item">
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
            <a class="nav-link" href="index.html">
              <i class="mdi mdi-home menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="users.html">
              <i class="mdi mdi-account menu-icon"></i>
              <span class="menu-title">Users</span>
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
                  <h4 class="card-title text-center">Add Account Requests</h4>
                   
                  
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>User Name</th>
                          <th>Role</th>
                          <th>Status</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Jacob Isaac</td>
                          <td>Supplier</td>
                          <td><label class="badge badge-warning">Pending</label></td>
                          <td > <a href="#change-password" data-toggle="modal" class="btn btn-default"><i class="mdi mdi-eye"></i> View</a></td>
                        </tr>
                        <tr>
                          <td>Jane Banda</td>
                          <td>Xerographics - Secretary</td>
                          <td><label class="badge badge-success">Active</label></td>
                          <td > <button class="btn btn-default"><i class="mdi mdi-eye"></i> View</button></td>
                        </tr>
                       
                        
                      </tbody>
                    </table>
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

          <form id="addBankForm" method="post">
            <div class="row">
              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label for="firstName">Firstname</label>
                  <input type="text"  class="form-control" data-bv-field="firstName" name="p_account_name" id="p_account_name" required placeholder="Firstname" />
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label for="fullName">Lastname</label>
                  <input type="text" class="form-control" data-bv-field="fullName" id="p_account_number" name="p_account_number" required placeholder="Last Name" />
                </div>
              </div>
              <div class="col-12 col-sm-6">
                 <div class="form-group">
                  <label for="fullName">User role</label>
                   <select class="form-control" data-bv-field="fullName" id="p_bankname" name="p_bankname" required>
                      <option selected disabled>-select role-</option>
                      <option value="NBS Bank">Admin</option>
                      <option value="FDH Bank">Client</option>
                   </select>

                </div>
              </div>    
             <div class="col-12 col-sm-6">
                 <div class="form-group">
                  <label for="fullName">Email</label>
                  <input type="email"  class="form-control" data-bv-field="fullName" id="p_branch" name="p_branch" required placeholder="Email" />
                </div>
              </div>

               <div class="col-12 col-sm-6">
                 <div class="form-group">
                  <label for="fullName">Password</label>
                  <input type="password"  class="form-control" data-bv-field="fullName" id="p_branch" name="p_branch" required placeholder="Password" />

                </div>
              </div>    
             <div class="col-12 col-sm-6">
                 <div class="form-group">
                  <label for="fullName">Confirm Password</label>
                  <input type="password"  class="form-control" data-bv-field="fullName" id="p_branch" name="p_branch" required placeholder="Confirm Password" />
                </div>
              </div>

             </div>
              <input type="hidden" value="<?=$id?>" id="p_pid" name="p_pid" required />
            <button onclick="addBankDetails()"  id="btn_addBank" class="btn btn-primary btn-block mt-2" type="submit">Save </button>
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
  <!-- End custom js for this page-->
</body>

</html>

