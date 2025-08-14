<?php
/* Template Name: Legal */
?>
<?php get_header(); ?>
<style>
    .policy-page { background-color: #f9f9fb; line-height: 1.7; }
    .policy-page .container { max-width: 900px; } 
	.policy-page h1 { font-size: 48px; }
	.policy-page h2 { font-size: 2.2em; margin-bottom: 20px; border-bottom: 3px solid var(--accent); padding-bottom: 10px; text-align: center }
	.policy-page :is(h3, .h3) { font-size: 1.5em; margin:0; }
    .policy-page :is(h4, .h4) { font-size: 1.25em; margin:0; }
    .policy-page :is(h5, .h5) { font-size: 1em; margin:0; }
	
	.policy-page p { font-size: 16px; text-align: justify; }
    .policy-page p:last-child { margin-bottom: 0; }
		
    .policy-page .wp-block-heading:is(h4, h5) { margin-top:8px; }
	.policy-page .term-list { counter-reset: section; list-style: none; padding-left: 0; }
    .policy-page :is(.wp-block-group-is-layout-flex) { counter-increment: section; background: #ffffff; padding: 16px; border: 1px solid #dde3ff; border-radius: 16px; gap: 6px; } 
	.policy-page :is(.wp-block-group-is-layout-flex) :is(h3, .h3):is(h3, .h3)> strong { font-size: 20px; display: flex; gap: 4px; color: var(--accent)}
    .policy-page :is(.wp-block-group-is-layout-flex) :is(h3, .h3)>strong::before { content: counter(section) ". "; font-weight: bold; color: var(--accent); font-size: 1em; display: block; float: left; }
    .policy-page :is(.wp-block-group-is-layout-flex) a { color: var(--secondary); text-decoration: none; font-size: 16px; }
    .policy-page :is(.wp-block-group-is-layout-flex) a:hover { text-decoration: underline; }
	
	.is-layout-flex .wp-block-list {list-style-type: circle; margin-left: 20px; }
	.is-layout-flex .wp-block-list> li> ul{ list-style-type: circle; list-style-position: inside; }
	
	.wp-block-group+.wp-block-group{ margin-top: 16px; }
	
	@media screen and (max-width: 767px){
    .policy-page { line-height: normal; }
	.policy-page h1 { font-size: 30px; }
	}
</style>
<div class="policy-page section-padded">
   <div class="container">
    <?php the_content(); ?>
   </div>
</div>

<?php get_footer(); ?>