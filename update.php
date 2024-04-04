<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$title = $author = $price = $image = "";
$title_err = $author_err = $price_err = $image_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate title
    $input_title = trim($_POST["title"]);
    if (empty($input_title)) {
        $title_err = "Please enter a title.";
    } else {
        $title = $input_title;
    }

    // Validate author
    $input_author = trim($_POST["author"]);
    if (empty($input_author)) {
        $author_err = "Please enter an author.";
    } else {
        $author = $input_author;
    }

    // Validate price
    $input_price = trim($_POST["price"]);
    if (empty($input_price)) {
        $price_err = "Please enter the price.";
    } elseif (!is_numeric($input_price) || $input_price <= 0) {
        $price_err = "Please enter a valid price.";
    } else {
        $price = $input_price;
    }

    // Check if image file is selected
    if (!empty($_FILES["image"]["name"])) {
        $targetDir = "uploads/";
        $targetFilePath = $targetDir . basename($_FILES["image"]["name"]);
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowedTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (!in_array($fileType, $allowedTypes)) {
            $image_err = "Sorry, only JPG, JPEG, PNG, GIF files are allowed.";
        } else {
            // Upload file to server
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                $image = $targetFilePath;
            } else {
                $image_err = "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Check input errors before updating the database
    if (empty($title_err) && empty($author_err) && empty($price_err) && empty($image_err)) {
        // Prepare an update statement
        $sql = "UPDATE books SET author=?, price=?, image=? WHERE title=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sdss", $param_author, $param_price, $param_image, $param_title);

            // Set parameters
            $param_author = $author;
            $param_price = $price;
            $param_image = $image;
            $param_title = $title;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
                header("location: read.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
} else {
    // Check existence of title parameter before processing further
    if (isset($_GET["title"]) && !empty(trim($_GET["title"]))) {
        // Get URL parameter
        $title =  trim($_GET["title"]);

        // Prepare a select statement
        $sql = "SELECT * FROM books WHERE title = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_title);

            // Set parameters
            $param_title = $title;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    // Fetch result row as an associative array
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $author = $row["author"];
                    $price = $row["price"];
                } else {
                    // URL doesn't contain valid title. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    } else {
        // URL doesn't contain title parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the book record.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                            <span class="invalid-feedback"><?php echo $title_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Author</label>
                            <input type="text" name="author" class="form-control <?php echo (!empty($author_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $author; ?>">
                            <span class="invalid-feedback"><?php echo $author_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" name="price" class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $price; ?>">
                            <span class="invalid-feedback"><?php echo $price_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control-file <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>" accept="image/*">
                            <span class="invalid-feedback"><?php echo $image_err; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="read.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
