{*
* @author    BizoSizco <info@bizosiz.com>
* @copyright 2025 BizoSizco
* @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
* Last updated: 2025-02-21 01:37:48 by BizoSizco
*}

<div class="panel">
    <div class="panel-heading">
        <i class="icon-list"></i> {l s='Video List' mod='bs_videoslider'}
        <span class="badge">{$videos|@count}</span>
        <span class="panel-heading-action">
            <a href="{$link->getAdminLink('AdminModules')|escape:'html':'UTF-8'}&configure=bs_videoslider&addVideo=1" 
               class="btn btn-default">
                <i class="process-icon-new"></i>
                {l s='Add New Video' mod='bs_videoslider'}
            </a>
        </span>
    </div>

    {if $videos|@count > 0}
        <div class="table-responsive">
            <table class="table" id="video-list">
                <thead>
                    <tr>
                        <th class="fixed-width-xs text-center">
                            <i class="icon-arrows"></i>
                        </th>
                        <th class="fixed-width-sm text-center">{l s='ID' mod='bs_videoslider'}</th>
                        <th class="fixed-width-lg">{l s='Thumbnail' mod='bs_videoslider'}</th>
                        <th>{l s='Title' mod='bs_videoslider'}</th>
                        <th class="fixed-width-md">{l s='Type' mod='bs_videoslider'}</th>
                        <th class="fixed-width-sm text-center">{l s='Position' mod='bs_videoslider'}</th>
                        <th class="fixed-width-sm text-center">{l s='Status' mod='bs_videoslider'}</th>
                        <th class="fixed-width-md text-center">{l s='Actions' mod='bs_videoslider'}</th>
                    </tr>
                </thead>
                <tbody class="sortable">
                    {foreach from=$videos item=video}
                        <tr id="video_{$video.id_video|intval}" data-id="{$video.id_video|intval}">
                            <td class="drag-handle text-center">
                                <i class="icon-move" title="{l s='Drag to reorder' mod='bs_videoslider'}"></i>
                            </td>
                            <td class="text-center">
                                {$video.id_video|intval}
                            </td>
                            <td>
                                {if $video.thumbnail}
                                    <img src="{$video.thumbnail_url|escape:'html':'UTF-8'}" 
                                         alt="{$video.title|escape:'html':'UTF-8'}"
                                         class="img-thumbnail" 
                                         style="max-width: 100px" />
                                {else}
                                    <div class="no-thumbnail">
                                        <i class="icon-picture-o"></i>
                                    </div>
                                {/if}
                            </td>
                            <td>
                                <strong>{$video.title|escape:'html':'UTF-8'}</strong>
                                <div class="video-url small text-muted">
                                    {if $video.type == 'direct'}
                                        <i class="icon-link"></i> {$video.url|truncate:50:'...'}
                                    {else}
                                        <i class="icon-code"></i> {l s='Embedded Video' mod='bs_videoslider'}
                                    {/if}
                                </div>
                            </td>
                            <td>
                                {if $video.type == 'direct'}
                                    <span class="badge badge-info">
                                        {l s='Direct Video' mod='bs_videoslider'}
                                    </span>
                                {elseif $video.type == 'aparat'}
                                    <span class="badge badge-success">
                                        {l s='Aparat' mod='bs_videoslider'}
                                    </span>
                                {else}
                                    <span class="badge badge-warning">
                                        {l s='Iframe' mod='bs_videoslider'}
                                    </span>
                                {/if}
                            </td>
                            <td class="position text-center">
                                {$video.position|intval}
                            </td>
                            <td class="text-center">
                                <a href="#" 
                                   class="toggle-video-status" 
                                   data-id="{$video.id_video|intval}"
                                   data-status="{$video.active|intval}">
                                    <i class="icon-{if $video.active}check{else}close{/if} text-{if $video.active}success{else}danger{/if}"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{$link->getAdminLink('AdminModules')|escape:'html':'UTF-8'}&configure=bs_videoslider&editVideo={$video.id_video|intval}" 
                                       class="btn btn-default btn-sm" 
                                       title="{l s='Edit' mod='bs_videoslider'}">
                                        <i class="icon-edit"></i>
                                    </a>
                                    <a href="#" 
                                       class="btn btn-default btn-sm delete-video" 
                                       data-id="{$video.id_video|intval}"
                                       data-title="{$video.title|escape:'html':'UTF-8'}"
                                       title="{l s='Delete' mod='bs_videoslider'}">
                                        <i class="icon-trash"></i>
                                    </a>
                                    <a href="#" 
                                       class="btn btn-default btn-sm preview-video" 
                                       data-type="{$video.type|escape:'html':'UTF-8'}"
                                       data-url="{if $video.type == 'direct'}{$video.url|escape:'html':'UTF-8'}{else}{$video.embed_code|escape:'html':'UTF-8'}{/if}"
                                       title="{l s='Preview' mod='bs_videoslider'}">
                                        <i class="icon-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    {else}
        <div class="alert alert-info">
            {l s='No videos found. Click "Add New Video" to create your first video.' mod='bs_videoslider'}
        </div>
    {/if}
</div>

{* Video Preview Modal *}
<div class="modal fade" id="video-preview-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
                <h4 class="modal-title">{l s='Video Preview' mod='bs_videoslider'}</h4>
            </div>
            <div class="modal-body">
                <div class="video-container" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                    <div id="video-preview-content"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{* Sorting and Preview Scripts *}
<script type="text/javascript">
    $(document).ready(function() {
        // Initialize sortable
        $('.sortable').sortable({
            handle: '.drag-handle',
            axis: 'y',
            update: function() {
                var order = [];
                $('.sortable tr').each(function() {
                    order.push($(this).data('id'));
                });
                
                $.ajax({
                    url: '{$link->getAdminLink('AdminModules')|addslashes}&configure=bs_videoslider&action=updatePositions',
                    type: 'POST',
                    data: { order: order },
                    success: function(response) {
                        if (response.success) {
                            showSuccessMessage(bs_videoslider_messages.order_updated);
                            updatePositions();
                        } else {
                            showErrorMessage(bs_videoslider_messages.ajax_error);
                        }
                    },
                    error: function() {
                        showErrorMessage(bs_videoslider_messages.ajax_error);
                    }
                });
            }
        });

        // Update position numbers
        function updatePositions() {
            $('.sortable tr').each(function(index) {
                $(this).find('.position').text(index + 1);
            });
        }

        // Video preview
        $('.preview-video').click(function(e) {
            e.preventDefault();
            var type = $(this).data('type');
            var url = $(this).data('url');
            var content = '';

            if (type === 'direct') {
                content = '<video src="' + url + '" controls style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></video>';
            } else {
                content = url; // Iframe code
            }

            $('#video-preview-content').html(content);
            $('#video-preview-modal').modal('show');
        });

// Clean up video on modal close
        $('#video-preview-modal').on('hidden.bs.modal', function() {
            $('#video-preview-content').empty();
        });
    });

    // Handle video status toggle
    $('.toggle-video-status').click(function(e) {
        e.preventDefault();
        var $button = $(this);
        var videoId = $button.data('id');
        var currentStatus = $button.data('status');

        $.ajax({
            url: '{$link->getAdminLink('AdminModules')|addslashes}&configure=bs_videoslider&action=toggleStatus',
            type: 'POST',
            data: { 
                id_video: videoId,
                status: currentStatus 
            },
            success: function(response) {
                if (response.success) {
                    // Update icon and status
                    var newStatus = currentStatus ? 0 : 1;
                    $button
                        .data('status', newStatus)
                        .find('i')
                        .removeClass('icon-check icon-close text-success text-danger')
                        .addClass(newStatus ? 'icon-check text-success' : 'icon-close text-danger');
                    
                    showSuccessMessage(bs_videoslider_messages.status_updated);
                } else {
                    showErrorMessage(bs_videoslider_messages.ajax_error);
                }
            },
            error: function() {
                showErrorMessage(bs_videoslider_messages.ajax_error);
            }
        });
    });

    // Handle video deletion
    $('.delete-video').click(function(e) {
        e.preventDefault();
        var $button = $(this);
        var videoId = $button.data('id');
        var videoTitle = $button.data('title');

        if (confirm(bs_videoslider_messages.confirm_delete.replace('%s', videoTitle))) {
            $.ajax({
                url: '{$link->getAdminLink('AdminModules')|addslashes}&configure=bs_videoslider&action=deleteVideo',
                type: 'POST',
                data: { id_video: videoId },
                success: function(response) {
                    if (response.success) {
                        $button.closest('tr').fadeOut(400, function() {
                            $(this).remove();
                            updatePositions();
                            
                            // Show empty message if no videos left
                            if ($('.sortable tr').length === 0) {
                                $('.table-responsive').replaceWith(
                                    '<div class="alert alert-info">' + 
                                    bs_videoslider_messages.no_videos +
                                    '</div>'
                                );
                            }
                        });
                        showSuccessMessage(bs_videoslider_messages.delete_success);
                    } else {
                        showErrorMessage(bs_videoslider_messages.ajax_error);
                    }
                },
                error: function() {
                    showErrorMessage(bs_videoslider_messages.ajax_error);
                }
            });
        }
    });
</script>

{* Loading overlay *}
<div id="loading-overlay" style="display: none;">
    <div class="spinner"></div>
</div>

{* Custom styles *}
<style>
    .drag-handle {
        cursor: move;
    }
    .sortable tr {
        transition: background-color 0.2s ease;
    }
    .sortable tr:hover {
        background-color: #f5f5f5;
    }
    .no-thumbnail {
        width: 100px;
        height: 56px;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
    }
    .no-thumbnail i {
        font-size: 24px;
    }
    #loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>