<?php

// GitHub API URL
$api_url = 'https://github.com/Magisk-Modules-Alt-Repo';

// GitHub repository owner and name
$owner = 'owner_username';
$repo = 'repository_name';

// Construct the URL with placeholders replaced
$api_url = str_replace([':owner', ':repo'], [$owner, $repo], $api_url);

// Set up cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'Your-App-Name'); // Set a user agent to avoid rate limiting

// Execute cURL session and get the response
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
}

// Close cURL session
curl_close($ch);

// Decode JSON response
$data = json_decode($response, true);

// Output the data
echo '<pre>';
print_r($data);
echo '</pre>';
