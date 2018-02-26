<?php if (!defined('ABSPATH')) exit;?>
<form action="options-general.php?page=<?php echo $this->plugin->name; ?>" class="form-control" method="post">
    <div class="form-group">
        <label for="cloudflare_email">Your Cloudflare Email Address (AKA: X-Auth-Email)</label>
        <input name="cloudflare_email" id="cloudflare_email" type="text" class="form-control" placeholder="Your Cloudflare Email..." value="<?php echo $this->settings['cloudflare_email']; ?>">
    </div>
    <div class="form-group">
        <label for="cloudflare_api_key">Your Cloudflare API Key (Aka: X-Auth-Key)</label>
        <input name="cloudflare_api_key" id="cloudflare_api_key" type="text" class="form-control" placeholder="Your Cloudflare API Key" value="<?php echo $this->settings['cloudflare_api_key']; ?>">
        <small class="form-text text-muted">You can find <a href="https://www.cloudflare.com/a/profile/" target="_blank">this here.</a></small>
    </div>
    <div class="form-group">
        <label for="zone_id">Your Cloudflare Zone ID</label>
        <input name="zone_id" id="zone_id" type="text" class="form-control" placeholder="Your Cloudflare Zone Id" value="<?php echo $this->settings['zone_id']; ?>">
        <small class="form-text text-muted">You can find <a href="https://www.cloudflare.com/a/overview/" target="_blank">this here.</a></small>
    </div>
    
    <?php wp_nonce_field($this->plugin->name, $this->plugin->name . '_nonce'); ?>
    <input name="submit" type="submit" class="button button-primary" value="<?php _e('SAVE', $this->plugin->name); ?>" />
</form>