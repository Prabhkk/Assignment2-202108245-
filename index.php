<?php require_once "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Books Catalog</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="my-4">Books Catalog</h2>
        <!-- Add button to navigate to the page for adding books -->
        <a href="create.php" class="btn btn-primary mb-3">Add Book</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Author</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query to fetch books data
                $sql = "SELECT * FROM books";
                $result = mysqli_query($link, $sql);

                // Display books data
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['title'] . "</td>";
                        echo "<td>$" . $row['price'] . "</td>";
                        echo "<td><img src='" . $row['image'] . "' alt='" . $row['title'] . "' style='max-width: 100px;'></td>";
                        echo "<td>" . $row['author'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No books found</td></tr>";
                }

                // Close connection
                mysqli_close($link);
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>