<?php
// Include config file
require_once "config.php";

// Define variables
$books = [];

// Attempt select query execution
$sql = "SELECT * FROM books";
if ($result = mysqli_query($link, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }
        // Free result set
        mysqli_free_result($result);
    } else {
        echo '<div class="alert alert-danger"><em>No books found.</em></div>';
    }
} else {
    echo "Oops! Something went wrong. Please try again later.";
}

// Close connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Books</title>
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
                    <h1 class="mt-5 mb-3">View Books</h1>
                    <?php if (!empty($books)): ?>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Image</th>
                                    <th>Author</th>
                                     
                                    <!-- New column for delete button -->
                                    <td colspan="2">Action</td>
        
                                    
                                </tr>
                               


                            </thead>
                            <tbody>
                                <?php foreach ($books as $book): ?>
                                    <tr>
                                        <td><?php echo $book['title']; ?></td>
                                        <td><?php echo $book['price']; ?></td>
                                        <td><img src="<?php echo $book['image']; ?>" alt="Book Image" style="max-height: 100px;"></td>
                                        <td><?php echo $book['author']; ?></td>
                                        <td>
                                            <a href="delete.php?title=<?php echo urlencode($book['title']); ?>" class="btn btn-danger">Delete</a>
                                </td>
                                <td>
                    <a href="update.php?title=<?php echo urlencode($book['title']); ?>" class="btn btn-danger">Update</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-danger"><em>No books found.</em></div>
                    <?php endif; ?>
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>


