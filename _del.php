<?php
$this->settings = get_option($this->plugin->name);

if (isset($this->settings['auth']) && isset($this->settings['cloudflare_email']) && isset($this->settings['cloudflare_api_key']) && isset($this->settings['zone_id'])) {

    $email = $this->settings['cloudflare_email'];
    $api = $this->settings['cloudflare_api_key'];
    $zone = $this->settings['zone_id'];
    $id = $_REQUEST['zonedelete'];

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.cloudflare.com/client/v4/zones/$zone/dns_records/$id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "DELETE",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "Content-Type: application/json",
            "x-auth-email: $email",
            "x-auth-key: $api"
        )
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

}