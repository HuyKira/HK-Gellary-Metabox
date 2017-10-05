<?php
/*
Plugin Name: HK Gellary Metabox
Plugin URI: http://www.huykira.net
Description: HK Gellary Metabox by Huy Kira
Author: Huy Kira
Version: 1.0
Author URI: http://www.huykira.net
*/
if ( !function_exists( 'add_action' ) ) {
  echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
  exit;
}

define('HK_GELLARY_PLUGIN_URL', plugin_dir_url(__FILE__));
define('HK_GELLARY_PLUGIN_DIR', plugin_dir_path(__FILE__));

function custom_style() {
	wp_enqueue_style( 'gellary_css', HK_GELLARY_PLUGIN_URL.'css/hk.gellary.css', false, '1.0.0' );
	wp_enqueue_script( 'jquery_ui_js', HK_GELLARY_PLUGIN_URL.'js/jquery-ui.min.js', true, '1.0.0' );
	wp_enqueue_script( 'gellary_js', HK_GELLARY_PLUGIN_URL.'js/hk.gellary.js', true, '1.0.0' );
}
add_action( 'admin_enqueue_scripts', 'custom_style' );
$post_types = get_post_types();


function hkgellary_meta_box(){
 	add_meta_box( 'thong-tin', 'Thư viện ảnh', 'hkgellary_meta_box_output', array('albums','page') );
}
add_action( 'add_meta_boxes', 'hkgellary_meta_box' );

function hkgellary_meta_box_output(){ ?>
    <div class="gallery-hk-metabox">
    	<?php $gellary = get_post_meta( get_the_id(), '_hk_gellary', true ); ?>
    	<input id="gallery_input" type="hidden" name="gallery" value="">
    	<?php wp_nonce_field( 'save_hkgellary_meta', 'hkgellary_meta_nonce' ); ?>
    	<ul id="gallery-hk-metabox">
    	<?php if(isset($gellary) and($gellary !='')) { ?>
    		<?php foreach ($gellary as $value) { ?>
    		<li class="kira">
    		<div class="over">
    			<img src="<?php echo wp_get_attachment_url($value); ?>">
    		</div>
    		<a class="del-img"><span class="dashicons dashicons-dismiss"></span></a>
    		<input type="hidden" name="hkgellary[]" value="<?php echo $value; ?>">
    		</li>		
    		<?php } ?>
    	<?php } ?>
    	</ul>
    	<ul id="gallery-hk">
    		<li><div class="over"><span>+</span></div></li>
    	</ul>
    	<div class="clear"></div>
    </div>
<?php }
function hkgellary_meta_box_save( $post_id )
{
 	$hkgellary_meta_nonce = $_POST['hkgellary_meta_nonce'];
	if( !isset( $hkgellary_meta_nonce ) ) {
	  return;
	}
	if( !wp_verify_nonce( $hkgellary_meta_nonce, 'save_hkgellary_meta' ) ) {
	  return;
	}

	$gellary = get_post_meta( get_the_id(), '_hk_gellary', false );
	if(isset($gellary) and ($gellary !='')){
 		update_post_meta( get_the_id(), '_hk_gellary', $_POST['hkgellary']);
 	} else {
 		add_post_meta(get_the_id(), '_hk_gellary', $_POST['hkgellary']);
 	}
}
add_action( 'save_post', 'hkgellary_meta_box_save' );