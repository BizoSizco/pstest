{*
* @author    BizoSizco <info@bizosiz.com>
* @copyright 2025 BizoSizco
* @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
* Last updated: 2025-02-21 01:44:18 by BizoSizco
*}

{extends file='page.tpl'}

{block name='page_header_container'}{/block}

{block name='page_content'}
    <div class="bs-videoslider-page">
        <h1 class="page-heading">
            {$page_title|escape:'html':'UTF-8'}
        </h1>

        {if isset($videos) && count($videos) > 0}
            <div class="bs-videoslider-grid">
                {foreach from=$videos item=video}
                    <div class="bs-video-item" data-id="{$video.id_video|intval}">
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
                                <button type="button" class="bs-play-button" aria-label="{l s='Play video' mod='bs_videoslider'}">
                                    <i class="material-icons">play_circle_outline</i>
                                </button>
                            </div>

                            {* Video info *}
                            <div class="bs-video-info">
                                <h2 class="bs-video-title">
                                    {$video.title|escape:'html':'UTF-8'}
                                </h2>
                                {if $video.description}
                                    <p class="bs-video-description">
                                        {$video.description|truncate:150:'...'|escape:'html':'UTF-8'}
                                    </p>
                                {/if}
                            </div>
                        </div>
                    </div>
                {/foreach}
            </div>

            {* Video Modal *}
            <div class="modal fade" id="bs-video-modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title"></h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="{l s='Close' mod='bs_videoslider'}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="video-container">
                                <div id="video-placeholder"></div>
                                <div class="loading-spinner">
                                    <div class="spinner"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {* Pagination *}
            {if isset($pagination)}
                <nav class="pagination">
                    <div class="col-md-4">
                        {l s='Showing %from%-%to% of %total% item(s)' 
                           sprintf=['%from%' => $pagination.from, '%to%' => $pagination.to, '%total%' => $pagination.total] 
                           mod='bs_videoslider'}
                    </div>
                    <div class="col-md-8">
                        <ul class="page-list clearfix text-sm-center">
                            {foreach from=$pagination.links item=link}
                                <li {if $link.current}class="current"{/if}>
                                    {if $link.url}
                                        <a href="{$link.url|escape:'html':'UTF-8'}" 
                                           {if $link.current}class="disabled"{/if}
                                           rel="nofollow">
                                            {$link.page|escape:'html':'UTF-8'}
                                        </a>
                                    {else}
                                        <span>{$link.page|escape:'html':'UTF-8'}</span>
                                    {/if}
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                </nav>
            {/if}
        {else}
            <div class="alert alert-warning">
                {l s='No videos available at this time.' mod='bs_videoslider'}
            </div>
        {/if}
    </div>

    {* JavaScript configuration *}
    <script type="text/javascript">
        var bs_videoslider_config = {
            ajax_url: '{$link->getModuleLink('bs_videoslider', 'ajax', [])|escape:'javascript':'UTF-8'}',
            messages: {
                error: "{l s='Error loading video' mod='bs_videoslider' js=1}",
                loading: "{l s='Loading...' mod='bs_videoslider' js=1}"
            }
        };
    </script>

    {* Custom styles *}
    <style>
        .bs-videoslider-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .bs-video-frame {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .bs-video-frame:hover {
            transform: translateY(-5px);
        }

        .bs-video-thumbnail {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .bs-video-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .bs-video-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            background: #f5f5f5;
            color: #999;
        }

        .bs-play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: none;
            border: none;
            color: white;
            font-size: 64px;
            opacity: 0.8;
            transition: all 0.3s ease;
            cursor: pointer;
            padding: 0;
        }

        .bs-video-thumbnail:hover .bs-play-button {
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

        #bs-video-modal .modal-dialog {
            max-width: 90%;
            margin: 30px auto;
        }

        #bs-video-modal .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
        }

        #bs-video-modal .video-container iframe,
        #bs-video-modal .video-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
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

        @media (max-width: 767px) {
            .bs-videoslider-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 15px;
            }

            .bs-video-title {
                font-size: 14px;
            }

            .bs-video-description {
                font-size: 12px;
            }

            #bs-video-modal .modal-dialog {
                margin: 10px;
            }
        }
    </style>
{/block}