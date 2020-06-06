<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:3000');
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
    $password = test_input($_POST['password']);

    $query = "SELECT * FROM users WHERE UserEmail='$email' AND UserPassword='$password' LIMIT 1;";
    $result = mysqli_query($conn, $query);
    $num_rows = mysqli_num_rows($result);

    if($num_rows > 0)
    {
        $q = "SELECT * FROM tokens WHERE UserID='$email' LIMIT 1;";
        $r = mysqli_query($conn, $q);
        $n_r = mysqli_num_rows($r);

        if($n_r > 0)
        {
            while($row = mysqli_fetch_assoc($r))
            {
                $output['token'] = $row['Token'];
                $output['user'] = $row['UserID'];
                $output['message'] = "Success";
                $output['error'] = "";
            }
        }
        else
        {
            $output['error'] = "No Authorization";
        }
    }
    else
    {
        $output['error'] = "No user found";
    }

    $json = json_encode($output, JSON_PRETTY_PRINT);
    echo $json;
}
?>