{*
* @author    BizoSizco <info@bizosiz.com>
* @copyright 2025 BizoSizco
* @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
* Last updated: 2025-02-21 01:42:28 by BizoSizco
*}

{if isset($videos) && count($videos) > 0}
    <div class="bs-videoslider-container">
        {* Main slider wrapper *}
        <div class="bs-videoslider-wrapper"
             data-desktop="{$settings.desktop|intval}"
             data-tablet="{$settings.tablet|intval}"
             data-mobile="{$settings.mobile|intval}"
             data-autoplay="{$settings.autoplay|intval}"
             data-speed="{$settings.speed|intval}"
             data-infinite="{$settings.infinite|intval}">
            
            {foreach from=$videos item=video}
                <div class="bs-video-item">
                    <div class="bs-video-frame">
                        {* Video thumbnail *}
                        <div class="bs-video-thumbnail">
                            {if $video.thumbnail}
                                <img src="{$video.thumbnail_url|escape:'html':'UTF-8'}"
                                     alt="{$video.title|escape:'html':'UTF-8'}"
                                     loading="lazy"
                                     width="640"
                                     height="360" />
                            {else}
                                <div class="bs-video-placeholder">
                                    <i class="material-icons">videocam_off</i>
                                </div>
                            {/if}
                            <div class="bs-play-button">
                                <i class="material-icons">play_circle_outline</i>
                            </div>
                        </div>

                        {* Video content - lazy loaded *}
                        <div class="bs-video-content" style="display:none;">
                            {if $video.type == 'direct'}
                                <div class="video-placeholder" data-type="direct" data-src="{$video.url|escape:'html':'UTF-8'}"></div>
                            {elseif $video.type == 'aparat'}
                                <div class="video-placeholder" data-type="aparat" data-src="{$video.embed_code|escape:'html':'UTF-8'}"></div>
                            {else}
                                <div class="video-placeholder" data-type="iframe" data-src="{$video.embed_code|escape:'html':'UTF-8'}"></div>
                            {/if}
                        </div>

                        {* Video info overlay *}
                        {if $settings.show_info}
                            <div class="bs-video-info">
                                <h3 class="bs-video-title">{$video.title|escape:'html':'UTF-8'}</h3>
                                {if $settings.show_description && $video.description}
                                    <p class="bs-video-description">{$video.description|truncate:150:'...'|escape:'html':'UTF-8'}</p>
                                {/if}
                            </div>
                        {/if}
                    </div>
                </div>
            {/foreach}
        </div>

        {* Navigation arrows *}
        {if $settings.show_arrows}
            <div class="bs-slider-nav">
                <button type="button" class="bs-slider-prev">
                    <i class="material-icons">chevron_left</i>
                    <span class="sr-only">{l s='Previous' mod='bs_videoslider'}</span>
                </button>
                <button type="button" class="bs-slider-next">
                    <i class="material-icons">chevron_right</i>
                    <span class="sr-only">{l s='Next' mod='bs_videoslider'}</span>
                </button>
            </div>
        {/if}

        {* Dots navigation *}
        {if $settings.show_dots}
            <div class="bs-slider-dots"></div>
        {/if}

        {* Loading spinner *}
        <div class="bs-loading-spinner">
            <div class="spinner"></div>
        </div>
    </div>

    {* JavaScript configuration *}
    <script type="text/javascript">
        var bs_videoslider_config = {
            desktop: {$settings.desktop|intval},
            tablet: {$settings.tablet|intval},
            mobile: {$settings.mobile|intval},
            autoplay: Boolean({$settings.autoplay|intval}),
            speed: {$settings.speed|intval},
            infinite: Boolean({$settings.infinite|intval}),
            rtl: Boolean({$settings.rtl|intval}),
            showInfo: Boolean({$settings.show_info|intval}),
            showArrows: Boolean({$settings.show_arrows|intval}),
            showDots: Boolean({$settings.show_dots|intval}),
            messages: {
                error: "{l s='Error loading video' mod='bs_videoslider' js=1}",
                loading: "{l s='Loading...' mod='bs_videoslider' js=1}"
            }
        };
    </script>

    {* Custom styles *}
    <style>
        .bs-videoslider-container {
            margin: {$settings.margin|intval}px 0;
            position: relative;
            overflow: hidden;
        }

        .bs-video-frame {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            background-color: #000;
            border-radius: {$settings.border_radius|intval}px;
            overflow: hidden;
        }

        .bs-video-thumbnail {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .bs-video-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .bs-play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 64px;
            opacity: 0.8;
            transition: all 0.3s ease;
        }

        .bs-video-frame:hover .bs-play-button {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1.1);
        }

        .bs-video-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px;
            background: rgba(0,0,0,0.7);
            color: white;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .bs-video-frame:hover .bs-video-info {
            opacity: 1;
        }

        .bs-video-title {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }

        .bs-video-description {
            margin: 5px 0 0;
            font-size: 14px;
            opacity: 0.8;
        }

        .bs-slider-nav button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,0.5);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .bs-slider-prev {
            left: 10px;
        }

        .bs-slider-next {
            right: 10px;
        }

        .bs-slider-nav button:hover {
            background: rgba(0,0,0,0.8);
        }

        .bs-slider-dots {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
        }

        .bs-loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 991px) {
            .bs-video-title {
                font-size: 14px;
            }

            .bs-video-description {
                font-size: 12px;
            }

            .bs-slider-nav button {
                width: 32px;
                height: 32px;
            }
        }

        @media (max-width: 767px) {
            .bs-slider-nav {
                display: none;
            }
        }

        .bs-videoslider-wrapper .slick-slide {
            padding: 0 {$settings.slide_padding|intval}px;
        }
    </style>
{else}
    <div class="alert alert-warning">
        {l s='No videos available' mod='bs_videoslider'}
    </div>
{/if}