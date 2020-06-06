<?php
require('db.php');

if(isset($_POST['groupName']))
{
    $groupName = test_input($_POST['groupName']);
    $query = "SELECT * FROM options WHERE OptionGroupID='$groupName';";
    $result = mysqli_query($conn, $query);
    $num_rows = mysqli_num_rows($result);

    $output = "";

    if($num_rows > 0)
    {
        while($row = mysqli_fetch_assoc($result))
        {
            $output .= "
                <option value='". $row['OptionID'] ."'>". $row['OptionName'] ."</option>
            "; 
        }
    }
    else
    {
        $output = "No Data found";
    }

    echo "$output";
}


?>