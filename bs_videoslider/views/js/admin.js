/**
 * @author    BizoSizco <info@bizosiz.com>
 * @copyright 2025 BizoSizco
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 * Last updated: 2025-02-21 01:28:55 by BizoSizco
 */

$(document).ready(function() {
    // Video Type Selection
    $('#video_type').on('change', function() {
        const selectedType = $(this).val();
        $('.video-input-group').hide();
        $(`#${selectedType}_input`).show();
        
        // Reset validation messages
        $('.video-error').hide();
        $('.video-success').hide();
    });

    // Form Validation
    $('#video_form').on('submit', function(e) {
        e.preventDefault();
        let isValid = true;
        const videoType = $('#video_type').val();
        
        // Reset error messages
        $('.form-error').remove();
        
        // Validate title
        if (!$('#video_title').val().trim()) {
            showError('video_title', bs_videoslider_messages.title_required);
            isValid = false;
        }
        
        // Validate video URL/code based on type
        switch(videoType) {
            case 'direct':
                if (!validateDirectVideo($('#direct_url').val())) {
                    showError('direct_url', bs_videoslider_messages.invalid_video_format);
                    isValid = false;
                }
                break;
                
            case 'aparat':
                if (!validateAparatCode($('#aparat_code').val())) {
                    showError('aparat_code', bs_videoslider_messages.invalid_aparat_code);
                    isValid = false;
                }
                break;
                
            case 'iframe':
                if (!validateIframeCode($('#iframe_code').val())) {
                    showError('iframe_code', bs_videoslider_messages.invalid_iframe_code);
                    isValid = false;
                }
                break;
        }
        
        // Validate image if uploaded
        const imageInput = $('#video_image')[0];
        if (imageInput.files.length > 0) {
            if (!validateImage(imageInput.files[0])) {
                showError('video_image', bs_videoslider_messages.invalid_image);
                isValid = false;
            }
        }
        
        // Submit if valid
        if (isValid) {
            submitVideoForm(this);
        }
    });

    // Image Preview
    $('#video_image').on('change', function() {
        const file = this.files[0];
        if (file && validateImage(file)) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#image_preview').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#image_preview').hide();
            showError('video_image', bs_videoslider_messages.invalid_image);
        }
    });

    // Delete Video Confirmation
    $('.delete-video').on('click', function(e) {
        e.preventDefault();
        const videoId = $(this).data('id');
        const videoTitle = $(this).data('title');
        
        if (confirm(bs_videoslider_messages.confirm_delete.replace('%s', videoTitle))) {
            deleteVideo(videoId);
        }
    });

    // Toggle Video Status
    $('.toggle-video-status').on('click', function() {
        const videoId = $(this).data('id');
        const currentStatus = $(this).data('status');
        toggleVideoStatus(videoId, currentStatus);
    });

    // Sort Videos
    if ($('#video-list').length) {
        initSortable();
    }
});

// Validation Functions
function validateDirectVideo(url) {
    if (!url) return false;
    const validExtensions = ['mp4', 'webm', 'ogg'];
    const extension = url.split('.').pop().toLowerCase();
    return validExtensions.includes(extension);
}

function validateAparatCode(code) {
    return /^<iframe.*src="https:\/\/(www\.)?aparat\.com\/video\/video\/embed\/.*".*<\/iframe>$/.test(code);
}

function validateIframeCode(code) {
    return /^<iframe.*src="https:\/\/.*".*<\/iframe>$/.test(code);
}

function validateImage(file) {
    const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
    const maxSize = 2 * 1024 * 1024; // 2MB
    return validTypes.includes(file.type) && file.size <= maxSize;
}

// UI Helper Functions
function showError(elementId, message) {
    $(`#${elementId}`).after(`<div class="form-error text-danger">${message}</div>`);
}

function showSuccess(message) {
    const alert = `<div class="alert alert-success">${message}</div>`;
    $('#form_messages').html(alert).show();
    setTimeout(() => $('#form_messages').fadeOut(), 3000);
}

function showLoading() {
    $('#loading_overlay').show();
}

function hideLoading() {
    $('#loading_overlay').hide();
}

// AJAX Functions
function submitVideoForm(form) {
    showLoading();
    const formData = new FormData(form);
    
    $.ajax({
        url: bs_videoslider_ajax_url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                showSuccess(bs_videoslider_messages.save_success);
                if (!formData.get('id_video')) {
                    resetForm(form);
                }
                reloadVideoList();
            } else {
                showError('form_messages', response.message);
            }
        },
        error: function() {
            showError('form_messages', bs_videoslider_messages.ajax_error);
        },
        complete: function() {
            hideLoading();
        }
    });
}

function deleteVideo(videoId) {
    showLoading();
    
    $.ajax({
        url: bs_videoslider_ajax_url,
        type: 'POST',
        data: {
            action: 'deleteVideo',
            id_video: videoId
        },
        success: function(response) {
            if (response.success) {
                $(`#video_${videoId}`).fadeOut(400, function() {
                    $(this).remove();
                });
                showSuccess(bs_videoslider_messages.delete_success);
            } else {
                showError('form_messages', response.message);
            }
        },
        error: function() {
            showError('form_messages', bs_videoslider_messages.ajax_error);
        },
        complete: function() {
            hideLoading();
        }
    });
}

function toggleVideoStatus(videoId, currentStatus) {
    showLoading();
    
    $.ajax({
        url: bs_videoslider_ajax_url,
        type: 'POST',
        data: {
            action: 'toggleStatus',
            id_video: videoId
        },
        success: function(response) {
            if (response.success) {
                const newStatus = currentStatus === '1' ? '0' : '1';
                $(`.toggle-video-status[data-id="${videoId}"]`)
                    .data('status', newStatus)
                    .find('i')
                    .toggleClass('icon-check icon-close');
                showSuccess(bs_videoslider_messages.status_updated);
            } else {
                showError('form_messages', response.message);
            }
        },
        error: function() {
            showError('form_messages', bs_videoslider_messages.ajax_error);
        },
        complete: function() {
            hideLoading();
        }
    });
}

function initSortable() {
    $('#video-list').sortable({
        handle: '.drag-handle',
        update: function() {
            const order = $(this).sortable('toArray', { attribute: 'data-id' });
            updateVideoOrder(order);
        }
    });
}

function updateVideoOrder(order) {
    showLoading();
    
    $.ajax({
        url: bs_videoslider_ajax_url,
        type: 'POST',
        data: {
            action: 'updateOrder',
            order: order
        },
        success: function(response) {
            if (response.success) {
                showSuccess(bs_videoslider_messages.order_updated);
            } else {
                showError('form_messages', response.message);
                $('#video-list').sortable('cancel');
            }
        },
        error: function() {
            showError('form_messages', bs_videoslider_messages.ajax_error);
            $('#video-list').sortable('cancel');
        },
        complete: function() {
            hideLoading();
        }
    });
}

function resetForm(form) {
    form.reset();
    $('#image_preview').hide();
    $('.video-input-group').hide();
    $(`#${$('#video_type').val()}_input`).show();
}

function reloadVideoList() {
    $('#video-list').load(window.location.href + ' #video-list > *', function() {
        initSortable();
    });
}

// Keyboard Shortcuts
$(document).keydown(function(e) {
    // Save with Ctrl+S
    if (e.ctrlKey && e.keyCode === 83) {
        e.preventDefault();
        $('#video_form').submit();
    }
    
    // Close preview with Escape
    if (e.keyCode === 27) {
        $('#image_preview').hide();
    }
});

// Dynamic form height adjustment
function adjustFormHeight() {
    const formHeight = $('#video_form').height();
    const listHeight = $('#video-list').height();
    const maxHeight = Math.max(formHeight, listHeight);
    
    $('#admin-container').css('min-height', maxHeight + 50);
}

// Call adjustFormHeight on load and resize
$(window).on('load resize', adjustFormHeight);