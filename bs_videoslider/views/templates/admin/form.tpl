{*
* @author    BizoSizco <info@bizosiz.com>
* @copyright 2025 BizoSizco
* @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
* Last updated: 2025-02-21 01:33:10 by BizoSizco
*}

<div class="panel">
    <div class="panel-heading">
        <i class="icon-film"></i> {l s='Add/Edit Video' mod='bs_videoslider'}
    </div>
    
    <div id="form_messages" class="alert" style="display: none;"></div>
    
    <form id="video_form" class="form-horizontal" enctype="multipart/form-data">
        {if isset($id_video)}
            <input type="hidden" name="id_video" value="{$id_video|intval}" />
        {/if}
        
        <div class="form-group">
            <label class="control-label col-lg-3 required">
                {l s='Title' mod='bs_videoslider'}
            </label>
            <div class="col-lg-9">
                <input type="text" name="title" id="video_title" 
                       value="{if isset($video_data)}{$video_data.title|escape:'html':'UTF-8'}{/if}"
                       class="form-control" required />
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label col-lg-3 required">
                {l s='Video Type' mod='bs_videoslider'}
            </label>
            <div class="col-lg-9">
                <select name="video_type" id="video_type" class="form-control">
                    <option value="direct" {if isset($video_data) && $video_data.type == 'direct'}selected{/if}>
                        {l s='Direct Video (MP4/WebM/Ogg)' mod='bs_videoslider'}
                    </option>
                    <option value="aparat" {if isset($video_data) && $video_data.type == 'aparat'}selected{/if}>
                        {l s='Aparat' mod='bs_videoslider'}
                    </option>
                    <option value="iframe" {if isset($video_data) && $video_data.type == 'iframe'}selected{/if}>
                        {l s='Other (iframe)' mod='bs_videoslider'}
                    </option>
                </select>
            </div>
        </div>
        
        <div id="direct_input" class="form-group video-input-group" 
             {if !isset($video_data) || $video_data.type != 'direct'}style="display: none;"{/if}>
            <label class="control-label col-lg-3 required">
                {l s='Video URL' mod='bs_videoslider'}
            </label>
            <div class="col-lg-9">
                <input type="url" name="direct_url" id="direct_url"
                       value="{if isset($video_data) && $video_data.type == 'direct'}{$video_data.video|escape:'html':'UTF-8'}{/if}"
                       class="form-control" />
                <p class="help-block">
                    {l s='Supported formats: MP4, WebM, Ogg' mod='bs_videoslider'}
                </p>
            </div>
        </div>
        
        <div id="aparat_input" class="form-group video-input-group"
             {if !isset($video_data) || $video_data.type != 'aparat'}style="display: none;"{/if}>
            <label class="control-label col-lg-3 required">
                {l s='Aparat Embed Code' mod='bs_videoslider'}
            </label>
            <div class="col-lg-9">
                <textarea name="aparat_code" id="aparat_code" rows="4" class="form-control">{if isset($video_data) && $video_data.type == 'aparat'}{$video_data.video|escape:'html':'UTF-8'}{/if}</textarea>
                <p class="help-block">
                    {l s='Paste the full iframe embed code from Aparat' mod='bs_videoslider'}
                </p>
            </div>
        </div>
        
        <div id="iframe_input" class="form-group video-input-group"
             {if !isset($video_data) || $video_data.type != 'iframe'}style="display: none;"{/if}>
            <label class="control-label col-lg-3 required">
                {l s='Iframe Code' mod='bs_videoslider'}
            </label>
            <div class="col-lg-9">
                <textarea name="iframe_code" id="iframe_code" rows="4" class="form-control">{if isset($video_data) && $video_data.type == 'iframe'}{$video_data.video|escape:'html':'UTF-8'}{/if}</textarea>
                <p class="help-block">
                    {l s='Paste the full iframe embed code' mod='bs_videoslider'}
                </p>
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label col-lg-3">
                {l s='Thumbnail Image' mod='bs_videoslider'}
            </label>
            <div class="col-lg-9">
                <input type="file" name="image" id="video_image" class="form-control-file" accept="image/*" />
                {if isset($video_data) && $video_data.image}
                    <div class="current-image mt-2">
                        <img src="{$module_dir}views/img/{$video_data.image|escape:'html':'UTF-8'}"
                             alt="{$video_data.title|escape:'html':'UTF-8'}"
                             class="img-thumbnail" style="max-width: 200px;" />
                    </div>
                {/if}
                <div class="mt-2">
                    <img id="image_preview" style="display: none; max-width: 200px;"
                         class="img-thumbnail" alt="{l s='Preview' mod='bs_videoslider'}" />
                </div>
                <p class="help-block">
                    {l s='Recommended size: 640x360 pixels. Max size: 2MB' mod='bs_videoslider'}
                </p>
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label col-lg-3">
                {l s='Status' mod='bs_videoslider'}
            </label>
            <div class="col-lg-9">
                <span class="switch prestashop-switch fixed-width-lg">
                    <input type="radio" name="active" id="active_on" value="1"
                           {if !isset($video_data) || $video_data.active}checked="checked"{/if} />
                    <label for="active_on">{l s='Yes' mod='bs_videoslider'}</label>
                    <input type="radio" name="active" id="active_off" value="0"
                           {if isset($video_data) && !$video_data.active}checked="checked"{/if} />
                    <label for="active_off">{l s='No' mod='bs_videoslider'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </div>
        </div>
        
        <div class="panel-footer">
            <button type="submit" class="btn btn-default pull-right">
                <i class="process-icon-save"></i> {l s='Save' mod='bs_videoslider'}
            </button>
        </div>
    </form>
</div>

<div id="loading_overlay" style="display: none;">
    <div class="spinner"></div>
</div>

{* JavaScript messages for translation *}
<script type="text/javascript">
    var bs_videoslider_messages = {
        title_required: "{l s='Title is required' mod='bs_videoslider' js=1}",
        invalid_video_format: "{l s='Invalid video format' mod='bs_videoslider' js=1}",
        invalid_aparat_code: "{l s='Invalid Aparat embed code' mod='bs_videoslider' js=1}",
        invalid_iframe_code: "{l s='Invalid iframe code' mod='bs_videoslider' js=1}",
        invalid_image: "{l s='Invalid image format or size' mod='bs_videoslider' js=1}",
        save_success: "{l s='Video saved successfully' mod='bs_videoslider' js=1}",
        delete_success: "{l s='Video deleted successfully' mod='bs_videoslider' js=1}",
        status_updated: "{l s='Status updated successfully' mod='bs_videoslider' js=1}",
        order_updated: "{l s='Order updated successfully' mod='bs_videoslider' js=1}",
        ajax_error: "{l s='An error occurred. Please try again.' mod='bs_videoslider' js=1}",
        confirm_delete: "{l s='Are you sure you want to delete video "%s"?' mod='bs_videoslider' js=1}"
    };
    
    // Additional configuration
    var bs_videoslider_config = {
        ajax_url: '{$link->getAdminLink('AdminModules')|addslashes}&configure=bs_videoslider',
        token: '{$token|escape:'html':'UTF-8'}',
        max_file_size: 2097152, // 2MB in bytes
        allowed_video_extensions: ['mp4', 'webm', 'ogg'],
        allowed_image_types: ['image/jpeg', 'image/png', 'image/gif']
    };
</script>

{* Initialize form validation *}
<script type="text/javascript">
    $(document).ready(function() {
        // Enable Bootstrap tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Initialize custom file input
        bsCustomFileInput.init();
    });
</script>