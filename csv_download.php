<?php

$client_curl = curl_init();

curl_setopt_array($client_curl, array(
    CURLOPT_URL => "https://api.cookiebot.com/auth/v1/connect/token",
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "grant_type=client_credentials&client_id=" . get_option('client_ID') . "&client_secret=" . get_option('client_Secret'),
    CURLOPT_HTTPHEADER => array(
        "Content-Type: application/x-www-form-urlencoded"
    ),
));

$client_response = curl_exec($client_curl);

curl_close($client_curl);

$token = json_decode($client_response);





$CSV_curl = curl_init();

curl_setopt_array($CSV_curl, array(
CURLOPT_URL => "https://app-cookiebot-api-umbracodashboard-prod.azurewebsites.net/urls/" . get_option('domainID') . "?format=json",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_SSL_VERIFYPEER => false,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "GET",
CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer " . $token->access_token
),
));

$CSV_response = curl_exec($CSV_curl);

curl_close($CSV_curl);

$CSV = json_decode($CSV_response);

$fileName = 'somefile.csv';

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header('Content-Description: File Transfer');
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename={$fileName}");
header("Expires: 0");
header("Pragma: public");

$fh = @fopen( 'php://output', 'w' );

$headerDisplayed = false;
 
foreach ( $CSV->crawlUrls as $data ) {
    $data_array = array(
        $data->url, $data->firstParentUrl
    );

    if ( !$headerDisplayed ) {
        $header_array = array(
            "url", "firstParentUrl"
        );
        fputcsv($fh, ($header_array));
        $headerDisplayed = true;

    }

    fputcsv($fh, $data_array);
}

fclose($fh);

exit;