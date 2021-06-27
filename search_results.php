<?php
  include("connection/Functions.php");
  $operation = new Functions();

  if (!isset($_GET['query']) || empty($_GET['query'])) {
    header("Location:index.html");
  }


  $per_page_record = 10;  // Number of entries to show in a page.   
        // Look for a GET variable page if not found default is 1.        
        if (isset($_GET["page"])) {    
            $page  = $_GET["page"];    
        }    
        else {    
          $page=1;    
        }    
    
        $start_from = ($page-1) * $per_page_record;   

  $query_string = addslashes($_GET['query']);
  $sql = "SELECT * FROM `items` 
    INNER JOIN item_categories ON items.category_id = item_categories.category_id
    INNER JOIN companies ON items.company_id = companies.company_id
    WHERE items.item_name LIKE '%$query_string%' OR items.price LIKE '%$query_string%' OR 
    item_categories.category_name LIKE '%$query_string%' LIMIT $start_from, $per_page_record
  ";

  $countResults = $operation->countAll($sql);
  $getResults = $operation->retrieveMany($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com    @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>Search Results - Henz Supplies</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://netdna.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/search_results.css" rel="stylesheet">
    <link rel="stylesheet" href="user/vendors/alertifyjs/css/alertify.min.css" />
<!-- include a theme -->
<link rel="stylesheet" href="user/vendors/alertifyjs/css/themes/default.min.css" />
<style>   
    table {  
        border-collapse: collapse;  
    }  
        .inline{   
            display: inline-block;   
            float: right;   
            margin: 20px 0px;   
        }   
         
        input, button{   
            height: 34px;   
        }   
  
    .pagination {   
        display: inline-block;   
    }   
    .pagination a {   
        font-weight:bold;   
        font-size:18px;   
        color: black;   
        float: left;   
        padding: 8px 16px;   
        text-decoration: none;   
        border:1px solid black;   
    }   
    .pagination a.active {   
            background-color: pink;   
    }   
    .pagination a:hover:not(.active) {   
        background-color: skyblue;   
    }   
        </style>   

</head>
<body>
<div class="container">
<div class="row">
    <div class="col-lg-12 card-margin">
      <div class="row">
      <div class="col-lg-5 col-md-3 col-sm-12 p-0"></div>
      <div class="col-lg-4 col-md-3 col-sm-12 p-0">
        <a href="index.html"> <img width="50%" style="margin-right: 5px;" class="image-responsive mt-3 mb-3 text-center" src="user/images/logo.svg" alt="logo"/>
        </a>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-5 col-md-4 "></div>
      <div class="col-lg-4 col-md-4 ">
        

          <!-- <a class="" style="text-align:center;;margin-bottom:30px;" href="user/">Sign In</a>  <a class="" style="text-align:center;margin:20px;margin-bottom:30px;" href="user/">Sign Up </a> -->
      </div>

    </div>
        <div class="card search-form">
            <div class="card-body p-0">
                <form id="search-form" method="get">
                    <div class="row">
                        <div class="col-12">
                            <div class="row no-gutters">
                                
                                <div class="col-lg-10 col-md-6 col-sm-12 p-0">
                                    <input required value="<?=$query_string?>" type="text" placeholder="Search..." class="form-control" id="query" name="query">
                                </div>
                            
                                <div class="col-lg-2 col-md-3 col-sm-12 p-0">
                                    <button type="submit" class="btn btn-base">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<div class="row">
        <div class="col-12">
            <div class="card card-margin">
                <div class="card-body">
                    <div class="row search-body">
                        <div class="col-lg-12">
                            <div class="search-result">
                                <div class="result-header">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="records">Retrieved: <?=$countResults?> records</div>
                                        </div>
                               
                                    </div>
                                </div>
                                <div class="result-body">
                                    <div class="table-responsive">
                                      <?php
                                        if ($countResults > 0) {
                                            ?>
                                            <table class="table widget-26">
                                              <tbody>
                                            <?php
                                            foreach ($getResults as $row) {
                                              ?>
                                              <tr>
                                                    <td>
                                                        <div class="widget-26-job-emp-img">
                                                              <?php
                                        if ($row['img_url'] != '') {
                                            echo '<img class="img-responsive mb-4 mx-4" src="user/supplier/items/'.$row['img_url'].'" height="300px" width="300px"/>';
                                        }else{
                                            echo '<img class="img-responsive mb-4 mx-4" width="311px" height="315px" src="user/images/logo.svg" alt="Company" />';
                                        }
                                        ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="widget-26-job-title">
                                                            <a href="#"><?=$row['item_name']?></a>
                                                            <p class="m-0"><a href="#" class="employer-name"><?=$row['category_name']?></a></p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="widget-26-job-info">
                                                            <!-- <p class="type m-0">Full-Time</p>
                                                            <p class="text-muted m-0">in <span class="location">London, UK</span></p> -->
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="widget-26-job-salary">K <?=number_format($row['price'],2)?>/piece</div>
                                                    </td>
                                                    <td>
                                                        <div class="widget-26-job-category bg-soft-base">
                                                            <i class="indicator bg-base"></i>
                                                            <span><?=$row['company_name']?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="widget-26-job-starred">
                                                             <a href="#view-item<?=$row['item_id']?>" data-toggle="modal" class="btn btn-default"><i class="mdi mdi-eye"></i> View</a> 
                                                        </div>
                                                    </td>
                                                </tr>
                         <div id="view-item<?=$row['item_id']?>" class="modal fade " role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title font-weight-400">VIEW ITEM</h5>
                                      <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                    <div class="modal-body p-4">

                                      <div id="firstForm<?=$row['item_id']?>">
                                        <div class="row">
                                        <?php
                                        if ($row['img_url'] != '') {
                                            echo '<img class="img-responsive mb-4 mx-4" src="user/supplier/items/'.$row['img_url'].'" height="200px" width="200px"/>';
                                        }else{
                                            echo '<img class="img-responsive mb-4 mx-4" width="311px" height="315px" src="user/images/logo.svg" alt="Company" />';
                                        }
                                        ?>
                                          <div class="col-12">
                                            <div class="form-group">
                                              <p class="" data-bv-field="firstName"><?=$row['item_name']?> in <?=$row['category_name']?></p>
                                            </div>
                                          </div>
                                          <div class="col-12 col-sm-6">
                                            <div class="">
                                              <p class="" data-bv-field="fullName" >K <?=number_format($row['price'],2)?>/pcs</p>
                                            </div>
                                          </div>
                                          <div class="col-12 col-sm-6">
                                             <div class="form-group">
                                              <p class="" data-bv-field="fullName" ><?=$row['quantity_total']?> pieces remaining</p>
                                            </div>
                                          </div>    
                                         <div class="col-12 col-sm-6">
                                             <div class="form-group">
                                              <label>How Many do you want?</label>
                                              <input required onkeypress="return isNumberKey(event)" min="1" type="text"  class="form-control" data-bv-field="fullName" id="etotal_products<?=$row['item_id']?>" name="etotal_products" required placeholder="Total Number of Products" value="1"/>
                                            </div>
                                          </div>


                                         </div>

                                        <div class="row">
                                        <div class="col-12 ">
                                           <button onclick="getItem('<?=$row['item_id']?>')" id="addUserBtn<?=$row['item_id']?>" class="btn btn-primary btn-block mt-2" type="button">Next </button>

                                        </div>  
                                         
                                        </div>
                                      </div>

                                      <div style="display: none;" id="secondForm<?=$row['item_id']?>">
                                        <p class="text-center">Please sign in as secretary to continue!</p>
                                          <form class="pt-3" method="post" id="loginForm<?=$row['item_id']?>">
                                            <div class="form-group">
                                              <input type="email" class="form-control form-control-lg" id="email<?=$row['item_id']?>" name="email" required="" placeholder="Email">
                                            </div>
                                            <div class="form-group">
                                              <input type="password" class="form-control form-control-lg" id="password<?=$row['item_id']?>" placeholder="Password" name="password" required="">
                                            </div>
                                            <div class="mt-3">
                                              <button onclick="loginOrder('<?=$row['item_id']?>')" id="loginBtn<?=$row['item_id']?>" type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" >SIGN IN</button>
                                            </div>
                                            <div class="my-2 d-flex justify-content-between align-items-center">
                                              <div class="form-check">
                                               
                                              </div>
                                              
                                            </div>
                                          
                                           
                                          </form>
                                      </div>

                                      <div style="display: none;" id="thirdForm<?=$row['item_id']?>">
                                        <p class="text-center">Please finish below to inquire!</p>
                                          <form class="pt-3" method="post" id="finishForm<?=$row['item_id']?>">
                                            <div class="form-group">

                                              <input type="hidden" class="form-control form-control-lg" id="uid<?=$row['item_id']?>" name="uid" required="" >

                                              <input type="hidden" class="form-control form-control-lg" id="qty<?=$row['item_id']?>" name="qty" required="" >
                                            </div>

                                            <input type="hidden" class="form-control form-control-lg" id="item_id<?=$row['item_id']?>" name="item_id" value="<?=$row['item_id']?>" required="" >
                                           
                                            <div class="mt-3">
                                              <button onclick="finishOrder('<?=$row['item_id']?>')" id="finishBtn<?=$row['item_id']?>" type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" >FINISH</button>
                                            </div>
                                          </form>
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
                                          echo '<p class="text-center alert aler-warning">No records match your query!</p>';
                                        }
                                      ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <nav class="d-flex justify-content-center">
                            <div class="pagination">
                        <?php  
                            $query = "SELECT * FROM `items` 
    INNER JOIN item_categories ON items.category_id = item_categories.category_id
    INNER JOIN companies ON items.company_id = companies.company_id
    WHERE items.item_name LIKE '%$query_string%' OR items.price LIKE '%$query_string%' OR 
    item_categories.category_name LIKE '%$query_string%'";  

                            $rs_result = $operation->retrieveMany($query);     
                            $row = $operation->retrieveSingle($query);     
                            $total_records = $operation->countAll($query);     

                               // Number of pages required.   
        $total_pages = ceil($total_records / $per_page_record);     
        $pagLink = "";       
      
        if($page>=2){   
            echo "<a href='search_results.php?query=".$query_string."&&page=".($page-1)."'>  Prev </a>";   
        }       
                   
        for ($i=1; $i<=$total_pages; $i++) {   
          if ($i == $page) {   
              $pagLink .= "<a class = 'active' href='search_results.php?query=".$query_string."&&page="  
                                                .$i."'>".$i." </a>";   
          }               
          else  {   
              $pagLink .= "<a href='search_results.php?query=".$query_string."&&page=".$i."'>   
                                                ".$i." </a>";     
          }   
        };     
        echo $pagLink;   
  
        if($page<$total_pages){   
            echo "<a href='search_results.php?query=".$query_string."&&page=".($page+1)."'>  Next </a>";   
        }   
  
                        ?>

                            </div>


                       <!--  <ul class="pagination pagination-base pagination-boxed pagination-square mb-0">
                            <li class="page-item">
                                <a class="page-link no-border" href="#">
                                    <span aria-hidden="true">«</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link no-border" href="#">1</a></li>
                            <li class="page-item"><a class="page-link no-border" href="#">2</a></li>
                            <li class="page-item"><a class="page-link no-border" href="#">3</a></li>
                            <li class="page-item"><a class="page-link no-border" href="#">4</a></li>
                            <li class="page-item">
                                <a class="page-link no-border" href="#">
                                    <span aria-hidden="true">»</span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                        </ul> -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="http://netdna.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <script src="user/vendors/alertifyjs/alertify.min.js"></script>
<script type="text/javascript">
  
//number input only
function isNumberKey(evt){
 var charCode = (evt.which) ? evt.which : event.keyCode
 if (charCode > 31 && (charCode < 48 || charCode > 57))
    return false;

 return true;
}

function getItem(id){
  if ($("#etotal_products"+id).val() !== '') {
    $("#firstForm"+id).hide(500);
    $("#secondForm"+id).show(800);
    $("#etotal_products"+id).removeClass("error");  
    $("#qty"+id).val($("#etotal_products"+id).val());
  }else{
    $("#etotal_products"+id).addClass("error");  
  }
 

}
function loginOrder(id){
  $("#loginForm"+id).on('submit',function(e){
     var form_data = $(this).serialize();
      
      var email = $("#email"+id).val();
      var password = $("#password"+id).val();

    
      
      if(email !== '' && password !== '' ){
            $("#loginBtn"+id).html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> SIGN IN...');
             $.ajax({ //make ajax request to cart_process.php
                url: "process/login.php",
                    type: "POST",
                    //dataType:"json", //expect json value from server
                    data: form_data
                }).done(function(dataResult){ //on Ajax success
                  console.log(dataResult);
                  $("#loginBtn"+id).html('SIGN IN');
                  var data = JSON.parse(dataResult);
               
                  document.getElementById("loginForm"+id).reset();//empty the form             
                  if(data.code == 1){
                    alertify.success(data.msg);
                     setTimeout(function(){
                      $("#secondForm"+id).hide(500);
                        $("#thirdForm"+id).show(800);
                        $("#uid"+id).val(data.uid)
                     },800);
                    
                  }else if(data.code == 2){
                      alertify.error(data.msg);
                  }else{
                    alertify.error('An error occured, try again');
                  }
                  document.getElementById("loginForm"+id).reset();//empty the form
             });

          
            
            
          }
      else{
        alertify.alert('All fields are required!');
      }
    
   
      e.preventDefault();
      e.stopImmediatePropagation();
    });
}

function finishOrder(id){
    $("#finishForm"+id).on('submit',function(e){
     var form_data = $(this).serialize();
      
      var uid = $("#uid"+id).val();
      var qty = $("#qty"+id).val();
      var item_id = $("#item_id"+id).val();
    
      
      if(uid !== '' && qty !== '' && item_id !== ''){
            $("#finishBtn"+id).html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> SIGN IN...');
             $.ajax({ //make ajax request to cart_process.php
                url: "process/login.php",
                    type: "POST",
                    //dataType:"json", //expect json value from server
                    data: form_data
                }).done(function(dataResult){ //on Ajax success
                  console.log(dataResult);
                  $("#finishBtn"+id).html('FINISH');
                  var data = JSON.parse(dataResult);
               
                  document.getElementById("finishForm"+id).reset();//empty the form             
                  if(data.code == 1){
                    alertify.success(data.msg);
                     setTimeout(function(){
                        $("#firstForm"+id).show(500);
                        $("#secondForm"+id).hide(500);
                        $("#thirdForm"+id).hide(500);
                        $("#finishForm"+id).hide(500);

                        $("#view-item"+id).modal('toggle')
                     },800);
                    
                  }else if(data.code == 2){
                      alertify.error(data.msg);
                  }else{
                    alertify.error('An error occured, try again');
                  }
                  document.getElementById("finishForm"+id).reset();//empty the form
             });

          
            
            
          }else{
        alertify.error('All fields are required!');
      }
    
   
      e.preventDefault();
      e.stopImmediatePropagation();
    });
}
</script>
</body>
</html>