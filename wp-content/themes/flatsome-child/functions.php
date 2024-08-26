<?php
// Add custom Theme Functions here
//Copy từng phần và bỏ vào file functions.php của theme:
//xoa mã bưu điện thanh toán
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
	unset($fields['billing']['billing_postcode']);
	unset($fields['billing']['billing_country']);
	unset($fields['billing']['billing_address_2']);
	unset($fields['billing']['billing_company']);


	return $fields;
}
function register_my_menu() {
	register_nav_menu('product-menu',__( 'Menu Danh mục' ));
}
add_action( 'init', 'register_my_menu' );
//Doan code thay chữ giảm giá bằng % sale
//* Add stock status to archive pages
add_filter( 'woocommerce_get_availability', 'custom_override_get_availability', 1, 2);

// The hook in function $availability is passed via the filter!
function custom_override_get_availability( $availability, $_product ) {
	if ( $_product->is_in_stock() ) $availability['availability'] = __('Còn hàng', 'woocommerce');
	return $availability;
}
// Enqueue Scripts and Styles.
add_action( 'wp_enqueue_scripts', 'flatsome_enqueue_scripts_styles' );
function flatsome_enqueue_scripts_styles() {
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'flatsome-ionicons', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
}
function new_excerpt_more( $excerpt ) {
	return str_replace( '[...]', '...', $excerpt );
}
add_filter( 'excerpt_more', 'new_excerpt_more' );
class Auto_Save_Images{

	function __construct(){     

		add_filter( 'content_save_pre',array($this,'post_save_images') ); 
	}

	function post_save_images( $content ){
		if( ($_POST['save'] || $_POST['publish'] )){
			set_time_limit(240);
			global $post;
			$post_id=$post->ID;
			$preg=preg_match_all('/<img.*?src="(.*?)"/',stripslashes($content),$matches);
			if($preg){
				foreach($matches[1] as $image_url){
					if(empty($image_url)) continue;
					$pos=strpos($image_url,$_SERVER['HTTP_HOST']);
					if($pos===false){
						$res=$this->save_images($image_url,$post_id);
						$replace=$res['url'];
						$content=str_replace($image_url,$replace,$content);
					}
				}
			}
		}
		remove_filter( 'content_save_pre', array( $this, 'post_save_images' ) );
		return $content;
	}

	function save_images($image_url,$post_id){
		$file=file_get_contents($image_url);
		$post = get_post($post_id);
		$posttitle = $post->post_title;
		$postname = sanitize_title($posttitle);
		$im_name = "$postname-$post_id.jpg";
		$res=wp_upload_bits($im_name,'',$file);
		$this->insert_attachment($res['file'],$post_id);
		return $res;
	}

	function insert_attachment($file,$id){
		$dirs=wp_upload_dir();
		$filetype=wp_check_filetype($file);
		$attachment=array(
			'guid'=>$dirs['baseurl'].'/'._wp_relative_upload_path($file),
			'post_mime_type'=>$filetype['type'],
			'post_title'=>preg_replace('/\.[^.]+$/','',basename($file)),
			'post_content'=>'',
			'post_status'=>'inherit'
		);
		$attach_id=wp_insert_attachment($attachment,$file,$id);
		$attach_data=wp_generate_attachment_metadata($attach_id,$file);
		wp_update_attachment_metadata($attach_id,$attach_data);
		return $attach_id;
	}
}
new Auto_Save_Images();
/*
// Add our custom product cat rewrite rules
function devvn_product_category_rewrite_rules($flash = false) {
    $terms = get_terms( array(
        'taxonomy' => 'product_cat',
        'post_type' => 'product',
        'hide_empty' => false,
    ));
    if($terms && !is_wp_error($terms)){
        $siteurl = esc_url(home_url('/'));
        foreach ($terms as $term){
            $term_slug = $term->slug;
            $baseterm = str_replace($siteurl,'',get_term_link($term->term_id,'product_cat'));
            add_rewrite_rule($baseterm.'?$','index.php?product_cat='.$term_slug,'top');
            add_rewrite_rule($baseterm.'page/([0-9]{1,})/?$', 'index.php?product_cat='.$term_slug.'&paged=$matches[1]','top');
            add_rewrite_rule($baseterm.'(?:feed/)?(feed|rdf|rss|rss2|atom)/?$', 'index.php?product_cat='.$term_slug.'&feed=$matches[1]','top');
        }
    }
    if ($flash == true)
        flush_rewrite_rules(false);
}
add_action('init', 'devvn_product_category_rewrite_rules');
*/
function the_dramatist_custom_login_css() {
    echo '<style type="text/css">.login h1:after{content:"Thi\1EBF t k\1EBF  website nhanh ch\00F3 ng, chuy\00EA n nghi\1EC7 p";font-size:16px;font-weight:normal;text-align:center}body #login{width:calc(100% - 30px);width:-webkit-calc(100% - 30px);width:-moz-calc(100% - 30px);width:-ms-calc(100% - 30px);width:-o-calc(100% - 30px);max-width:420px;background:#fff;padding:29px 24px 16px 24px!important;box-shadow:0 0 2rem 0 rgba(136,152,170,.15);border-radius:.375rem}body #login form{width:100%;margin:0 auto;box-shadow:none!important;border:0!important;padding:0!important}body #login .message{width:100%;margin-left:auto;margin-right:auto;box-shadow:none!important;color:#155724;background-color:#d4edda;border:1px solid #c3e6cb!important;border-radius:3px}body.login{display:flex;flex-direction:column;justify-content:center;align-items:center}body.login *{box-sizing:border-box}.login #backtoblog,.login #nav{padding:0!important}.login form .input,.login form input[type=checkbox],.login input[type=text]{background:#fff!important;font-size:16px;padding:0 12px;border:1px solid #DCE1E7;box-shadow:none!important}.login form .input:focus,.login form input[type=checkbox]:focus,.login input[type=text]:focus{border-color:#4DA6E8}.login #wp-submit{box-shadow: none !important;padding:2px 20px;background:#4DA6E8;background:linear-gradient(to right,#00d4fd,#338aff);background-image:linear-gradient(135deg,#03cffd 10%,#0396FF 100%);background-size:200% auto;border:0;outline:none!important}.login #wp-submit:hover{background-size:125% auto}.login #backtoblog a:hover,.login #nav a:hover{color:#4DA6E8}.login h1{margin-bottom:15px}.login h1 a{background-image:url('.str_replace("http://","",get_home_url()).'/logo.png)!important;width:150px!important;height:41px!important;background-size:150px 41px!important;margin-bottom:10px!important}</style>';
}
add_action('login_head', 'the_dramatist_custom_login_css');
// Thay doi duong dan logo admin
function wpc_url_login(){
return get_home_url(); // duong dan vao website cua ban
}
add_filter('login_headerurl', 'wpc_url_login');
//Tùy chỉnh admin footer
function custom_admin_footer() { 
 echo 'Thiết kế bởi Hệ thống tạo web tự động!';}
 add_filter('admin_footer_text', 'custom_admin_footer');
//Xóa logo wordpress
add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );

function remove_wp_logo( $wp_admin_bar ) {
    $wp_admin_bar->remove_node( 'wp-logo' );
}
// hide update notifications
function remove_core_updates(){
global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
}
add_filter('pre_site_transient_update_core','remove_core_updates'); //hide updates for WordPress itself
add_filter('pre_site_transient_update_plugins','remove_core_updates'); //hide updates for all plugins
add_filter('pre_site_transient_update_themes','remove_core_updates'); //hide updates for all themes