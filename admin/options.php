<?php
require('include/db.php');
session_start();
if(!isset($_SESSION['admin_user']))
{
    header('location: login.php');
    exit();
}
else
{
    $admin = $_SESSION['admin_user'];
}

$error_msg = "";

if(isset($_GET['error']))
{
    if($_GET['error'] == 'empty')
    {
        $error_msg = "<div class='alert alert-danger'>Please fill all fields</div>";
    }
    else if($_GET['error'] == 'file_empty')
    {
        $error_msg = "<div class='alert alert-danger'>Please choose product image</div>";
    }
    else if($_GET['error'] == 'file_format')
    {
        $error_msg = "<div class='alert alert-danger'>Only jpeg, jpg, png are allowded</div>";
    }
}


if(isset($_POST['submit']))
{
    $productOptionGroup = test_input($_POST['productOptionGroup']);
    $productOptionName = test_input($_POST['productOptionName']);

    if(empty($productOptionGroup) || empty($productOptionName))
    {
        header('location: options.php?error=empty');
        exit();
    }
    else
    {
        $query = "INSERT INTO options (OptionGroupID, OptionName) VALUES ('$productOptionGroup', '$productOptionName');";
        
        if(mysqli_query($conn, $query))
        {
            $error_msg = "<div class='alert alert-success'>Option added successfully</div>";
        }
        else{
            $error_msg = "<div class='alert alert-danger'>Please try later</div>";
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
        <title>Product Options</title>
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
                                    <h3 class="text-center font-weight-light my-4">Add Product Options</h3>
                                    <?php echo "$error_msg"; ?>
                                </div>
                                <div class="card-body">
                                    <?php $option_array = getProductOptions($conn); ?>
                                    <form method="post" action="">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                <label class="small mb-1" for="productOptionGroup">
                                                Product Option Group</label>
                                                <select class="form-control" name="productOptionGroup">
                                                    <?php
                                                    foreach($option_array as $key => $option)
                                                    {
                                                        echo "<option value=". $key .">". $option ."</option>";
                                                    }
                                                    ?>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="productOptionName">Product Option Name</label>
                                                    <input class="form-control" name="productOptionName" type="text" placeholder="Ex. Red, Green, XL etc." />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-4 mb-0"><input type="submit" name="submit" class="btn btn-primary btn-block" value="Add Option" /></div>
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
                            $query = "SELECT * FROM optiongroups";
                            $result = mysqli_query($conn, $query);
                            $num_rows = mysqli_num_rows($result);

                            $output = "";
                            $child_output = '';
                        
                            if($num_rows > 0)
                            {
                                echo "<div>";
                                while($row = mysqli_fetch_assoc($result))
                                {
                                    $q = "SELECT * FROM options WHERE OptionGroupID=".$row['OptionGroupID'].";";
                                    $rslt = mysqli_query($conn, $q);
                                    $n_r = mysqli_num_rows($rslt);

                                    echo "<div style='margin: 15px 0px 0px 0px' class='alert alert-primary'>". $row['OptionGroupName'] ."</div>";

                                    if($n_r > 0)
                                    {
                                        while($row_child = mysqli_fetch_assoc($rslt))
                                        {
                                            echo "<div style='padding:5px;'>".$row_child['OptionName']."</div>";
                                        }
                                    }

                                }

                                echo "</div>";
                            }
                            else
                            {
                                echo "<p style='text-align:center'>No options added yet</p>";
                            }
                        ?>
                            
                        </div>
                    </div>
                </div>
            </div>

            <?php include_once "include/footer.php"; ?>
        </div>
        
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>

