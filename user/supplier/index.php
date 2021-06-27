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

  $getPayPalDetails = $operation->retrieveSingle("SELECT * FROM `supplier_payment_details` WHERE company_id = '$company_id'");
  $countPayPalDetails = $operation->countAll("SELECT * FROM `supplier_payment_details` WHERE company_id = '$company_id'");


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
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                  <div class="mr-md-3 mr-xl-5">
                    <h2>Welcome back,</h2>
                    <p class="mb-md-0">Your analytics dashboard.</p>

                  </div>
                 
                </div>
                <a href="subscribe.php" class="btn btn-secondary text-center dropdown-toggle" >Subscriptions</a>
                <a href="#" class="btn btn-primary text-center dropdown-toggle" data-toggle='modal' data-target='#modalPay<?=$user_id?>'>Payment Details</a>
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
                            <small class="mb-1 text-muted">Total Supplied</small>
                            <h5 class="mr-2 mb-0">K<?=number_format($totalSupplied,2)?></h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-eye mr-3 icon-lg text-success"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Total Clients</small>
                            <h5 class="mr-2 mb-0"><?=$countOrders?></h5>
                          </div>
                        </div>
                       
                        <div class="d-flex py-3 border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-flag mr-3 icon-lg text-danger"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Returned</small>
                            <h5 class="mr-2 mb-0"><?=$countReturned?></h5>
                          </div>
                        </div>
                      </div>
                    </div>
                    
              
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-7 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title">Supplied Components in <?=$year?></p>
                  <p class="mb-4">Total Amount of Components Supplied </p>
                  <div id="cash-deposits-chart-legend" class="d-flex justify-content-center pt-3"></div>
                  <canvas id="cash-deposits-chart"></canvas>
                </div>
              </div>
            </div>
            <div class="col-md-5 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title">Supplies </p>
                  <h1>K<?=number_format($totalSupplied,2)?></h1>
                  <h4>Total Supplies in <?=$year?></h4>
                  
                  <div id="total-sales-chart-legend"></div>                  
                </div>
                <canvas id="total-sales-chart"></canvas>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title">Recent Transactions</p>
                  <div class="table-responsive">
                     <?php
                      if ($countOrders > 0) {
                        ?>
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th>Client Name</th>
                              <th>Item Name</th>
                               <th>Qty</th>
                               <th>Total Amount</th>
                              <th>Status</th>
                            </tr>
                          </thead>
                          <tbody>
                        <?php
                        foreach ($getOrders as $row) {
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
                            <td><?=$row['fullname']?></td>
                            <td><?=$row['item_name']?></td>
                            <td><?=$row['quantity']?></td>
                            <td><?=number_format($total,2)?></td>
                            <td><?=$status?></td>
                          
                          </tr>
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
       
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
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

<div class="modal fade" id="modalPay<?=$user_id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title " id="exampleModalLabel">Payment Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
           <div class="modal-body p-4">
      
            <form id="payDetails" method="post">
              <div class="row">
               
                <div class="col-12 ">
                  <div class="form-group">
                    <label for="firstName">Client ID</label>
                    <?php
                      if ($countPayPalDetails>0) {
                        ?>
                         <input type="text"  class="form-control" data-bv-field="firstName" name="client_id" id="client_id" required placeholder="PayPal Client ID" value="<?=$getPayPalDetails['paypal_client_id']?>" />
                        <?php
                      }else{
                        ?>
                         <input type="text"  class="form-control" data-bv-field="firstName" name="client_id" id="client_id" required placeholder="PayPal Client ID"  />
                        <?php
                      }
                    ?>
                   
                  </div>
                </div>

                <input type="hidden" name="e_company_id" value="<?=$company_id?>" id="e_company_id" />
              <div class="col-12 ">
                 
              

                  </div>
                    
                       <div class="row">
                        <div class="col-12 ">
                                <button  id="addUserBtn" class="btn btn-warning btn-block mt-2" type="submit">Save</button>
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
     <script src="../vendors/alertifyjs/alertify.min.js"></script>
         <script src="js/js.js"></script>
         <script src="../js/js.js"></script>
</body>

<script type="text/javascript">
  
  (function($) {
  'use strict';
  $(function() {

    if ($('#cash-deposits-chart').length) {
      var cashDepositsCanvas = $("#cash-deposits-chart").get(0).getContext("2d");
      var data = {
        labels: <?=$labels?>,
        datasets: [
          {
            label: 'Returns',
            data: <?=$datapointsReturned?>,
            borderColor: [
              '#ff4747'
            ],
            borderWidth: 2,
            fill: false,
            pointBackgroundColor: "#fff"
          },
          {
            label: 'Delivered',
            data: <?=$datapointsDelivered?>,
            borderColor: [
              '#4d83ff'
            ],
            borderWidth: 2,
            fill: false,
            pointBackgroundColor: "#fff"
          }
        ]
      };
      var options = {
        scales: {
          yAxes: [{
            display: true,
            gridLines: {
              drawBorder: false,
              lineWidth: 1,
              color: "#e9e9e9",
              zeroLineColor: "#e9e9e9",
            },
            ticks: {
              min: 0,
              max: 100,
              stepSize: 20,
              fontColor: "#6c7383",
              fontSize: 16,
              fontStyle: 300,
              padding: 15
            }
          }],
          xAxes: [{
            display: true,
            gridLines: {
              drawBorder: false,
              lineWidth: 1,
              color: "#e9e9e9",
            },
            ticks : {
              fontColor: "#6c7383",
              fontSize: 16,
              fontStyle: 300,
              padding: 15
            }
          }]
        },
        legend: {
          display: false
        },
        legendCallback: function(chart) {
          var text = [];
          text.push('<ul class="dashboard-chart-legend">');
          for(var i=0; i < chart.data.datasets.length; i++) {
            text.push('<li><span style="background-color: ' + chart.data.datasets[i].borderColor[0] + ' "></span>');
            if (chart.data.datasets[i].label) {
              text.push(chart.data.datasets[i].label);
            }
          }
          text.push('</ul>');
          return text.join("");
        },
        elements: {
          point: {
            radius: 3
          },
          line :{
            tension: 0
          }
        },
        stepsize: 1,
        layout : {
          padding : {
            top: 0,
            bottom : -10,
            left : -10,
            right: 0
          }
        }
      };
      var cashDeposits = new Chart(cashDepositsCanvas, {
        type: 'line',
        data: data,
        options: options
      });
      document.getElementById('cash-deposits-chart-legend').innerHTML = cashDeposits.generateLegend();
    }

    if ($('#total-sales-chart').length) {
      var totalSalesChartCanvas = $("#total-sales-chart").get(0).getContext("2d");

      var data = {
        labels: ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9",'10', '11','12', '13', '14', '15','16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26','27','28','29', '30','31', '32', '33', '34', '35', '36', '37','38', '39', '40'],
        datasets: [
          {
            data: [42, 42, 30, 30, 18, 22, 16, 21, 22, 22, 22, 20, 24, 20, 18, 22, 30, 34 ,32, 33, 33, 24, 32, 34 , 30, 34, 19 ,34, 18, 10, 22, 24, 20, 22, 20, 21, 10, 10, 5, 9, 14 ],
            borderColor: [
              'transparent'
            ],
            borderWidth: 2,
            fill: true,
          },
          {
            data: [35, 28, 32, 42, 44, 46, 42, 50, 48, 30, 35, 48, 42, 40, 54, 58, 56, 55, 59, 58, 57, 60, 66, 54, 38, 40, 42, 44, 42, 43, 42, 38, 43, 41, 43, 50, 58 ,58, 68, 72, 72 ],
            borderColor: [
              'transparent'
            ],
            borderWidth: 2,
            fill: true,
          },
          {
            data: [98, 88, 92, 90, 98, 98, 90, 92, 78, 88, 84, 76, 80, 72, 74, 74, 88, 80, 72, 62, 62, 72, 72, 78, 78, 72, 75, 78, 68, 68, 60, 68, 70, 75, 70, 80, 82, 78, 78, 84, 82 ],
            borderColor: [
              'transparent'
            ],
            borderWidth: 2,
            fill: true,
            backgroundColor: "rgba(77,131,255,0.43)"
          }
        ]
      };
      var options = {
        scales: {
          yAxes: [{
            display: false,
            gridLines: {
              drawBorder: false,
              lineWidth: 1,
              color: "#e9e9e9",
              zeroLineColor: "#e9e9e9",
            },
            ticks: {
              display : true,
              min: 0,
              max: 100,
              stepSize: 10,
              fontColor: "#6c7383",
              fontSize: 16,
              fontStyle: 300,
              padding: 15
            }
          }],
          xAxes: [{
            display: false,
            gridLines: {
              drawBorder: false,
              lineWidth: 1,
              color: "#e9e9e9",
            },
            ticks : {
              display: true,
              fontColor: "#6c7383",
              fontSize: 16,
              fontStyle: 300,
              padding: 15
            }
          }]
        },
        legend: {
          display: false
        },
        legendCallback: function(chart) {
          var text = [];
          text.push('<ul class="dashboard-chart-legend mb-0 mt-4">');
          for(var i=0; i < chart.data.datasets.length; i++) {
            text.push('<li><span style="background-color: ' + chart.data.datasets[i].backgroundColor + ' "></span>');
            if (chart.data.datasets[i].label) {
              text.push(chart.data.datasets[i].label);
            }
          }
          text.push('</ul>');
          return text.join("");
        },
        elements: {
          point: {
            radius: 0
          },
          line :{
            tension: 0
          }
        },
        stepsize: 1,
        layout : {
          padding : {
            top: 0,
            bottom : 0,
            left : 0,
            right: 0
          }
        }
      };
      var totalSalesChart = new Chart(totalSalesChartCanvas, {
        type: 'line',
        data: data,
        options: options
      });
      document.getElementById('total-sales-chart-legend').innerHTML = totalSalesChart.generateLegend();
    }

    $('#recent-purchases-listing').DataTable({
      "aLengthMenu": [
        [5, 10, 15, -1],
        [5, 10, 15, "All"]
      ],
      "iDisplayLength": 10,
      "language": {
        search: ""
      },
      searching: false, paging: false, info: false
    });

  });
})(jQuery);

</script>
</html>

