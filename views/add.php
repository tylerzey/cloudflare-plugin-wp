<?php if (!defined('ABSPATH')) exit; ?>
<h3>Add Another Domain Record:</h3>
<form action="options-general.php?page=<?php echo $this->plugin->name; ?>" class="form-control" method="post">
    <div class="form-group">
        <label for="exampleFormControlSelect1">Type Of Record</label>
        <select name="cloudflare_type" class="form-control" id="exampleFormControlSelect1">
            <option>A</option>
            <option>CNAME</option>
        </select>
    </div>
    <div class="form-group">
        <label for="cloudflare_name">DNS Record Name:</label>
        <input name="cloudflare_name" placeholder="Example: www or blog" type="text" class="form-control">
        <small class="form-text text-muted">The name of the DNS record you want to add. For example, to add a sub domain called blog.example.com you'd just enter blog</small>
    </div>
    <div class="form-group">
        <label for="cloudflare_content">DNS Value:</label>
        <input name="cloudflare_content" placeholder="192.333.44" type="text" class="form-control">
        <small class="form-text text-muted">The IP or CNAME you want to add.</small>
    </div>
    
    <?php wp_nonce_field($this->plugin->name, $this->plugin->name . '_nonce'); ?>
    <input name="add_dns_cloudflare" type="submit" class="button button-primary" value="<?php _e('SAVE', $this->plugin->name); ?>" />
</form>