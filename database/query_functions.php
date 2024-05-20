<?php

    function check_voter_details($first_name, $last_name, $address) {
        global $db;

        $query = "SELECT COUNT(*) FROM VOTER ";
        $query .= "WHERE VOTERNAME = '" . $first_name . "' AND VOTERLASTNAME = '" . $last_name . "' AND VOTERRESIDENTIALADDRESS = '" . $address . "' ";
        $stid = oci_parse($db, $query);
        oci_execute($stid);

        $result = oci_fetch_assoc($stid);

        if ($result['COUNT(*)'] == 1) {
            return true;
        } else {
            return false;
        }
        
    }

    function check_voted_before($first_name, $last_name, $address) {
        global $db;

        $query = "SELECT COUNT(*) FROM ISSUANCE_BALLOT ";
        $query .= "WHERE VOTERNAME_FK = '" . $first_name . "' AND VOTERLASTNAME_FK = '" . $last_name . "' AND VOTERRESIDENTIALADDRESS_FK = '" . $address . "' ";
        $stid = oci_parse($db, $query);
        oci_execute($stid);

        $result = oci_fetch_assoc($stid);

        if ($result['COUNT(*)'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function find_voter_electorate_by_details($first_name, $last_name, $address) {
        global $db;

        $query = "SELECT ELECTORATENAME_FK FROM VOTER  ";
        $query .= "WHERE VOTERNAME = '" . $first_name . "' AND VOTERLASTNAME = '" . $last_name . "' AND VOTERRESIDENTIALADDRESS = '" . $address . "' ";
        $stid = oci_parse($db, $query);
        oci_execute($stid);

        $result = oci_fetch_assoc($stid);

        return $result['ELECTORATENAME_FK'];
    }

    function find_voter_DOB_by_details($first_name, $last_name, $address){
        global $db;

        $query = "SELECT VOTERDOB FROM VOTER  ";
        $query .= "WHERE VOTERNAME = '" . $first_name . "' AND VOTERLASTNAME = '" . $last_name . "' AND VOTERRESIDENTIALADDRESS = '" . $address . "' ";
        $stid = oci_parse($db, $query);
        oci_execute($stid);

        $result = oci_fetch_assoc($stid);

        return $result['VOTERDOB'];
    }

    function find_number_of_candidates_by_electorate($electorate_name) {
        global $db;

        $query = "SELECT COUNT(*) ";
        $query .= "FROM Candidate c LEFT JOIN Party p ON c.PartyID_FK = p.PartyID ";
        $query .= "LEFT JOIN Election_Event ee ON ee.electorateName_FK = c.electorateName_FK ";
        $query .= "WHERE ee.electorateName_FK = '" . $electorate_name ."' AND ee.electionID_FK = 20220521 AND ee.electionID_FK = c.electionID_FK" ;
        $stid = oci_parse($db, $query);
        oci_execute($stid);

        $result = oci_fetch_assoc($stid);

        return $result['COUNT(*)'];
    }

    function find_candidates_by_electorate($electorate_name) {
        global $db;

        $query = "SELECT c.CandidateID, c.CandidateName, p.PartyName, ee.ElectorateName_FK ";
        $query .= "FROM Candidate c LEFT JOIN Party p ON c.PartyID_FK = p.PartyID ";
        $query .= "LEFT JOIN Election_Event ee ON ee.electorateName_FK = c.electorateName_FK ";
        $query .= "WHERE ee.electorateName_FK = '" . $electorate_name ."' AND ee.electionID_FK = 20220521 AND ee.electionID_FK = c.electionID_FK ";
        $query .= "ORDER BY dbms_random.value ";
        $stid = oci_parse($db, $query);
        oci_execute($stid);

        return $stid;
    }

?>