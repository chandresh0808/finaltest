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

<link rel="icon" href="<?php echo get_site_url();?>/wp-content/themes/custom/images/favicon.ico" type="image/x-ico"/>


<style>
/* expandable search css files starts*/

.search-form {
	position: absolute;
	right: 85px;	
	top: 25px;
}

.search-field {
	background-color: transparent;
	background-image: url(<?php echo get_site_url();?>/wp-content/themes/custom/images/search.png);
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
	border: 1px solid #ccc;
	cursor: text;
	outline: 0;
	width: 300px;
	
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


aside#meta
{
display:none;
}

</style>



<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->


 <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

<script>
    var jqueryVar = jQuery.noConflict();
    jqueryVar( document ).ready(function() {
        
   	    jqueryVar(".wpcf7-submit").click(function(){
   	    	setTimeout(function() {
   	     	jqueryVar(".wpcf7-response-output").fadeOut(2000);
   	         //window.location.reload(true);
   	     	},3000)
    	});
    	
      jqueryVar(".mc4wp-alert").delay(4500).fadeOut(300);
    	
    });
</script>






<?php wp_head(); ?>   
</head>

<body>
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
	<?php
	 if(is_front_page())
	{?>
	<header class="navbar navbar-default navbar-fixed-top" role="navigation">
	<?php }
	else 
	{?>
	<header class="navbar navbar-default navbarinnerpages" role="navigation">
	<?php }
	?>
	
     <div class="container">
     <div class="navbar-header">
	   <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
		</button>
		<a id="site-title" class="navbar-brand" style="color:#<?php header_textcolor(); ?>;" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"  class="navbar-brand"><img class="small-logo" src="<?php echo site_url();?>/wp-content/themes/custom/images/audit.png">
<img class="large-logo" src="<?php echo site_url();?>/wp-content/themes/custom/images/Audit_Logo.png"> </a>
	  </div>
				        
      <div class="header_right mar-t20 mar-r30 mar-b20">
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
		//custom_jumbotron();
		//custom_home_page_default_widgets(); ?>
			
		
	

		 <?php 
		 if(is_front_page())
		 {?>
		 
		 <!-- Banner background starts -->
		 <?php
		 $feat_image = wp_get_attachment_url( get_post_thumbnail_id(40) ); 
	 	 ?>
		 <div class="banner-image"><img src="<?php echo $feat_image;?>" alt="bg"/></div>	
		 <?php 
		 ?>
		  <!-- Banner background ends -->
		  
		  
		 <!-- Slider module starts -->
		 <div class="container content">
		 <?php
		  if(function_exists("get_testimonial_slider_recent"))
		  {
		  get_testimonial_slider_recent($set="1");
		  }
		  ?>
		  </div>
		  <!-- Slider module ends -->
		  
		  <!-- Accounting professionals bg starts -->
		  <?php }
		  ?>
		  <!-- Accounting professionals bg ends -->
			
		
	     
		   
		  <?php 
		  if(is_front_page())
		  {?>
		  <div class="hometxtcontent">
		  
		   <?php
		  $feat_image = wp_get_attachment_url( get_post_thumbnail_id(43) ); 
	 	  ?>
		  <div class="home-macbook"><img src="<?php echo $feat_image;?>" alt="bg"/></div>
		  <?php 
		   ?>
			
		  <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('home_testimonials') ) : endif; ?>
		  </div>
		  <?php }
		  ?>
		  
			 
		 <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('home_offer') ) : endif; ?>
		 
		 
		
		 

		<div id="main">
			<div class="row">
			<?php
			/* Do not display sidebars if full width option selected on single
			post/page templates */
			if ( is_custom_full_width() && 5 == $custom_theme_options['layout'] )
				get_sidebar(); ?>
			
                
                
<script>
    
/*!
 * classie - class helper functions
 * from bonzo https://github.com/ded/bonzo
 * 
 * classie.has( elem, 'my-class' ) -> true/false
 * classie.add( elem, 'my-new-class' )
 * classie.remove( elem, 'my-unwanted-class' )
 * classie.toggle( elem, 'my-class' )
 */

/*jshint browser: true, strict: true, undef: true */
/*global define: false */

( function( window ) {

'use strict';

// class helper functions from bonzo https://github.com/ded/bonzo

function classReg( className ) {
  return new RegExp("(^|\\s+)" + className + "(\\s+|$)");
}

// classList support for class management
// altho to be fair, the api sucks because it won't accept multiple classes at once
var hasClass, addClass, removeClass;

if ( 'classList' in document.documentElement ) {
  hasClass = function( elem, c ) {
    return elem.classList.contains( c );
  };
  addClass = function( elem, c ) {
    elem.classList.add( c );
  };
  removeClass = function( elem, c ) {
    elem.classList.remove( c );
  };
}
else {
  hasClass = function( elem, c ) {
    return classReg( c ).test( elem.className );
  };
  addClass = function( elem, c ) {
    if ( !hasClass( elem, c ) ) {
      elem.className = elem.className + ' ' + c;
    }
  };
  removeClass = function( elem, c ) {
    elem.className = elem.className.replace( classReg( c ), ' ' );
  };
}

function toggleClass( elem, c ) {
  var fn = hasClass( elem, c ) ? removeClass : addClass;
  fn( elem, c );
}

var classie = {
  // full names
  hasClass: hasClass,
  addClass: addClass,
  removeClass: removeClass,
  toggleClass: toggleClass,
  // short names
  has: hasClass,
  add: addClass,
  remove: removeClass,
  toggle: toggleClass
};

// transport
if ( typeof define === 'function' && define.amd ) {
  // AMD
  define( classie );
} else {
  // browser global
  window.classie = classie;
}

})( window );    
    
var cbpAnimatedHeader = (function() {

	var docElem = document.documentElement,
		header = document.querySelector( '.navbar-fixed-top' ),
		didScroll = false,
		changeHeaderOn = 50;

	function init() {
		window.addEventListener( 'scroll', function( event ) {
			if( !didScroll ) {
				didScroll = true;
				setTimeout( scrollPage, 250 );
			}
		}, false );
	}

	function scrollPage() {
		var sy = scrollY();
		if ( sy >= changeHeaderOn ) {
			classie.add( header, 'navbar-fixed-bg' );
		}
		else {
			classie.remove( header, 'navbar-fixed-bg' );
		}
		didScroll = false;
	}

	function scrollY() {
		return window.pageYOffset || docElem.scrollTop;
	}

	init();

})();    
</script>                
			

				