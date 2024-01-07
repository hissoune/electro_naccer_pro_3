<?php
session_start(); // Start the session
include 'database.php';
include 'classes.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <link rel="icon" href="img/electric.png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="font-sans bg-gray-100">

    <?php include("navbar.php") ?>

    <section class="container mx-auto py-10">
        <div class="flex flex-col md:flex-row gap-5">
            <div id="hah" class="md:w-1/2">
                <h6 class="text-xl text-gray-600">#1 Store in Morocco</h6>
                <h5 class="text-2xl">Mar7ba bljami3</h5>
                <h1 class="text-5xl font-bold mb-4">ElectroNacer</h1>
                <p class="text-gray-700">Welcome to Electronacer, your destination for cutting-edge electronics!
                    Discover a curated selection
                    of top-tier gadgets and accessories that redefine innovation. Elevate your lifestyle with premium
                    electronic essentials.</p>
                <div class="mt-4">
                    <a class="btn btn-outline-light rounded-5" href="products.php">Products</a>
                </div>
            </div>
            <div class="md:w-1/2">
                <img class="pss" src="img/tlata.png" alt="">
            </div>
        </div>
        <div class="mt-8">
            <a class="wt" href="#upp"></a>
        </div>
    </section>

    <?php
    $query = "SELECT imag_category FROM Categories";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    ?>

    <section class="py-10">
        <div class="text-center pb-5">
            <h1 class="text-6xl font-bold">Categories</h1>
        </div>
        <div class="flex flex-wrap justify-around gap-5">
            <?php
            while ($row = $stmt->fetch()) {
                $imagePath = $row['imag_category'];
                echo '<img class="opa" width="350" src="img/' . $imagePath . '" alt="oui">';
            }
            ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center text-white bg-indigo-800">
        <div class="container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4 pb-0">
            <div>
                <h6 class="text-uppercase mb-4 font-semibold">ElectroNacer</h6>
                <p>Here you can use rows and columns to organize your footer content. Lorem ipsum dolor sit amet,
                    consectetur adipisicing elit.</p>
            </div>
            <!-- Repeat the above structure for other sections -->
        </div>
    </footer>

</body>

</html>