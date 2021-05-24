<?php
   include("../../../connection/Functions.php");
	$operation = new Functions();
  //add item


if(isset($_FILES['item_file']) && isset($_POST['user_id']) && isset($_POST['item_name']) && isset($_POST['item_price']) && isset($_POST['category_name']) && isset($_POST['total_products']) && !empty($_POST['user_id']) && !empty($_POST['item_name']) && !empty($_POST['item_price']) && !empty($_POST['category_name']) && !empty($_POST['total_products']) ){

    /* Getting file name */
    $id = addslashes($_POST['user_id']);
    $item_name = addslashes($_POST['item_name']);
    $item_price = addslashes($_POST['item_price']);
    $category_name = addslashes($_POST['category_name']);
    $total_products = addslashes($_POST['total_products']);

    $images = $_FILES['item_file']['name'];
    $image=strtolower(pathinfo($images,PATHINFO_EXTENSION));
    $filename=rand(1000, 1000000).".".$image;
    /* Location */
    $location = "../items/".$filename;
    $uploadOk = 1;
    $imageFileType = pathinfo($location,PATHINFO_EXTENSION);

    /* Valid Extensions */
    $valid_extensions = array("jpg","jpeg","png");
    /* Check file extension */
    if( !in_array(strtolower($imageFileType),$valid_extensions) ) {
       $uploadOk = 0;
    }
    
    
    if($uploadOk == 0){
         echo json_encode(array("code"=>1,"msg"=>"✖ File type not supported, try jpg, jpeg or png!"));
    }else{
       /* Upload file */
       if(move_uploaded_file($_FILES['item_file']['tmp_name'],$location)){
           
	       $table = "items";
	       $data = [
	       	'category_id'=>"$category_name",
	       	'company_id'=>"$id",
	       	'item_name'=>"$item_name",
	       	'quantity_total'=>"$total_products",
	       	'quantity_remaining'=>"$total_products",
	       	'price'=>"$item_price",
	         'img_url' => "$filename"  
	       ];
	       
	       if($operation->insertData($table,$data) == 1){
	//                   echo $filename;
	           echo json_encode(array("code"=>1,"msg"=>"Item saved, please wait..."));
	       }else{
	//                   echo 0;
	           echo json_encode(array("code"=>2,"msg"=>"✖ An error occured while saving the item!"));
	       }
          
       }else{
          echo json_encode(array("code"=>2,"msg"=>"✖ An error occured while saving the item!"));
       }
    }
}elseif(isset($_POST['euser_id']) && isset($_POST['eitem_name']) && isset($_POST['eitem_price']) && isset($_POST['ecategory_name']) && isset($_POST['etotal_products']) && !empty($_POST['euser_id']) && !empty($_POST['eitem_name']) && !empty($_POST['eitem_price']) && !empty($_POST['ecategory_name']) && !empty($_POST['etotal_products']) ){

    /* Getting file name */
    $id = $_POST['euser_id'];
    $item_name = addslashes($_POST['eitem_name']);
    $item_id = addslashes($_POST['eitem_id']);
    $item_price = addslashes($_POST['eitem_price']);
    $category_name = addslashes($_POST['ecategory_name']);
    $total_products = addslashes($_POST['etotal_products']);

         $table = "items";
         $data = [
          'category_id'=>"$category_name",
          'item_name'=>"$item_name",
          'quantity_total'=>"$total_products",
          'quantity_remaining'=>"$total_products",
          'price'=>"$item_price",
         ];
         $WHERE = "item_id = '$item_id'";
         
         if($operation->updateData($table,$data,$WHERE) == 1){
  //                   echo $filename;
             echo json_encode(array("code"=>1,"msg"=>"Item saved, please wait..."));
         }else{
  //                   echo 0;
             echo json_encode(array("code"=>2,"msg"=>"✖ An error occured while saving the item!"));
         }
          
    }elseif(isset($_FILES['efile']) ){

    /* Getting file name */
    $id = $_POST['euser_id'];
    $item_id = addslashes($_POST['eitem_id']);

    $images = $_FILES['efile']['name'];
    $image=strtolower(pathinfo($images,PATHINFO_EXTENSION));
    $filename=rand(1000, 1000000).".".$image;
    /* Location */
    $location = "../items/".$filename;
    $uploadOk = 1;
    $imageFileType = pathinfo($location,PATHINFO_EXTENSION);

    /* Valid Extensions */
    $valid_extensions = array("jpg","jpeg","png");
    /* Check file extension */
    if( !in_array(strtolower($imageFileType),$valid_extensions) ) {
       $uploadOk = 0;
    }
    
    
    if($uploadOk == 0){
         echo json_encode(array("code"=>1,"msg"=>"✖ File type not supported, try jpg, jpeg or png!"));
    }else{
       /* Upload file */
       if(move_uploaded_file($_FILES['efile']['tmp_name'],$location)){

        //get old file
        $getFile = $operation->retrieveSingle("SELECT * FROM `items` WHERE item_id = '$item_id'");
        $directory = "../items/".$getFile['img_url'];
        if (unlink($directory)) {
          # code...
        }
           
         $table = "items";
         $data = [
           'img_url' => "$filename"  
         ];
         $where = "item_id = '$item_id'";
         
         if($operation->updateData($table,$data,$where) == 1){
  //                   echo $filename;
             echo json_encode(array("code"=>1,"msg"=>"Item saved, please wait..."));
         }else{
  //                   echo 0;
             echo json_encode(array("code"=>2,"msg"=>"✖ An error occured while saving the item!"));
         }
          
       }else{
          echo json_encode(array("code"=>2,"msg"=>"✖ An error occured while saving the item!"));
       }
    }
}elseif(isset($_POST['delItem']) && !empty($_POST['delItem'])){
    $id = addslashes($_POST['delItem']);
    
    $table = "items";
    
    $where = "item_id = '$id'";
      //get old file
        $getFile = $operation->retrieveSingle("SELECT * FROM `items` WHERE item_id = '$id'");
        $directory = "../items/".$getFile['img_url'];
        if (unlink($directory)) {
          # code...
        }
           


    //print out response
    if($operation->deleteData($table,$where)==1){
      echo json_encode(array("code"=>1,"msg"=>"Item deleted, please wait!"));
    }else{
      echo json_encode(array("code"=>2,"msg"=>"An error occured while deleting item!"));
    }
    
    //edit user
  } 
?>