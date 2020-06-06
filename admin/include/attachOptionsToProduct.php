<?php
require('db.php');

if(isset($_POST['productOptionGroup']))
{
    $productId = test_input($_POST['productId']);
    $productOptionGroupId = test_input($_POST['productOptionGroup']);
    $productOptionNameId = test_input($_POST['productOptionName']);
    $productOptionPrice = test_input($_POST['productOptionPrice']);

    if(empty($productId) || empty($productOptionGroupId) || empty($productOptionNameId) || empty($productOptionPrice))
    {
        echo "<div class='alert alert-danger'>Please fill all fields</div>";
    }
    else
    {

        $query = "SELECT * FROM productoptions WHERE ProductID='$productId' AND OptionID='$productOptionNameId';";
        $result = mysqli_query($conn, $query);
        $num_rows = mysqli_num_rows($result);

        if($num_rows > 0)
        {
            $sql = "UPDATE productoptions SET OptionPriceIncrement='$productOptionPrice' WHERE ProductID='$productId' AND OptionID='$productOptionNameId';";
            $rslt = mysqli_query($conn, $sql);
            if($rslt)
            {
                echo "<div class='alert alert-success'>Option price updated successfully</div>";
            }
            else
            {
                echo "<div class='alert alert-danger'>Error in updating price</div>";
            }
        }
        else
        {
            $sql = "INSERT INTO productoptions (ProductID, OptionID, OptionPriceIncrement, OptionGroupID) VALUES ('$productId', '$productOptionNameId', '$productOptionPrice', '$productOptionGroupId');";
            $rslt = mysqli_query($conn, $sql);
            if($rslt)
            {
                echo "<div class='alert alert-success'>Option attach successfully</div>";
            }
            else
            {
                echo "<div class='alert alert-danger'>Error in attaching option</div>";
            }
        }
    }
}


?>