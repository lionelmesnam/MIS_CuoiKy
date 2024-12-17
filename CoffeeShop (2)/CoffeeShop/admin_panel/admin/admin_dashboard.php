<?php session_start();

if (!isset($_SESSION['admin_id'])) {
    header("location:../index.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Admin</title>
        
    <!-- Bootstrap css file -->
    <link rel="stylesheet" href="../../plugins/bootstrap-5.1.3/css/bootstrap.min.css">

    <!--  Iconify SVG framework link -->
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/custom.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/top_navbar.css">
    <link rel="stylesheet" href="../css/tabbed_box.css">

    <!--google material icon-->
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php
include_once('../../includes/db_connect.php');

if(isset($_POST["update_info"])) { 
    $del = trim($_POST['del']);
    $query ="UPDATE delivery set delivery_cost=$del WHERE delivery_id=1";
    mysqli_query($conn,$query);
    header("Refresh: 0;");
}

if(isset($_POST["update_cinfo"])) { 
    $price = trim($_POST['cp']);
    $id = $_POST['hidden_id'];
    $query ="UPDATE currency set  convert_price='".$price."' WHERE currency_id=$id";
    mysqli_query($conn,$query);
    header("Refresh: 0;");
}

if(isset($_POST["update_cinfo2"])) { 
    $to = trim($_POST['to']);
    $price = trim($_POST['cp']);
    $id = $_POST['hidden_id'];
    
    $query = "INSERT INTO `currency` (`from_currency`,`to_currency`, `convert_price`) 
            VALUES ('$','$to','$price')";
    mysqli_query($conn,$query);
    header("Refresh: 0;");
}
?>

<div class="wrapper">
    <div class="body-overlay"></div>

    <?php 
    // sidebar
    include('../includes/admin_sidebar.php'); ?>

    <div id="content">
    
    <?php 
        $section="Dashboard";
        include('../includes/top_navbar.php'); ?>
    
        <div class="main-content">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <?php
                    $result = mysqli_query($conn,"SELECT count(*) as nb FROM review_table");
                    $row = mysqli_fetch_assoc($result);
                    ?>
                    <div class="card card-stats">
                        <div class="card-header">
                            <div class="icon icon-warning">
                               <span class="material-icons">equalizer</span>
                            </div>
                        </div>
                        <div class="card-content">
                            <p class="category"><strong>Reviews</strong></p>
                            <h3 class="card-title"><?php echo $row['nb'] ?></h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="material-icons text-info">info</i>
                                <a href="view_reviews.php">See detailed report</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <?php
                    $result = mysqli_query($conn,"SELECT count(*) as nb FROM orderinfo");
                    $row = mysqli_fetch_assoc($result);
                    ?>
                    <div class="card card-stats">
                        <div class="card-header">
                            <div class="icon icon-rose">
                               <span class="material-icons">shopping_cart</span>
                            </div>
                        </div>
                        <div class="card-content">
                            <p class="category"><strong>Orders</strong></p>
                            <h3 class="card-title"><?php echo $row['nb'] ?></h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="material-icons">local_offer</i>
                                <a class="viewOrder" href="view_orders.php" id="orders">View</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <?php
                    $result = mysqli_query($conn,"SELECT SUM(order_total) as nb FROM orderinfo");
                    $row = mysqli_fetch_assoc($result);
                    ?>
                    <div class="card card-stats">
                        <div class="card-header">
                            <div class="icon icon-success">
                                <span class="material-icons">attach_money</span>
                            </div>
                        </div>
                        <div class="card-content">
                            <p class="category"><strong>Revenue</strong></p>
                            <h3 class="card-title">$<?php echo $row['nb'] ?></h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="material-icons">date_range</i> Weekly sales
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <?php
                    $result = mysqli_query($conn,"SELECT count(distinct customer_name) as nb FROM orderinfo;");
                    $row = mysqli_fetch_assoc($result);
                    ?>
                    <div class="card card-stats">
                        <div class="card-header">
                            <div class="icon icon-info">
                                <span class="material-icons">follow_the_signs</span>
                            </div>
                        </div>
                        <div class="card-content">
                            <p class="category"><strong>Costumers</strong></p>
                            <h3 class="card-title">+<?php echo $row['nb'] ?></h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="material-icons">update</i> Just Updated
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Charts Section -->
            <div class="row mt-4">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Monthly Revenue Statistics</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="monthlyRevenueChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Revenue Forecast</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueForecastChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <hr />
            <h4>Delivery</h4>
            <hr />

            <div class="delivery_infos">
                <?php
                $result = mysqli_query($conn,"SELECT delivery_cost FROM delivery");
                $row = mysqli_fetch_assoc($result);
                ?>
                <label class="col-sm-3 col-md-4 control-label">Delivery cost: <span id="cost"><?php echo $row['delivery_cost']; ?> </span>$ </label>     
                <input type="button" id="delivery" value="change delivery cost" />
            </div>

            <hr />
            <h4>Currency (from $)</h4>
            <hr />

            <input type="button" id="curr" value="add new currency" />

            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-content table-responsive">
                            <table class="table table-hover">
                                <thead class="text-primary">
                                    <tr>
                                        <th>to currency</th>
                                        <th>convert </th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = mysqli_query($conn,"SELECT * FROM currency"); 
                                    while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td id="to_<?php echo $row['currency_id'] ?>"><?php echo $row['to_currency']; ?></td>
                                            <td id="price_<?php echo $row['currency_id'] ?>"><?php echo $row['convert_price']; ?></td>
                                            <td>
                                                <button class="update_currency" id="<?php echo $row['currency_id'] ?>">
                                                    <span class="iconify" data-icon="bx:edit" style="color: green;" data-width="30" data-height="30"></span>
                                                </button>
                                            </td>
                                            <td>
                                                <button class="delete_currency" id="<?php echo $row['currency_id'] ?>">
                                                    <span class="iconify" data-icon="fluent:delete-24-filled" style="color: red;" data-width="30" data-height="30"></span>
                                                </button>
                                            </td>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals remain unchanged -->
<!-- ... (keep all your existing modals) ... -->

<script src="js/popper.min.js"></script>
<script src="../../plugins/bootstrap-5.1.3/js/bootstrap.min.js"></script>
<script src="../../plugins/jquery-3.6.0/jquery.min.js"></script>
<script src="../../plugins/sweetalert2/sweetalert2.js"></script>
<script src="../js/script.js"></script>
<script src="../js/update_delete.js"></script>

<script>
// Get monthly revenue data from PHP/MySQL
<?php
$monthlyRevenueQuery = mysqli_query($conn, "
    SELECT 
        DATE_FORMAT(order_date, '%Y-%m') as month,
        SUM(order_total) as revenue
    FROM orderinfo 
    GROUP BY DATE_FORMAT(order_date, '%Y-%m')
    ORDER BY month DESC
    LIMIT 12
");

$months = [];
$revenues = [];

while ($row = mysqli_fetch_assoc($monthlyRevenueQuery)) {
    $months[] = date('F Y', strtotime($row['month']));
    $revenues[] = $row['revenue'];
}
?>

// Monthly Revenue Chart
const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
new Chart(monthlyRevenueCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_reverse($months)); ?>,
        datasets: [{
            label: 'Monthly Revenue',
            data: <?php echo json_encode(array_reverse($revenues)); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value;
                    }
                }
            }
        }
    }
});

// Revenue Forecast Chart
const forecastCtx = document.getElementById('revenueForecastChart').getContext('2d');

// Calculate simple forecast based on last 3 months trend
<?php
$lastThreeMonths = array_slice($revenues, -3);
$avgGrowth = 0;
if (count($lastThreeMonths) >= 2) {
    for ($i = 1; $i < count($lastThreeMonths); $i++) {
        $avgGrowth += ($lastThreeMonths[$i] - $lastThreeMonths[$i-1]) / $lastThreeMonths[$i-1];
    }
    $avgGrowth = $avgGrowth / (count($lastThreeMonths) - 1);
}

$lastRevenue = end($revenues);
$forecast = [];
$forecastMonths = [];

for ($i = 1; $i <= 6; $i++) {
    $predictedRevenue = $lastRevenue * (1 + $avgGrowth);
    $forecast[] = round($predictedRevenue, 2);
    $lastRevenue = $predictedRevenue;
    $forecastMonths[] = date('F Y', strtotime("+$i month"));
}
?>

new Chart(forecastCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($forecastMonths); ?>,
        datasets: [{
            label: 'Revenue Forecast',
            data: <?php echo json_encode($forecast); ?>,
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 2,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value;
                    }
                }
            }
        }
    }
});
</script>

</body>
</html>