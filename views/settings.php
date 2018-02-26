<?php if (!defined('ABSPATH')) exit; ?>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<div class="happy-cat-body" id="post-body-content">
    <div class="et-title-heading">
        <h2>Cloudflare Cache Clear On Settings Save</h2>
        <p>This clears your cloudflare cache everytime you update something in your wp_options database table. This generals means this will fire after you do something that changes how your site looks on every page.</p>
    </div>
        <?php 
        include('form.php');
        include('clear.php');
        include('data.php');
        include('add.php');
        ?>
    <div class="et-bottom-credit grow">
        <?php
        echo '<a href="http://happycatplugins.com/" target="_blank"><p>created by <img src="' . plugins_url('../happycatlogo.png', __FILE__) . '" alt="happy cat logo" style="width:50px"> Happy Cat Plugins
            </p></a>';
        ?>
    </div>
</div>
