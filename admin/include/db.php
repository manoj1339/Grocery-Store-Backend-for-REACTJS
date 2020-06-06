<?php
$host = "localhost";
$username = "root";
$password = "";
$db_name = "store_php";

$conn = mysqli_connect($host, $username, $password, $db_name);
mysqli_set_charset($conn, 'utf8');

if(!$conn)
{
    echo "Database connection unsuccessful";
    exit();
}


function test_input($data) 
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function getCategoryName($conn, $id)
{
    $query = "SELECT * FROM productcategories WHERE CategoryID='$id'";
    $result = mysqli_query($conn, $query);
    $num_rows = mysqli_num_rows($result);

    $output = '';

    if($num_rows > 0)
    {
        while($row = mysqli_fetch_assoc($result))
        {
            $output = $row['CategoryName'];
        }
    }

    return $output;
    
}

function getProductOptions($conn)
{
    $query = "SELECT * FROM optiongroups;";
    $result = mysqli_query($conn, $query);
    $num_rows = mysqli_num_rows($result);

    $output = array();

    if($num_rows > 0)
    {
        while($row = mysqli_fetch_assoc($result))
        {
            $output[$row['OptionGroupID']] = $row['OptionGroupName'];
        }
    }

    return $output;
}

function get_token($conn, $user)
{
    $query = "SELECT * FROM tokens WHERE UserID='$user';";
    $result = mysqli_query($conn, $query); 
    $num_rows = mysqli_num_rows($result);

    $output = '';

    if($num_rows > 0)
    {
        while($row = mysqli_fetch_assoc($result))
        {
            $output = $row['Token'];
        }
    }

    return $output;

}

function get_product_options_id($conn, $product)
{
    $query = "SELECT * FROM products INNER JOIN productoptions ON products.ProductID = productoptions.ProductID WHERE products.ProductID='$product';";
    $result = mysqli_query($conn, $query); 
    $num_rows = mysqli_num_rows($result);

    $output = array();

    if($num_rows > 0)
    {
        while($row = mysqli_fetch_assoc($result))
        {
            $inner_array = array();
            $inner_array['option_id'] = $row['OptionID'];
            $inner_array['option_group_id'] = $row['OptionGroupID'];
            $inner_array['option_price'] = $row['OptionPriceIncrement'];

            array_push($output, $inner_array);
        }
    }
    else
    {
        $output['error'] = "No Option";
    }
    return $output;
}

function get_product_options_name($conn, $id)
{
    $query = "SELECT * FROM options WHERE OptionID='$id';";
    $result = mysqli_query($conn, $query); 
    $num_rows = mysqli_num_rows($result);

    $output = "";

    if($num_rows > 0)
    {
        while($row = mysqli_fetch_assoc($result))
        {
            $output = $row["OptionName"];
        }
    }
    else
    {
        $output = "No Option Name";
    }
    echo $output;
}

?>