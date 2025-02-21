{*
* @author    BizoSizco <info@bizosiz.com>
* @copyright 2025 BizoSizco
* @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
* Last updated: 2025-02-21 01:35:22 by BizoSizco
*}

<form id="video_form" method="post" enctype="multipart/form-data" class="defaultForm form-horizontal">
    <input type="hidden" name="submitVideoForm" value="1" />
    {if isset($video)}
        <input type="hidden" name="id_video" value="{$video.id_video|intval}" />
    {/if}

    <div class="panel">
        <div class="panel-heading">
            {if isset($video)}
                <i class="icon-edit"></i> {l s='Edit Video' mod='bs_videoslider'}
            {else}
                <i class="icon-plus"></i> {l s='Add New Video' mod='bs_videoslider'}
            {/if}
        </div>

        <div class="form-wrapper">
            {* Video Title *}
            <div class="form-group required">
                <label class="control-label col-lg-3">
                    {l s='Video Title' mod='bs_videoslider'}
                </label>
                <div class="col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon-file-text-o"></i>
                        </span>
                        <input type="text" 
                               name="video_title" 
                               id="video_title"
                               value="{if isset($video)}{$video.title|escape:'html':'UTF-8'}{/if}"
                               class="form-control" 
                               required="required" />
                    </div>
                    <p class="help-block">{l s='Enter a descriptive title for the video' mod='bs_videoslider'}</p>
                </div>
            </div>

            {* Video Type *}
            <div class="form-group required">
                <label class="control-label col-lg-3">
                    {l s='Video Type' mod='bs_videoslider'}
                </label>
                <div class="col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon-film"></i>
                        </span>
                        <select name="video_type" id="video_type" class="form-control" required="required">
                            <option value="">
                                {l s='-- Select Video Type --' mod='bs_videoslider'}
                            </option>
                            <option value="direct" {if isset($video) && $video.type == 'direct'}selected="selected"{/if}>
                                {l s='Direct Video (MP4/WebM/Ogg)' mod='bs_videoslider'}
                            </option>
                            <option value="aparat" {if isset($video) && $video.type == 'aparat'}selected="selected"{/if}>
                                {l s='Aparat Video' mod='bs_videoslider'}
                            </option>
                            <option value="iframe" {if isset($video) && $video.type == 'iframe'}selected="selected"{/if}>
                                {l s='Custom Iframe' mod='bs_videoslider'}
                            </option>
                        </select>
                    </div>
                    <p class="help-block">{l s='Choose the type of video you want to add' mod='bs_videoslider'}</p>
                </div>
            </div>

            {* Direct Video URL *}
            <div id="direct_video_container" class="form-group video-input {if !isset($video) || $video.type != 'direct'}hidden{/if}">
                <label class="control-label col-lg-3">
                    {l s='Video URL' mod='bs_videoslider'}
                </label>
                <div class="col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon-link"></i>
                        </span>
                        <input type="url" 
                               name="direct_url" 
                               id="direct_url"
                               value="{if isset($video) && $video.type == 'direct'}{$video.url|escape:'html':'UTF-8'}{/if}"
                               class="form-control" />
                    </div>
                    <p class="help-block">{l s='Enter the direct URL to your video file (MP4, WebM, or Ogg format)' mod='bs_videoslider'}</p>
                </div>
            </div>

            {* Aparat Embed Code *}
            <div id="aparat_video_container" class="form-group video-input {if !isset($video) || $video.type != 'aparat'}hidden{/if}">
                <label class="control-label col-lg-3">
                    {l s='Aparat Embed Code' mod='bs_videoslider'}
                </label>
                <div class="col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon-code"></i>
                        </span>
                        <textarea name="aparat_code" 
                                  id="aparat_code"
                                  rows="5"
                                  class="form-control">{if isset($video) && $video.type == 'aparat'}{$video.embed_code|escape:'html':'UTF-8'}{/if}</textarea>
                    </div>
                    <p class="help-block">{l s='Paste the embed code from Aparat' mod='bs_videoslider'}</p>
                </div>
            </div>

            {* Custom Iframe Code *}
            <div id="iframe_video_container" class="form-group video-input {if !isset($video) || $video.type != 'iframe'}hidden{/if}">
                <label class="control-label col-lg-3">
                    {l s='Iframe Code' mod='bs_videoslider'}
                </label>
                <div class="col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon-code"></i>
                        </span>
                        <textarea name="iframe_code" 
                                  id="iframe_code"
                                  rows="5"
                                  class="form-control">{if isset($video) && $video.type == 'iframe'}{$video.embed_code|escape:'html':'UTF-8'}{/if}</textarea>
                    </div>
                    <p class="help-block">{l s='Paste your custom iframe embed code' mod='bs_videoslider'}</p>
                </div>
            </div>

            {* Thumbnail Image *}
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {l s='Thumbnail Image' mod='bs_videoslider'}
                </label>
                <div class="col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon-picture-o"></i>
                        </span>
                        <input type="file" 
                               name="thumbnail" 
                               id="thumbnail"
                               class="form-control-file" 
                               accept="image/*" />
                    </div>
                    {if isset($video) && $video.thumbnail}
                        <div class="current-thumbnail mt-3">
                            <img src="{$video.thumbnail_url|escape:'html':'UTF-8'}" 
                                 alt="{$video.title|escape:'html':'UTF-8'}"
                                 class="img-thumbnail"
                                 style="max-width: 200px" />
                        </div>
                    {/if}
                    <div id="thumbnail_preview" class="mt-3 hidden">
                        <img src="" alt="" class="img-thumbnail" style="max-width: 200px" />
                    </div>
                    <p class="help-block">
                        {l s='Upload a thumbnail image (JPG, PNG, GIF - Max 2MB). Recommended size: 640x360px' mod='bs_videoslider'}
                    </p>
                </div>
            </div>

            {* Position/Order *}
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {l s='Position' mod='bs_videoslider'}
                </label>
                <div class="col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon-sort"></i>
                        </span>
                        <input type="number" 
                               name="position" 
                               value="{if isset($video)}{$video.position|intval}{else}{$next_position|intval}{/if}"
                               class="form-control" 
                               min="0" />
                    </div>
                    <p class="help-block">{l s='Set the display order of the video' mod='bs_videoslider'}</p>
                </div>
            </div>

            {* Active Status *}
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {l s='Active' mod='bs_videoslider'}
                </label>
                <div class="col-lg-9">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input type="radio" 
                               name="active" 
                               id="active_on" 
                               value="1" 
                               {if !isset($video) || $video.active}checked="checked"{/if} />
                        <label for="active_on">{l s='Yes' mod='bs_videoslider'}</label>
                        <input type="radio" 
                               name="active" 
                               id="active_off" 
                               value="0" 
                               {if isset($video) && !$video.active}checked="checked"{/if} />
                        <label for="active_off">{l s='No' mod='bs_videoslider'}</label>
                        <a class="slide-button btn"></a>
                    </span>
                </div>
            </div>
        </div>

 <div class="panel-footer">
            <button type="submit" 
                    name="submitVideo" 
                    class="btn btn-default pull-right">
                <i class="process-icon-save"></i> 
                {l s='Save' mod='bs_videoslider'}
            </button>
            <a href="{$link->getAdminLink('AdminModules')|escape:'html':'UTF-8'}&configure=bs_videoslider" 
               class="btn btn-default">
                <i class="process-icon-cancel"></i>
                {l s='Cancel' mod='bs_videoslider'}
            </a>
        </div>
    </div>
</form>

{* Form validation and preview scripts *}
<script type="text/javascript">
    $(document).ready(function() {
        // Handle video type change
        $('#video_type').change(function() {
            $('.video-input').addClass('hidden');
            $('#' + $(this).val() + '_video_container').removeClass('hidden');
        });

        // Thumbnail preview
        $('#thumbnail').change(function() {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#thumbnail_preview')
                        .removeClass('hidden')
                        .find('img')
                        .attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        });

        // Form validation
        $('#video_form').submit(function(e) {
            var videoType = $('#video_type').val();
            var isValid = true;
            
            // Reset error messages
            $('.has-error').removeClass('has-error');
            $('.help-block').remove();

            // Validate title
            if (!$('#video_title').val().trim()) {
                isValid = false;
                showError('video_title', '{l s='Title is required' mod='bs_videoslider' js=1}');
            }

            // Validate video input based on type
            switch(videoType) {
                case 'direct':
                    if (!validateUrl($('#direct_url').val())) {
                        isValid = false;
                        showError('direct_url', '{l s='Please enter a valid video URL' mod='bs_videoslider' js=1}');
                    }
                    break;
                case 'aparat':
                    if (!validateAparatCode($('#aparat_code').val())) {
                        isValid = false;
                        showError('aparat_code', '{l s='Please enter a valid Aparat embed code' mod='bs_videoslider' js=1}');
                    }
                    break;
                case 'iframe':
                    if (!validateIframeCode($('#iframe_code').val())) {
                        isValid = false;
                        showError('iframe_code', '{l s='Please enter a valid iframe code' mod='bs_videoslider' js=1}');
                    }
                    break;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        // Validation helpers
        function validateUrl(url) {
            if (!url) return false;
            var pattern = /\.(mp4|webm|ogg)$/i;
            return pattern.test(url);
        }

        function validateAparatCode(code) {
            if (!code) return false;
            return code.includes('aparat.com') && code.includes('<iframe');
        }

        function validateIframeCode(code) {
            if (!code) return false;
            return code.includes('<iframe') && code.includes('</iframe>');
        }

        function showError(fieldId, message) {
            $('#' + fieldId)
                .closest('.form-group')
                .addClass('has-error')
                .find('.input-group')
                .after('<p class="help-block text-danger">' + message + '</p>');
        }
    });
</script>