<?php
/**
 * This template opens the html5  document.
 *
 * @since 1.0
 */
$custom_theme_options = custom_theme_options();
?>

<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" <?php language_attributes(); ?>><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<head>

<style>
/* expandable search css files starts*/

.search-form {
	position: absolute;
	right: 20px;	
	top: 1px;
}

.search-field {
	background-color: transparent;
	background-image: url(http://api.ning.com:80/files/dr2jDpSXWFhyuoGJ4YR5Fu-PwuqLz70YxRaUcpdKNxf2HI5ECzddyx3NHuPz-MB4ujGS4rEtdtS-aQoFlQrtv*XwVOu*AvC8/searchicon.png);
	background-position: 5px center;
	background-repeat: no-repeat;
	background-size: 24px 24px;
	border: none;
	cursor: pointer;
	height: 37px;
	margin: 3px 0;
	padding: 0 0 0 34px;
	position: relative;
	-webkit-transition: width 400ms ease, background 400ms ease;
	transition:         width 400ms ease, background 400ms ease;
	width: 0;
}

.search-field:focus {
	background-color: #fff;
	border: 2px solid black;
	cursor: text;
	outline: 0;
	width: 230px;
}

.search-submit {
  display: none;	
}

input[type="search"] {
  -webkit-appearance: textfield;
}




input[type="searchdd"] {
  -webkit-appearance: textfield;
}
</style>





<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<?php wp_head(); ?>
</head>

<body <?php body_class( 'basic' ); ?>>
	<?php 	//echo'<pre>';
			//var_dump(custom_theme_options());
			//echo '</pre>';

				/**
				* @since 1.6
				* displays custom header 
				* @link http://codex.wordpress.org/Custom_Headers
				*/
				custom_admin_header_image();
				/**
				* @since 1.1
				* This displays the front page carousel with CTP Bootstrap Carousel plugin, that you can download to the WordPress Plugin Repository
				* @link http://wordpress.org/plugins/cpt-bootstrap-carousel/
				*/
				if ( function_exists( 'cptbc_shortcode' )  && is_front_page()){ echo do_shortcode('[image-carousel]');} ?>

	<div id="page">

    <header class="navbar-inverse navbar navbar" role="navigation">
    <div class="container">
    
    
          
		
				        <div class="navbar-header">
				            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					            <span class="icon-bar"></span>
					            <span class="icon-bar"></span>
					            <span class="icon-bar"></span>
							</button>
				            
				            <a id="site-title" class="navbar-brand" style="color:#<?php header_textcolor(); ?>;" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"  class="navbar-brand"><img src="<?php echo site_url();?>/wp-content/themes/custom/images/audit.png"></a>
				            
				          
				            
				            
				            
				        </div>
				        
				        
				        <div class="header_right">
						<div class="header_cart"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('header_sidebar') ) : endif; ?></div>
					    <div class="header_search"><?php get_search_form(); ?></div>
						</div>
				       
						
						<?php 
						$args = array('theme_location' => 'primary', 
							'container_class' => 'nav navbar-collapse collapse navbar-right custom-nav', 
							'menu_class' => 'nav navbar-nav', 
							'fallback_cb' => '', 
							'menu_id' => 'main-menu',
							'walker' => new Cyber_DC_Walker_Nav_Menu()); 
						wp_nav_menu($args);
						?>
						
				
						
						
						
					
		</div>	
		</header>

<?php
			custom_jumbotron();
			//custom_home_page_default_widgets(); ?>
			
			 <div class="container"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('home_testimonials') ) : endif; ?></div>
			 
			  <div class="container"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('home_offer') ) : endif; ?></div>

		<div id="main">
			<div class="row">
			<?php
			/* Do not display sidebars if full width option selected on single
			post/page templates */
			if ( is_custom_full_width() && 5 == $custom_theme_options['layout'] )
				get_sidebar(); ?>
			
			
				