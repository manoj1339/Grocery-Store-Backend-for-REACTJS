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

$productName = '';
$productCartDesc = '';
$productLongDesc = '';
$productPrice = '';
$productWeight = '';
$productCategory = '';
$productDiscount = 0;
$in_stock = '';
$productImage = '';

if(isset($_GET['id']))
{

    $id = $_GET['id'];

    $query = "SELECT * FROM products WHERE ProductID='$id' LIMIT 1;";
    $result = mysqli_query($conn, $query);
    $num_rows = mysqli_num_rows($result);

    if($num_rows > 0){
        while($row = mysqli_fetch_assoc($result))
        {
            $productName = $row['ProductName'];
            $productCartDesc = $row['ProductCartDesc'];
            $productLongDesc = $row['ProductLongDesc'];
            $productPrice = $row['ProductPrice'];
            $productWeight = $row['ProductWeight'];
            $productCategory = $row['ProductCategoryID'];
            $productDiscount = $row['ProductDiscount'];
            $in_stock = $row['in_stock'];
            $productImage = $row['ProductImage'];
        }
    }

    if(isset($_GET['error']))
    {
        if($_GET['error'] == 'empty')
        {
            $error_msg = '<div class="alert alert-danger">Please fill all fields</div>';
        }
        else if($_GET['error'] == 'file_format')
        {
            $error_msg = '<div class="alert alert-danger">Only jpeg, jpg, png files are allowed</div>';
        }
    }
}
else{
    header('location: index.php');
    exit();
}



if(isset($_POST['submit']))
{
    $productName = test_input($_POST['productName']);
    $productCartDesc = test_input($_POST['productCartDesc']);
    $productLongDesc = test_input($_POST['productLongDesc']);
    $productPrice = test_input($_POST['productPrice']);
    $productWeight = test_input($_POST['productWeight']);
    $productCategory = test_input($_POST['productCategory']);
    $productDiscount = test_input($_POST['productDiscount']);
    $in_stock = test_input($_POST['in_stock']);

    $id = '';
    if(isset($_GET['id']))
    {
        $id = $_GET['id'];
    }


    if(empty($productName) || empty($productCartDesc) || empty($productLongDesc) || empty($productPrice) || empty($productWeight) || empty($productCategory))
    {
        header('location: add_product.php?id='.$id.'&error=empty');
        exit();
    }
    else
    {
        if($_FILES['productImage']['error'] == 0)
        {
            $file = $_FILES['productImage'];
            $file_name = $file['name'];
            $file_size = $file['size'];
            $file_tmp_name = $file['tmp_name'];
            $file_type = $file['type'];
            
            $name_array = explode('.', $file_name);
            $file_ext = end($name_array);
            $allowed = array('jpg', 'jpeg', 'png');

            $new_file_name = uniqid('image'). '.' . $file_ext;
            $destination = "uploads/product/". $new_file_name;

            if(!in_array($file_ext, $allowed))
            {
                header('location: add_product.php?id='.$id.'&error=file_format');
                exit();
            }
            else
            {
                if(move_uploaded_file($file_tmp_name, $destination))
                {
                    if(file_exists($productImage))
                    {
                        unlink($productImage);
                    }

                    $query = "UPDATE products SET ProductName='$productName', ProductCartDesc='$productCartDesc', ProductLongDesc='$productLongDesc', ProductPrice='$productPrice', productImage='$destination', ProductWeight='$productWeight', ProductCategoryID='$productCategory', ProductDiscount='$productDiscount', in_stock='$in_stock' WHERE ProductID='$id';";
                    if(mysqli_query($conn, $query))
                    {
                        $error_msg = "<div class='alert alert-success'>Product has been updated successfully</div>";
                    }
                    else{
                        $error_msg = "<div class='alert alert-danger'>". mysqli_error($conn) ."</div>";
                    }
                }
                else{
                    $error_msg = "<div class='alert alert-danger'>Error in uploading file. Please try later</div>";
                }
            }
        }
        else
        {
            $query = "UPDATE products SET ProductName='$productName', ProductCartDesc='$productCartDesc', ProductLongDesc='$productLongDesc', ProductPrice='$productPrice', ProductWeight='$productWeight', ProductCategoryID='$productCategory', ProductDiscount='$productDiscount', in_stock='$in_stock' WHERE ProductID='$id';";
            if(mysqli_query($conn, $query))
            {
                $error_msg = "<div class='alert alert-success'>Product has been updated successfully</div>";
            }
            else{
                $error_msg = "<div class='alert alert-danger'>". mysqli_error($conn) ."</div>";
            }
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
        <title>Update Product</title>
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
                                    <div style="text-align:center;">
                                        <img src="<?php echo $productImage; ?>" style="height: 80px; width: 80px;" />
                                    </div>
                                    <h3 class="text-center font-weight-light my-4">Update Product</h3>
                                    <?php echo "$error_msg"; ?>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                <label class="small mb-1" for="productName">
                                                Product Name</label>
                                                <input class="form-control py-4" name="productName" type="text" placeholder="Enter product name" value="<?php echo isset($productName) ? $productName : ''; ?>" ></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"><label class="small mb-1" for="productCartDesc">Product cart desc</label><input class="form-control py-4" name="productCartDesc" type="text" placeholder="Enter product cart desc" value="<?php echo isset($productCartDesc) ? $productCartDesc : ''; ?>" /></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="productLongDesc">Product long desc</label>
                                            <textarea class="form-control" name="productLongDesc" type="text" placeholder="Enter product long desc"><?php echo isset($productLongDesc) ? $productLongDesc : ''; ?></textarea>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="productPrice">Product Price</label>
                                                    <input class="form-control py-4" name="productPrice" type="number" placeholder="Enter price" value="<?php echo isset($productPrice) ? $productPrice : ''; ?>" /></div>
                                                </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="productWeight">Product Weight(in grams)</label>
                                                    <input class="form-control py-4" name="productWeight" type="number" placeholder="Enter Single unit Weight"  value="<?php echo isset($productWeight) ? $productWeight : ''; ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group"><label class="small mb-1" for="productImage">Product Image</label><input class="form-control" name="productImage" type="file"/></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"><label class="small mb-1" for="productCategory">Product Category</label>
                                                <select class="form-control" name="productCategory">
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
                                                                <option value=".$row['CategoryID'].">".$row['CategoryName']."</li>
                                                            ";
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $output = "<p style='text-align:center'>No category added yet</p>";
                                                    }

                                                    echo "$output";
                                                ?>
                                                </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="productDiscount">Product Discount(percentage)</label>
                                                    <input class="form-control py-4" name="productDiscount" type="number" placeholder="Enter Discount" value="<?php echo isset($productDiscount) ? $productDiscount : ''; ?>" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>In stock</label><br/>
                                                    True<input type="radio" name="in_stock" value="true" checked/><br/>
                                                    False<input type="radio" name="in_stock" value="false"/>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="form-group mt-4 mb-0"><input type="submit" name="submit" class="btn btn-primary btn-block" value="Update Product" /></div>
                                    </form>
                                </div>
                                <div class="card-footer text-center">
                                   <div class="small">Image is not compulsory in update</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include_once "include/footer.php"; ?>
        </div>
        
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>

