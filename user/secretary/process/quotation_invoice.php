<?php
   include("../../../connection/Functions.php");
	$operation = new Functions();
  //add quotation
  if(isset($_FILES['quotation_file']) ){

    /* Getting file name */
    $id = addslashes($_POST['quotation_item_id']);

    $images = $_FILES['quotation_file']['name'];
    $image=strtolower(pathinfo($images,PATHINFO_EXTENSION));
    $filename=rand(1000, 1000000).".".$image;
    /* Location */
    $location = "../files/".$filename;
    $uploadOk = 1;
    $imageFileType = pathinfo($location,PATHINFO_EXTENSION);

    /* Valid Extensions */
    $valid_extensions = array("pdf");
    /* Check file extension */
    if( !in_array(strtolower($imageFileType),$valid_extensions) ) {
       $uploadOk = 0;
    }
    
    
    if($uploadOk == 0){
         echo json_encode(array("code"=>1,"msg"=>"✖ File type not supported, try pdf!"));
    }else{
       /* Upload file */
       if(move_uploaded_file($_FILES['quotation_file']['tmp_name'],$location)){

        //get old file
        $getFile = $operation->countAll("SELECT * FROM `quotations` WHERE item_supply_id = '$id'");
        if ($getFile > 0) {
            $directory = "../files/".$getFile['quotation_file'];
            if (unlink($directory)) {
            }
        }
           
         $table = "quotations";
         $data = [
          'item_supply_id'=>"$id",
           'quotation_file' => "$filename"  
         ];
         
         if($operation->insertData($table,$data) == 1){
  //                   echo $filename;
             echo json_encode(array("code"=>1,"msg"=>"Quotation saved, please wait..."));
         }else{
  //                   echo 0;
             echo json_encode(array("code"=>2,"msg"=>"✖ An error occured while saving quotation!"));
         }
          
       }else{
          echo json_encode(array("code"=>2,"msg"=>"✖ An error occured while saving!"));
       }
    }
}elseif(isset($_FILES['invoice_file']) ){

    /* Getting file name */
    $id = addslashes($_POST['invoice_item_id']);

    $images = $_FILES['invoice_file']['name'];
    $image=strtolower(pathinfo($images,PATHINFO_EXTENSION));
    $filename=rand(1000, 1000000).".".$image;
    /* Location */
    $location = "../files/".$filename;
    $uploadOk = 1;
    $imageFileType = pathinfo($location,PATHINFO_EXTENSION);

    /* Valid Extensions */
    $valid_extensions = array("pdf");
    /* Check file extension */
    if( !in_array(strtolower($imageFileType),$valid_extensions) ) {
       $uploadOk = 0;
    }
    
    
    if($uploadOk == 0){
         echo json_encode(array("code"=>1,"msg"=>"✖ File type not supported, try pdf!"));
    }else{
       /* Upload file */
       if(move_uploaded_file($_FILES['invoice_file']['tmp_name'],$location)){

        //get old file
        $getFile = $operation->countAll("SELECT * FROM `invoices` WHERE item_supply_id = '$id'");
        if ($getFile > 0) {
            $directory = "../files/".$getFile['invoice_file'];
            if (unlink($directory)) {
            }
        }
           
         $table = "invoices";
         $data = [
          'item_supply_id'=>"$id",
           'invoice_file' => "$filename"  
         ];
         
         if($operation->insertData($table,$data) == 1){
          $status =2;
          $tbl = "item_supplies";
          $dt = [
            'status' =>"$status"
          ];
          $where = "item_supply_id = '$id'";
          $operation->updateData($tbl,$dt,$where);
  //                   echo $filename;
             echo json_encode(array("code"=>1,"msg"=>"Invoice saved, please wait..."));
         }else{
  //                   echo 0;
             echo json_encode(array("code"=>2,"msg"=>"✖ An error occured while saving invoice!"));
         }
          
       }else{
          echo json_encode(array("code"=>2,"msg"=>"✖ An error occured while saving!"));
       }
    }
}
?>