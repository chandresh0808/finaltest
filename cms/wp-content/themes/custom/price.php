<?php

?>
<!-- script for readmore in testimonial sliders -->
<!-- 
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>


<style>

.morectnt span {
display: none;
}
</style>

<script>	
$(function() {

var showTotalChar = 150, showChar = "Read More", hideChar = "Read Less";
$('.audit_read_more').each(function() {
var content = $(this).text();
if (content.length > showTotalChar) {
var con = content.substr(0, showTotalChar);
var hcon = content.substr(showTotalChar, content.length - showTotalChar);
var txt= con +  '<span class="dots">...</span><span class="morectnt"><span>' + hcon + '</span>&nbsp;&nbsp;<a href="" class="showmoretxt">' + showChar + '</a></span>';
$(this).html(txt);
}
});
$(".showmoretxt").click(function() {
if ($(this).hasClass("sample")) {
$(this).removeClass("sample");
$(this).text(showChar);
} else {
$(this).addClass("sample");
$(this).text(hideChar);
}
$(this).parent().prev().toggle();
$(this).prev().toggle();
return false;
});
});
</script> -->
<?php
/*
Template Name: Pricing
*/
$custom_theme_options = custom_theme_options();
get_header();
?>
	<div class="price-page" id="primary"  <?php custom_primary_attr(); ?>>

		<?php while ( have_posts() ) : the_post(); ?>
			<div class="container">
			
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<!--<h1 class="entry-title"><?php the_title(); ?></h1>-->

					    <div class="entry-content">
						    <?php the_content( __( 'Read more &#133;', 'custom' ) ); ?>
					    </div><!-- .entry-content -->

					    <?php get_template_part( 'content', 'footer' ); ?>
				</article><!-- #post-<?php the_ID(); ?> -->
				<?php
				//comments_template( '', true );
				?>
		
			<?php
		endwhile; // end of the loop.
		?>
		</div>
		
		
	<div class="Pricing-qst">	
	<div class="container">
	<h1 class="font-size-xl txtc">Common Questions</h1>

	<?php
	$postsInCat = get_term_by('id','7','category');
    $postsInCat = $postsInCat->count;
	if ( $postsInCat == 1)
	{
		$class .= 'col-sm-12' ;
	}
	
	else if ( $postsInCat == 2)
	{
		$class .= 'col-sm-6';
	}
	else
	{
		$class .= 'col-sm-4';
	}
    $c = 0;
	//$class = '';
	query_posts('cat=7');
	if ( have_posts() ) : while ( have_posts() ) : the_post();
	$c++;
	?>
	
	<div class="<?php echo $class ?>">
	<h2 class="font-size-m font-family-bold"><?php the_title(); ?></h2>
	<div class="audit_read_more"><?php the_content(); ?></div>
	</div>
	
	<?php
	endwhile;endif;
	
	wp_reset_query();
	
	if($postsInCat ==0)
	{?>
	<div class="pricing-empty-questions">No Questions Found</div>	
	<?php }
	
	
	?>
	
	</div>
	</div>
	</div>
	
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="<?php echo get_template_directory_uri();?>/js/iframeResizer.min.js"></script>
	
		<script type="text/javascript">
			/*
			 * If you do not understand what the code below does, then please just use the
			 * following call in your own code.
			 *
			 *   iFrameResize({log:true});
			 *
			 * Once you have it working, set the log option to false.
			 */
			iFrameResize({
				log                     : true,                  // Enable console logging
				enablePublicMethods     : true,                  // Enable methods within iframe hosted page
				enableInPageLinks       : true,
				resizedCallback         : function(messageData){ // Callback fn when resize is received
					$('p#callback').html(
						'<b>Frame ID:</b> '    + messageData.iframe.id +
						' <b>Height:</b> '     + messageData.height +
						' <b>Width:</b> '      + messageData.width +
						' <b>Event type:</b> ' + messageData.type
					);
				},
				messageCallback         : function(messageData){ // Callback fn when message is received
					$('p#callback').html(
						'<b>Frame ID:</b> '    + messageData.iframe.id +
						' <b>Message:</b> '    + messageData.message
					);
					alert(messageData.message);
				},
				closedCallback         : function(id){ // Callback fn when iFrame is closed
					$('p#callback').html(
						'<b>IFrame (</b>'    + id +
						'<b>) removed from page.</b>'
					);
				}
			});
		</script>
<?php get_footer(); ?>
