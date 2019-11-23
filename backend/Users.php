<?php 
require "Db.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class Users {

 public function __construct(){
     //$db = Db::getInstance(); Moze se ovo staviti da se ne ponavlja
 }

 public function login($user, $pass){
     $db = Db::getInstance();
/*
     if(!empty($user) || !empty($pass)) {
         $query = $db-> prepare("SELECT * FROM users WHERE username = :user AND password = :pass");
     
         $query -> execute(array(
			':user' => $_POST['user'],
			':pass' => $_POST['pass']
        ));
        $check = $query ->rowCount();
        if($check > 0) {
            $_SESSION['logedIn'] = true;

        } else{
            return false;
        }
        } else {
         return false;
     }
*/

     $data = json_decode(file_get_contents("php://input"));
     $msg['message'] = '';

     if(isset($data->user) && isset($data->pass)){
         if(!empty($data->user) && !empty($data->pass)){
             $query = $db-> prepare("SELECT * FROM users WHERE username = :user AND password = :pass");
             $query -> execute(array(
                 //':id' => $data->id,
                 ':user' => $data->user,
                 ':pass' => $data->pass
             ));

             $check = $query ->rowCount();
             if($check > 0) {
                 $_SESSION['logedIn'] = true;
                 $token = uniqid();
                 $ass = [
                     'token' => $token,
                     'user' => $data->user,
                     'pass' => $data->pass
                 ];
                 $sql = "UPDATE users SET token=:token WHERE username = :user AND password = :pass";
                 $stmt= $db->prepare($sql);
                 $stmt->execute($ass);

                $msg['user']= $data->user;
                $msg['message'] = 'Success';
                $msg['token'] = $token;
               //$msg['id'] = $data->id;

                 //return true;


             } else{
                $msg['message'] = 'Error';
                 //return false;
             }
         } else{
             $msg['message'] = 'Username adn password are required fields';
             //return false;
         }

     } else {
         $msg['message'] = ' Add username, password';
         //return false;
     }
     echo  json_encode($msg);
 }
 public function logout(){
     //session_destroy();
     unset($_SESSION['logedIn']);
     $word ="Logged out";
     echo json_encode($word);
 }

 public function addUser(){

     $db = Db::getInstance();
     $data = json_decode(file_get_contents("php://input"));
     $msg['message'] = '';
     if(isset($data->username) && isset($data->password)) {
         if (!empty($data->username) && !empty($data->password)) {
             // main code go here

             $sql = "INSERT INTO `users`(username,password,firstName, lastName, address, zip, city) VALUES(:username,:password,:firstName, :lastName, :address, :zip, :city)";
             $insert= $db->prepare($sql);
             $insert->bindValue(':username', htmlspecialchars(strip_tags($data->username)),PDO::PARAM_STR);
             $insert->bindValue(':password', htmlspecialchars(strip_tags($data->password)),PDO::PARAM_STR);
             $insert->bindValue(':firstName', htmlspecialchars(strip_tags($data->firstName)),PDO::PARAM_STR);
             $insert->bindValue(':lastName', htmlspecialchars(strip_tags($data->lastName)),PDO::PARAM_STR);
             $insert->bindValue(':address', htmlspecialchars(strip_tags($data->address)),PDO::PARAM_STR);
             $insert->bindValue(':zip', htmlspecialchars(strip_tags($data->zip)),PDO::PARAM_STR);
             $insert->bindValue(':city', htmlspecialchars(strip_tags($data->city)),PDO::PARAM_STR);

             if($insert->execute()){
                 $msg['message'] = 'Success';
             }else{
                 $msg['message'] = 'Data not added';
             }
         } else{
             $msg['message'] = 'All fields are required';
         }
     } else{
         $msg['message'] = 'Input all fields';
     }
     echo  json_encode($msg);
 }
 public function editUser($id){

     $db = Db::getInstance();

     $data = json_decode(file_get_contents("php://input"));
     if(isset($id)){

         $msg['message'] = '';
         //$user_id = $data->id;

         $get_user = "SELECT * FROM `users` WHERE id=:id";
         $get_result = $db->prepare($get_user);
         $get_result->bindValue(':id', $id,PDO::PARAM_INT);
         $get_result->execute();

         if($get_result->rowCount() > 0){

             $row = $get_result->fetch(PDO::FETCH_ASSOC);

             $username = isset($data->username) ? $data->username : $row['username']; //can not edit username
             $firstName = isset($data->firstName) ? $data->firstName : $row['firstName'];
             $lastName = isset($data->lastName) ? $data->lastName : $row['lastName'];
             $address = isset($data->address) ? $data->address : $row['address'];
             $zip = isset($data->zip) ? $data->zip : $row['zip'];
             $city = isset($data->city) ? $data->city : $row['city'];

             $update_query = "UPDATE `users` SET username= :username, firstName = :firstName, lastName= :lastName, address = :address, zip= :zip, city= :city
        WHERE id = :id";

             $update_stmt = $db->prepare($update_query);
             $update_stmt->bindValue(':username', htmlspecialchars(strip_tags($username)),PDO::PARAM_STR);
             $update_stmt->bindValue(':firstName', htmlspecialchars(strip_tags($firstName)),PDO::PARAM_STR);
             $update_stmt->bindValue(':lastName', htmlspecialchars(strip_tags($lastName)),PDO::PARAM_STR);
             $update_stmt->bindValue(':address', htmlspecialchars(strip_tags($address)),PDO::PARAM_STR);
             $update_stmt->bindValue(':zip', htmlspecialchars(strip_tags($zip)),PDO::PARAM_STR);
             $update_stmt->bindValue(':city', htmlspecialchars(strip_tags($city)),PDO::PARAM_STR);
             $update_stmt->bindValue(':id', $id,PDO::PARAM_INT);


             if($update_stmt->execute()){
                 $msg['message'] = 'Success';
             }else{
                 $msg['message'] = 'Error';
             }

         }
         else{
             $msg['message'] = 'Error';
         }

         echo  json_encode($msg);

     }
 }
 /*public function checkAccessToken($user, $token){
        $msg['message'] = '';
     $db = Db::getInstance();

     //$id = $_GET['id'];
        if(isset($id)) {
            $result = $db->query("SELECT * FROM users WHERE username = " . $user . " AND token = " . $token . "");

            if($result->rowCount() > 0){

                while($row = $result->fetch(PDO::FETCH_ASSOC)){
                    $data[] = $row;

                }
            }
        }
        var_dump($data);
      json_encode($data);
    }*/

 public function checkAccessToken($user, $token){
     $db = Db::getInstance();
     $msg['message'] = '';
     $dataUser= array();

     if(isset($user) && isset($token)) {
        //$dataUser[]= '';

         $stmt = $db->prepare("SELECT * FROM users WHERE username=:user AND token=:token ");
         $stmt->execute(['user' => $user, 'token' => $token]);
         if($stmt->rowCount() > 0) {
             $data = $stmt->fetchAll();

             foreach ($data as $row) {
                 $dataUser = [
                     'id' => $row['id'],
                     'username' => $row['username'],
                     'firstName' => $row['firstName'],
                     'lastName' => $row['lastName'],
                     'address' => $row['address'],
                     'zip' => $row['zip'],
                     'city' => $row['city']
                 ];
             }
         } else {
             $msg['message'] = 'Error';
         }
     } else {
         $msg['message'] = 'Error';
     }
     if($dataUser !==null) {
        echo json_encode($dataUser);
     } 
 }


 public function getUser($id){
     $msg['message'] = '';
     //$id = $_GET['id'];
     if(isset($id)) {
         $db = Db::getInstance();
         $result = $db->prepare("SELECT * FROM users WHERE id = " . $id . " ORDER BY id");

         $result->execute();
         if($result->rowCount() > 0){
        //     $data = [];

             while($row = $result->fetch(PDO::FETCH_ASSOC)){
                 $dataUser = [
                     'id' => $row['id'],
                     'username' => $row['username'],
                     'firstName' => $row['firstName'],
                     'lastName' => $row['lastName'],
                     'address' => $row['address'],
                     'zip' => $row['zip'],
                     'city' => $row['city']
                 ];
             }
             //array_push($data, $dataUser);
         }
     }
     echo json_encode($dataUser);
 }
 public function getUsers(){
     $db = Db::getInstance();
     $data    = array();
     $result 	= $db->query('SELECT * FROM users ORDER BY id ASC');
     while($row  = $result->fetch(PDO::FETCH_OBJ))
     {
         $data[] = $row;
     }

     echo json_encode($data);//

 }
 public function deleteUser($id){
     $msg['message'] = '';
     //$id = $_GET['id'];
     if(isset($id)) {
         $db = Db::getInstance();

         $sql = "DELETE FROM users WHERE id =  :id";
         $stmt = $db->prepare($sql);
         $stmt->bindParam(':id', $id, PDO::PARAM_INT);
         if($stmt->execute()){
             $msg['message'] = 'Data successfully deleted ';
         }else{
             $msg['message'] = 'Data not deleted';
         }     } else {
         $msg['message'] = 'Error';

     }
     echo json_encode($msg);//

 }
}