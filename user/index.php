

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Henz Supplies SIGN IN</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/base/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- include the style -->
<link rel="stylesheet" href="vendors/alertifyjs/css/alertify.min.css" />
<!-- include a theme -->
<link rel="stylesheet" href="vendors/alertifyjs/css/themes/default.min.css" />
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>
<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="images/logo.svg" style="width: 100%; height: 50px;" alt="logo">
              </div>
              <h6 class="font-weight-light text-center">Sign in to continue.</h6>
              <form class="pt-3" method="post" id="loginForm">
                <div class="form-group">
                  <input type="email" class="form-control form-control-lg" id="email" name="email" required="" placeholder="Email">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="password" placeholder="Password" name="password" required="">
                </div>
                <div class="mt-3">
                  <button id="loginBtn" type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" >SIGN IN</button>
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                   
                  </div>
                  <a href="forgot_password.php" class="auth-link text-black">Forgot password?</a>
                </div>
              
               
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>

  <!-- plugins:js -->
  <script src="vendors/base/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="vendors/chart.js/Chart.min.js"></script>
  <script src="vendors/datatables.net/jquery.dataTables.js"></script>
  <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="js/dashboard.js"></script>
  <script src="js/data-table.js"></script>
  <script src="js/jquery.dataTables.js"></script>
  <script src="js/dataTables.bootstrap4.js"></script>
  <script src="vendors/alertifyjs/alertify.min.js"></script>
   <script src="js/js.js"></script>
  <!-- End custom js for this page-->
</body>
<script type="text/javascript">
  
  $(function(){
    // alertify.alert('Ready!');

  })

  $("#loginForm").on('submit',function(e){
   var form_data = $(this).serialize();
    
    var email = $("#email").val();
    var password = $("#password").val();

  
    
    if(email !== '' && password !== '' ){
          $("#loginBtn").html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> SIGN IN...');
           $.ajax({ //make ajax request to cart_process.php
              url: "process/login.php",
                  type: "POST",
                  //dataType:"json", //expect json value from server
                  data: form_data
              }).done(function(dataResult){ //on Ajax success
                console.log(dataResult);
                $("#loginBtn").html('SIGN IN');
                var data = JSON.parse(dataResult);
             
                document.getElementById("loginForm").reset();//empty the form             
                if(data.code == 1){
                   setTimeout(function(){
                     window.location = data.location+"/index.php";
                   },800);
                  
                }else if(data.code == 2){
                    alertify.error(data.msg);
                }else{
                  alertify.error('An error occured, try again');
                }
                document.getElementById("loginForm").reset();//empty the form
           });

        
          
          
        }
    else{
      alertify.alert('All fields are required!');
    }
  
 
    e.preventDefault();
    e.stopImmediatePropagation();
});

</script>
</html>

