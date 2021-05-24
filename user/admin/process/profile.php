<?php
   include("../../../connection/Functions.php");
	$operation = new Functions();
  //add user
  if(isset($_POST['fullname']) && isset($_POST['user_role']) && isset($_POST['email']) && isset($_POST['password1']) && isset($_POST['user_company']) && !empty($_POST['fullname']) && !empty($_POST['user_role']) && !empty($_POST['email']) && !empty($_POST['password1']) && !empty($_POST['user_company']) ){
    
    $fullname = addslashes($_POST['fullname']);
    $user_role = addslashes($_POST['user_role']);
    $user_company = addslashes($_POST['user_company']);
    $email = addslashes($_POST['email']);
    $pass = addslashes($_POST['password1']);
    
    //encyrpt password
    $password=password_hash($pass, PASSWORD_DEFAULT);
    
    $table = "users";
    
    
    //check email existance
    if($operation->countAll("SELECT *FROM users WHERE email = '$email'") == 0){
      //check id user role is admin or chef    
  
     
           //save admin user
        $data = [
          'company_id'=>"$user_company",
          'fullname'=>"$fullname",
          'email'=>"$email",
          'password'=>"$password",
          'user_role'=>"$user_role",
        ];
        //print out response
        if($operation->insertData($table,$data)==1){
          echo json_encode(array("code"=>1,"msg"=>"User saved, please wait!"));
        }else{
          echo json_encode(array("code"=>2,"msg"=>"An error occured while saving user!"));
        }
        
      
    } else{
      echo json_encode(array("code"=>2,"msg"=>"User already exists try another email!"));
    } 
    

    //suspend user
  }elseif(isset($_POST['susUser']) && isset($_POST['susStatus']) && !empty($_POST['susUser']) && !empty($_POST['susStatus'])){
    $id = addslashes($_POST['susUser']);
    $status = addslashes($_POST['susStatus']);
    
    $table = "users";
    $data = [
      'account_status'=>"$status"
    ];
    $where = "user_id = '$id'";
    
    //print out response
    if($operation->updateData($table,$data,$where)==1){
      echo json_encode(array("code"=>1,"msg"=>"User suspended, please wait!"));
    }else{
      echo json_encode(array("code"=>2,"msg"=>"An error occured while suspending user!"));
    }
    
    //delete user
  }elseif(isset($_POST['delUser']) && !empty($_POST['delUser'])){
    $id = addslashes($_POST['delUser']);
    
    $table = "users";
    
    $where = "user_id = '$id'";
    
    //print out response
    if($operation->deleteData($table,$where)==1){
      echo json_encode(array("code"=>1,"msg"=>"User deleted, please wait!"));
    }else{
      echo json_encode(array("code"=>2,"msg"=>"An error occured while deleting user!"));
    }
    
    //edit user
  } elseif(isset($_POST['efullname']) && isset($_POST['euser_role']) && isset($_POST['eemail']) && isset($_POST['epassword']) && isset($_POST['euser_company']) && !empty($_POST['efullname']) && !empty($_POST['euser_role']) && !empty($_POST['eemail']) && !empty($_POST['epassword']) && !empty($_POST['euser_company']) ){
    
    $fullname = addslashes($_POST['efullname']);
    $company = addslashes($_POST['euser_company']);
    $role = addslashes($_POST['euser_role']);
    $email = addslashes($_POST['eemail']);
    $pass = addslashes($_POST['epassword']);
    $id = addslashes($_POST['e_user_id']);
    



    $table = "users";
    $password = '';
    $where = "user_id = '$id'";
    
      //getpassword
      $getUser = $operation->retrieveSingle("SELECT *FROM users WHERE user_id = '$id'");
      if($pass == "password"){
        $password = $getUser['password'];
        // print_r($password);die();
      }else{
          //encyrpt password
          $password=password_hash($pass, PASSWORD_DEFAULT);
          // echo $password; die();
      }
      
      

        
          //save user
        $data = [
          'fullname'=>"$fullname",
          'email'=>"$email",
          'password'=>"$password",
          'user_role'=>"$role"
        ];
        //print out response
        if($operation->updateData($table,$data,$where)==1){
          echo json_encode(array("code"=>1,"msg"=>"User saved, please wait!"));
        }else{
          echo json_encode(array("code"=>2,"msg"=>"An error occured while saving user!"));
        }
      
    //suspend user
  }

?>