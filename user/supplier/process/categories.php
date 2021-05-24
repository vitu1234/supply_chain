<?php
   include("../../../connection/Functions.php");
	$operation = new Functions();
  //add category
  if(isset($_POST['category_name']) && isset($_POST['user_id']) && !empty($_POST['category_name']) && !empty($_POST['user_id']) ){
    
    $category_name = addslashes($_POST['category_name']);
    $user_id = addslashes($_POST['user_id']);
    
    $table = "item_categories";
    
    
    //check email existance
    if($operation->countAll("SELECT *FROM item_categories WHERE category_name = '$category_name'") == 0){

           //save admin user
        $data = [
          'user_id'=>"$user_id",
          'category_name'=>"$category_name",
        ];
        //print out response
        if($operation->insertData($table,$data)==1){
          echo json_encode(array("code"=>1,"msg"=>"Category saved, please wait!"));
        }else{
          echo json_encode(array("code"=>2,"msg"=>"An error occured while saving category!"));
        }
        
      
    } else{
      echo json_encode(array("code"=>2,"msg"=>"That category already exists try another category name!"));
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
    
    //edit category
  } elseif(isset($_POST['ecategory_name']) && isset($_POST['e_user_id']) && !empty($_POST['ecategory_name']) && !empty($_POST['e_user_id']) ){
    
    $category_name = addslashes($_POST['ecategory_name']);
    $user_id = addslashes($_POST['e_user_id']);
    $category_id = addslashes($_POST['e_category_id']);

    $table = "item_categories";

    $where = "category_id = '$category_id'";
    
    $data = [
          'category_name'=>"$category_name",
        ];
    //print out response
    if($operation->updateData($table,$data,$where)==1){
      echo json_encode(array("code"=>1,"msg"=>"Category saved, please wait!"));
    }else{
      echo json_encode(array("code"=>2,"msg"=>"An error occured while saving category!"));
    }
      
  }elseif(isset($_POST['delCategory']) && !empty($_POST['delCategory'])){
    $id = addslashes($_POST['delCategory']);
    
    $table = "item_categories";
    
    $where = "category_id = '$id'";
    
    //print out response
    if($operation->deleteData($table,$where)==1){
      echo json_encode(array("code"=>1,"msg"=>"Category deleted, please wait!"));
    }else{
      echo json_encode(array("code"=>2,"msg"=>"An error occured while deleting category!"));
    }
    
    //edit user
  } 

?>