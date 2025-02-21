{**
* @author BizoSizco <info@bizosiz.com>
    * @copyright 2025 BizoSizco
    * @license https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
    * Last updated: 2025-02-21 01:08:43 by BizoSizco
    *}

    {if isset($slider) && $slider}
    <div class="bs-videoslider-container">
        <div class="bs-videoslider"
            data-slides-desktop="{$slider.slides_desktop|intval}"
            data-slides-tablet="{$slider.slides_tablet|intval}"
            data-slides-mobile="{$slider.slides_mobile|intval}"
            data-autoplay="{$slider.autoplay|intval}"
            data-autoplay-speed="{$slider.autoplay_speed|intval}"
            data-infinite="{$slider.infinite|intval}"
            data-dots="{$slider.dots|intval}"
            data-arrows="{$slider.arrows|intval}">
            {foreach from=$videos item=video name=videos}
            <div class="bs-videoslider-item">
                <div class="video-container">
                    {if $video.video|strpos:'<iframe' !==false}
                        {* For iframe-based videos (like Aparat) *}
                        <div class="video-wrapper iframe-video">
                        {$video.video nofilter}
                </div>
                {elseif $video.video|strpos:'.mp4' !== false}
                {* For direct MP4 videos *}
                <div class="video-wrapper direct-video">
                    <video controls poster="{if $video.image}{$module_dir}{$video.image}{/if}">
                        <source src="{$video.video|escape:'html':'UTF-8'}" type="video/mp4">
                        {l s='Your browser does not support the video tag.' mod='bs_videoslider'}
                    </video>
                </div>
                {else}
                {* For other embed codes *}
                <div class="video-wrapper embed-video">
                    {$video.video nofilter}
                </div>
                {/if}
                <div class="video-info">
                    <h3 class="video-title">{$video.title|escape:'html':'UTF-8'}</h3>
                </div>
            </div>
        </div>
        {/foreach}
    </div>
    </div>

    {* Styles *}
    <style>
        .bs-videoslider-container {
            margin: 30px 0;
            padding: 0 15px;
        }

        .bs-videoslider {
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .bs-videoslider.slick-initialized {
            visibility: visible;
            opacity: 1;
        }

        .bs-videoslider-item {
            padding: 0 10px;
        }

        .video-container {
            position: relative;
            background: #f8f8f8;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .video-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .video-wrapper {
            position: relative;
            width: 100%;
            padding-top: 56.25%;
            /* 16:9 Aspect Ratio */
        }

        .video-wrapper video,
        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        .video-info {
            padding: 15px;
            background: rgba(255, 255, 255, 0.95);
        }

        .video-title {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: #333;
            text-align: center;
        }

        /* Slick Customization */
        .bs-videoslider .slick-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .bs-videoslider .slick-prev {
            left: -20px;
        }

        .bs-videoslider .slick-next {
            right: -20px;
        }

        .bs-videoslider .slick-arrow:before {
            font-family: "FontAwesome";
            font-size: 20px;
            color: #333;
        }

        .bs-videoslider .slick-prev:before {
            content: "\f104";
        }

        .bs-videoslider .slick-next:before {
            content: "\f105";
        }

        .bs-videoslider .slick-arrow:hover {
            background: #fff;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
        }

        .bs-videoslider .slick-dots {
            position: relative;
            bottom: 0;
            padding: 15px 0;
            margin: 0;
            list-style: none;
            text-align: center;
        }

        .bs-videoslider .slick-dots li {
            display: inline-block;
            margin: 0 5px;
        }

        .bs-videoslider .slick-dots li button {
            width: 10px;
            height: 10px;
            padding: 0;
            border: none;
            border-radius: 50%;
            background: #ddd;
            text-indent: -9999px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .bs-videoslider .slick-dots li.slick-active button {
            background: #2fb5d2;
            transform: scale(1.2);
        }

        /* Responsive Styles */
        @media (max-width: 1200px) {
            .video-title {
                font-size: 14px;
            }
        }

        @media (max-width: 768px) {
            .bs-videoslider-container {
                padding: 0 10px;
            }

            .bs-videoslider .slick-arrow {
                width: 30px;
                height: 30px;
            }

            .bs-videoslider .slick-prev {
                left: -15px;
            }

            .bs-videoslider .slick-next {
                right: -15px;
            }
        }

        @media (max-width: 576px) {
            .video-info {
                padding: 10px;
            }

            .video-title {
                font-size: 12px;
            }
        }
    </style>

    {* Scripts *}
    <script type="text/javascript">
        $(document).ready(function() {
            var $slider = $('.bs-videoslider');

            // Initialize Slick Slider
            $slider.slick({
                slidesToShow: $slider.data('slides-desktop'),
                slidesToScroll: 1,
                autoplay: Boolean($slider.data('autoplay')),
                autoplaySpeed: $slider.data('autoplay-speed'),
                infinite: Boolean($slider.data('infinite')),
                dots: Boolean($slider.data('dots')),
                arrows: Boolean($slider.data('arrows')),
                responsive: [{
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: $slider.data('slides-tablet')
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: $slider.data('slides-mobile'),
                            arrows: false // Hide arrows on mobile
                        }
                    }
                ]
            });

            // Pause video when sliding
            $slider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
                var $videos = $(this).find('video');
                var $iframes = $(this).find('iframe');

                // Pause HTML5 videos
                $videos.each(function() {
                    this.pause();
                });

                // Pause iframes (like Aparat)
                $iframes.each(function() {
                    var src = $(this).attr('src');
                    $(this).attr('src', src);
                });
            });

            // Lazy load videos
            $slider.on('afterChange', function(event, slick, currentSlide) {
                var $currentSlide = $(slick.$slides[currentSlide]);
                var $video = $currentSlide.find('video');

                if ($video.length) {
                    $video[0].load();
                }
            });

            // Handle video click
            $('.video-wrapper').on('click', function(e) {
                var $video = $(this).find('video');
                if ($video.length) {
                    if ($video[0].paused) {
                        $video[0].play();
                    } else {
                        $video[0].pause();
                    }
                }
            });

            // Add loading state
            $('.video-wrapper video').on({
                loadstart: function() {
                    $(this).closest('.video-container').addClass('loading');
                },
                canplay: function() {
                    $(this).closest('.video-container').removeClass('loading');
                }
            });

            // Handle iframe loading
            $('.video-wrapper iframe').on('load', function() {
                $(this).closest('.video-container').removeClass('loading');
            }).each(function() {
                $(this).closest('.video-container').addClass('loading');
            });
        });
    </script>

    {* Additional Styles for Loading State and iframes *}
    <style>
        .video-container.loading:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 10;
        }

        .video-container.loading:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 30px;
            height: 30px;
            margin: -15px 0 0 -15px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #2fb5d2;
            border-radius: 50%;
            z-index: 11;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Aparat Video Specific Styles */
        .h_iframe-aparat_embed_frame {
            position: relative;
            height: 0;
            padding-bottom: 57%;
        }

        .h_iframe-aparat_embed_frame iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Improve iframe video wrapper */
        .video-wrapper.iframe-video {
            background: #000;
        }

        .video-wrapper.iframe-video iframe {
            border: none;
            max-width: 100%;
        }

        /* Error State */
        .video-error {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #e74c3c;
            width: 80%;
        }

        .video-error i {
            font-size: 48px;
            margin-bottom: 10px;
        }

        /* RTL Support */
        html[dir="rtl"] .bs-videoslider .slick-prev {
            right: -20px;
            left: auto;
        }

        html[dir="rtl"] .bs-videoslider .slick-next {
            left: -20px;
            right: auto;
        }

        html[dir="rtl"] .bs-videoslider .slick-prev:before {
            content: "\f105";
        }

        html[dir="rtl"] .bs-videoslider .slick-next:before {
            content: "\f104";
        }

        @media (max-width: 768px) {
            html[dir="rtl"] .bs-videoslider .slick-prev {
                right: -15px;
            }

            html[dir="rtl"] .bs-videoslider .slick-next {
                left: -15px;
            }
        }

        /* Print styles */
        @media print {
            .bs-videoslider-container {
                display: none;
            }
        }
    </style>
    {/if}