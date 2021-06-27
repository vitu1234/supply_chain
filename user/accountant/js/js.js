function finishOrder(){
   //  $("#finishPaymentForm").on('submit',function(e){
   // var form_data = $(this).serialize();

   var amount = $("#amount").val();
   var supplyID = $("#supplyID").val();
   if (amount != "" && supplyID != "") {
          $.ajax({ //make ajax request to cart_process.php
          url: "process/finishPayment.php",
          type: "POST",
          data: {'amount':amount,'supplyID':supplyID},
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