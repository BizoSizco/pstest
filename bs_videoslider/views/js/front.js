/**
 * @author    BizoSizco <info@bizosiz.com>
 * @copyright 2025 BizoSizco
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 * Last updated: 2025-02-21 01:31:01 by BizoSizco
 */

$(document).ready(function() {
    // Initialize video slider
    const initVideoSlider = function() {
        $('.bs-videoslider-wrapper').slick({
            dots: bs_videoslider.dots,
            arrows: bs_videoslider.arrows,
            infinite: bs_videoslider.infinite,
            autoplay: bs_videoslider.autoplay,
            autoplaySpeed: bs_videoslider.speed,
            slidesToShow: bs_videoslider.desktop,
            slidesToScroll: 1,
            rtl: $('body').hasClass('lang-rtl'),
            prevArrow: '<button type="button" class="bs-slider-prev"><i class="material-icons">chevron_left</i></button>',
            nextArrow: '<button type="button" class="bs-slider-next"><i class="material-icons">chevron_right</i></button>',
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: bs_videoslider.desktop
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: bs_videoslider.tablet
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: bs_videoslider.mobile,
                        arrows: false
                    }
                }
            ]
        });
    };

    // Initialize video players
    const initVideoPlayers = function() {
        $('.bs-video-frame').each(function() {
            const $frame = $(this);
            const $video = $frame.find('video');
            
            // Handle direct video players
            if ($video.length) {
                $video.on('loadedmetadata', function() {
                    $frame.addClass('video-loaded');
                }).on('error', function() {
                    showVideoError($frame);
                });

                // Add play/pause on click
                $frame.on('click', function() {
                    const video = $video[0];
                    if (video.paused) {
                        video.play();
                    } else {
                        video.pause();
                    }
                });
            }
            
            // Handle iframes (Aparat and others)
            const $iframe = $frame.find('iframe');
            if ($iframe.length) {
                $iframe.on('load', function() {
                    $frame.addClass('video-loaded');
                }).on('error', function() {
                    showVideoError($frame);
                });
            }
        });
    };

    // Error handling
    const showVideoError = function($frame) {
        $frame.html('<div class="bs-video-error">' + bs_videoslider_messages.video_error + '</div>');
    };

    // Lazy loading for videos
    const lazyLoadVideos = function() {
        const options = {
            root: null,
            rootMargin: '50px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const $frame = $(entry.target);
                    const $placeholder = $frame.find('.video-placeholder');
                    
                    if ($placeholder.length) {
                        const videoUrl = $placeholder.data('video');
                        const videoType = $placeholder.data('type');
                        
                        if (videoType === 'direct') {
                            $placeholder.replaceWith(`<video src="${videoUrl}" controls preload="metadata"></video>`);
                        } else {
                            $placeholder.replaceWith(videoUrl); // For iframes
                        }
                        
                        observer.unobserve(entry.target);
                    }
                }
            });
        }, options);

        $('.bs-video-frame').each(function() {
            observer.observe(this);
        });
    };

    // Handle window resize
    const handleResize = function() {
        const $slider = $('.bs-videoslider-wrapper');
        if ($slider.length) {
            if (window.innerWidth < 768) {
                if (!$slider.hasClass('mobile-initialized')) {
                    $slider.slick('unslick').slick({
                        slidesToShow: bs_videoslider.mobile,
                        arrows: false,
                        dots: true
                    });
                    $slider.addClass('mobile-initialized');
                }
            } else {
                if ($slider.hasClass('mobile-initialized')) {
                    $slider.slick('unslick');
                    initVideoSlider();
                    $slider.removeClass('mobile-initialized');
                }
            }
        }
    };

    // Performance optimization
    const debounce = function(func, wait) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    };

    // Initialize everything
    const init = function() {
        initVideoSlider();
        initVideoPlayers();
        lazyLoadVideos();
        $(window).on('resize', debounce(handleResize, 250));
        
        // Stop videos when sliding
        $('.bs-videoslider-wrapper').on('beforeChange', function() {
            $('.bs-video-frame video').each(function() {
                this.pause();
            });
        });
    };

 // Start when document is ready
    init();
    
    // Add keyboard navigation
    $(document).keydown(function(e) {
        // Left arrow key
        if (e.keyCode === 37) {
            $('.bs-slider-prev').click();
        }
        // Right arrow key
        if (e.keyCode === 39) {
            $('.bs-slider-next').click();
        }
        // Space bar - Play/Pause current video
        if (e.keyCode === 32 && e.target === document.body) {
            e.preventDefault();
            const $currentVideo = $('.slick-current video')[0];
            if ($currentVideo) {
                if ($currentVideo.paused) {
                    $currentVideo.play();
                } else {
                    $currentVideo.pause();
                }
            }
        }
    });

    // Add touch swipe support
    $('.bs-videoslider-wrapper').on('touchstart touchmove touchend', function(e) {
        const touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
        
        if (e.type === 'touchstart') {
            $(this).data('touchStartX', touch.pageX);
        } else if (e.type === 'touchend') {
            const touchStartX = $(this).data('touchStartX');
            const touchEndX = touch.pageX;
            const threshold = 50;
            
            if (Math.abs(touchStartX - touchEndX) > threshold) {
                if (touchEndX < touchStartX) {
                    $('.bs-slider-next').click();
                } else {
                    $('.bs-slider-prev').click();
                }
            }
        }
    });
});