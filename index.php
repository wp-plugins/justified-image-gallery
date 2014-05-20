<?php
/*
Plugin Name: Justified Image Gallery
Plugin URI: http://kentothemes.com
Description: Pure HTML and CSS and Jquery Justified Image Gallery.
Version: 1.0
Author: KentoThemes
Author URI: http://kentothemes.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
define('KENTO_JUSTIFIED_IMAGE_GALLERY_PLUGIN_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/scripts.php');
function kento_justified_image_gallery_init()
	{
	wp_enqueue_script('jquery');
	wp_enqueue_style('kento_justified_image_gallery-css', KENTO_JUSTIFIED_IMAGE_GALLERY_PLUGIN_PATH.'css/style.css');
	wp_enqueue_style('kento_justified_image_justifiedGallery', KENTO_JUSTIFIED_IMAGE_GALLERY_PLUGIN_PATH.'css/justifiedGallery.css');	
	wp_enqueue_script('kento_justified_image_gallery_justifiedGallery', plugins_url( '/js/justifiedGallery.js' , __FILE__ ) , array( 'jquery' ));	
	wp_enqueue_script('kento_justified_image_gallery_ajax_js', plugins_url( '/js/script.js' , __FILE__ ) , array( 'jquery' ));
	wp_localize_script( 'kento_justified_image_gallery_ajax_js', 'kento_justified_image_gallery_ajax', array( 'kento_justified_image_gallery_ajaxurl' => admin_url( 'admin-ajax.php')));


	
	}
add_action('init', 'kento_justified_image_gallery_init');




function kento_justified_image_gallery_display($atts,  $content = null ) {
		$atts = shortcode_atts(
			array(
				'kjg_id' => "",
				'posttype' => "post",
				'bgcolor' => "#fff",

				), $atts);
				
	$posttype = $atts['posttype'];
	$kjg_id = "_".$atts['kjg_id'];
	$bgcolor = $atts['bgcolor'];
	$posts_per_page = get_option('posts_per_page');
	
	global $post;

	$kento_justified_image_gallery = "";
	$kento_justified_image_gallery .= gallery_script($kjg_id);

	$kento_justified_image_gallery .= '<script>
		jQuery(document).ready(function () {
			jQuery("#'.$kjg_id.' .kento-justified-gallery-girds").justifiedGallery'.$kjg_id.'();
		});
	</script>';
	
	$kento_justified_image_gallery .= '<div id="'.$kjg_id.'" style="background-color:'.$bgcolor.';">';	
	$kento_justified_image_gallery .= '<div class="kento-justified-gallery-girds" style="background-color:#FFFFFF;">';

query_posts('post_type='.$posttype.'&posts_per_page='.$posts_per_page);
		while (have_posts()) : the_post();
			
				
				if ( has_post_thumbnail() ) { 
					  $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
					  $kento_justified_image_gallery .=  '<a href="'.$url.'">';
					  $kento_justified_image_gallery .= "<img height='100%' src='".$url."' />";
					  $kento_justified_image_gallery .=  "</a>";	
					}
	endwhile;
	
	wp_reset_query();
	
	
	$kento_justified_image_gallery .= '</div>';
	$kento_justified_image_gallery .= '<div class="kjg-loadmore" has_post="yes" kjg_id="'.$kjg_id.'" offset="'.$posts_per_page.'" per_page="'.$posts_per_page.'"  posttype="'.$posttype.'"><div class="content">Load More...</div></div>';	
	$kento_justified_image_gallery .= '</div>';
	
	return $kento_justified_image_gallery;
	
	}









		



add_shortcode('kento_justified_image_gallery', 'kento_justified_image_gallery_display');










function kento_justified_image_gallery_ajax()
		{
			$posttype = $_POST['posttype'];
			$offset = (int)$_POST['offset'];
			$kjg_id = $_POST['kjg_id'];
			$posts_per_page = get_option('posts_per_page');			
			
			global $post;

			$query = new WP_Query( array( 'post_type' => $posttype, 'posts_per_page' => $posts_per_page, 'offset' => $offset ) );

			if ( $query->have_posts() )
				{
	$kento_justified_image_gallery ="";	
	$kento_justified_image_gallery.= '<script>
		jQuery(document).ready(function () {
			jQuery("#'.$kjg_id.' .kento-justified-gallery-girds").justifiedGallery'.$kjg_id.'();
		});
	</script>';
					
					
				while ($query->have_posts()) : $query->the_post();
					{
						if ( has_post_thumbnail() ) { 
							  $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
							  $kento_justified_image_gallery.=  '<a class="new" style="width: 200px; height: 180px; opacity: 1;" href="#">';
							  $kento_justified_image_gallery.= "<img height='100%' src='".$url."' />";
							  $kento_justified_image_gallery.=  "</a>";	
							}
					}	
				endwhile;			
				
				echo $kento_justified_image_gallery;
				}
		else {
				?>
				<script>
				jQuery(document).ready(function(jQuery)
					{
						jQuery("<?php echo "#".$kjg_id; ?> .kjg-loadmore").attr("has_post","no");
						
						
					})
				</script>
				<?php

		}

			die();
			
			
		}


add_action('wp_ajax_kento_justified_image_gallery_ajax', 'kento_justified_image_gallery_ajax');
add_action('wp_ajax_nopriv_kento_justified_image_gallery_ajax', 'kento_justified_image_gallery_ajax');







?>