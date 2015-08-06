<?php
/**
 * Set up the default theme options
 *
 * @since 1.0
 */
function custom_theme_options() {
	//delete_option( 'custom_theme_options' );
	$default_theme_options = array(
		'width' => '1200',
		'layout' => '2',
		'primary' => 'col-md-8',
		'display_author' => 'on',
		'display_date' => 'on',
		'display_comment_count' => 'on',
		'display_categories' => 'on',
		'excerpt_content' => 'excerpt',
		'home_widget' =>'on',
		'home_posts' =>'on',
		'jumbo_headline_title' => 'custom!',
		'jumbo_headline_text' => 'You could write something extraordinary and profound here!',
		'cust_header_headline_title' => 'This is your Custom header!',
		'cust_header_headline_text' => 'You could could change the World!',
		'custom_header_display' => '',
	);

	return get_option( 'custom_theme_options', $default_theme_options );
}

/** 
 * Create the jumbo headline section on the home page
 *
 * @since 1.0
 */
function custom_jumbotron() {
	$custom_theme_options = custom_theme_options();
	if ( is_home() || is_front_page() && ! empty( $custom_theme_options['jumbo_headline_title'] ) ) {
	?>
		
				<div class="home-jumbotron jumbotron col-xs-12">
					<h3 style="color:#<?php header_textcolor(); ?>;"><?php echo $custom_theme_options['jumbo_headline_title']; ?></h3>
					<i style="color:#<?php header_textcolor(); ?>;" class="jumbo-icon icon-star-empty"></i>
					<p class="lead"><?php if ( is_front_page() ) { echo $custom_theme_options['jumbo_headline_text'];} elseif ( is_home() ){ echo '<small>'.bloginfo( 'description' ).'</small>';} ?></p>
					<?php if ( ! empty( $custom_theme_options['jumbo_headline_button_text'] ) ) { ?>
					<small><?php echo bloginfo( 'description' ); ?></small>
					<a class="btn btn-lg btn-primary" href="<?php echo $custom_theme_options['jumbo_headline_button_link']; ?>"><?php echo $custom_theme_options['jumbo_headline_button_text']; ?></a>
					<?php } ?>
				</div>
			
	<?php
	}
}

/**
 * Create the default widgets that are displayed in the home page top area
 *
 * @since 1.0
 */
function custom_home_page_default_widgets() {
	global $paged;
	$custom_theme_options = custom_theme_options();
	if ( $custom_theme_options['home_widget'] && is_front_page() && 2 > $paged ) {
		?>
	<div id="home-page-widgets" style="background-color:#<?php header_textcolor(); ?>;">
		<div class="container">
			<div class="row">
			<?php if ( ! dynamic_sidebar( 'home-page-top-area' ) ) : ?>
				<?php
				/**
				 * Default home page top area widgets
				 */
				?>
				<aside class="home-widget col-md-4 custom_custom_text_widget">
					<img src="<?php echo CYBER_DC_custom_URL; ?>/images/sample1.png" alt="" class="aligncenter" />
					<h3 class="home-widget-title">Mobile First</h3>
					<div class="textwidget">
						<p>100% Responsive. With <strong><?php echo CYBER_DC_custom; ?></strong> you just simply resize your browser and watch how this theme adjusts from smartphones, to tablets and desktops.</p>
					</div>
				</aside>

				<aside class="home-widget col-md-4 custom_custom_text_widget">
					<img src="<?php echo CYBER_DC_custom_URL; ?>/images/sample2.png" alt="" class="aligncenter" />
					<h3 class="home-widget-title">Launch Ready!</h3>
					<div class="textwidget">
						<p>Launch your new business with <?php echo CYBER_DC_custom; ?>! Use the Theme Options to customize this theme to your liking and preview your changes in real time before going live!</p>
					</div>
					<?php if ( current_user_can( 'edit_theme_options' ) ) { ?>
						<div class=""><?php printf( __( '%sClick Here to Change Widgets%s', 'custom' ), '<a href="' . admin_url( 'widgets.php' ) . '">', '</a>', '<a href="' . admin_url( 'customize.php' ) . '">', '</a>' ); ?></div>
				<?php } ?>
				</aside>

				<aside class="home-widget col-md-4 custom_custom_text_widget">
					<img src="<?php echo CYBER_DC_custom_URL; ?>/images/sample3.png" alt="" class="aligncenter" />
					<h3 class="home-widget-title">Bootstrap 3</h3>
					<div class="textwidget">
						<p>Avail of all the great Bootstrap elements that you know and love, except now it is all mobile first! <strong><?php echo CYBER_DC_custom; ?></strong> is proudly powered by WordPress and Bootstrap. <a href="#">Link color test</a></p>
					</div>
				</aside>
			<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
	}
}


class custom_Customizer {
	public function __construct() {

		add_action( 'customize_register', array( $this, 'customize_register' ) );
	}

	/**
	 * Adds theme options to the Customizer screen
	 *
	 * This function is attached to the 'customize_register' action hook.
	 *
	 * @param	class $wp_customize
	 *
	 * @since 1.0
	 */
	public function customize_register( $wp_customize ) {
		$custom_theme_options = custom_theme_options();

		// Layout section panel
		$wp_customize->add_section( 'custom_layout', array(
			'title' => __( 'Layout', 'custom' ),
			'priority' => 35,
		) );

		$wp_customize->add_setting( 'custom_theme_options[width]', array(
			'default' => $custom_theme_options['width'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'custom_width', array(
			'label' => __( 'Site Width', 'custom' ),
			'section' => 'custom_layout',
			'settings' => 'custom_theme_options[width]',
			'priority' => 10,
			'type' => 'select',
			'choices' => array(
				'1200' => __( '1200px', 'custom' ),
				'960' => __( '960px', 'custom' ),
			),
		) );

		$wp_customize->add_setting( 'custom_theme_options[layout]', array(
			'default' => $custom_theme_options['layout'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'custom_site_layout', array(
			'label' => __( 'Site Layout', 'custom' ),
			'section' => 'custom_layout',
			'settings' => 'custom_theme_options[layout]',
			'priority' => 15,
			'type' => 'radio',
			'choices' => array(
				'1' => __( '1 Sidebar - Left', 'custom' ),
				'2' => __( '1 Sidebar - Right', 'custom' ),
				'6' => __( 'No Sidebars', 'custom' )
			),
		) );

		$choices =  array(
			'col-md-2' 	=> '17%',
			'col-md-3' 	=> '25%',
			'col-md-4' 	=> '34%',
			'col-md-5' 	=> '42%',
			'col-md-6' 	=> '50%',
			'col-md-7' 	=> '58%',
			'col-md-8' 	=> '66%',
			'col-md-9' 	=> '75%',
			'col-md-10' => '83%',
			'col-md-12' => '100%',
		);

		$wp_customize->add_setting( 'custom_theme_options[primary]', array(
			'default' => $custom_theme_options['primary'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'custom_primary_column', array(
			'label' => __( 'Main Content', 'custom' ),
			'section' => 'custom_layout',
			'settings' => 'custom_theme_options[primary]',
			'priority' => 20,
			'type' => 'select',
			'choices' => $choices,
		) );

		$wp_customize->add_setting( 'custom_theme_options[excerpt_content]', array(
			'default' => $custom_theme_options['excerpt_content'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'custom_excerpt_content', array(
			'label' => __( 'Post Content Display', 'custom' ),
			'section' => 'custom_layout',
			'settings' => 'custom_theme_options[excerpt_content]',
			'priority' => 30,
			'type' => 'radio',
			'choices' => array(
				'excerpt' => __( 'Teaser Excerpt', 'custom' ),
				'content' => __( 'Full Content', 'custom' ),
			),
		) );

		$wp_customize->add_setting( 'custom_theme_options[home_widget]', array(
			'default' => $custom_theme_options['home_widget'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'custom_home_widget', array(
			'label' => __( 'Display Home Page Top Widget Area', 'custom' ),
			'section' => 'custom_layout',
			'settings' => 'custom_theme_options[home_widget]',
			'priority' => 35,
			'type' => 'checkbox',
		) );

		$wp_customize->add_setting( 'custom_theme_options[home_posts]', array(
			'default' => $custom_theme_options['home_posts'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'custom_home_posts', array(
			'label' => __( 'Display Home Page Posts', 'custom' ),
			'section' => 'custom_layout',
			'settings' => 'custom_theme_options[home_posts]',
			'priority' => 40,
			'type' => 'checkbox',
		) );

		// Jumbo headline section panel
		$wp_customize->add_section( 'custom_jumbo', array(
			'title' => __( 'Jumbo Headline', 'custom' ),
			'priority' => 36,
		) );

		$wp_customize->add_setting( 'custom_theme_options[jumbo_headline_title]', array(
			'default' => $custom_theme_options['jumbo_headline_title'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'custom_jumbo_headline_title', array(
			'label' => __( 'Jumbo Headline Title', 'custom' ),
			'section' => 'custom_jumbo',
			'settings' => 'custom_theme_options[jumbo_headline_title]',
			'priority' => 26,
			'type' => 'text',
		) );

		$wp_customize->add_setting( 'custom_theme_options[jumbo_headline_text]', array(
			'default' => $custom_theme_options['jumbo_headline_text'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'custom_jumbo_headline_text', array(
			'label' => __( 'Jumbo Headline Text', 'custom' ),
			'section' => 'custom_jumbo',
			'settings' => 'custom_theme_options[jumbo_headline_text]',
			'priority' => 27,
			'type' => 'text',
		) );

		// Posts panel
		$wp_customize->add_section( 'custom_posts', array(
			'title' => __( 'Posts', 'custom' ),
			'priority' => 45,
		) );

		$wp_customize->add_setting( 'custom_theme_options[display_categories]', array(
			'default' => $custom_theme_options['display_categories'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'custom_display_categories', array(
			'label' => __( 'Display Categories', 'custom' ),
			'section' => 'custom_posts',
			'settings' => 'custom_theme_options[display_categories]',
			'type' => 'checkbox',
		) );

		$wp_customize->add_setting( 'custom_theme_options[display_author]', array(
			'default' => $custom_theme_options['display_author'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'custom_display_author', array(
			'label' => __( 'Display Author', 'custom' ),
			'section' => 'custom_posts',
			'settings' => 'custom_theme_options[display_author]',
			'type' => 'checkbox',
		) );

		$wp_customize->add_setting( 'custom_theme_options[display_date]', array(
			'default' => $custom_theme_options['display_date'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'custom_display_date', array(
			'label' => __( 'Display Date', 'custom' ),
			'section' => 'custom_posts',
			'settings' => 'custom_theme_options[display_date]',
			'type' => 'checkbox',
		) );

		$wp_customize->add_setting( 'custom_theme_options[display_comment_count]', array(
			'default' => $custom_theme_options['display_comment_count'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'custom_display_comment_count', array(
			'label' => __( 'Display Comment Count', 'custom' ),
			'section' => 'custom_posts',
			'settings' => 'custom_theme_options[display_comment_count]',
			'type' => 'checkbox',
		) );

		// Custom header section panel
		$wp_customize->add_section( 'custom_cust_header', array(
			'title' => __( 'Custom Header', 'custom' ),
			'priority' => 54,
		) );

		$wp_customize->add_setting( 'custom_theme_options[cust_header_headline_title]', array(
			'default' => $custom_theme_options['cust_header_headline_title'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'custom_cust_header_headline_title', array(
			'label' => __( 'Custom Header Headline Title', 'custom' ),
			'section' => 'custom_cust_header',
			'settings' => 'custom_theme_options[cust_header_headline_title]',
			'priority' => 34,
			'type' => 'text',
		) );

		$wp_customize->add_setting( 'custom_theme_options[cust_header_headline_text]', array(
			'default' => $custom_theme_options['cust_header_headline_text'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'custom_cust_header_headline_text', array(
			'label' => __( 'Custom Header Headline Text', 'custom' ),
			'section' => 'custom_cust_header',
			'settings' => 'custom_theme_options[cust_header_headline_text]',
			'priority' => 38,
			'type' => 'text',
		) );

		$wp_customize->add_setting( 'custom_theme_options[custom_header_display]', array(
			'default' => $custom_theme_options['custom_header_display'],
			'type' => 'option',
			'capability' => 'edit_theme_options',
		) );

		$wp_customize->add_control( 'custom_cust_header_display', array(
			'label' => __( 'Display Custom Header and Header Image (This box must be ticked to enable the Header Image feature below.)', 'custom' ),
			'section' => 'custom_cust_header',
			'settings' => 'custom_theme_options[custom_header_display]',
			'priority' => 25,
			'type' => 'checkbox',
		) );

		
	}
}
$custom_customizer = new custom_Customizer;