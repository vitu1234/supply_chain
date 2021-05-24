
//number input only
function isNumberKey(evt){
 var charCode = (evt.which) ? evt.which : event.keyCode
 if (charCode > 31 && (charCode < 48 || charCode > 57))
    return false;

 return true;
}





function AddQuotation(id){
  $("#addQuotationForm"+id).on('submit',function(e){
   var form_data = $(this).serialize();

    var user_id = $("#quotation_item_id"+id).val();
      
    if(user_id !== ''){
          $("#addUserBtn"+id).html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Saving...');
           $.ajax({ //make ajax request to cart_process.php
              url: "process/quotation_invoice.php",
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
                     location.reload();
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

function AddInvoice(id){
  $("#addInvoiceForm"+id).on('submit',function(e){
   var form_data = $(this).serialize();

    var user_id = $("#invoice_item_id"+id).val();
      
    if(user_id !== ''){
          $("#addUserBtnInvo"+id).html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Saving...');
           $.ajax({ //make ajax request to cart_process.php
              url: "process/quotation_invoice.php",
              type: "POST",
              data: new FormData(this),
              contentType: false,
              cache: false,
              processData: false,
              success:function(dataResult){ //on Ajax success
                console.log(dataResult);
                $("#addUserBtnInvo"+id).html('Save');
                var data = JSON.parse(dataResult);
                
                if(data.code == 1){
                   alertify.success(data.msg);
                   setTimeout(function(){
                     location.reload();
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

