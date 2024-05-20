<?php

    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        $complete = $_GET['complete'] ?? '';
    }
    
?>


<html>
    <head>
        <title> Time to Vote - Australian Electoral Commission </title>
        <link rel="stylesheet" media="all" href="../stylesheets/aec.css" /> 
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
        <script src="../assets/random-transition.js" defer></script>
    </head>
    <body>
        <header>
            <h1>Voting Portal </h1>
            <img src="../assets/government-logo" > 
        </header>


        <div class="content">
            <div class="dismissal">
                <?php 
                    if ($complete === 'y') {
                        echo '<h1>&#10003;</h1>';
                        echo '<h2>Thanks for voting</h2>';
                        echo '<p>Your voice will help shape Australia.</p>';
                    } else { //if ($complete === 'n')
                        echo '<h1>&#10008;</h1>';
                        echo '<h2>Seems like you have already voted</h2>';
                        echo '<p>Voter fraud is a federal offence.</p>';
                    }
                ?>
                
            </div>
        </div>

        <div class="dot-grid">
            <?php
                for ($x = 0; $x < 26; $x++){
                    echo '<div class="dot-column">';
                        for ($y = 0; $y < 10; $y++){
                            echo '<div class="dot-box">';
                                echo '<p class="dot"> &#9679; </p>';
                            echo '</div>';
                        }
                    echo '</div>';
                }
            ?>
        </div>

        <footer>
            &copy; <?php echo date('Y'); ?> Australian Electoral Commission
        </footer>
    </body>
</html>
