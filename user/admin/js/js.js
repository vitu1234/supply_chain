
//number input only
function isNumberKey(evt){
 var charCode = (evt.which) ? evt.which : event.keyCode
 if (charCode > 31 && (charCode < 48 || charCode > 57))
    return false;

 return true;
}


//add user
$("#addUserForm").on('submit',function(e){
   var form_data = $(this).serialize();
    
    var email = $("#email").val();
    var password = $("#password1").val();
    var password2 = $("#password2").val();
    var fullname = $("#fullname").val();
    var user_company = $("#user_company").val();
    var user_role = $("#user_role").val();
  
    
    if(email !== '' && password !== '' && user_company !== '' && fullname !== '' && user_role !== ''){
      if(password === password2){
          $("#addUserBtn").html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Saving...');
           $.ajax({ //make ajax request to cart_process.php
              url: "process/users.php",
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
                     window.location = "users.php";
                   },800);
                  
                }else if(data.code == 2){
                    alertify.error(data.msg);
                }else{
                  salertify.success("An error occured, try again later!");
                }
       
           });

        }else{
         alertify.error("Passwords do not match!");
          
          
        }
    }else{
     alertify.error("All fields are required!");
    }
  
 
    e.preventDefault();
    e.stopImmediatePropagation();
});

//suspend user 
$("#suspendUserForm").on('submit',function(e){
   var form_data = $(this).serialize();
    
    var id = $("#susUser").val();
    if(id !== '' ){
      
      $("#suspendBtn").html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Suspending...');
      
      $.ajax({ //make ajax request to cart_process.php
          url: "process/users.php",
              type: "POST",
              //dataType:"json", //expect json value from server
              data: form_data
          }).done(function(dataResult){ //on Ajax success
            console.log(dataResult);
            $("#suspendBtn").html('Yes');
            var data = JSON.parse(dataResult);

            document.getElementById("suspendUserForm").reset();//empty the form
            $("#suspendUserModal").modal('toggle');

            if(data.code == 1){
              alertify.success(data.msg);
               setTimeout(function(){
                 window.location = "users.php";
               },800);

            }else if(data.code == 2){
                alertify.error(data.msg);
            }else{
              alertify.error("An error occured, try again later!");
            }
            document.getElementById("suspendUserForm").reset();//empty the form
            $("#suspendUserModal").modal('toggle');
       });

        
      
    }else{
        alertify.error("Refresh page and try again!");
      document.getElementById("suspendUserForm").reset();//empty the form
      $("#suspendUserModal").modal('toggle');
    }
    e.preventDefault();
    e.stopImmediatePropagation();
});

//delete user
$("#deleteUserForm").on('submit',function(e){
   var form_data = $(this).serialize();
    
    var id = $("#delUser").val();
    if(id !== '' ){
      
      $("#delBtn").html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Deleting...');
      
      $.ajax({ //make ajax request to cart_process.php
          url: "process/users.php",
              type: "POST",
              //dataType:"json", //expect json value from server
              data: form_data
          }).done(function(dataResult){ //on Ajax success
            console.log(dataResult);
            $("#delBtn").html('Yes');
            var data = JSON.parse(dataResult);

            document.getElementById("deleteUserForm").reset();//empty the form
            $("#deleteUserModal").modal('toggle');

            if(data.code == 1){
              alertify.success(data.msg);
               setTimeout(function(){
                 window.location = "users.php";
               },800);

            }else if(data.code == 2){
                alertify.error(data.msg);
            }else{
              alertify.error("An error occured, try again!");
            }
            document.getElementById("deleteUserForm").reset();//empty the form
            $("#deleteUserModal").modal('toggle');
       });

        
      
    }else{
        alertify.error("Refresh page &amp; try again!");
      
      document.getElementById("deleteUserForm").reset();//empty the form
            $("#deleteUserModal").modal('toggle');
    }
    e.preventDefault();
    e.stopImmediatePropagation();
});

//edit user
function editUser(id){

    $("#editUserForm"+id).on('submit',function(e){
   var form_data = $(this).serialize();
    
    var email = $("#eemail"+id).val();
    var eUid = id;
    var fullname = $("#efullname"+id).val();
    var password = $("#epassword"+id).val();
      
    if(email !== '' && eUid !== ''  && fullname !== '' && password !== ''){
      
      $("#editUserBtn"+id).html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Saving...');
                $.ajax({ //make ajax request to cart_process.php
              url: "process/users.php",
                  type: "POST",
                  //dataType:"json", //expect json value from server
                  data: form_data
              }).done(function(dataResult){ //on Ajax success

                console.log(dataResult);

                $("#editUserBtn"+id).html('Update User');
                var data = JSON.parse(dataResult);
             
                document.getElementById("editUserForm"+id).reset();//empty the form
                $("#view-user"+id).modal('toggle');
             
                if(data.code == 1){
                   alertify.success(data.msg);
                   setTimeout(function(){
                     window.location = "users.php";
                   },800);
                  
                }else if(data.code == 2){
                     alertify.error(data.msg);
                }else{
                   alertify.error("An error occured, try again later");
                  
                  document.getElementById("editUserForm"+id).reset();//empty the form
                $("#view-user"+id).modal('toggle');
                }
                 
             
           });
     
    }else{
     
       alertify.error("all fields are required");
      
      document.getElementById("editUserForm"+id).reset();//empty the form
      $("#view-user"+id).modal('toggle');
      
    }
      
             
    
    e.preventDefault();
    e.stopImmediatePropagation();
});

}
  
function editUserProfile(id){

    $("#editUserProfileForm"+id).on('submit',function(e){
   var form_data = $(this).serialize();
    
    var email = $("#eemail"+id).val();
    var eUid = id;
    var fullname = $("#efullname"+id).val();
    var password = $("#epassword"+id).val();
      
    if(email !== '' && eUid !== ''  && fullname !== '' && password !== ''){
      
      $("#editUserBtn"+id).html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Saving...');
                $.ajax({ //make ajax request to cart_process.php
              url: "process/profile.php",
                  type: "POST",
                  //dataType:"json", //expect json value from server
                  data: form_data
              }).done(function(dataResult){ //on Ajax success

                console.log(dataResult);

                $("#editUserBtn"+id).html('Update User');
                var data = JSON.parse(dataResult);
             
                document.getElementById("editUserProfileForm"+id).reset();//empty the form
                $("#view-user"+id).modal('toggle');
             
                if(data.code == 1){
                   alertify.success(data.msg);
                   setTimeout(function(){
                     location.reload();
                   },800);
                  
                }else if(data.code == 2){
                     alertify.error(data.msg);
                }else{
                   alertify.error("An error occured, try again later");
                  
                  document.getElementById("editUserProfileForm"+id).reset();//empty the form
                $("#view-user"+id).modal('toggle');
                }
                 
             
           });
     
    }else{
     
       alertify.error("all fields are required");
      
      document.getElementById("editUserProfileForm"+id).reset();//empty the form
      $("#view-user"+id).modal('toggle');
      
    }
      
             
    
    e.preventDefault();
    e.stopImmediatePropagation();
});

}


//add company
$("#addCompanyForm").on('submit',function(e){
   var form_data = $(this).serialize();
   alert(":ah")
    
    var company_name = $("#company_name").val();
    var company_email = $("#company_email").val();
    var company_phone = $("#company_phone").val();
    var company_address = $("#company_address").val();
    var user_company = $("#user_company").val();
  
    
    if(company_name !== '' && company_email !== '' && company_phone !== '' && company_address !== ''){
          $("#addUserBtn").html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Saving...');
           $.ajax({ //make ajax request to cart_process.php
              url: "process/companies.php",
                  type: "POST",
                  //dataType:"json", //expect json value from server
                  data: form_data
              }).done(function(dataResult){ //on Ajax success
                console.log(dataResult);
                $("#addUserBtn").html('Add Company');
                var data = JSON.parse(dataResult);
                
                if(data.code == 1){
                   alertify.success(data.msg);
                   setTimeout(function(){
                     window.location = "companies.php";
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

//delete company
$("#deleteCompanyForm").on('submit',function(e){
   var form_data = $(this).serialize();
    
    var id = $("#delCompany").val();
    if(id !== '' ){
      
      $("#delBtn").html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Deleting...');
      
      $.ajax({ //make ajax request to cart_process.php
          url: "process/companies.php",
              type: "POST",
              //dataType:"json", //expect json value from server
              data: form_data
          }).done(function(dataResult){ //on Ajax success
            console.log(dataResult);
            $("#delBtn").html('Yes');
            var data = JSON.parse(dataResult);

            document.getElementById("deleteCompanyForm").reset();//empty the form
            $("#deleteUserModal").modal('toggle');

            if(data.code == 1){
              alertify.success(data.msg);
               setTimeout(function(){
                 window.location = "companies.php";
               },800);

            }else if(data.code == 2){
                alertify.error(data.msg);
            }else{
              alertify.error("An error occured, try again!");
            }
            document.getElementById("deleteCompanyForm").reset();//empty the form
            $("#deleteUserModal").modal('toggle');
       });

        
      
    }else{
        alertify.error("Refresh page &amp; try again!");
      
      document.getElementById("deleteCompanyForm").reset();//empty the form
            $("#deleteUserModal").modal('toggle');
    }
    e.preventDefault();
    e.stopImmediatePropagation();
});

//edit company
function editCompany(id){

    $("#editCompanyForm"+id).on('submit',function(e){
   var form_data = $(this).serialize();
    
    var eUid = id;
    var company_name = $("#ecompany_name"+id).val();
    var company_email = $("#ecompany_email"+id).val();
    var company_phone = $("#ecompany_phone"+id).val();
    var company_address = $("#ecompany_address"+id).val();
    var user_company = $("#euser_company"+id).val();
  
    
    if(company_name !== '' && company_email !== '' && company_phone !== '' && company_address !== ''){
      
      $("#editUserBtn"+id).html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Saving...');
        $.ajax({ //make ajax request to cart_process.php
            url: "process/companies.php",
                type: "POST",
                //dataType:"json", //expect json value from server
                data: form_data
            }).done(function(dataResult){ //on Ajax success

              console.log(dataResult);

              $("#editUserBtn"+id).html('Update Company');
              var data = JSON.parse(dataResult);
           
              document.getElementById("editCompanyForm"+id).reset();//empty the form
              $("#view-user"+id).modal('toggle');
           
              if(data.code == 1){
                 alertify.success(data.msg);
                 setTimeout(function(){
                   window.location = "companies.php";
                 },800);
                
              }else if(data.code == 2){
                   alertify.error(data.msg);
              }else{
                 alertify.error("An error occured, try again later");
                
                document.getElementById("editCompanyForm"+id).reset();//empty the form
              $("#view-user"+id).modal('toggle');
              }
                 
             
           });
     
    }else{
     
       alertify.error("all fields are required");
      
      document.getElementById("editCompanyForm"+id).reset();//empty the form
      $("#view-user"+id).modal('toggle');
      
    }
      
             
    
    e.preventDefault();
    e.stopImmediatePropagation();
});

}

 

   






