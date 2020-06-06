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
        <title>Attach options to product</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
        <style type="text/css">
        #options-modal
        {
            display: none;
            position: absolute;
            width: 100%;
            height: 100%;
            background: #000;
            top: 0;
            left: 0;
            z-index: 100;
            justify-content: center;
            align-items: center;
            transition: all 0.3s;
        }

        #options-modal.show
        {
            display: flex;
        }

        #options-modal > div
        {
            width: 450px;
            padding: 15px 10px;
            background: #fff;
            border: 10px;
            position: relative;
        }

        #closeBtn
        {
            position: absolute;
            top: 0px;
            right: 15px;
            font-size: 25px;
            font-weight: bold;
            cursor: pointer;
        }

        #pname
        {
            color: gray;
        }
        </style>
    </head>
    <body class="sb-nav-fixed">

        <?php include_once "include/header.php" ?>
        <div id="layoutSidenav_content">
            <div class="container" style="position:relative;">
                <div class="card mb-4">
                    <div class="card-header"><i class="fas fa-table mr-1"></i>Attach options to product</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Desc</th>
                                        <th>Price</th>
                                        <th>Weight</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Desc</th>
                                        <th>Price</th>
                                        <th>Weight</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                        $query = "SELECT * FROM products;";
                                        $result = mysqli_query($conn, $query);
                                        $num_rows = mysqli_num_rows($result);

                                        $output = "";

                                        if($num_rows > 0)
                                        {
                                            while($row = mysqli_fetch_assoc($result))
                                            {
                                                $output .= "
                                                <tr>
                                                    <td><img src='". $row['ProductImage'] ."' style='height: 50px;width: 50px;' /></td>
                                                    <td>". $row['ProductName'] ."</td>
                                                    <td>". $row['ProductCartDesc'] ."</td>
                                                    <td>Rs.". $row['ProductPrice'] ."</td>
                                                    <td>". $row['ProductWeight'] ." grams</td>
                                                    <td><button class='btn btn-primary open' data-id='". $row['ProductID'] ."' data-name='". $row['ProductName'] ."'>Attach options</button></td>
                                                </tr>
                                                "; 
                                            }
                                        }
                                        else{
                                            $output = "No Data found";
                                        }

                                        echo "$output";
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php include_once "include/footer.php"; ?>

                <div id="options-modal">
                    <div>
                        <div id='closeBtn'>&times;</div>
                        <h5 style="text-align:center;">Attach options</h5>
                        <p id="pname"></p>
                        <form method="post" action="" id="optionForm">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="productOptionGroup">Product Option Group</label>
                                        <select class="form-control" id="productOptionGroup">
                                        <option value=''>Select Group</option>
                                        <?php
                                            $q = "SELECT * FROM optiongroups;";
                                            $r = mysqli_query($conn, $q);
                                            $n_r = mysqli_num_rows($r);


                                            if($n_r > 0)
                                            {
                                                while($row = mysqli_fetch_assoc($r))
                                                {
                                                    echo "
                                                    <option value='". $row['OptionGroupID'] ."'>" . $row['OptionGroupName'] . "</option>
                                                    ";
                                                }
                                            } 
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="productOptionName">Product Option Name</label>
                                        <select class="form-control" id="productOptionName">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="small mb-1" for="productOptionPrice">Product Option Price</label>
                                        <input type="number" class="form-control" id="productOptionPrice" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-4 mb-0">
                                <input type="hidden" id="pnameInput" />
                                <input type="submit" name="submit" class="btn btn-primary btn-block" value="Attach Option" />
                            </div>
                        </form>
                        <div style="text-align:center; margin-top: 8px;" id="errorDiv">
                        </div>
                    </div>
                </div>
            </div>


        </div>
        
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
        <script>

        $(document).ready(function(){
            $('.open').click(function(e){
                $('#options-modal').addClass('show');
                $('#pname').html($(this).data('name'));
                $('#pnameInput').val($(this).data('id'));
            });

            /* onchange listener for select dropdown */
            $('#productOptionGroup').on('change', function(){
                var val = $(this).val();

                $.ajax({
                    method: 'post',
                    url: 'include/option_dropdown.php',
                    data: {
                        groupName: val
                    },
                    success: function(data)
                    {
                        $('#productOptionName').html(data);
                    }
                });
            });

            $('#closeBtn').on('click', function(){
                $('#options-modal').removeClass('show');
            });

            $('#optionForm').on('submit', function(e){
                e.preventDefault();

                var productOptionGroup = $('#productOptionGroup').val();
                var productOptionName = $('#productOptionName').val();
                var productOptionPrice = $('#productOptionPrice').val();
                var pnameInput = $('#pnameInput').val();

                $.ajax({
                    method: 'post',
                    url: 'include/attachOptionsToProduct.php',
                    data: {
                        productId : pnameInput,
                        productOptionGroup: productOptionGroup,
                        productOptionName: productOptionName,
                        productOptionPrice: productOptionPrice
                    },
                    success: function(data){
                        $('#errorDiv').html(data);
                    }
                })
            });

        });//Ready ends here
 
        </script>
    </body>
</html>

