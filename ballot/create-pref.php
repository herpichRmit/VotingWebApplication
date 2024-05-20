<?php

    // TODO: reference https://www.uuidgenerator.net/dev-corner/php
    function guidv4($data = null) {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    include('../database/query_functions.php');
    $db = oci_connect("s3935413", "Whiteairforce1s!", "talsprddb01.int.its.rmit.edu.au/CSAMPR1.ITS.RMIT.EDU.AU");

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        // if a post request occurs, load varaibles from post request
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $address = $_POST['address'] ?? '';
        $electorate_name = $_POST['electorate_name'] ?? '';
        $voter_DOB = $_POST['voter_DOB'] ?? '';
        $pref_input_1 = $_POST['pref_input_1'] ?? '';
        $pref_input_2 = $_POST['pref_input_2'] ?? '';
        $pref_input_3 = $_POST['pref_input_3'] ?? '';
        $pref_input_4 = $_POST['pref_input_4'] ?? '';
        $pref_input_5 = $_POST['pref_input_5'] ?? '';
        $pref_input_6 = $_POST['pref_input_6'] ?? '';
        $pref_input_7 = $_POST['pref_input_7'] ?? '';
        $pref_input_8 = $_POST['pref_input_8'] ?? '';
        $pref_input_9 = $_POST['pref_input_9'] ?? '';

        // Iterate through, and check all values, in order to determine the number of candidates
        // For each I will split "<preference_number> <candidate_id>" into ["<preference_number>", "<candidate_id>"] 
        // I'll then append all of those to an array 

        $preferences = array();

        if ($pref_input_1 !== '') {
            $pref_input_1_array=  explode(" ", $pref_input_1 );
            array_push($preferences, $pref_input_1_array);
        }
        if ($pref_input_2 !== '') {
            $pref_input_2_array=  explode(" ", $pref_input_2 );
            array_push($preferences, $pref_input_2_array);
        }
        if ($pref_input_3 !== '') {
            $pref_input_3_array=  explode(" ", $pref_input_3 );
            array_push($preferences, $pref_input_3_array);
        }
        if ($pref_input_4 !== '') {
            $pref_input_4_array=  explode(" ", $pref_input_4 );
            array_push($preferences, $pref_input_4_array);
        }
        if ($pref_input_5 !== '') {
            $pref_input_5_array=  explode(" ", $pref_input_5 );
            array_push($preferences, $pref_input_5_array);
        }
        if ($pref_input_6 !== '') {
            $pref_input_6_array=  explode(" ", $pref_input_6 );
            array_push($preferences, $pref_input_6_array);
        }
        if ($pref_input_7 !== '') {
            $pref_input_7_array=  explode(" ", $pref_input_7 );
            array_push($preferences, $pref_input_7_array);
        }
        if ($pref_input_8 !== '') {
            $pref_input_8_array=  explode(" ", $pref_input_8 );
            array_push($preferences, $pref_input_8_array);
        }
        if ($pref_input_9 !== '') {
            $pref_input_9_array=  explode(" ", $pref_input_9 );
            array_push($preferences, $pref_input_9_array);
        }


        ## Create issuance of ballot
        
        // 1. Create query
        $query = "INSERT INTO issuance_ballot ";
        $query .= "(VOTERNAME_FK, VOTERLASTNAME_FK, VOTERDOB_FK, VOTERRESIDENTIALADDRESS_FK, ELECTIONID_FK, ELECTORATENAME_FK, POLLINGSTATIONNAME, TIMESTAMP) ";
        $query .= "VALUES (:first_name, :last_name, :voter_DOB, :address, :election_ID, :electorate_name, :polling_station, :timestamp) ";

        // 2. Parse query
        $stid = oci_parse($db, $query);

        $election_ID = 20220521;
        $polling_station = 'Online';
        $timestamp = date("y/M/d");
        
        // 3. Assign values to query through bindings
        oci_bind_by_name($stid, ':first_name', $first_name);
        oci_bind_by_name($stid, ':last_name', $last_name);
        oci_bind_by_name($stid, ':voter_DOB', $voter_DOB);
        oci_bind_by_name($stid, ':address', $address);
        oci_bind_by_name($stid, ':election_ID', $election_ID);
        oci_bind_by_name($stid, ':electorate_name', $electorate_name);
        oci_bind_by_name($stid, ':polling_station', $polling_station);
        oci_bind_by_name($stid, ':timestamp', $timestamp);

        // 4. Exectue query
        $r = oci_execute($stid); // executes query

        if ($r) {
            echo '<p> SQL Success </p>';
        } else {
            echo '<p> SQL Failure </p>';
        }


        ## Create ballot_cast
        
        // 1. Create query
        $query = "INSERT INTO ballot_cast ";
        $query .= "(BALLOTID, ELECTIONID_FK, ELECTORATENAME_FK) ";
        $query .= "VALUES (:ballot_id, :election_id, :electorate_name)";

        // 2. Parse query
        $stid = oci_parse($db, $query);
        $ballot_id = guidv4();
        
        // 3. Assign values to query through bindings
        oci_bind_by_name($stid, ':ballot_id', $ballot_id);
        oci_bind_by_name($stid, ':election_id', $election_ID);
        oci_bind_by_name($stid, ':electorate_name', $electorate_name);

        // 4. Exectue query
        $r = oci_execute($stid); // executes query

        if ($r) {
            echo '<p> SQL Success </p>';
        } else {
            echo '<p> SQL Failure </p>';
        }


        ## Then create realted preference casts for the number of preferences given

        foreach ($preferences as &$value) {
            // 1. Create query
            $query = "INSERT INTO preference_cast ";
            $query .= "(BALLOTCAST_FK, CANDIDATEID_FK, PREFERENCEPOSITION) ";
            $query .= "VALUES (:ballot_id, :candidate_id, :preference)";

            // 2. Parse query
            $stid = oci_parse($db, $query);
            $preference = $value[0];
            $candidate_ID = $value[1];
            
            // 3. Assign values to query through bindings
            oci_bind_by_name($stid, ':ballot_id', $ballot_id);
            oci_bind_by_name($stid, ':candidate_id', $candidate_ID);
            oci_bind_by_name($stid, ':preference', $preference);

            // 4. Exectue query
            $r = oci_execute($stid); // executes query

            if ($r) {
                echo '<p> SQL Success </p>';
            } else {
                echo '<p> SQL Failure </p>';
            }

        }
        unset($value);


    } else {
        // In the case a user has tried to access link without submitting form
        header("Location: ../index.php");
        exit;
    }

    header("Location: ./dismiss.php?complete=y");
    exit;
?>