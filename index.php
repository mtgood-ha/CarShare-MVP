<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="styleV.css">
</head>
<body>
    <header>
        <div class="brand-container">
            <img src="CarShareLogo.png" width="200">
            <h1 class="h1CARSHARE">CarShare</h1>
        </div>
        <div class ="navONTOP">
            <a href="LoginPage.html">Log In</a>
            <a href="RegisterPage.html">Register</a>
            <a href="AboutUs.php">About Us</a>
        </div>
    </header>
    
    <main>
        <section class="info-section">
            <h2>What is CarShare?</h2>
            <div class="textBorder centerText textBoxStyle">
                CarShare is a sustainable ride-sharing web app project made by APU students for APU students.
                This project was made to address the lack of affordable and accessible transportation for
                APU students. The expected result of this project is that students of APU would save money, 
                time, and petrol while simultaneously saving the environment by reducing their carbon footprint. 
                As a result, there would be less traffic around the APU campus, a reduction in harmful gases 
                released into the environment, and a reduction in overall fuel consumption. 
                <br><br>
                What are you waiting for? Log in or Register NOW!
            </div>
        </section>

        <section class="buttonDisplay buttonColumn">
            <button style="width: 200px;" class="baseBtn whiteBtn" id="btnRewards" onclick="window.location.href='ViewRewardSystem.php';">View Rewards</button>
            <button style="width: 200px;" class="baseBtn whiteBtn" id="btnEvents" onclick="window.location.href='EventPlanning.php';">View Events</button>
        </section>
    </main>
    
    <footer>
        <div class="copyright">
            <p>&copy; 2026 CarShare. All Rights Reserved.</p>
        </div>
    </footer> 

</body>
</html>