<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Book Hub</title>
    <link rel="import" href="Categories.php">
</head>
<body> 

    <header>
        <h1>Book Hub</h1>
        <p>Discover Inspiration on Every Page</p>
    </header>
    
    <nav>
        <a href="#" class="active">Home</a>
        <a href="Search.php">Search</a>
        <a href="About.php">About Us</a>
        <a href="#">Contact</a>
    </nav>
    
    <section class="hero">
        <h2>Find Your Next Favorite Book: Embrace the Journey</h2>
        <p>Explore Book Hub, where the latest stories refresh your reading experience and make every book a new adventure.</p>
        <a href="#" class="btn">Shop Now</a>
    </section>

    <br>

    <div class="intro">
        <p>This proposal introduces the development of "Book Hub" an interactive website for people who love to read. The goal of Book Hub is to offer a centralized platform where readers can explore and buy new books. You can explore a wide range of books, discover new releases, and find your next favorite read.</p>
        <p>Book Hub is a dedicated platform for book lovers which will provide a convenient place to explore and purchase a variety of books. It will manage a database containing information about books, authors, and new releases. This data will help suggest books personalized to the reader's taste and keep the book catalog organized for easy browsing.</p>
        <p>Come and explore Book Hub for your next favorite read...</p>
    </div>

    <?php
    // Database credentials
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'book_hub');

    // Attempt to connect to MySQL database
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check connection
    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    } 

    // Query to fetch book details from the database
    $sql = "SELECT * FROM books";
    $result = mysqli_query($link, $sql);

    // Display books fetched from the database
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="book">';
            echo '<img src="' . $row['image'] . '" alt="' . $row['title'] . '">';
            echo '<p>' . $row['title'] . '</p>';
            echo '<p>$' . $row['price'] . '</p>';
            echo '</div>';
        }
    } else {
        echo "No books found.";
    }

    // Close connection
    mysqli_close($link);
    ?>

    <section class="featured">
        <h2>Featured Collections</h2>
        <div class="product1">
            <img src="image1.jpg" alt="Kid's Collection">
            <h3>Kid's Collection</h3>
        </div>
        <div class="product2">
            <img src="image2.jpg" alt="Recipe Books Collection">
            <h3>Recipe Book Collection</h3>
        </div>
    </section>
    <br>
    <footer>
        <p>&copy; 2024 Book Hub. All rights reserved.</p>
    </footer>

</body>
</html>