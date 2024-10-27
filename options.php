<?php

add_action('admin_menu', 'arena_seo_plugin_create_menu');

function arena_seo_plugin_create_menu() {
	add_menu_page('Arena SEO Plugin Settings', 'SEO Settings', 'manage_options','arena-seo', 'arena_seo_plugin_settings_page' );
	add_action( 'admin_init', 'register_arena_seo_plugin_settings' );
}


function register_arena_seo_plugin_settings() {
	register_setting( 'arena-seo-plugin-settings-group', 'ha_expired_headers' );
	register_setting( 'arena-seo-plugin-settings-group', 'ha_minify_html' );
	register_setting( 'arena-seo-plugin-settings-group', 'ha_sitemap' );
	register_setting( 'arena-seo-plugin-settings-group', 'ha_sitemap_pages' );
	register_setting( 'arena-seo-plugin-settings-group', 'ha_analytics_code' );
	register_setting( 'arena-seo-plugin-settings-group', 'ha_wmt_code' );
	register_setting( 'arena-seo-plugin-settings-group', 'ha_adsense_code_content_head' );
	register_setting( 'arena-seo-plugin-settings-group', 'ha_adsense_code_content_head_pnum' );
	register_setting( 'arena-seo-plugin-settings-group', 'ha_adsense_code_content_bottom' );
	register_setting( 'arena-seo-plugin-settings-group', 'ha_redirect_media' );
	register_setting( 'arena-seo-plugin-settings-group', 'ha_noindex_author' );
	register_setting( 'arena-seo-plugin-settings-group', 'ha_noindex_tag' );
	register_setting( 'arena-seo-plugin-settings-group', 'ha_noindex_category' );
	register_setting( 'arena-seo-plugin-settings-group', 'ha_remove_qstring' );
	register_setting( 'arena-seo-plugin-settings-group', 'ha_jq_to_footer' );
	register_setting( 'arena-seo-plugin-settings-group', 'ha_remove_gfonts' );
}

function arena_seo_plugin_settings_page() {
?>
<?php arena_expired_headers(); ?>
<?php arena_create_sitemap(); ?>
<div class="wrap">
<h1>Arena SEO Plugin</h1>
<div style="display:inline-block;">Visit <a href="https://www.html5arena.com">www.HTML5Arena.com</a></div>
<img src="<?php echo plugin_dir_url( __DIR__ ).'arenaseo/images/html5arena-logo.png';?>" style="display:inline-block;float:right">
<form method="post" action="options.php">
    <?php settings_fields( 'arena-seo-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'arena-seo-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Analytics Code</th>
        <td>Add your Google analytics code here: <br><textarea rows=8 cols=40 name="ha_analytics_code"><?php echo esc_attr( get_option('ha_analytics_code') ); ?></textarea>
		</td>
        </tr>
         
        <tr valign="top">
        <th scope="row">WMT Code</th>
        <td>Add your Google search console verification code:<br><input type="text" name="ha_wmt_code" value="<?php echo esc_attr( get_option('ha_wmt_code') ); ?>" />
		</td>
        </tr>
 
        <tr valign="top">
        <th scope="row">Adsense content header</th>
        <td>Enter ads code that will be injected to the content: <br><textarea rows=8 cols=40 name="ha_adsense_code_content_head"><?php echo esc_attr( get_option('ha_adsense_code_content_head') ); ?></textarea>
		<br>After Paragraph : <br><select name="ha_adsense_code_content_head_pnum">
		<option value="1" <?php if (get_option('ha_adsense_code_content_head_pnum') == '1' ) echo ' selected '; ?>>First</option>
		<option value="2" <?php if (get_option('ha_adsense_code_content_head_pnum') == '2' ) echo ' selected '; ?>>Second</option>
		<option value="3" <?php if (get_option('ha_adsense_code_content_head_pnum') == '3' ) echo ' selected '; ?>>Third</option>
        </td>        
		</tr>
        <tr valign="top">
        <th scope="row">Adsense content bottom</th>
        <td>Enter ads code that will be injected to the content bottom: <br><textarea rows=8 cols=40 name="ha_adsense_code_content_bottom"><?php echo esc_attr( get_option('ha_adsense_code_content_bottom') ); ?></textarea>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Strip Query Strings</th>
        <td><input type="checkbox" name="ha_remove_qstring" value="1"<?php if ( get_option('ha_remove_qstring') == true ) echo ' checked '; ?>" />
        Checking this option will remove any query strings from static resources such as JS and CSS files</td>
        </tr>
		
        <tr valign="top">
        <th scope="row">No Index Author Pages</th>
        <td><input type="checkbox" name="ha_noindex_author" value="1"<?php if ( get_option('ha_noindex_author') == true ) echo ' checked '; ?>" />
        Checking this option will add a NOINDEX robots tag to all AUTHOR pages</td>
        </tr>
		
        <tr valign="top">
        <th scope="row">No Index Tags</th>
        <td><input type="checkbox" name="ha_noindex_tag" value="1"<?php if ( get_option('ha_noindex_tag') == true ) echo ' checked '; ?>" />
        Checking this option will add a NOINDEX robots tag to all TAGS pages</td>
        </tr>
		
        <tr valign="top">
        <th scope="row">No Index Categories</th>
        <td><input type="checkbox" name="ha_nonindex_category" value="1"<?php if ( get_option('ha_noindex_category') == true ) echo ' checked '; ?>" />
        Checking this option will add a NOINDEX robots tag to all category pages</td>
		</tr>
    
		<tr valign="top">
        <th scope="row">Redirect Media Pages</th>
        <td><input type="checkbox" name="ha_redirect_media" value="1"<?php if ( get_option('ha_redirect_media', true) == true ) echo ' checked '; ?>" />
        Checking this option will redirect any media pages to their parent post. Media page without a parent will be redirected to the home page</td>
		</tr>
        
		<tr valign="top">
        <th scope="row">Move jQuery To Footer</th>
        <td><input type="checkbox" name="ha_jq_to_footer" value="1"<?php if ( get_option('ha_jq_to_footer',true) == true ) echo ' checked '; ?>" />
		Move jquery loading to the footer for better loading speed</td>
        </tr>
        
		<tr valign="top">
        <th scope="row">Remove Google Fonts</th>
        <td><input type="checkbox" name="ha_remove_gfonts" value="1"<?php if ( get_option('ha_remove_gfonts') == true ) echo ' checked '; ?>" />
		This option will remove the default fonts of twenty seventeen default theme
		</td>
        </tr>
        
		<tr valign="top">
        <th scope="row">Expired Headers</th>
        <td><input type="checkbox" name="ha_expired_headers" value="1"<?php if ( get_option('ha_expired_headers',true) == true ) echo ' checked '; ?>" />
		This option will add correct expired headers for better page load time. This feature depends on mod_expired and mod_headers, which are enabled in most servers
		</td>
        </tr>
        
		<tr valign="top">
        <th scope="row">Minify</th>
        <td><input type="checkbox" name="ha_minify_html" value="1"<?php if ( get_option('ha_minify_html',true) == true ) echo ' checked '; ?>" />
		Minify html output
		</td>
		</tr>
		
		<tr valign="top">
        <th scope="row">Sitemap.XML</th>
        <td><input type="checkbox" name="ha_sitemap" value="1"<?php if ( get_option('ha_sitemap') == true ) echo ' checked '; ?>" />
		Add posts to sitemap.xml
		<br>
		<input type="checkbox" name="ha_sitemap_pages" value="1"<?php if ( get_option('ha_sitemap_pages') == true ) echo ' checked '; ?>" />
		Add pages to sitemap.xml
		</td>
		</tr>
		
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>