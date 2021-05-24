<?php
   include("../../../connection/Functions.php");
	$operation = new Functions();
  //add user
  if(isset($_POST['company_name']) && isset($_POST['company_email']) && isset($_POST['company_phone']) && isset($_POST['company_phone']) && isset($_POST['company_address']) && !empty($_POST['company_name']) && !empty($_POST['company_email']) && !empty($_POST['company_address']) ){
    
    $company_name = addslashes($_POST['company_name']);
    $company_email = addslashes($_POST['company_email']);
    $company_address = addslashes($_POST['company_address']);
    $company_phone = addslashes($_POST['company_phone']);

    $table = "companies";
    
    
    //check email existance
    if($operation->countAll("SELECT *FROM companies WHERE company_email = '$company_email'") == 0){
      //check id user role is admin or chef    
  
     
           //save admin user
        $data = [
          'company_name'=>"$company_name",
          'company_email'=>"$company_email",
          'company_phone'=>"$company_phone",
          'company_address'=>"$company_address",
        ];
        //print out response
        if($operation->insertData($table,$data)==1){
          echo json_encode(array("code"=>1,"msg"=>"Company saved, please wait!"));
        }else{
          echo json_encode(array("code"=>2,"msg"=>"An error occured while saving company!"));
        }
        
      
    } else{
      echo json_encode(array("code"=>2,"msg"=>"Company already exists try another email!"));
    } 
    

    //suspend user
  }elseif(isset($_POST['delCompany']) && !empty($_POST['delCompany'])){
    $id = addslashes($_POST['delCompany']);
    
    $table = "companies";
    
    $where = "company_id = '$id'";
    
    //print out response
    if($operation->deleteData($table,$where)==1){
      echo json_encode(array("code"=>1,"msg"=>"Company deleted, please wait!"));
    }else{
      echo json_encode(array("code"=>2,"msg"=>"An error occured while deleting company!"));
    }
    
    //edit user
  } elseif(isset($_POST['ecompany_name']) && isset($_POST['ecompany_email']) && isset($_POST['ecompany_phone']) && isset($_POST['ecompany_phone']) && isset($_POST['ecompany_address']) && !empty($_POST['ecompany_name']) && !empty($_POST['ecompany_email']) && !empty($_POST['ecompany_address']) ){
    
    $company_name = addslashes($_POST['ecompany_name']);
    $company_email = addslashes($_POST['ecompany_email']);
    $company_address = addslashes($_POST['ecompany_address']);
    $company_phone = addslashes($_POST['ecompany_phone']);
    $id = addslashes($_POST['e_company_id']);


    $table = "companies";
    $where = "company_id = '$id'";
    
    $data = [
          'company_name'=>"$company_name",
          'company_email'=>"$company_email",
          'company_phone'=>"$company_phone",
          'company_address'=>"$company_address",
        ];
    //print out response
    if($operation->updateData($table,$data,$where)==1){
      echo json_encode(array("code"=>1,"msg"=>"Company saved, please wait!"));
    }else{
      echo json_encode(array("code"=>2,"msg"=>"An error occured while saving Company!"));
    }
      
  }

?>