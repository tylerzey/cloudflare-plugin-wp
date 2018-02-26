<?php if (!defined('ABSPATH')) exit; ?>

<?php 
if (isset($this->settings['cloudflare_email']) && isset($this->settings['cloudflare_api_key']) && isset($this->settings['zone_id'])) {

    $email = $this->settings['cloudflare_email'];
    $api = $this->settings['cloudflare_api_key'];
    $zone = $this->settings['zone_id'];

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.cloudflare.com/client/v4/zones/$zone/dns_records",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "Content-Type: application/json",
            "x-auth-email: $email",
            "x-auth-key: $api"
        )
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    $response = json_decode($response);
    $options = get_option($this->plugin->name);
    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else if ($response->success == false) {
        echo '<div class="alert alert-danger" role="alert">' . $response->errors[0]->message . '</div>';
        
        $options['auth'] = false;
        update_option($this->plugin->name, $options);
    } else {
        echo '<div class="container"><div class="row"><div class="col-sm">Type</div><div class="col-sm">Name</div><div class="col-sm">Value</div><div class="col-sm">Proxied?</div></div>';
        foreach ($response->result as $r) {
            echo '<div class="row">';
            echo '<div class="col-sm">' . $r->type . '</div>';
            echo '<div class="col-sm">' . $r->name . '</div>';
            echo '<div class="col-sm">' . $r->content . '</div>';
            echo '<div class="col-sm">' . $r->proxied . '</div>';
            echo '<div class="col-xs"><form action="options-general.php?page='.$this->plugin->name.'" method="post"><input type="hidden" name="zonedelete" value="' . $r->id . '"/><input class="btn btn-danger" type="submit" value="X"/></form></div>';
            echo '</div>';
        }
        echo '</div>';
        $options['auth'] = true;
        update_option($this->plugin->name, $options);
    }
}

    



