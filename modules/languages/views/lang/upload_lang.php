<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">

        <div class="tw-mb-8">
            <?php echo form_open_multipart(admin_url('languages/upload_languages'), ['id' => 'module_install_form', 'class' => 'sm:flex sm:items-center']); ?>
            <h3 class="tw-mb-2 tw-text-lg tw-font-medium tw-leading-6 tw-text-neutral-900">Upload Language</h3>
            <div class="tw-mt-2 tw-max-w-xl tw-text-sm tw-text-neutral-600">
                <p>If you have a module in a .zip format, you may install it by uploading it here.</p>
            </div>
            <form class="">
                <div class="w-full tw-inline-flex sm:max-w-xs">
                    <input type="file" class="form-control" name="lang">

                    <button type="submit" class="btn btn-primary tw-ml-2">Upload</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
        
<?php init_tail(); ?>
<script>
$(function() {
    appValidateForm($('#module_install_form'), {
        module: {
            required: true,
            extension: "zip"
        }
    });
});
</script>
</body>

</html>