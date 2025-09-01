<?php

// Header Scripts
add_action('wp_head', 'add_to_head', -50);
function add_to_head()
{
	if (has_post_thumbnail()) {
		$thumb_id = get_post_thumbnail_id(get_the_ID());
		$featured_img_src = wp_get_attachment_image_src($thumb_id, 'large')[0];
		$featured_img_srcset = wp_get_attachment_image_srcset($thumb_id);
		printf('<link rel="preload" as="image" importance="high" href="%s" srcset="%s" />', $featured_img_src, $featured_img_srcset);
	}

	echo '<meta name="google-site-verification" content="NfdpW5_1t-4uTF5Rx4WNx2QGTDQuZNiDTCN8E5VFiC4" />';


	if(is_front_page()){
		echo '<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Botphonic",
  "url": "https://botphonic.ai/",
  "logo": "https://botphonic.ai/wp-content/uploads/2025/04/Botphonic-logo-1.svg",
  "sameAs": [
    "https://facebook.com/botphonic/",
    "https://x.com/botphonic/",
    "https://instagram.com/botphonicai/",
    "https://youtube.com/@BotPhonic/",
    "https://linkedin.com/company/botphonic/"
  ],
  "email": "mailto:contact@botphonic.ai",
  "description": "Botphonic is an AI-powered call assistant platform that helps businesses automate and scale customer communication with smart voice technology.",
  "founder": {
    "@type": "Person",
    "name": "Ketan Mangukiya",
	"jobTitle": "Founder & CEO"
  },
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "1915, 447 Broadway, 2nd Floor",
    "addressLocality": "New York",
    "addressRegion": "NY",
    "postalCode": "10013",
    "addressCountry": "US"
  }
}
</script>
';
	}
	echo '<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Automate Your Business With Botphonic AI Call Assistant",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web, Mobile",
  "offers": {
    "@type": "Offer",
    "priceCurrency": "USD",
    "price": "22.00",
    "url": "https://botphonic.ai/pricings-plans/"
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": 5,
    "bestRating": 5,
    "ratingCount": 25527
  }
}
</script>
';


	echo "<script>(function(w,d,s,l,i){
 w[l]=w[l]||[];
 w[l].push({ 'gtm.start':new Date().getTime(), event:'gtm.js' });
 var f=d.getElementsByTagName(s)[0], j=d.createElement(s), dl=l!='dataLayer'?'&l='+l:'';
 j.async=true;
 j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-KCWJMQZW');
</script>";
}

// ============================================================================================================================================================
add_action('wp_footer', 'mobile_validation');
function mobile_validation(){
?>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const phoneInput = document.getElementById('your-number');
		if (phoneInput) {
			phoneInput.addEventListener('input', function () {
				// Allow only digits, +, (, )
				let cleaned = this.value.replace(/[^\d()+\s]/g, '');
				// Limit to 15 characters total (including symbols and space)
				this.value = cleaned.slice(0, 15);
			});
		}
	});
</script>
<?php if(!(is_user_logged_in()) ) { ?> 
<script type="text/javascript">
	window.onload = () => {
		window.lc_id = '431699510458';
		window.lc_dc = 'botphonic';
		let v = localStorage.getItem("dmVyc2lvbg==") || Date.now().toString();
		if (!document.querySelector('app-chat-box')) {
			var chatWidget = document.createElement('app-chat-box');
			chatWidget.setAttribute('id', "widget");
			document.body.insertAdjacentElement('beforeend', chatWidget);
		}
		var deskuInstall = document.createElement('script');
		deskuInstall.src = `https://widget.desku.io/chat-widget.js?v=${v}`;
		deskuInstall.setAttribute('defer', true);
		document.body.insertAdjacentElement('beforeend', deskuInstall);
	}
</script>
<?php 
}
							}
// ============================================================================================================================================================


// Footer Scripts

if (!is_page(973)) {
	add_action('wp_footer', 'AI_popup');
}

function AI_popup(){ ?>
<style>
	.partner-modal-backdrop { position: fixed; top: 0; left: 0; width: 100%; height: 100vh; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(6px); display: none; align-items: center; justify-content: center; z-index: 9999;	padding: 12px; }
	.partner-modal { width: 100%; max-width: 540px; background: rgba(255, 255, 255, 0.95); border-radius: 12px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15); padding: 20px; position: relative; animation: fadeInUp 0.3s ease-out;	}
	.partner-modal h2 { margin-top: 0; font-size: 28px; color: #222; }
	.partner-modal p { font-size: 15px; color: #555; margin-bottom: 24px; }
	.partner-modal-close { position: absolute; top: 12px; right: 12px; background: #eee; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 20px; color: #fff; padding:0; background-color: var(--accent); } 
	.partner-modal-close:hover { background: #ccc;	}
	.partner-modal .wpcf7-form .w-form .field-wrapper{ margin-bottom: 8px; }
	.partner-modal .wpcf7-form .w-form .w-textarea { height: 80px; }	

	@media screen and (min-width: 992px){
		.partner-modal{ padding: 32px; border-radius: 20px; }	
		.partner-modal .wpcf7-form .w-form .field-wrapper{ margin-bottom: 12px; }
		.partner-modal .wpcf7-form .w-form .w-textarea { height: 120px; }
	}

	@keyframes fadeInUp {
		from { opacity: 0; transform: translateY(30px); }
		to { opacity: 1; transform: translateY(0); }
	}
</style>

<!-- Partner Modal -->
<div id="partnerModalBackdrop" class="partner-modal-backdrop">
	<div class="partner-modal">
		<button class="partner-modal-close" aria-label="Close Modal">&times;</button>
		<h2>Become a Partner</h2>
		<p>Collaborate with us to expand reach and maximize impact. Fill the form below:</p>
		<div class="form-area">
			<?= do_shortcode('[contact-form-7 id="d812351" title="Become A Partner"]'); ?>
		</div>
	</div>
</div>

<script>
	document.addEventListener("DOMContentLoaded", function () {
		const modal = document.getElementById("partnerModalBackdrop");
		const closeBtn = modal.querySelector(".partner-modal-close");
		const openModal = () => modal.style.display = "flex";
		const closeModal = () => modal.style.display = "none";
		closeBtn.addEventListener("click", closeModal);
		window.addEventListener("keydown", (e) => {
			if (e.key === "Escape") closeModal();
		});
		const openBtn = document.getElementById("openPartnerModalBtn");
		if (openBtn) openBtn.addEventListener("click", openModal);
	});
</script>


<?php }