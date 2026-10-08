( function ( window, document ) {
	'use strict';

	var SLIDER_SELECTOR = '.botphonic-industry-slider';
	var LAZY_MARGIN     = '250px 0px';

	var ICON_PLAY  = '<svg data-icon="play" xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 12 12" height="16" fill="none"><path d="M11.25 5.99999C11.2503 6.12731 11.2177 6.25255 11.1552 6.36352C11.0928 6.4745 11.0027 6.56742 10.8937 6.63327L4.14 10.7648C4.02613 10.8346 3.89572 10.8726 3.76222 10.8751C3.62873 10.8776 3.49699 10.8444 3.38063 10.7789C3.26536 10.7144 3.16935 10.6205 3.10245 10.5066C3.03556 10.3928 3.00019 10.2631 3 10.1311V1.8689C3.00019 1.73684 3.03556 1.60722 3.10245 1.49337C3.16935 1.37951 3.26536 1.28553 3.38063 1.22108C3.49699 1.15562 3.62873 1.12241 3.76222 1.12488C3.89572 1.12736 4.02613 1.16542 4.14 1.23515L10.8937 5.36671C11.0027 5.43255 11.0928 5.52548 11.1552 5.63645C11.2177 5.74743 11.2503 5.87266 11.25 5.99999Z" fill="currentColor"></path></svg>';
	var ICON_PAUSE = '<svg data-icon="pause" xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 12 12" height="16" fill="none"><path d="M2.30038 3.6C2.30038 2.8456 2.30038 2.4688 2.53478 2.2344C2.76918 2 3.14598 2 3.90038 2C4.65478 2 5.03158 2 5.26598 2.2344C5.50038 2.4688 5.50038 2.8456 5.50038 3.6V8.4C5.50038 9.1544 5.50038 9.5312 5.26598 9.7656C5.03158 10 4.65478 10 3.90038 10C3.14598 10 2.76918 10 2.53478 9.7656C2.30038 9.5312 2.30038 9.1544 2.30038 8.4V3.6ZM7.10038 3.6C7.10038 2.8456 7.10038 2.4688 7.33478 2.2344C7.56918 2 7.94598 2 8.70038 2C9.45478 2 9.83158 2 10.066 2.2344C10.3004 2.4688 10.3004 2.8456 10.3004 3.6V8.4C10.3004 9.1544 10.3004 9.5312 10.066 9.7656C9.83158 10 9.45478 10 8.70038 10C7.94598 10 7.56918 10 7.33478 9.7656C7.10038 9.5312 7.10038 9.1544 7.10038 8.4V3.6Z" fill="currentColor"></path></svg>';

	var WAVE_OPTIONS = {
		waveColor: '#d1c9f5',
		progressColor: '#6d3ee3',
		cursorColor: '#6d3ee3',
		barWidth: 2,
		barRadius: 3,
		height: 40,
		responsive: true
	};

	var active = { wave: null, card: null, button: null, swiper: null };

	function paint( card, button, isPlaying ) {
		var playLabel  = card.getAttribute( 'data-label-play' ) || 'Play voice preview';
		var pauseLabel = card.getAttribute( 'data-label-pause' ) || 'Pause voice preview';

		button.innerHTML = isPlaying ? ICON_PAUSE : ICON_PLAY;
		button.setAttribute( 'aria-label', isPlaying ? pauseLabel : playLabel );
		button.setAttribute( 'aria-pressed', isPlaying ? 'true' : 'false' );

		if ( isPlaying ) {
			card.classList.add( 'playing' );
		} else {
			card.classList.remove( 'playing' );
		}
	}

	function autoplay( swiper, action ) {
		if ( swiper && swiper.autoplay && typeof swiper.autoplay[ action ] === 'function' ) {
			swiper.autoplay[ action ]();
		}
	}

	function clearActive( exceptWave ) {
		if ( active.wave && active.wave !== exceptWave ) {
			active.wave.pause();

			if ( active.card && active.button ) {
				paint( active.card, active.button, false );
			}

			autoplay( active.swiper, 'start' );
		}

		active = { wave: null, card: null, button: null, swiper: null };
	}

	function initCard( card, swiper ) {
		if ( ! card || card.dataset.botphonicReady === '1' ) {
			return;
		}

		var audioUrl  = card.getAttribute( 'data-audio' );
		var button     = card.querySelector( '.play-button' );
		var container  = card.querySelector( '.waveform' );

		if ( ! audioUrl || ! button || ! container ) {
			return;
		}

		if ( typeof window.WaveSurfer === 'undefined' ) {
			return;
		}

		card.dataset.botphonicReady = '1';
		var options = {};
		var key;
		for ( key in WAVE_OPTIONS ) {
			if ( Object.prototype.hasOwnProperty.call( WAVE_OPTIONS, key ) ) {
				options[ key ] = WAVE_OPTIONS[ key ];
			}
		}
		options.container = container;

		var wave        = window.WaveSurfer.create( options );
		var isReady     = false;
		var playPending = false;

		container._wavesurfer = wave;

		wave.on( 'ready', function () {
			isReady = true;

			if ( playPending ) {
				playPending = false;
				wave.play();
			}
		} );

		wave.on( 'finish', function () {
			paint( card, button, false );
			clearActive();
			autoplay( swiper, 'start' );
		} );

		wave.on( 'error', function () {
			card.classList.add( 'is-audio-error' );
			paint( card, button, false );
		} );

		var loading = wave.load( audioUrl );
		if ( loading && typeof loading.catch === 'function' ) {
			loading.catch( function () {} );
		}

		button.addEventListener( 'click', function () {
			if ( wave.isPlaying() ) {
				wave.pause();
				paint( card, button, false );
				clearActive();
				autoplay( swiper, 'start' );
				return;
			}

			clearActive( wave );
			autoplay( swiper, 'stop' );

			if ( isReady ) {
				wave.play();
			} else {
				playPending = true;
			}

			paint( card, button, true );
			active = { wave: wave, card: card, button: button, swiper: swiper };
		} );
	}

	function initSlider( wrapper ) {
		if ( ! wrapper || wrapper.dataset.botphonicSliderReady === '1' ) {
			return;
		}

		var container = wrapper.querySelector( '.industrySwiper' );

		if ( ! container || typeof window.Swiper === 'undefined' ) {
			return;
		}

		wrapper.dataset.botphonicSliderReady = '1';

		var swiper = new window.Swiper( container, {
			slidesPerView: 3,
			spaceBetween: 30,
			loop: true,
			centeredSlides: true,
			autoplay: {
				delay: 2500,
				disableOnInteraction: false
			},
			breakpoints: {
				0: { slidesPerView: 1 },
				651: { slidesPerView: 2 },
				1367: { slidesPerView: 3 }
			}
		} );

		container.addEventListener( 'mouseenter', function () {
			autoplay( swiper, 'stop' );
		} );

		container.addEventListener( 'mouseleave', function () {
			if ( active.swiper !== swiper ) {
				autoplay( swiper, 'start' );
			}
		} );

		function initVisibleSlides() {
			var slides = swiper.slides;

			if ( ! slides || ! slides.length ) {
				return;
			}

			var visible = container.querySelectorAll( '.swiper-slide-visible' );

			if ( visible.length ) {
				Array.prototype.forEach.call( visible, function ( slide ) {
					initCard( slide.querySelector( '.industry-card' ), swiper );
				} );
				return;
			}

			[ swiper.activeIndex, swiper.activeIndex + 1 ].forEach( function ( i ) {
				var slide = slides[ i % slides.length ];

				if ( slide ) {
					initCard( slide.querySelector( '.industry-card' ), swiper );
				}
			} );
		}

		initVisibleSlides();
		swiper.on( 'slideChange', initVisibleSlides );
		swiper.on( 'transitionEnd', initVisibleSlides );
	}

	function observeSlider( wrapper ) {
		if ( ! window.IntersectionObserver ) {
			initSlider( wrapper );
			return;
		}

		var observer = new window.IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					observer.unobserve( entry.target );
					initSlider( entry.target );
				}
			} );
		}, { rootMargin: LAZY_MARGIN } );

		observer.observe( wrapper );
	}

	function initAll() {
		Array.prototype.forEach.call( document.querySelectorAll( SLIDER_SELECTOR ), observeSlider );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initAll );
	} else {
		initAll();
	}

	if ( window.jQuery ) {
		window.jQuery( window ).on( 'elementor/frontend/init', function () {
			if ( ! window.elementorFrontend || ! window.elementorFrontend.hooks ) {
				return;
			}

			window.elementorFrontend.hooks.addAction(
				'frontend/element_ready/botphonic-industry-slider.default',
				function ( $scope ) {
					var root = $scope && $scope[0] ? $scope[0] : $scope;

					if ( ! root || ! root.querySelectorAll ) {
						return;
					}

					Array.prototype.forEach.call( root.querySelectorAll( SLIDER_SELECTOR ), initSlider );
				}
			);
		} );
	}
} )( window, document );