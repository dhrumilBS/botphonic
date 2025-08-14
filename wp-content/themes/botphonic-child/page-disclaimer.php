<?php get_header(); ?>
<?php wp_enqueue_style("jv"); ?>

<style>
	.section-padded{ --padd: 40px; }
	@media screen and (max-width: 768){
		.section-padded{ --padd: 30px; }
	}
	@media screen and (max-width: 440px){
		.section-padded{ --padd: 25px; }
	}

	.custom-logo-link img{ max-width: 180px; height: auto; }
	.disclamer-section { min-height: calc(100lvh - 73px - 69px); }
	.text-justify { text-align: justify; }
</style>

<section class="border-bottom py-3 bg-light">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-4">
				<div class="logo-wrap">
					<?php the_custom_logo(); ?>
				</div>
			</div>
			<div class="col-8 text-end">
				<div class="affiliate-link-btn">
					<a class="theme-btn" href="#" target="_blank">Get Affiliate Link</a>

				</div>
			</div>
		</div>
	</div>
</section>

<!-- Disclaimer Content -->
<main class="py-5 section-padded disclamer-section">
	<div class="container" style="max-width: 768px; ">
		<h1 class="mb-4 fw-bold text-primary text-center">Disclaimer</h1>
		<div class="text-justify">

			<p class="text-muted-foreground fs-6">
				Please note that this product does not provide any guarantee of income or success. The results achieved by the product owner or any other individuals mentioned are not indicative of future success or earnings. This website is not affiliated with FaceBook or any of its associated entities. Once you navigate away from FaceBook, the responsibility for the content and its usage lies solely with the user.
			</p>
			<p class="text-muted-foreground fs-6">
				All content on this website, including but not limited to text, images, and multimedia, is protected by copyright law and the Digital Millennium Copyright Act. Unauthorized copying, duplication, modification, or theft, whether intentional or unintentional, is strictly prohibited. Violators will be prosecuted to the fullest extent of the law.
			</p>
			<p class="text-muted-foreground fs-6">
				We want to clarify that JVZoo serves as the retailer for the products featured on this site. JVZoo® is a registered trademark of BBC Systems Inc., a Florida corporation located at 1809 E. Broadway Street, Suite 125, Oviedo, FL 32765, USA, and is used with permission. The role of JVZoo as a retailer does not constitute an endorsement, approval, or review of these products or any claims, statements, or opinions used in their promotion.
			</p>

		</div>
	</div>
</main>


<?php get_footer(); ?>