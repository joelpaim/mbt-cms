<?php
include '../config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = $_POST["title"];
    $content = $_POST["content"];
    
    // Upload image
    $image_path = "uploads/" . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
        // Prepare and bind the INSERT statement
        $sql = "INSERT INTO `blog-post` (title, date, picture, post) VALUES (?, NOW(), ?, ?);"; // Ensure table name is correct
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            // SQL preparation failed, debug information
            echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        } else {
            $stmt->bind_param("sss", $title, $image_path, $content);
            
            // Execute the statement
            if ($stmt->execute()) {
                echo "Blog post created successfully";
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        }
    } else {
        echo "Failed to upload image.";
    }

    // Close database connection
    $conn->close();
}
?>
