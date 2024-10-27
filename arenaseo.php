<?php
/*
Plugin Name: Arena SEO Plugin
Plugin URI: https://www.html5arena.com/
Description: Simplified Minimalistic SEO Plugin, with strong set of features.
Author: Html5Arena <info@html5arena.com>
Version: 1.0
Author URI: https://www.html5arena.com/
*/

/* Arena Minification - The function below will perform basic html minification. */
function arena_minify_html($buf)
{
   if (!is_admin() && get_option('ha_minify_html',false) == true)
   {
	$search = array(
	'/\>[^\S ]+/s',
	'/[^\S ]+\</s',
	'/(\s)+/s'
	);

	$replace = array(
	'>',
	'<',
	'\\1'
	);

	if (preg_match("/\<html/i",$buf) == 1 && preg_match("/\<\/html\>/i",$buf) == 1) 
	{
		$buf = preg_replace($search, $replace, $buf);
	}
   }
   return $buf;
}
ob_start("arena_minify_html");

/* Arena Expired Headers - The function below will add expired headers to .htaccess file */
function arena_expired_headers()
{
    $file = ABSPATH . ".htaccess";
    $current = file_get_contents($file);
	if (strpos($current,"# BEGIN - Arena") === false)
	{   if (get_option('ha_expired_headers') == true)
		{
			$current .= "\n" . '# BEGIN - Arena SEO'."\n".'<IfModule mod_expires.c>' . "\n" . '
			ExpiresActive on ' . "\n" . '
			ExpiresDefault "access plus 1 month"' . "\n" . '
			ExpiresByType image/gif "access plus 1 month"' . "\n" . '
			ExpiresByType image/png "access plus 1 month"' . "\n" . '
			ExpiresByType image/jpg "access plus 1 month"' . "\n" . '
			ExpiresByType image/jpeg "access plus 1 month"' . "\n" . '
			ExpiresByType text/html "access plus 3 days"' . "\n" . '
			ExpiresByType text/xml "access plus 1 seconds"' . "\n" . '
			ExpiresByType text/plain "access plus 1 seconds"' . "\n" . '
			ExpiresByType application/xml "access plus 1 seconds"' . "\n" . '
			ExpiresByType application/rss+xml "access plus 1 seconds"' . "\n" . '
			ExpiresByType application/json "access plus 1 seconds"' . "\n" . '
			ExpiresByType text/css "access plus 1 week"' . "\n" . '
			ExpiresByType text/javascript "access plus 1 week"' . "\n" . '
			ExpiresByType application/javascript "access plus 1 week"' . "\n" . '
			ExpiresByType application/x-javascript "access plus 1 week"' . "\n" . '
			ExpiresByType image/x-ico "access plus 1 year"' . "\n" . '
			ExpiresByType image/x-icon "access plus 1 year"' . "\n" . '
			ExpiresByType application/pdf "access plus 1 month"' . "\n" . '
			<IfModule mod_headers.c>' . "\n" . '
			   Header append Cache-Control "public, no-transform, must-revalidate"' . "\n" . '
			</IfModule>' . "\n" . '
			</IfModule>' . "\n" . '#END - Arena SEO';

			file_put_contents($file, $current);
		}
	} else
	{
			$beginpos = strpos($current,'# BEGIN - Arena SEO');
			$endpos = strpos ($current,'#END - Arena SEO');
			$currentstart = substr($current,0,$beginpos);
			$currentend = substr ($current,$endpos + 16);
		    file_put_contents($file, $currentstart . $currentend);	
	}
}

// Arena SEO Sitemap Generator
add_action( "save_post", "arena_create_sitemap" );
function arena_create_sitemap() 
{
    if (get_option('ha_sitemap') == false && get_option('ha_sitemap_pages') == false)
	{
		unlink ( ABSPATH . "sitemap.xml" );
		return;
	}
	
    $ptype = array ('');
    if (get_option('ha_sitemap') == true)
	$ptype[] = 'post';
    if (get_option('ha_sitemap_pages') == true)
	$ptype[] = 'page';

    if ( str_replace( '-', '', get_option( 'gmt_offset' ) ) < 10 ) { 
        $tempo = '-0' . str_replace( '-', '', get_option( 'gmt_offset' ) ); 
    } else { 
        $tempo = get_option( 'gmt_offset' ); 
    }
    if( strlen( $tempo ) == 3 ) { $tempo = $tempo . ':00'; }

    $postsForSitemap = get_posts( array(
        'numberposts' => -1,
        'orderby'     => 'modified',
        'post_type'   => $ptype,
        'order'       => 'DESC'
    ) );
    $sitemap .= '<?xml version="1.0" encoding="UTF-8"?>' . '<?xml-stylesheet type="text/xsl" href="' . 
        esc_url( home_url( '/' ) ) . 'sitemap.xsl"?>';
    $sitemap .= "\n" . '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    $sitemap .= "\t" . '<url>' . "\n" .
        "\t\t" . '<loc>' . esc_url( home_url( '/' ) ) . '</loc>' .
        "\n\t\t" . '<lastmod>' . date( "Y-m-d\TH:i:s", current_time( 'timestamp', 0 ) ) . $tempo . '</lastmod>' .
        "\n\t\t" . '<changefreq>daily</changefreq>' .
        "\n\t\t" . '<priority>1.0</priority>' .
        "\n\t" . '</url>' . "\n";
    foreach( $postsForSitemap as $post ) {
        setup_postdata( $post);
        $postdate = explode( " ", $post->post_modified );
        $sitemap .= "\t" . '<url>' . "\n" .
            "\t\t" . '<loc>' . get_permalink( $post->ID ) . '</loc>' .
            "\n\t\t" . '<lastmod>' . $postdate[0] . 'T' . $postdate[1] . $tempo . '</lastmod>' .
            "\n\t\t" . '<changefreq>Weekly</changefreq>' .
            "\n\t\t" . '<priority>0.5</priority>' .
            "\n\t" . '</url>' . "\n";
    }
    $sitemap .= '</urlset>';
    $fp = fopen( ABSPATH . "sitemap.xml", 'w' );
    fwrite( $fp, $sitemap );
    fclose( $fp );
}
 
//Arena SEO Ad Injection - This function will inject ads code after the content.

add_filter( 'the_content', 'arena_insert_post_ads_bottom' );

function arena_insert_post_ads_bottom( $content ) 
{
	$adsense_code = get_option ('ha_adsense_code_content_bottom');

	if ($adsense_code !== '' && is_single() && ! is_admin() ) {
		return $content . $adsense_code;
	}
	
	return $content;
}

//Arena SEO Ad Injection - This function will inject ads code after number of paragraphs of post content.

add_filter( 'the_content', 'arena_insert_post_ads' );

function arena_insert_post_ads( $content ) 
{	
	$adsense_code = get_option ('ha_adsense_code_content_head');
	$pnum = get_option ('ha_adsense_code_content_head_pnum',2);

	if ($adsense_code !== '' && is_single() && ! is_admin() ) {
		return arena_insert_after_paragraph( $adsense_code, $pnum, $content );
	}
	
	return $content;
}
  
function arena_insert_after_paragraph( $insertion, $paragraph_id, $content ) {
	$closing_p = '</p>';
	$paragraphs = explode( $closing_p, $content );
	foreach ($paragraphs as $index => $paragraph) {

		if ( trim( $paragraph ) ) {
			$paragraphs[$index] .= $closing_p;
		}

		if ( $paragraph_id == $index + 1 ) {
			$paragraphs[$index] .= $insertion;
		}
	}
	
	return implode( '', $paragraphs );
}

/* Add analytics coe to footer */
add_action('wp_footer', 'ha_analytics_footer', 100);
function ha_analytics_footer()
{
	if (!is_admin())
	{
		$analytics_code = get_option ('ha_analytics_code');
		if ($analytics_code !== '')
			echo $analytics_code;
	}
}

/* Move jQuery to the footer */
add_action( 'wp_enqueue_scripts', 'arena_enqueue_jq_to_footer' );
function arena_enqueue_jq_to_footer() 
{
	if (!is_admin() && get_option('ha_jq_to_footer',false) == true)
	{
           wp_enqueue_script( 'jquery' );
	}
}

/* Remove default twenty seventeen font */
add_action( 'wp_enqueue_scripts', 'arena_enqueue_twentyseventeen_fonts',20 );
function arena_enqueue_twentyseventeen_fonts() 
{
   if (get_option('ha_remove_gfonts',false) == true)
   {
      wp_dequeue_style( 'twentyseventeen-fonts' );
      wp_deregister_style( 'twentyseventeen-fonts' );
   }
}

/* Remove Query String From Resources */
function arena_remove_script_version( $src )
{
	if (is_admin())
		return $src;

	if (get_option('ha_remove_qstring',true) == false)
		return $src;

	if (strpos($src,"font") > 0)
		return $src;

	$parts = explode( '?', $src );
		return $parts[0];
}

add_filter( 'script_loader_src', 'arena_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'arena_remove_script_version', 15, 1 );

/* Filter title string */
function arena_filter_wp_title( $title ) {
    global $paged, $page,$post;
 
    if ( is_feed() )
        return $title;
  
    $meta_title_value = '';
    if (is_single()) {$meta_title_value=get_post_meta( $post->ID, 'ha_post_meta_title', true );}

    if ($meta_title_value !== '')
	$title = $meta_title_value;

    return $title;
}
add_filter( 'pre_get_document_title', 'arena_filter_wp_title', 10, 2 );

/* Add meta for posts and pages */
function arena_add_meta_tags() 
{
    global $post;
    if ( is_single() ) {
 	$meta_desc_value = get_post_meta( $post->ID, 'ha_post_description', true );
	$meta_out = '';
 	if ($meta_desc_value !== '')
	{
		$meta_out = '<meta name="description" content="' . $meta_desc_value . '" />' . "\n";
	}
	
	$meta_keywords_value = get_post_meta( $post->ID, 'ha_post_keywords', true );
 	if ($meta_keywords_value !== '')
	{
		$meta_out .='<meta name="keywords" content="' . $meta_keywords_value . '" />' . "\n";
	}

	$meta_noindex_value = get_post_meta( $post->ID, 'ha_post_noindex', true );
 	if ($meta_noindex_value == 'true')
		$meta_out = '<META name="ROBOTS" content="NOINDEX, FOLLOW"/>';

	echo $meta_out;
    }

    if (is_page())
    {
	$meta_out = '';
	$meta_desc_value = get_post_meta( $post->ID, 'ha_post_description', true );
 	if ($meta_desc_value !== '')
	{
		$meta_out = '<meta name="description" content="' . $meta_desc_value . '" />' . "\n";
	}
	
	$meta_keywords_value = get_post_meta( $post->ID, 'ha_post_keywords', true );
 	if ($meta_keywords_value !== '')
	{
		$meta_out .='<meta name="keywords" content="' . $meta_keywords_value . '" />' . "\n";
	}
	
	$meta_noindex_value = get_post_meta( $post->ID, 'ha_post_noindex', true );
 	
	if ($meta_noindex_value == 'true')
		$meta_out = '<META name="ROBOTS" content="NOINDEX, FOLLOW"/>';

	echo $meta_out;

    } else if (is_home()) {
	$blog_id = get_option( 'page_for_posts' );
	$meta_desc_value = get_post_meta( $blog_id, 'ha_post_description', true );
 	if ($meta_desc_value !== '')
	{
		echo '<meta name="description" content="' . $meta_desc_value . '" />' . "\n";
	}
    }
    if (get_option('ha_wmt_code') !== '')
		echo get_option('ha_wmt_code');
}
add_action( 'wp_head', 'arena_add_meta_tags' , 2 );

/* Add NOINDEX meta in author pages, category pages and tag pages */
function arena_add_meta_noindex() 
{
	$opt_author_noindex = get_option('ha_noindex_author',true) ;
	$opt_cat_noindex = get_option('ha_noindex_category',true) ;
	$opt_tag_noindex = get_option('ha_noindex_tag',true) ;

	if ( (is_author() && $opt_author_noindex == true) 
		|| (is_tag() && $opt_tag_noindex == true) 
		|| (is_category() && $opt_cat_noindex == true))
	{
		echo '<META name="ROBOTS" content="NOINDEX, FOLLOW"/>';
	} 
}
add_action( 'wp_head', 'arena_add_meta_noindex' , 4 );

/* Display the post meta box. */
function arena_post_description_meta_box( $object, $box ) { ?>

  <?php wp_nonce_field( basename( __FILE__ ), 'ha_post_description_nonce' ); ?>

  <p>
    <label for="ha-post-meta-title"><?php _e( "Meta Title : ", 'html5arena' ); ?></label>
    <br />
	<input class="widefat" type="text" name="ha-post-meta-title" id="ha-post-meta-title" value="<?php echo esc_attr( get_post_meta( $object->ID, 'ha_post_meta_title', true ) ); ?>" size="30" />
    
	<label for="ha-post-description"><?php _e( "Meta Description : ", 'html5arena' ); ?></label>
	<br>
	<textarea class="widefat" type="text" name="ha-post-description" id="ha-post-description" size="30" cols="30" rows="4" /><?php echo esc_attr( get_post_meta( $object->ID, 'ha_post_description', true ) ); ?></textarea>
	
    <label for="ha-post-keywords"><?php _e( "Meta Keywords : ", 'html5arena' ); ?></label>
	<br>    
	<input class="widefat" type="text" name="ha-post-keywords" id="ha-post-keywords" value="<?php echo esc_attr( get_post_meta( $object->ID, 'ha_post_keywords', true ) ); ?>" size="30" />

    <label for="ha-post-redirect"><?php _e( "Redirect To : ", 'html5arena' ); ?></label>
    <br />
	<input class="widefat" type="text" name="ha-post-redirect" id="ha-post-redirect" value="<?php echo esc_attr( get_post_meta( $object->ID, 'ha_post_redirect', true ) ); ?>" size="30" />

    <label for="ha-post-noindex"><?php _e( "No Index : ", 'html5arena' ); ?></label>
    <br />
	<input class="widefat" type="checkbox" name="ha-post-noindex" id="ha-post-noindex" value="" <?php if ('true' == esc_attr( get_post_meta( $object->ID, 'ha_post_noindex', true ) ) ) echo ' checked '; ?> size="30" />
    
  </p>
<?php }

/* Add the meta boxes to the editor in posts and pages */
function arena_add_post_meta_boxes() {

  add_meta_box(
    'ha-post-description',      
    esc_html__( 'Arena SEO', 'default' ),    
    'arena_post_description_meta_box',   
    'post',         
    'normal',         
    'default'         
  );
  add_meta_box(
    'ha-post-description',      
    esc_html__( 'Arena SEO', 'default' ),    
    'arena_post_description_meta_box',   
    'page',         
    'normal',         
    'default'         
  );

}
		
/* Save the meta box's post metadata. */
function arena_save_post_class_meta( $post_id, $post ) {

  /* Nonce should be verified */
  if ( !isset( $_POST['ha_post_description_nonce'] ) || !wp_verify_nonce( $_POST['ha_post_description_nonce'], basename( __FILE__ ) ) )
    return $post_id;

  /* Verify permissions */
  $post_type = get_post_type_object( $post->post_type );
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

  /* Sanitize posted data to be used as HTML. */
  $new_meta_value = ( isset( $_POST['ha-post-description'] ) ? sanitize_text_field( $_POST['ha-post-description'] ) : '' );
  $new_meta_title_value = ( isset( $_POST['ha-post-meta-title'] ) ? sanitize_text_field( $_POST['ha-post-meta-title'] ) : '' );
  $new_meta_keywords_value = ( isset( $_POST['ha-post-keywords'] ) ? sanitize_text_field( $_POST['ha-post-keywords'] ) : '' );
  $new_redirect_value = ( isset( $_POST['ha-post-redirect'] ) ? sanitize_text_field( $_POST['ha-post-redirect'] ) : '' );
  $new_noindex_value = ( isset( $_POST['ha-post-noindex'] ) ? 'true' : '' );

  /* Get the meta keys. */
  $meta_key = 'ha_post_description';
  $meta_keywords_key = 'ha_post_keywords';
  $meta_title_key = 'ha_post_meta_title';
  $redirect_key = 'ha_post_redirect';
  $noindex_key = 'ha_post_noindex';

  /* Get the meta value of the custom field key. */
  $meta_value = get_post_meta( $post_id, $meta_key, true );
  $meta_title_value = get_post_meta( $post_id, $meta_title_key, true );
  $redirect_value = get_post_meta( $post_id, $redirect_key, true);
  $meta_keywords_value = get_post_meta( $post_id, $meta_keywords_key, true);
  $noindex_value = get_post_meta( $post_id, $noindex_key, true);
  
  /* Update no index */
  if ( $new_noindex_value && '' == $noindex_value )
    add_post_meta( $post_id, $noindex_key, $new_noindex_value, true );
  elseif ( $new_noindex_value && $new_noindex_value != $noindex_value )
    update_post_meta( $post_id, $noindex_key, $new_noindex_value );
  elseif ( '' == $new_noindex_value && $noindex_value )
    delete_post_meta( $post_id, $noindex_key, $noindex_value );

  /* Update meta description value */
  if ( $new_meta_value && '' == $meta_value )
    add_post_meta( $post_id, $meta_key, $new_meta_value, true );
  elseif ( $new_meta_value && $new_meta_value != $meta_value )
    update_post_meta( $post_id, $meta_key, $new_meta_value );
  elseif ( '' == $new_meta_value && $meta_value )
    delete_post_meta( $post_id, $meta_key, $meta_value );

  /* Update meta title value */
  if ( $new_meta_title_value && '' == $meta_title_value )
    add_post_meta( $post_id, $meta_title_key, $new_meta_title_value, true );
  elseif ( $new_meta_title_value && $new_meta_title_value != $meta_title_value )
    update_post_meta( $post_id, $meta_title_key, $new_meta_title_value );
  elseif ( '' == $new_meta_title_value && $meta_title_value )
    delete_post_meta( $post_id, $meta_title_key, $meta_title_value );
	
  /* Update redirection values */
  if ( $new_redirect_value && '' == $redirect_value )
    add_post_meta( $post_id, $redirect_key, $new_redirect_value, true );
  elseif ( $new_redirect_value && $new_redirect_value != $redirect_value )
    update_post_meta( $post_id, $redirect_key, $new_redirect_value );
  elseif ( '' == $new_redirect_value && $redirect_value )
    delete_post_meta( $post_id, $redirect_key, $redirect_value );

  /* Update meta keywords value */
  if ( $new_meta_keywords_value && '' == $meta_keywords_value )
    add_post_meta( $post_id, $meta_keywords_key, $new_meta_keywords_value, true );
  elseif ( $new_meta_keywords_value && $new_meta_keywords_value != $meta_keywords_value )
    update_post_meta( $post_id, $meta_keywords_key, $new_meta_keywords_value );
  elseif ( '' == $new_meta_keywords_value && $meta_keywords_value )
    delete_post_meta( $post_id, $meta_keywords_key, $meta_keywords_value );

}

/* Add arena meta boxes setup function. */
function arena_post_meta_boxes_setup() 
{
  add_action( 'add_meta_boxes', 'arena_add_post_meta_boxes' );
  add_action( 'save_post', 'arena_save_post_class_meta', 10, 2 );
}

/* Invoke arena meta boxes setup when lanuching post editor. */

add_action( 'load-page.php', 'arena_post_meta_boxes_setup' );
add_action( 'load-post.php', 'arena_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'arena_post_meta_boxes_setup' );
add_action( 'load-page-new.php', 'arena_post_meta_boxes_setup' );

/* REDIRECT */
/* The function below will redirect any media attachment pages back to their parent post. */
/* If there is no parent post then the page will be redirect to the root */
/* Media pages redirection can be disabled via options */
/* Regular pages or posts will be redirected according to their meta field, if defined */
function arena_custom_redirect () 
{
	global $post;
	if (is_attachment())
	{
		$opt_redirect_media = get_option('ha_redirect_media',true);
		if ($opt_redirect_media == true)
		{
			$perma = '';
			$perma = get_permalink($post->post_parent);
			if ($perma !== '')
				wp_redirect($perma); 
			else
 				wp_redirect('/');
			exit;
		}
	}
	if (is_page() || is_single()) { 
		if ( $redirect = get_post_meta($post->ID, 'ha_post_redirect', true ) ) {
                        wp_redirect( $redirect );
                        exit;
                }
        }
}

add_action( 'get_header', 'arena_custom_redirect' );

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'arena_add_action_links' );

function arena_add_action_links ( $links ) 
{
   $ha_links = array('<a href="' . admin_url( 'admin.php?page=arena-seo' ) . '">Settings</a>');
   return array_merge( $links, $ha_links );
}

include( plugin_dir_path( __FILE__ ) . "options.php");
?>
