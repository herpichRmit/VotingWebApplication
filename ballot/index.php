<?php

    # 1. Redirect users who have tried to access within completing form
    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        header("Location: ../index.php");
        exit;
    }

    # 2. Setup database connection
    include('../database/query_functions.php');
    $db = oci_connect("s3935413", "Whiteairforce1s!", "talsprddb01.int.its.rmit.edu.au/CSAMPR1.ITS.RMIT.EDU.AU");

    if(!$db) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);

    } else {
        //echo "<p>Successfully connected to CSAMPR1.ITS.RMIT.EDU.AU.</p>";
        
        # 3. Get variables from post request
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // if a post request occurs, load varaibles from post request
            $first_name = $_POST['first_name'] ?? '';
            $last_name = $_POST['last_name'] ?? '';
            $address = $_POST['address'] ?? '';
            $suburb = $_POST['suburb'] ?? '';
            $state = $_POST['state'] ?? '';
            $postcode = $_POST['postcode'] ?? '';
            $check = $_POST['check'] ?? '';

            function test() {
                return false;
            }

            # 4. Validation checks
            if (empty($first_name) || empty($last_name) || empty($address) || empty($state) || empty($suburb) || empty($postcode) || empty($check)) {
                // check if any values are empty
                header("Location: ../index.php?error=empty&first_name=$first_name&last_name=$last_name&address=$address&state=$state&suburb=$suburb&postcode=$postcode&check=$check");
                exit;
            }
                
            if (check_voter_details($first_name, $last_name, $address) === false) {
                // check name and address match electoral register
                header("Location: ../index.php?error=no_match");
                exit;
            }

            if (check_voted_before($first_name, $last_name, $address) === true) {
                // check if they have previously voted
                header("Location: ./dismiss.php?complete=n");
                exit;
            }

            # 5. Get extra information needed to format the page
            // now that we know the voter is correct, lets get their electorate
            $electorate_name = find_voter_electorate_by_details($first_name, $last_name, $address);

            // and their DOB
            $voter_DOB = find_voter_DOB_by_details($first_name, $last_name, $address);

            // find number of candidates
            $num_candidates = find_number_of_candidates_by_electorate($electorate_name);

            // then lets get all the candidates for their electorate
            $stid = find_candidates_by_electorate($electorate_name);

        }
        
        oci_close($db);
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
            <form action="./create-pref.php" method="post"> 
                <h2>Electoral Division of <?php echo $electorate_name ?> </h2>
                <p>Hello, <?php echo $first_name . " " . $last_name ?> </p>
                <p>Please order your preferences from 1 to <?php echo $num_candidates ?> in the boxes below</p>

                <?php
                    $count = 1;
                    // Populate the table with data fetched from the Oracle table
                    while (($row = oci_fetch_assoc($stid)) != false) {
                        //echo '<p>' . $row[] . '</p>';
                        echo '<div class="row padding" >';
                            echo '<select class="pref-input state-dropdown" name="pref_input_' . $count . '" id="pref_input">';
                                // Ensure there is a correct number of options in the drop down box
                                for ($x = 0; $x < $num_candidates; $x++){
                                    echo '<option value="' . ($x + 1) . " " . $row['CANDIDATEID'] . '">' . ($x + 1) . '</option>';
                                }
                            echo '</select>';
                            echo '<div class="column">';
                                echo '<label class="pref-name" for="pref_input">&emsp;' . $row['CANDIDATENAME'] . '</label>';
                                echo '<label class="pref-party" for="pref_input">&emsp; ' . $row['PARTYNAME'] . '</label>';
                            echo '</div>';
                        echo '</div>';
                        $count++;
                    } 
                ?>

                <div class="invisible">
                    <label for="first_name">First name:</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>">
                    <label for="last_name">Last name:</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" value="<?php echo $address; ?>">
                    <label for="electorate_name">Electorate name</label>
                    <input type="text" id="electorate_name" name="electorate_name" value="<?php echo $electorate_name; ?>">
                    <label for="voter_DOB">Voter DOB</label>
                    <input type="text" id="voter_DOB" name="voter_DOB" value="<?php echo $voter_DOB; ?>">
                </div>

                <br>

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
