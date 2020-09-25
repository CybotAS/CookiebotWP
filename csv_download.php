<?php

/* $CSV_curl = curl_init();

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
    "Authorization: Bearer " . $_SESSION['token']
),
));

$CSV_response = curl_exec($CSV_curl);

curl_close($CSV_curl);

$CSV = json_decode($CSV_response); */

$CSV = curl("https://app-cookiebot-api-umbracodashboard-prod.azurewebsites.net/urls/" . get_option('domainID') . "?format=json", "GET", "Authorization: Bearer " . $_SESSION['token']);

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