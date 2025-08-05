<?php
/* Template Name: Legal */
?>
<?php get_header(); ?>
<style>
    .policy-page { background-color: #f9f9fb; line-height: 1.7; }
    .policy-page .container { max-width: 800px; text-align: justify; } 
	.policy-page h2 { font-size: 2.2em; margin-bottom: 20px; border-bottom: 3px solid var(--accent); padding-bottom: 10px; text-align: center }
	
	.policy-page p { font-size: 16px; }
    .policy-page p:last-child { margin-bottom: 0; }
	
    .policy-page .h3 { font-size: 1.25em;; margin:0; }
	.policy-page .term-list { counter-reset: section; list-style: none; padding-left: 0; }
    .policy-page :is(.wp-block-group-is-layout-flex) { counter-increment: section; background: #ffffff; padding: 20px; border-left: 3px solid var(--accent); border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); } 
	.policy-page :is(.wp-block-group-is-layout-flex) .h3> strong { font-size: 20px; display: flex; align-items: center; gap: 4px; }
    .policy-page :is(.wp-block-group-is-layout-flex) .h3>strong::before { content: counter(section) ". "; font-weight: bold; color: var(--accent); font-size: 1em; display: block; float: left; }
    .policy-page :is(.wp-block-group-is-layout-flex) a { color: var(--secondary); text-decoration: none; font-size: 16px; }
    .policy-page :is(.wp-block-group-is-layout-flex) a:hover { text-decoration: underline; }
	
	.wp-block-list {list-style-type: disc; list-style-position: inside; margin-left: 20px; }
	.wp-block-list> li> ul{ list-style-type: circle; list-style-position: inside;  }
	
	.wp-block-group+.wp-block-group{ margin-top: 16px; }
</style>
<div class="policy-page section-padded">
   <div class="container">
    <?php the_content(); ?>
   </div>
</div>



<?php get_footer(); ?>