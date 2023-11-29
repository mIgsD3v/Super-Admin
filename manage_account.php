<?php
session_start();
error_reporting(0);

if (!isset($_SESSION['UserLogin'])) {
    header("location: login.php");
    exit();
}

include_once("connections/connection.php");
$conn = connection();

// FOR PASSWORD CONFIRMATION BEFORE DELETE
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $password = $_POST['password'];

    // Fetch user data from the database based on ID
    $sql_fetch = "SELECT * FROM user_list WHERE id = '$delete_id'";
    $result_fetch = $conn->query($sql_fetch);

    if ($result_fetch->num_rows > 0) {
        $row = $result_fetch->fetch_assoc();

        // Check if the entered password matches the stored password (without hashing)
        if ($password === $row['password']) {
            // Password is correct, proceed with deletion
            $sql_delete = "DELETE FROM user_list WHERE id = '$delete_id'";
            if ($conn->query($sql_delete) === TRUE) {
                // Deletion successful
                echo '<script>
                    setTimeout(function(){
                        Swal.fire({
                            title: "Success!",
                            text: "User deleted successfully",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "manage_account.php";
                            }
                        });
                    }, 500);
                </script>';
            } else {
                // Deletion failed
                echo '<script>
                    Swal.fire({
                        title: "Error!",
                        text: "Operation failed. Please try again.",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                </script>';
            }
        } else {
            // Password is incorrect
            echo '<script>
                setTimeout(function(){
                    Swal.fire({
                        title: "Incorrect Password",
                        text: "The password you entered is incorrect. Please try again.",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }, 500);
            </script>';
        }
    }
}



$sql = "SELECT * FROM user_list";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="This is a Philippine Cancer Center HR Management System">
    <meta name="keywords" content="PCC-HRMS, HRMS, Human Resource, Capstone, System, HR">
    <meta name="author" content="Heionim">
    <meta name="robots" content="noindex, nofollow">
    <title>PCC HRMS</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/pcc-logo.svg">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">

    <!-- Lineawesome CSS -->
    <link rel="stylesheet" href="assets/css/line-awesome.min.css">

    <!-- Datatable CSS -->
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="assets/css/select2.min.css">

    <!-- Datetimepicker CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #D4DEDB;
        }

        .body-container {
            background-color: #FAFAFA;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        table {
            text-align: center;
            border: 1px solid #285D4D;
        }

        .page-title {
            font-size: 1.3rem;
            color: #204A3D;
        }

        .btn-blue {
            background-color: #0D6EFD;
        }

        .search-container {
            position: relative;
        }

        .search-input {
            border: none;
            border-radius: 5px;
            width: 100%;
            border: 1px solid #9E9E9E;
            margin-bottom: 20px;
        }

        .search-input:focus {
            outline: none;
        }

        .search-container i {
            position: absolute;
            left: 15px;
            top: 45%;
            transform: translateY(-50%);
            color: #888;
        }

        .add-btn {
            border-radius: 5px;
            padding: 8px 2rem;
        }
    </style>
</head>

<body>
    <div class="main-wrapper">

        <?php include_once("includes/header.php"); ?>
        <?php include_once("includes/sidebar.php"); ?>

        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="body-container">

                    <!-- HEADER -->
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Manage Admin Accounts</h3>
                            </div>
                        </div>
                    </div>

                    <!-- SEARCH -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="search-container">
                                <i class="fa fa-search"></i>
                                <input type="text" class="form-control pl-5 search-input" placeholder="Search">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <!-- EMPTY SPACE -->
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-auto ml-auto m-right">
                                    <a href="add_admin.php" class="btn add-btn">
                                        <i class="fa fa-plus"></i> Add Admin
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TABLE -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped custom-table datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employee ID</th>
                                            <th>Name</th>
                                            <th>Department</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <th class="no-sort">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            $rowNumber = 1; // Initialize the row number

                                            while ($row = $result->fetch_assoc()) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $rowNumber; ?></td>
                                                    <td><?php echo $row['empID']; ?></td>
                                                    <td><?php echo $row['firstname']; ?></td>
                                                    <td><?php echo $row['department']; ?></td>
                                                    <td><?php echo $row['email']; ?></td>
                                                    <td><?php echo $row['contact']; ?></td>
                                                    <td>
                                                        <a href="details.php?ID=<?php echo $row['id'] ?>" title="View" class="btn text-xs text-white btn-secondary action-icon"><i class="fa fa-eye"></i></a>
                                                        <a href="edit_admin.php?ID=<?php echo $row['id'] ?>" title="Edit" class="btn text-xs text-white btn-blue action-icon"><i class="fa fa-pencil"></i></a>

                                                        <!-- Delete button with a password confirmation -->
                                                        <button type="button" class="btn text-xs text-white btn-danger action-icon" data-toggle="modal" data-target="#confirmDelete<?php echo $row['id']; ?>"><i class="fa fa-trash-o"></i></button>

                                                        <!-- Modal for delete confirmation -->
                                                        <div class="modal custom-modal fade" id="confirmDelete<?php echo $row['id']; ?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Password Confirmation</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p class="text-left">Please enter your password to confirm deletion.</p>
                                                                        <form method="post" action="">
                                                                            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                                                            <div class="form-group">
                                                                                <input type="password" class="form-control" name="password" required>
                                                                            </div>
                                                                            <div class="text-right">
                                                                                <button type="submit" class="btn btn-primary">Delete</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </td>
                                                </tr>
                                        <?php
                                                // Increment the row number for the next iteration
                                                $rowNumber++;
                                            }
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include_once 'includes/modals/admin/delete_admin.php'; ?>

        </div>
    </div>


    <!-- jQuery -->
    <script src=" assets/js/jquery-3.2.1.min.js"></script>

    <!-- Bootstrap Core JS -->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Slimscroll JS -->
    <script src="assets/js/jquery.slimscroll.min.js"></script>

    <!-- Select2 JS -->
    <script src="assets/js/select2.min.js"></script>

    <!-- Datetimepicker JS -->
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>

    <!-- Datatable JS -->
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap4.min.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/app.js"></script>
</body>

</html>