<?php

function acym_makeCurlCall($url, $fields, $headers = [], $dontVerifySSL = false)
{
    $urlPost = '';
    if (!empty($fields)) {
        foreach ($fields as $key => $value) {
            $urlPost .= $key.'='.urlencode($value).'&';
        }

        $urlPost = trim($urlPost, '&');
    }

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $urlPost);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if ($dontVerifySSL) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    }
    if (!empty($headers)) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);

        curl_close($ch);

        return ['error' => $error];
    }

    curl_close($ch);

    return json_decode($result, true);
}
