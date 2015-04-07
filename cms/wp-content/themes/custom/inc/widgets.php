<?php
/**
 * Custom Text widget class
 *
 * @since 1.0
 */
class Cyber_DC_Custom_Text_Widget extends WP_Widget {
	
	function __construct() {
		$widget_ops = array( 'classname' => 'custom_custom_text_widget', 'description' => __( 'Custom Text Widget with Image', 'custom' ) );
		$control_ops = array( 'width' => 400, 'height' => 350 );
		parent::__construct( 'custom_custom_text_widget', '(' . CYBER_DC_custom . ') ' . __( 'Image Text Widget', 'custom' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$image = esc_url( $instance['image'] );
		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );

		echo $before_widget;

		if ( ! empty( $image ) )
			echo '<img src="' . $image. '" alt="" class="aligncenter" />';

		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;

		?>

		<div class="textwidget">
			<?php echo ( ! empty( $instance['filter'] ) ) ? wpautop( $text ) : $text; ?>
		</div>
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['image'] = esc_url( $new_instance['image'] );

		if ( current_user_can( 'unfiltered_html' ) )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes( $new_instance['text'] ) ) ); // wp_filter_post_kses() expects slashed

		$instance['filter'] = isset( $new_instance['filter'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'image' => '' ) );
		$title = strip_tags( $instance['title'] );
		$image = esc_url( $instance['image'] );
		$text = esc_textarea( $instance['text'] );
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'custom' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e( 'Image URL:', 'custom' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'image' ); ?>" name="<?php echo $this->get_field_name( 'image' ); ?>" type="text" value="<?php echo esc_url( $image ); ?>" /></p>

		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>

		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e( 'Automatically add paragraphs', 'custom' ); ?></label></p>
		<?php
	}
}
register_widget( 'Cyber_DC_Custom_Text_Widget' );


/**
 * Creating the two sidebars
 *
 * This function is attached to the 'widgets_init' action hook.
 *
 * @uses register_sidebar()
 *
 * @since 1.0
 */
function custom_widgets_init() {

	register_sidebar( array(
		'name' => __( 'First Sidebar', 'custom' ),
		'id' => 'sidebar',
		'description' => __( 'This is the first sidebar widgetized area. All defaults widgets work great here.', 'custom' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Home Page Widgets', 'custom' ),
		'id' => 'home-page-top-area',
		'description' => __( 'Widgetized area on the home page directly below the the Jumbotron (custom! Star) menu. Specifically designed for 3 text widgets. These widgets are turned on by default on the Theme Customizer page.', 'custom' ),
		'before_widget' => '<aside id="%1$s" class="home-widget col-md-4 %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="home-widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'custom_widgets_init' );