<?php
/**
 * API TEMPLATE for displaying GMB reviews
 *
 */

require get_stylesheet_directory() . '/php/vendor/autoload.php';

function getClient() {
    $client = new Google\Client();
    $client->setScopes('https://www.googleapis.com/auth/business.manage');
    $client->setAuthConfig(ABSPATH . '_wpeprivate/client_secret.json');
    $client->setIncludeGrantedScopes(true);
    $credentialsPath = ABSPATH . '_wpeprivate/token.json'; 
    $accessToken = file_get_contents($credentialsPath);
    $client->setAccessToken($accessToken);
    if ($client->isAccessTokenExpired()) {
        $refreshTokenSaved = $client->getRefreshToken();
        $client->fetchAccessTokenWithRefreshToken($refreshTokenSaved);
        $accessTokenUpdated = $client->getAccessToken();
        $accessTokenUpdated['refresh_token'] = $refreshTokenSaved;
        $accessToken = json_encode($accessTokenUpdated);
        $client->setAccessToken($accessToken);
        file_put_contents($credentialsPath, json_encode($accessTokenUpdated));
    }
    return $client;
}

$client = getClient();

//Pulling in MyBusiness.php to access Google_Service_MyBusiness class
require_once get_stylesheet_directory() . '/php/src/MyBusiness.php';
$mybusinessService = new Google_Service_MyBusiness($client);

//Get accounts
$accountsArray = $mybusinessService->accounts->listAccounts()->getAccounts();
$name = '<Name of Location group goes here>';
$accountObject = new stdClass();
foreach($accountsArray as $item) {
    if ($name == $item->accountName) {
        $accountObject = $item;
        break;
    }
}
$nameForReviews = $accountObject->name;

//Get locations
$locationsArray = $mybusinessService->accounts_locations->listAccountsLocations($nameForReviews)->getLocations();
$location = "<Location object stored here>";

function getReviewCount($location){
    $key = "<Google Place API key goes here>";
    $id = $location->locationKey->placeId;
    $curl = curl_init();
    $url = 'https://maps.googleapis.com/maps/api/place/details/json?placeid='.$id.'&key='.$key;
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,  // Capture response.
        CURLOPT_ENCODING => "gzip",  // Accept gzip/deflate/whatever.
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET"
    ));
    if(curl_errno($curl)){
        echo curl_error($curl);
    }
    $response = curl_exec($curl);
    $obj = json_decode($response);
    // Close connection
    curl_close($curl);
    return $obj->result;
}
$countTotal = getReviewCount($location);

//Get reviews
$reviewsArray = $mybusinessService->accounts_locations_reviews->listAccountsLocationsReviews($location->name)->getReviews();
?>