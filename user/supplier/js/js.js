
//number input only
function isNumberKey(evt){
 var charCode = (evt.which) ? evt.which : event.keyCode
 if (charCode > 31 && (charCode < 48 || charCode > 57))
    return false;

 return true;
}


//add category
$("#addcategoryForm").on('submit',function(e){
   var form_data = $(this).serialize();
    var category_name = $("#category_name").val();
    var user_id = $("#user_id").val();
      
    if(category_name !== '' && user_id !== ''){
          $("#addUserBtn").html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Saving...');
           $.ajax({ //make ajax request to cart_process.php
              url: "process/categories.php",
                  type: "POST",
                  //dataType:"json", //expect json value from server
                  data: form_data
              }).done(function(dataResult){ //on Ajax success
                console.log(dataResult);
                $("#addUserBtn").html('Save');
                var data = JSON.parse(dataResult);
                
                if(data.code == 1){
                   alertify.success(data.msg);
                   setTimeout(function(){
                     window.location = "categories.php";
                   },800);
                  
                }else if(data.code == 2){
                    alertify.error(data.msg);
                }else{
                  salertify.success("An error occured, try again later!");
                }
       
           });

        
    }else{
     alertify.error("All fields are required!");
    }
  
 
    e.preventDefault();
    e.stopImmediatePropagation();
});

//delete category
$("#deleteCategoryForm").on('submit',function(e){
   var form_data = $(this).serialize();
    
    var id = $("#delCategory").val();
    if(id !== '' ){
      
      $("#delBtn").html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Deleting...');
      
      $.ajax({ //make ajax request to cart_process.php
          url: "process/categories.php",
              type: "POST",
              //dataType:"json", //expect json value from server
              data: form_data
          }).done(function(dataResult){ //on Ajax success
            console.log(dataResult);
            $("#delBtn").html('Yes');
            var data = JSON.parse(dataResult);

            document.getElementById("deleteCategoryForm").reset();//empty the form
            $("#deleteUserModal").modal('toggle');

            if(data.code == 1){
              alertify.success(data.msg);
               setTimeout(function(){
                 window.location = "categories.php";
               },800);

            }else if(data.code == 2){
                alertify.error(data.msg);
            }else{
              alertify.error("An error occured, try again!");
            }
            document.getElementById("deleteCategoryForm").reset();//empty the form
            $("#deleteUserModal").modal('toggle');
       });

        
      
    }else{
        alertify.error("Refresh page &amp; try again!");
      
      document.getElementById("deleteCategoryForm").reset();//empty the form
            $("#deleteUserModal").modal('toggle');
    }
    e.preventDefault();
    e.stopImmediatePropagation();
});

//update category
function updateCategory(id){
  $("#editcategoryForm"+id).on('submit',function(e){
    var form_data = $(this).serialize();

    var category_name = $("#ecategory_name"+id).val();
    var user_id = $("#e_user_id"+id).val();
        
      if(category_name !== '' && user_id !== ''){
            $("#addUserBtn"+id).html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Saving...');
             $.ajax({ //make ajax request to cart_process.php
                url: "process/categories.php",
                    type: "POST",
                    //dataType:"json", //expect json value from server
                    data: form_data
                }).done(function(dataResult){ //on Ajax success
                  console.log(dataResult);
                  $("#addUserBtn"+id).html('Save');
                  var data = JSON.parse(dataResult);
                  
                  if(data.code == 1){
                     alertify.success(data.msg);
                     setTimeout(function(){
                       window.location = "categories.php";
                     },800);
                    
                  }else if(data.code == 2){
                      alertify.error(data.msg);
                  }else{
                    alertify.success("An error occured, try again later!");
                  }
         
             });

          
      }else{
       alertify.error("All fields are required!");
      }
    
   
      e.preventDefault();
      e.stopImmediatePropagation();
  });
}

//add item
$("#addItemForm").on('submit',function(e){
   var form_data = $(this).serialize();
    var item_name = $("#item_name").val();
    var item_price = $("#item_price").val();
    var category_name = $("#category_name").val();
    var total_products = $("#total_products").val();
    var user_id = $("#user_id").val();
      
    if(category_name !== '' && user_id !== '' && item_name !=='' && total_products !==''){
          $("#addUserBtn").html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Saving...');
           $.ajax({ //make ajax request to cart_process.php
              url: "process/items.php",
              type: "POST",
              data: new FormData(this),
              contentType: false,
              cache: false,
              processData: false,
              success:function(dataResult){ //on Ajax success
                console.log(dataResult);
                $("#addUserBtn").html('Save');
                var data = JSON.parse(dataResult);
                
                if(data.code == 1){
                   alertify.success(data.msg);
                   setTimeout(function(){
                     window.location = "items.php";
                   },800);
                  
                }else if(data.code == 2){
                    alertify.error(data.msg);
                }else{
                  salertify.success("An error occured, try again later!");
                }
       
           }
         })

        
    }else{
     alertify.error("All fields are required!");
    }
  
 
    e.preventDefault();
    e.stopImmediatePropagation();
});

//edit item
function editItem(id){
  $("#editItemForm"+id).on('submit',function(e){
   var form_data = $(this).serialize();
    var item_name = $("#eitem_name"+id).val();
    var item_price = $("#eitem_price"+id).val();
    var category_name = $("#ecategory_name"+id).val();
    var total_products = $("#etotal_products"+id).val();
    var user_id = $("#euser_id"+id).val();
      
    if(category_name !== '' && user_id !== '' && item_name !=='' && total_products !==''){
          $("#addUserBtn"+id).html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Saving...');
           $.ajax({ //make ajax request to cart_process.php
              url: "process/items.php",
              type: "POST",
              data: new FormData(this),
              contentType: false,
              cache: false,
              processData: false,
              success:function(dataResult){ //on Ajax success
                console.log(dataResult);
                $("#addUserBtn"+id).html('Save');
                var data = JSON.parse(dataResult);
                
                if(data.code == 1){
                   alertify.success(data.msg);
                   setTimeout(function(){
                     window.location = "items.php";
                   },800);
                  
                }else if(data.code == 2){
                    alertify.error(data.msg);
                }else{
                  salertify.success("An error occured, try again later!");
                }
       
           }
         })

        
    }else{
     alertify.error("All fields are required!");
    }
  
 
    e.preventDefault();
    e.stopImmediatePropagation();
});
}

function editFile(id){
  $("#editPictureForm"+id).on('submit',function(e){
   var form_data = $(this).serialize();

    var user_id = $("#euser_id"+id).val();
      
    if(user_id !== ''){
          $("#addUserBtn"+id).html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Saving...');
           $.ajax({ //make ajax request to cart_process.php
              url: "process/items.php",
              type: "POST",
              data: new FormData(this),
              contentType: false,
              cache: false,
              processData: false,
              success:function(dataResult){ //on Ajax success
                console.log(dataResult);
                $("#addUserBtn"+id).html('Save');
                var data = JSON.parse(dataResult);
                
                if(data.code == 1){
                   alertify.success(data.msg);
                   setTimeout(function(){
                     window.location = "items.php";
                   },800);
                  
                }else if(data.code == 2){
                    alertify.error(data.msg);
                }else{
                  salertify.success("An error occured, try again later!");
                }
       
           }
         })

        
    }else{
     alertify.error("All fields are required!");
    }
  
 
    e.preventDefault();
    e.stopImmediatePropagation();
});
}

//delete item
$("#deleteItemForm").on('submit',function(e){
   var form_data = $(this).serialize();
    
    var id = $("#delItem").val();
    if(id !== '' ){
      
      $("#delBtn").html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Deleting...');
      
      $.ajax({ //make ajax request to cart_process.php
          url: "process/items.php",
              type: "POST",
              //dataType:"json", //expect json value from server
              data: form_data
          }).done(function(dataResult){ //on Ajax success
            console.log(dataResult);
            $("#delBtn").html('Yes');
            var data = JSON.parse(dataResult);

            document.getElementById("deleteItemForm").reset();//empty the form
            $("#deleteUserModal").modal('toggle');

            if(data.code == 1){
              alertify.success(data.msg);
               setTimeout(function(){
                 window.location = "items.php";
               },800);

            }else if(data.code == 2){
                alertify.error(data.msg);
            }else{
              alertify.error("An error occured, try again!");
            }
            document.getElementById("deleteItemForm").reset();//empty the form
            $("#deleteUserModal").modal('toggle');
       });

        
      
    }else{
        alertify.error("Refresh page &amp; try again!");
      
      document.getElementById("deleteItemForm").reset();//empty the form
            $("#deleteUserModal").modal('toggle');
    }
    e.preventDefault();
    e.stopImmediatePropagation();
});


//add paypal tokens
$("#payDetails").on('submit',function(e){
   var form_data = $(this).serialize();
    var e_company_id = $("#e_company_id").val();
    var client_id = $("#client_id").val();
      
    if(e_company_id !== '' && client_id !== ''){
          $("#addUserBtn").html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Saving...');
           $.ajax({ //make ajax request to cart_process.php
              url: "process/paypal_client_id.php",
                  type: "POST",
                  //dataType:"json", //expect json value from server
                  data: form_data
              }).done(function(dataResult){ //on Ajax success
                console.log(dataResult);
                $("#addUserBtn").html('Save');
                var data = JSON.parse(dataResult);
                
                if(data.code == 1){
                   alertify.success(data.msg);
                   setTimeout(function(){
                     location.reload();
                   },800);
                  
                }else if(data.code == 2){
                    alertify.error(data.msg);
                }else{
                  alertify.success("An error occured, try again later!");
                }
       
           });

        
    }else{
     alertify.error("All fields are required!");
    }
  
 
    e.preventDefault();
    e.stopImmediatePropagation();
});


function finishSubscription(){
   //  $("#finishPaymentForm").on('submit',function(e){
   // var form_data = $(this).serialize();

   var plan_period = $("#plan_period").val();
   var plan_company = $("#plan_company").val();
   if (plan_period != "" && plan_company != "") {
          $.ajax({ //make ajax request to cart_process.php
          url: "process/finish_subscription.php",
          type: "POST",
          data: {'plan_company':plan_company,'plan_period':plan_period},
          // contentType: false,
          // cache: false,
          // processData: false,
          success:function(dataResult){ //on Ajax success
            console.log(dataResult)
            var data = JSON.parse(dataResult);
            
            if(data.code == 1){
               alertify.success(data.msg);
               setTimeout(function(){
                 location.reload();
               },800);
              
            }else if(data.code == 2){
                alertify.error(data.msg);
            }else{
              alertify.success("An error occured, try again later!");
            }
   
       }
     })
   }else{
         alertify.success("Empty dataset, try again later!");
   }

//     e.preventDefault();
//     e.stopImmediatePropagation();
// });
}