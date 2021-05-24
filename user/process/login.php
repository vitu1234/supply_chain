<?php
	include("../../connection/Functions.php");
	$operation = new Functions();
	session_start();
//login
if(isset($_POST['email']) && isset($_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])){
    $email = addslashes($_POST['email']);
    $password = addslashes($_POST['password']);
    
         $query = "SELECT * FROM `users` WHERE email = '$email' ";
            $count = $operation->countAll($query);

            if($count == 1){

                //check password and email then redirect
                $user = $operation->retrieveSingle($query);
                $hashed_password = $user['password'];

                if(password_verify($password, $hashed_password)){
                    //check if user account is active
                    if($operation->countAll($query." AND account_status = 1") > 0){
                        
                        $role = $user['user_role'];
                        if ($role == 'admin') {
                            $_SESSION['user'] = $user['user_id'];
                        }elseif ($role == 'supplier') {
                            $_SESSION['user_supplier'] = $user['user_id'];
                        }elseif ($role == 'accountant') {
                            $_SESSION['user_accountant'] = $user['user_id'];
                        }elseif ($role == 'secretary') {
                            $_SESSION['user_secretary'] = $user['user_id'];
                        }


                        echo json_encode(array("code" => 1,"msg"=>"Success, redirecting!","location"=>$role));
                        
                    }else{
                        echo json_encode(array("code" => 2,"msg"=>"Your account has been terminated, contact admin!"));
                    }
                     

                }else{
                    echo json_encode(array("code" => 2,"msg"=>"Wrong password or email, try again!"));
                }

            }else{
                echo json_encode(array("code" => 2,"msg"=>"Email does not match any records!"));
            }
}

?>