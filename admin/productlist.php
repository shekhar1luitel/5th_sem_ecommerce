<?php
session_start();
include("../db.php");
error_reporting(0);

if (isset($_GET['action']) && $_GET['action'] != "" && $_GET['action'] == 'delete') {
    $product_id = $_GET['product_id'];

    $result = mysqli_query($con, "select product_image from products where product_id='$product_id'") or die("Query is incorrect...");
    list($picture) = mysqli_fetch_array($result);
    $path = "../product_images/$picture";

    if (file_exists($path) == true) {
        unlink($path);
    } else {
    }

    // Delete query
    $delete_query = "DELETE FROM products WHERE product_id='$product_id'";
    $delete_result = mysqli_query($con, $delete_query);

    if ($delete_result) {
        echo "<script>alert('Product Deleted Successfully');</script>";
    }
}

// Pagination
$page = $_GET['page'];

if ($page == "" || $page == "1") {
    $page1 = 0;
} else {
    $page1 = ($page * 10) - 10;
}

include "sidenav.php";
include "topheader.php";
?>
<!-- End Navbar -->
<div class="content">
    <div class="container-fluid">
        <div class="col-md-14">
            <div class="card ">
                <div class="card-header card-header-primary">
                    <h4 class="card-title"> Products List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive ps">
                        <table class="table tablesorter " id="page1">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th><a class="btn btn-primary" href="addproduct.php">Add New</a></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = mysqli_query($con, "SELECT product_id, product_image, product_title, product_price FROM products WHERE product_cat=2 OR product_cat=3 OR product_cat=4 LIMIT $page1,12") or die("Query incorrect.....");
                                while (list($product_id, $image, $product_name, $price) = mysqli_fetch_array($result)) {
                                    echo "<tr>
                                    <td><img src='../product_images/$image' style='width:50px; height:50px; border:groove #000'></td>
                                    <td>$product_name</td>
                                    <td>$price</td>
                                    <td>
                                        <a class='btn btn-success' href='productlist.php?action=delete&product_id=$product_id' onclick=\"return confirm('Are you sure you want to delete this product?');\">Delete</a>
                                    </td>
                                </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                        <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div>
                    </div>
                </div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                        <?php 
                        $paging = mysqli_query($con, "SELECT product_id, product_image, product_title, product_price FROM products");
                        $count = mysqli_num_rows($paging);
                        $a = $count / 10;
                        $a = ceil($a);
                        for ($b = 1; $b <= $a; $b++) {
                            echo "<li class='page-item'><a class='page-link' href='productlist.php?page=$b'>$b</a></li>";
                        }
                        ?>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
