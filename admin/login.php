<?php 
require('include/db.php');
session_start();

$error_message = '';

if(isset($_GET['error']))
{
    if($_GET['error'] == "no_value")
    {
        $error_message = '<div style="margin-top:8px;" class="alert alert-danger">Please enter all fields</div>';
    }
    else if($_GET['error'] == "no_user")
    {
        $error_message = '<div style="margin-top:8px;" class="alert alert-danger">Wrong Credentials</div>';
    }
}

if(isset($_POST['submit']))
{
    $admin = test_input($_POST['username']);
    $password = test_input($_POST['password']);

    if(empty($admin) || empty($password))
    {
        header('location: login.php?error=no_value');
        exit();
    }
    else{
        $query = "SELECT * FROM admin WHERE admin='$admin' AND password='$password' LIMIT 1;";
        $result = mysqli_query($conn, $query);
        $num_rows = mysqli_num_rows($result);

        if($num_rows > 0)
        {
            $_SESSION['admin_user'] = $admin;
            header('location: index.php');
        }
        else
        {
            header('location: login.php?error=no_user');
            exit();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Admin Login</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Admin Login</h3></div>
                                    <div class="card-body">
                                        <form method="post" action="">
                                            <div class="form-group"><label class="small mb-1" for="inputEmailAddress">Username</label><input class="form-control py-4" id="inputEmailAddress" type="text" name="username" placeholder="Enter Username" /></div>
                                            <div class="form-group"><label class="small mb-1" for="inputPassword">Password</label><input class="form-control py-4" id="inputPassword" type="password" name="password" placeholder="Enter password" /></div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox"><input class="custom-control-input" id="rememberPasswordCheck" type="checkbox" /><label class="custom-control-label" for="rememberPasswordCheck">Remember password</label></div>
                                            </div>
                                            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                               <input class="btn btn-primary" type="submit" name="submit" value="Login" />
                                            </div>
                                        </form>
                                        <?php echo "$error_message"; ?>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small">Admin panel login</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2019</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
