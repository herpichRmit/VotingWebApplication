<?php

    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        // if form is accessed by a post request, pre-fill values
        $error = $_GET['error'] ?? '';
        $first_name = $_GET['first_name'] ?? '';
        $last_name = $_GET['last_name'] ?? '';
        $address = $_GET['address'] ?? '';
        $state = $_GET['state'] ?? '';
        $suburb = $_GET['suburb'] ?? '';
        $postcode = $_GET['postcode'] ?? '';
        $check = $_GET['check'] ?? '';

        $error_message = "";

        // determine error message
        if ( $error == 'empty' ) {
            // if a field is left blank
            if ($first_name == ''){
                $error_message = "Please enter your first name.";
            } else if ($last_name == ''){
                $error_message = "Please enter your last name.";
            } else if ($address == ''){
                $error_message = "Please enter your address.";
            } else if ($state == ''){
                $error_message = "Please enter your state.";
            } else if ($suburb == ''){
                $error_message = "Please enter your suburb.";
            } else if ($postcode == ''){
                $error_message = "Please enter your postcode.";
            } else if ($check == ''){
                $error_message = "Please confirm that you have not already voted in this election.";
            } 

        } else if ( $error == 'no_match' ) {
            // if a match cannot be found on electoral role
            $error_message = "Voter could not be found. Please ensure your provided details match what has been registered on the electoral role.";

        } 
    }

?>


<html>
    <head>
        <title> Time to Vote - Australian Electoral Commission </title>
        <link rel="stylesheet" media="all" href="./stylesheets/aec.css" /> 
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
        <script src="./assets/random-transition.js" defer></script>
    </head>
    <body>
        <header>
            <h1>Voting Portal </h1>
            <img src="./assets/government-logo" > 
        </header>

        <div class="content">
            <form class=form action="./ballot/index.php" method="post"> 
                <h2>Electoral Role Search</h2>
                <label for="first_name">First name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>"><br><br>
                <label for="last_name">Last name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>"><br><br>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo $address; ?>"><br><br>
                <label for="state">State:</label>
                <select class="state-dropdown" name="state" id="state" value="<?php echo $state; ?>">
                    <option value="VIC">VIC</option>;
                    <option value="SA">SA</option>;
                    <option value="WA">WA</option>;
                    <option value="NT">NT</option>;
                    <option value="NSW">NSW</option>;
                    <option value="QLD">QLD</option>;
                    <option value="TAS">TAS</option>;
                    <option value="ACT">ACT</option>;
                </select><br><br>
                <label for="suburb">Suburb:</label>
                <input type="text" id="suburb" name="suburb" value="<?php echo $suburb; ?>"><br><br>
                <label for="postcode">Postcode:</label>
                <input type="text" id="postcode" name="postcode" value="<?php echo $postcode; ?>"><br><br>

                <div class="row" >
                    <input class="checkbox" type="checkbox" id="check" name="check">
                    <label class="checkbox-label" for="check">&emsp; You agree you have not already voted in this election</label>
                </div>

                <p class="error-msg"><?php echo $error_message ?></p>
                <div class="center-children">
                    <input class="button" type="submit" value="Submit">
                </div>
            </form>
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
