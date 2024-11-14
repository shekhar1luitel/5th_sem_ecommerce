<?php
session_start();
include("../db.php");

// Display errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the action is to delete an order
if(isset($_GET['action']) && $_GET['action'] === 'delete') {
    // Check if 'user_id' key is set in the $_GET array
    if(isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];

        // Check if the 'user_id' exists in the database
        $result = mysqli_query($con, "SELECT * FROM orders_info WHERE user_id='$user_id'");
        
        if(mysqli_num_rows($result) > 0) {
            // Delete query
            mysqli_query($con, "DELETE FROM orders_info WHERE user_id='$user_id'") or die("Delete query is incorrect...");
            echo "Orders for user_id=$user_id deleted successfully.";
        } else {
            echo "Error: No orders found for user_id=$user_id in the database.";
        }
    } else {
        // Handle the case when 'user_id' is not set
        echo "Error: 'user_id' is not set. GET array: ";
        print_r($_GET);
    }
}

// Pagination logic
// Check if 'page' key is set in the $_GET array
$page = isset($_GET['page']) ? $_GET['page'] : 1;

if($page == "" || $page == "1") {
    $page1 = 0;    
} else {
    $page1 = ($page * 10) - 10;    
}

include "sidenav.php";
include "topheader.php";
?>
<!-- Rest of your HTML structure -->
<div class="content">
    <div class="container-fluid">
        <!-- Content section -->
        <div class="col-md-14">
            <div class="card">
                <!-- Card header -->
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Orders / Page <?php echo $page; ?> </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive ps">
                        <table class="table table-hover tablesorter" id="">
                            <thead class="text-primary">
                                <tr>
                                    <!-- Table headers -->
                                    <th>Customer Name</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Zip Code</th>
                                    <th>Product Count</th>
                                    <th>Total Amount</th>
                                    <!-- Add 'Delete' column header -->
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $result = mysqli_query($con, "SELECT orders_info.f_name, orders_info.email, orders_info.address, orders_info.city, orders_info.state, orders_info.zip, orders_info.prod_count, orders_info.total_amt, orders_info.user_id
                                FROM orders_info
                                LIMIT $page1, 10") or die("Query error...");

                                while($row = mysqli_fetch_assoc($result)) {
                                    // Displaying fetched data in table rows
                                    echo "<tr>
                                            <td>{$row['f_name']}</td>
                                            <td>{$row['email']}</td>
                                            <td>{$row['address']}</td>
                                            <td>{$row['city']}</td>
                                            <td>{$row['state']}</td>
                                            <td>{$row['zip']}</td>
                                            <td>{$row['prod_count']}</td>
                                            <td>{$row['total_amt']}</td>
                                            <td><a class='btn btn-danger' href='orders.php?user_id={$row['user_id']}&action=delete'>Delete</a></td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer -->
<?php include "footer.php"; ?>
