<?php
/**
 * Plugin Name:  New Recent Posts Select Categories By Thao Marky
 * Plugin URI:   http://www.thaomarky.com
 * Description:  Display Recent Posts in your Website with images thumbnail of the Contents.
 * Version:      1.0
 * Author:       Thao Marky
 * Author URI:   http://www.thaomarky.com
 * Author Email: thaomarky@gmail.com
 */
 
 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
class NewRPSC {
 
	public function __construct() {

		add_action( 'plugins_loaded', array( &$this, 'constants' ) );
		add_action( 'widgets_init', array( &$this, 'register_widget' ) );
		add_action( 'wp_enqueue_scripts', 'safely_add_stylesheet' );

	}
	public function constants() {

		/* Set constant path to the plugin directory. */
		define( 'RPTM_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		/* Set the constant path to the plugin directory URI. */
		define( 'RPTM_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

		/* Set the constant path to the assets directory. */
		define( 'RPTM_ASSETS', RPTM_URI . trailingslashit( 'assets' ) );

	}
	public function register_widget() {
		register_widget( 'New_Recent_Posts_Select_Categories_By_Thao_Marky' );
	}
}
new NewRPSC;
?>
<?php
/**
 * The custom recent posts widget. 
 * This widget gives total control over the output to the user.
 */
 
class New_Recent_Posts_Select_Categories_By_Thao_Marky extends WP_Widget {

	function __construct() {

		/* Set up the widget options. */
		$widget_options = array(
			'classname'   => 'nrpsctm_widget new-recent-posts',
			'description' => __( 'Display Recent Posts in your Website with images thumbnail of the Contents.', 'nrpsctm' )
		);

		$control_options = array(
			'width'  => 200,
			'height' => 350
		);

		/* Create the widget. */
		$this->WP_Widget(
			'nrpsctm_widget',                         // $this->id_base
			__( 'New Recent Posts Select Categories By Thao Marky', 'nrpsctm' ), // $this->name
			$widget_options,                       // $this->widget_options
			$control_options                       // $this->control_options
		);

	}
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );

		$title          = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$limit          = (int)( $instance['limit'] );
		$excerpt        = $instance['excerpt'];
		$length         = (int)( $instance['length'] );
		$thumb          = $instance['thumb'];
		$thumb_height   = (int)( $instance['thumb_height'] );
		$thumb_width    = (int)( $instance['thumb_width'] );
		$thumb_default  = esc_url( $instance['thumb_default'] );
		$thumb_align    = sanitize_html_class( $instance['thumb_align'] );
		$categories     = $instance['categories'];
		$post_type      = $instance['post_type'];
		$date           = $instance['date'];
		$readmore       = $instance['readmore'];
		$readmore_text  = strip_tags( $instance['readmore_text'] );

		echo $before_widget;

		/* If both title and title url not empty then display the data. */
		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		global $post;

		/* Set up the query arguments. */
		$args = array(
			'posts_per_page'   => $limit,
			'category'     	   => $categories,
			'post_type'        => $post_type,
			/* Set it to false to allow WPML modifying the query. */
			'suppress_filters' => false
		);

		/* Allow developer to filter the query. */
		$default_args = apply_filters( 'nrpsctm_default_query_arguments', $args );

		/**
		 * The main Query
		 * 
		 * @link http://codex.wordpress.org/Function_Reference/get_posts
		 */
		$nrpsctmwidget = get_posts( $default_args );

		/* Check if posts exist. */
		if ( $nrpsctmwidget ) {
		?>
			<div class= "nrpsctm-block">

				<ul class="nrpsctm-ul">

					<?php foreach ( $nrpsctmwidget as $post ) : setup_postdata( $post ); ?>

						<li class="nrpsctm-clearfix">
							<div class="recent_posts_left">
								<?php if ( $thumb == true ) { // Check if the thumbnail option enable. ?>

									<?php if ( has_post_thumbnail() ) { // Check If post has post thumbnail. ?>

										<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'nrpsctm' ), the_title_attribute('echo=0' ) ); ?>" rel="bookmark">
											<?php the_post_thumbnail( 
												array( $thumb_height, $thumb_width, true ),
												array( 
													'class' => $thumb_align . ' nrpsctm-thumb the-post-thumbnail',
													'alt'   => esc_attr( get_the_title() ),
													'title' => esc_attr( get_the_title() ) 
												) 
											); ?>
										</a>

									<?php } elseif ( function_exists( 'get_the_image' ) ) { // Check if get-the-image plugin installed and active. ?>

										<?php get_the_image( array( 
											'height'        => $thumb_height,
											'width'         => $thumb_width,
											'size'          => 'nrpsctm-thumbnail',
											'image_class'   => $thumb_align . ' nrpsctm-thumb get-the-image',
											'image_scan'    => true,
											'default_image' => $thumb_default
										) ); ?>

									<?php } elseif ( $thumb_default ) { // Check if the default image not empty. ?>

										<?php 
										printf( '<a href="%1$s" rel="bookmark"><img class="%2$s nrpsctm-thumb nrpsctm-default-thumb" src="%3$s" alt="%4$s" width="%5$s" height="%6$s"></a>',
											esc_url( get_permalink() ),
											$thumb_align,
											$thumb_default,
											esc_attr( get_the_title() ),
											$thumb_width,
											$thumb_height
										);
										?>

									<?php } // endif ?>

								<?php } // endif ?>
							</div>
							<div class="recent_posts_right">
							<h3 class="nrpsctm-title">
								<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'nrpsctm' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
							</h3>

							<?php if ( $date == true ) { // Check if the date option enable. ?>
								<time class="nrpsctm-time published" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo get_the_date(); ?></time>
							<?php } // endif ?>
							
							<?php if ( $excerpt == true ) { // Check if the excerpt option enable. ?>
								<div class="nrpsctm-summary">
									<?php echo nrpsctm_excerpt( $length ); ?> 
									<?php if ( $readmore == true ) { echo '<a href="' . esc_url( get_permalink() ) . '" class="more-link">' . $readmore_text . '</a>'; } ?>
								</div>
							<?php } // endif ?>
							</div>
						</li>

					<?php endforeach; wp_reset_postdata(); ?>

				</ul>

			</div><!-- .nrpsctm-block - http://wordpress.org/plugins/recent-posts-widget-extended/ -->

		<?php
		} /* End check. */

		echo $after_widget;

	}
	function update( $new_instance, $old_instance ) {

		$instance                   = $old_instance;
		$instance['title']          = strip_tags( $new_instance['title'] );
		$instance['limit']          = (int)( $new_instance['limit'] );
		$instance['excerpt']        = $new_instance['excerpt'];
		$instance['length']         = (int)( $new_instance['length'] );
		$instance['thumb']          = $new_instance['thumb'];
		$instance['thumb_height']   = (int)( $new_instance['thumb_height'] );
		$instance['thumb_width']    = (int)( $new_instance['thumb_width'] );
		$instance['thumb_default']  = esc_url_raw( $new_instance['thumb_default'] );
		$instance['thumb_align']    = $new_instance['thumb_align'];
		$instance['categories']     = $new_instance['categories'];
		$instance['post_type']      = $new_instance['post_type'];
		$instance['date']           = $new_instance['date'];
		$instance['readmore']       = $new_instance['readmore'];
		$instance['readmore_text']  = strip_tags( $new_instance['readmore_text'] );

		return $instance;

	}
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array(
			'title'          => '',
			'limit'          => 5,
			'excerpt'        => false,
			'length'         => 10,
			'thumb'          => true,
			'thumb_height'   => 45,
			'thumb_width'    => 45,
			'thumb_default'  => '',
			'thumb_align'    => 'nrpsctm-alignleft',
			'categories'     => '',
			'post_type'      => '',
			'date'           => true,
			'readmore'       => false,
			'readmore_text'  => __( 'Read More &raquo;', 'nrpsctm' ),
		);

		$instance       = wp_parse_args( (array)$instance, $defaults );
		$title          = strip_tags( $instance['title'] );
		$limit          = (int)( $instance['limit'] );
		$excerpt        = $instance['excerpt'];
		$length         = (int)($instance['length']);
		$thumb          = $instance['thumb'];
		$thumb_height   = (int)( $instance['thumb_height'] );
		$thumb_width    = (int)( $instance['thumb_width'] );
		$thumb_default  = $instance['thumb_default'];
		$thumb_align    = $instance['thumb_align'];
		$categories     = $instance['categories'];
		$post_type      = $instance['post_type'];
		$date           = $instance['date'];
		$readmore       = $instance['readmore'];
		$readmore_text  = strip_tags( $instance['readmore_text'] );

		?>

		<div class="nrpsctm-columns">

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'nrpsctm' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo $title; ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php _e( 'Limit:', 'nrpsctm' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text" value="<?php echo $limit; ?>"/>
			</p>
			<p>
			<label for="<?php echo $this->get_field_id('categories'); ?>">Category:</label> 
				<select id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>" class="widefat categories" style="width:100%;">
					<option value='all' <?php if ('all' == $instance['categories']) echo 'selected="selected"'; ?>>All categories</option>
					<?php $categories = get_categories('hide_empty=0&depth=1&type=post'); ?>
					<?php foreach($categories as $category) { ?>
					<option value='<?php echo $category->term_id; ?>' <?php if ($category->term_id == $instance['categories']) echo 'selected="selected"'; ?>><?php echo $category->cat_name; ?></option>
					<?php } ?>
				</select>
			</p>
			<?php if ( current_theme_supports( 'post-thumbnails' ) ) { ?>

				<p>
					<label class="input-checkbox" for="<?php echo esc_attr( $this->get_field_id( 'thumb' ) ); ?>"><?php _e( 'Display Thumbnail', 'nrpsctm' ); ?></label>
					<input id="<?php echo esc_attr( $this->get_field_id( 'thumb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumb' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $thumb ); ?> />&nbsp;
				</p>
				<p>
					<label class="nrpsctm-block" for="<?php echo esc_attr( $this->get_field_id( 'thumb_height' ) ); ?>"><?php _e( 'Thumbnail (height, width, align):', 'nrpsctm' ); ?></label>
					<input class= "small-input" id="<?php echo esc_attr( $this->get_field_id( 'thumb_height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumb_height' ) ); ?>" type="text" value="<?php echo $thumb_height; ?>"/>
					<input class="small-input" id="<?php echo esc_attr( $this->get_field_id( 'thumb_width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumb_width' ) ); ?>" type="text" value="<?php echo $thumb_width; ?>"/>
					<select class="small-input" id="<?php echo esc_attr( $this->get_field_id( 'thumb_align' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumb_align' ) ); ?>">
						<option value="nrpsctm-alignleft" <?php selected( $thumb_align, 'nrpsctm-alignleft' ); ?>><?php _e( 'Left', 'nrpsctm' ) ?></option>
						<option value="nrpsctm-alignright" <?php selected( $thumb_align, 'nrpsctm-alignright' ); ?>><?php _e( 'Right', 'nrpsctm' ) ?></option>
						<option value="nrpsctm-alignnone" <?php selected( $thumb_align, 'nrpsctm-alignnone' ); ?>><?php _e( 'Center', 'nrpsctm' ) ?></option>
					</select>
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'thumb_default' ) ); ?>"><?php _e( 'Default Thumbnail:', 'nrpsctm' ); ?></label>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'thumb_default' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumb_default' ) ); ?>" type="text" value="<?php echo $thumb_default; ?>"/>
					<small><?php _e( 'Leave it blank to disable.', 'nrpsctm' ); ?></small>
				</p>

			<?php } ?>

			<p>
				<label class="input-checkbox" for="<?php echo esc_attr( $this->get_field_id( 'excerpt' ) ); ?>"><?php _e( 'Display Excerpt', 'nrpsctm' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'excerpt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $excerpt ); ?> />&nbsp;
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'length' ) ); ?>"><?php _e( 'Excerpt Length:', 'nrpsctm' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'length' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'length' ) ); ?>" type="text" value="<?php echo $length; ?>"/>
			</p>
			<p>
				<label class="input-checkbox" for="<?php echo esc_attr( $this->get_field_id( 'readmore' ) ); ?>"><?php _e( 'Display Readmore', 'nrpsctm' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'readmore' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'readmore' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $readmore ); ?> />&nbsp;
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'readmore_text' ) ); ?>"><?php _e( 'Readmore Text:', 'nrpsctm' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'readmore_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'readmore_text' ) ); ?>" type="text" value="<?php echo $readmore_text; ?>"/>
			</p>
			<p>
				<label class="input-checkbox" for="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"><?php _e( 'Display Date', 'nrpsctm' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'date' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $date ); ?> />&nbsp;
			</p>

		</div>

		<div class="clear"></div>

	<?php
	}
}
function nrpsctm_excerpt( $length ) {
	$content = get_the_content();
	$excerpt = wp_trim_words( $content, $length );

	return $excerpt;
}
function safely_add_stylesheet() {
	wp_enqueue_style( 'prefix-style', plugins_url('css/pluginstyle.css', __FILE__) );
}
