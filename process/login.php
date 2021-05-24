<?php
	include("../connection/Functions.php");
	$operation = new Functions();
	session_start();
//login
if(isset($_POST['email']) && isset($_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])){
    $email = addslashes($_POST['email']);
    $password = addslashes($_POST['password']);
    $role = "secretary";

         $query = "SELECT * FROM `users` WHERE email = '$email' AND user_role = '$role'";
            $count = $operation->countAll($query);

            if($count == 1){

                //check password and email then redirect
                $user = $operation->retrieveSingle($query);
                $hashed_password = $user['password'];
                $user_id = $user['user_id'];

                if(password_verify($password, $hashed_password)){
                    //check if user account is active
                    if($operation->countAll($query." AND account_status = 1") > 0){
                    

                        echo json_encode(array("code" => 1,"msg"=>"Success, please confirm the inquiry request!","uid"=>$user_id));
                        
                    }else{
                        echo json_encode(array("code" => 2,"msg"=>"Your account has been terminated, contact admin!"));
                    }
                     

                }else{
                    echo json_encode(array("code" => 2,"msg"=>"Wrong password or email, try again!"));
                }

            }else{
                echo json_encode(array("code" => 2,"msg"=>"Email does not match any records!"));
            }
}elseif (isset($_POST['qty']) && isset($_POST['item_id']) && isset($_POST['uid']) && !empty($_POST['qty']) && !empty($_POST['item_id']) && !empty($_POST['uid'])) {
    
    $uid = addslashes($_POST['uid']);
    $item_id = addslashes($_POST['item_id']);
    $qty = addslashes($_POST['qty']);
    $status =1;
    //check remaining amounts
    $items = $operation->retrieveSingle("SELECT * FROM `items` WHERE item_id = '$item_id'");
    if ($qty <= $items['quantity_remaining']) {

        //make sure client id and company id is different
        $getUser = $operation->retrieveSingle("SELECT *FROM user WHERE user_id = '$uid'");
        //get company id 
        $getCompany = $operation->retrieveSingle("SELECT *FROM items WHERE item_id = '$item_id'");
        if ($getUser['company_id'] == $getCompany['company_id']) {
            echo json_encode(array("code" => 2,"msg"=>"Sorry, you cannot order from a company you belong to, try a different item!"));
        }else{
            $table = "item_supplies";
            $data = [
                'user_id'=>"$uid",
                'item_id'=>"$item_id",
                'quantity'=>"$qty",
                'status'=>"$status"
            ];

            if ($operation->insertData($table,$data) == 1) {

               echo json_encode(array("code" => 1,"msg"=>"Order request sent to supplier, you can login to your panel to track the status!"));
            }else{
                echo json_encode(array("code" => 2,"msg"=>"An error occured, try again later!"));
            }
        }

        
    }else{
        echo json_encode(array("code" => 2,"msg"=>"The remaining items is less than the number of items you want!"));
    }
}

?>