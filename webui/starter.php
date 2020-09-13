<?php
/**
 * Created by PhpStorm.
 * User: aravind
 * Date: 8/25/17
 * Time: 7:50 PM
 */
try {
    // create curl resource
    $ch = curl_init();
    // set url
    curl_setopt($ch, CURLOPT_URL, "https://api.dialogflow.com/v1/query?v=20170712&e=WELCOME&lang=en&sessionId=a953f074-cb45-47c7-9399-1d9dc1f939b7&timezone=Asia/Kolkata");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer 5d9460e273e24fa6a57027c5f3f2e20b'));
    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // $output contains the output string
    $output = curl_exec($ch);
    // close curl resource to free up system resources
    curl_close($ch);
}catch (Exception $e) {
    $speech = $e->getMessage();
    $fulfillment = new stdClass();
    $fulfillment->speech = $speech;
    $result = new stdClass();
    $result->fulfillment = $fulfillment;
    $response = new stdClass();
    $response->result = $result;
    echo json_encode($response);
}
?>

<div id="dom-target" style="display: none;">
    <?php

        echo htmlspecialchars($output); /* You have to escape because the result will not be valid HTML otherwise. */
    ?>
</div>