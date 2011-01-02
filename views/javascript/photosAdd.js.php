var settings = {
  flash_url: '/swf/swfupload.swf',
  upload_url: '/photos/add/<?php echo $childId; ?>',
  file_type: '*.jpg;*.jpeg;',
  file_types_description: "Your Baby's Photos",
  file_post_name: 'photo',
  http_success : [200],
  post_params: {'usrhsh': '<?php echo User::postHash(); ?>'},

  button_placeholder_id: 'button-select',
  //button_image_url: '/foo/bar',
  button_text: 'Select',
  button_width: 100,
  button_height:100,

  // handlers
  swfupload_loaded_handler : mmh.swfHandlers.loaded,
  file_queued_handler : mmh.swfHandlers.queued,
  file_dialog_complete_handler : mmh.swfHandlers.dialog,
  upload_start_handler : mmh.swfHandlers.start,
  upload_progress_handler : mmh.swfHandlers.progress,
  upload_error_handler : mmh.swfHandlers.error,
  upload_success_handler : mmh.swfHandlers.success,
  upload_complete_handler : mmh.swfHandlers.complete,
  debug_handler : mmh.swfHandlers.debug

  //file_queue_error_handler : file_queue_error_function,
  //file_dialog_start_handler : file_dialog_start_function,
};

var swfu = new SWFUpload(settings);
