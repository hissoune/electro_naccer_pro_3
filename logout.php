<?php
session_start();

// Destroy the session
session_destroy();

// Redirect to the home page or any other desired page
header("Location: products.php");
exit();
