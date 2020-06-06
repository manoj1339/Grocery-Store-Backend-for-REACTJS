<?php
require('include/db.php');
session_start();
if(!isset($_SESSION['admin_user']))
{
    header('location: login.php');
    exit();
}
else{
    $admin = $_SESSION['admin_user'];
}

$error_msg = "";

if(isset($_GET['error']))
{
    if($_GET['error'] == 'empty')
    {
        $error_msg = "<div class='alert alert-danger'>Field is empty</div>";
    }
}

if(isset($_POST['submit']))
{
    $productCategory = test_input($_POST['productCategory']);
    if(empty($productCategory))
    {
        header('location: add_category.php?error=empty');
        exit();
    }

    $query = "INSERT INTO productcategories (CategoryName) VALUES ('$productCategory');";
    $result = mysqli_query($conn, $query);

    if($result)
    {
        $error_msg = "<div class='alert alert-success'>Category added successfully</div>";
    }
    
}

?>;

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Add Category</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">

        <?php include_once "include/header.php" ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Add Category</h3>
                                    <?php echo "$error_msg"; ?>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="">
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="productCategory">
                                                    Product Category</label>
                                                    <input class="form-control py-4" name="productCategory" type="text" placeholder="Enter product category">
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <div class="form-group mt-4 mb-0"><input type="submit" name="submit" class="btn btn-primary btn-block" value="Add Category" /></div>
                                    </form>
                                </div>
                                <div class="card-footer text-center">
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                        <?php
                            $query = "SELECT * FROM productcategories";
                            $result = mysqli_query($conn, $query);
                            $num_rows = mysqli_num_rows($result);

                            $output = "";
                        
                            if($num_rows > 0)
                            {
                                while($row = mysqli_fetch_assoc($result))
                                {
                                    $output .= "
                                        <li>".$row['CategoryName']."</li>
                                    ";
                                }
                            }
                            else
                            {
                                $output = "<p style='text-align:center'>No category added yet</p>";
                            }
                        ?>
                        <ul>
                            <?php echo "$output"; ?>
                        </ul>
                    </div>
                    </div>
                </div>

                <?php include_once "include/footer.php"; ?>
            </div>
        </div>
        
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>

