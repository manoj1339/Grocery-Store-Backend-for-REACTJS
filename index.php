<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: *');

require('admin/include/db.php');

$query = "SELECT * FROM products;";
$result = mysqli_query($conn, $query);
$num_rows = mysqli_num_rows($result);

$output = array();
$server_name = 'http://' . $_SERVER['HTTP_HOST'] . '/store_php/admin';

if($num_rows > 0)
{
    while($row = mysqli_fetch_assoc($result))
    {
        $inner_array = array();

        $inner_array['ProductID'] = $row['ProductID'];
        $inner_array['ProductImage'] = $row['ProductImage'];
        $inner_array['ProductName'] = $row['ProductName'];
        $inner_array['ProductCartDesc'] = $row['ProductCartDesc'];
        $inner_array['ProductPrice'] = $row['ProductPrice'];
        $inner_array['ProductWeight'] = $row['ProductWeight'];
        $inner_array['ProductCategory'] = getCategoryName($conn, $row['ProductCategoryID']);
        $inner_array['ProductDiscount'] = $row['ProductDiscount'];
        $inner_array['in_stock'] = $row['in_stock'];
        $inner_array['host'] = $server_name;

        array_push($output, $inner_array);
    }

    $json = json_encode($output, JSON_PRETTY_PRINT);
    echo $json;
}
?>