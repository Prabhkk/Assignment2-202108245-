<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$title = $price = $image = $author = "";
$title_err = $price_err = $image_err = $author_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate title
    $input_title = trim($_POST["title"]);
    if (empty($input_title)) {
        $title_err = "Please enter a title.";
    } else {
        $title = $input_title;
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

    // Validate and handle file upload
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $temp_name = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        
        // Check if the uploaded file is an image
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
        if(in_array(strtolower($image_extension), $allowed_extensions)) {
            // Move the uploaded file to desired directory
            $upload_directory = "uploads/"; // Directory where you want to store uploaded images
            $image_path = $upload_directory . uniqid() . '.' . $image_extension;
            move_uploaded_file($temp_name, $image_path);
            $image = $image_path;
        } else {
            $image_err = "Please upload a valid image file.";
        }
    } else {
        $image_err = "Please select an image.";
    }

    // Validate author
    $input_author = trim($_POST["author"]);
    if (empty($input_author)) {
        $author_err = "Please enter an author.";
    } else {
        $author = $input_author;
    }

    // Check input errors before inserting in database
    if (empty($title_err) && empty($price_err) && empty($image_err) && empty($author_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO books (title, price, image, author) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sdss", $param_title, $param_price, $param_image, $param_author);

            // Set parameters
            $param_title = $title;
            $param_price = $price;
            $param_image = $image;
            $param_author = $author;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                    <h2 class="mt-5">Add New Book</h2>
                    <p>Please fill this form and submit to add a new book to the catalog.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                            <span class="invalid-feedback"><?php echo $title_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" name="price" class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $price; ?>">
                            <span class="invalid-feedback"><?php echo $price_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $image_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Author</label>
                            <input type="text" name="author" class="form-control <?php echo (!empty($author_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $author; ?>">
                            <span class="invalid-feedback"><?php echo $author_err; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
