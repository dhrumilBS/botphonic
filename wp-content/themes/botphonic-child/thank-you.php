<?php
/*
 * Template name: Thank you 
 * */

get_header(); 
?>
<style>
	.hero-card-wrapper .hero-card-item { display: flex; padding: 24px; flex-direction: column; align-items: center; gap: 24px; align-self: stretch; border-radius: 30px; background: var(--bg-dark-1); height: 100%; }
	.hero-card-wrapper .theme-btn { margin-top: auto; color: white;}
	.hero-card-wrapper .theme-btn:hover { color: var(--accent);}
</style>
<section class="hero-section section-padded dark-bg--bg2">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-9">
				<div class="text-center">
					<div class="heading text-center">
						<h1 class="title">Thank You, We Received Your Request.</h1>
						<p>Our sales team will contact you shortly for better understanding your requirements,<br>
							offering the custom made AI assistant solution.</p>
					</div>                       
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="hero-card-wrapper px-md-5">
			<div class="row row-cols-1 row-cols-lg-3 gy-4">
				<div class="col">
					<div class="hero-card-item dark-bg">
						<span class="icon--i75 w"> 
							<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="75" height="75" x="0" y="0" viewBox="0 0 32 32" xml:space="preserve" fill="currentcolor">
								<path d="M16 1C7.729 1 1 7.729 1 16s6.729 15 15 15 15-6.729 15-15S24.271 1 16 1zm0 27.097C9.33 28.097 3.903 22.67 3.903 16S9.33 3.903 16 3.903 28.097 9.33 28.097 16 22.67 28.097 16 28.097z"></path>
								<path d="M16 9.71h-2.895a1.451 1.451 0 1 0 0 2.903h1.443v8.226a1.451 1.451 0 1 0 2.904 0V11.16c0-.802-.65-1.451-1.452-1.451z"></path>
							</svg>
						</span>
						<div class="hero-card-text text-center">
							<div class="hero-card-text h3 w">
								Immediate response</div>
							<div class="hero-card-text paragraph p w">
								We respect your time and don’t want you to wait longer. Usually answer to customers on the same business day. For urgent queries, you can directly call our sales team.</div>
						</div>
					</div>
				</div>

				<div class="col">
					<div class="hero-card-item dark-bg">
						<span class="icon--i75 w">
							<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="75" height="75" x="0" y="0" viewBox="0 0 24 24" xml:space="preserve" fill="currentcolor">
								<path d="M12 24C5.383 24 0 18.617 0 12S5.383 0 12 0s12 5.383 12 12-5.383 12-12 12zm0-22C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm4 15a1 1 0 0 0-1-1h-4.781c.426-.37 1.069-.72 1.742-1.086 1.754-.956 4.156-2.265 4.035-5.131C15.907 7.662 14.152 6 12.001 6c-2.206 0-4 1.794-4 4a1 1 0 1 0 2 0c0-1.103.897-2 2-2 1.058 0 1.954.838 1.997 1.867.064 1.513-1.088 2.253-2.994 3.29-.99.54-1.925 1.049-2.559 1.797a1.839 1.839 0 0 0-.272 1.983 1.818 1.818 0 0 0 1.666 1.062h5.162a1 1 0 0 0 1-1z"></path>
							</svg>
						</span>
						<div class="hero-card-text text-center">
							<div class="hero-card-text h3 w">Rapid deployment</div>
							<div class="hero-card-text paragraph p w">Ease to install in any operating device due to automation tools that quickly download the necessary elements, decreasing the set up time.</div>
						</div>
					</div>
				</div>

				<div class="col">
					<div class="hero-card-item dark-bg">
						<span class="icon--i75 w">
							<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="75" height="75" x="0" y="0" viewBox="0 0 24 24" xml:space="preserve" fill="currentcolor">
								<path d="M12 0C5.383 0 0 5.383 0 12s5.383 12 12 12 12-5.383 12-12S18.617 0 12 0zm0 22C6.486 22 2 17.514 2 12S6.486 2 12 2s10 4.486 10 10-4.486 10-10 10zm4-8c0 2.206-1.794 4-4 4H9a1 1 0 1 1 0-2h3c1.103 0 2-.897 2-2s-.897-2-2-2h-2a1 1 0 1 1 0-2h2a1.001 1.001 0 0 0 0-2H9a1 1 0 1 1 0-2h3c1.654 0 3 1.346 3 3 0 .68-.236 1.301-.619 1.805A3.984 3.984 0 0 1 16 14z"></path>
							</svg>
						</span>
						<div class="hero-card-text text-center">
							<div class="hero-card-text h3 w">Execution Keeping You Safe Is Our Top Priority</div>
							<div class="hero-card-text paragraph p w">Botphonic adapts in the evolving work environment and business operations. We modified it without affecting the existing framework, which leads to save costs.
							</div>
						</div> 
					</div>
				</div>
			</div>

			<div class="btn-wrap text-center mt-4">
				<a class="theme-btn" href="/">Back to Home </a>
			</div>
		</div>
	</div>

</section>

<?php geT_footer(); ?>