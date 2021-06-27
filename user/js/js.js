//add user
$("#profilePicForm").on('submit',function(e){
   var form_data = new FormData(this); //Creates new FormData object
    
    var uidProf = $("#uidProf").val();
    
    if(uidProf !== '' ){
          $("#btnPro").html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Saving...');
          
          $.ajax({ //ajax form submit
                url : "../process/profile_pic.php",
                type: "POST",
                data : form_data,
                contentType: false,
                cache: false,
                processData:false
            }).done(function(dataResult){ //fetch server "json" messages when done
               console.log(dataResult);
                $("#btnPro").html('Save');
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