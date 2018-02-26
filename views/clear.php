<?php if (!defined('ABSPATH')) exit;?>
<form action="options-general.php?page=<?php echo $this->plugin->name; ?>" class="form-control" method="post">
    <div class="form-group">
        <label for="clearcache">Clear Your Cache:</label>
        <input name="clearcache" id="clearcache" type="checkbox" class="form-control">
        <input name="clearcache_2" type="submit" class="button button-primary" value="<?php _e('CLEAR', $this->plugin->name); ?>" />
    </div>
</form>