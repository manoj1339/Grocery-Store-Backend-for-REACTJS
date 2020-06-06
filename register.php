<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: *');

require('admin/include/db.php');

$output = array();
$server_name = 'http://' . $_SERVER['HTTP_HOST'] . '/store_php/admin';


if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $_POST = json_decode(file_get_contents('php://input'), true); 

    $email_u = test_input($_POST['email']);
    $email = strtolower($email_u);
    $mobile = test_input($_POST['mobile']);
    $password = test_input($_POST['password']);

    $query = "SELECT * FROM users WHERE UserEmail='$email';";
    $result = mysqli_query($conn, $query);
    $num_rows = mysqli_num_rows($result);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $output['error'] = 'Email is not valid';
    }
    else if(!preg_match("/^[0-9]{10}+$/", $mobile))
    {
        $output['error'] = 'Mobile is not valid';
    }
    else if(strlen($password) < 6){
        $output['error'] = 'Password should be min 6 characters';
    }
    else if($num_rows > 0)
    {
        $output['error'] = 'Email already exists';
    }
    else
    {
        $sql = "INSERT INTO users (UserEmail, UserPhone, UserPassword) VALUES ('$email', '$mobile', '$password');";
        if(mysqli_query($conn, $sql))
        {
            $token = uniqid("token") . date('Ymdhis');
            $q = "INSERT INTO tokens (UserID, Token) VALUES ('$email', '$token');";
            if(mysqli_query($conn, $q))
            {
                $output['error'] = 'No Error';
                $output['message'] = 'Your account has been created successfully';
                $output['token'] = "$token";
            }

        }
    }

    $json = json_encode($output, JSON_PRETTY_PRINT);
    echo $json;
}
?>