<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Modal Contact -->


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    @import url("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"); ;
</style>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



<div class="modal fade" id="contact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php echo form_open(admin_url('clients/form_contact_car/' . $customer_id . ($contactid ? '/' . $contactid : '')), ['id' => 'car-form', 'autocomplete' => 'off']); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <div class="tw-flex">
                    <div class="tw-mr-4 tw-flex-shrink-0 tw-relative">
                        <?php if (isset($contact)) { ?>
                        <img src="<?php echo contact_profile_image_url($contact->id, 'small'); ?>" id="contact-img"
                            class="client-profile-image-small">
                        <?php if (!empty($contact->profile_image)) { ?>
                        <a href="#" onclick="delete_contact_profile_image(<?php echo $contact->id; ?>); return false;"
                            class="tw-bg-neutral-500/30 tw-text-neutral-600 hover:tw-text-neutral-500 tw-h-8 tw-w-8 tw-inline-flex tw-items-center tw-justify-center tw-rounded-full tw-absolute tw-inset-0"
                            id="contact-remove-img"><i class="fa fa-remove tw-mt-1"></i></a>
                        <?php } ?>
                        <?php } ?>
                    </div>
                    <div>
                        <h4 class="modal-title tw-mb-0"><?php echo $title; ?></h4>
                        <p class="tw-mb-0">
                            <?php echo get_company_name($customer_id, true); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- // For email exist check -->
                        <?php echo form_hidden('contactid', $contactid); ?>
                        <?php $value = (isset($contact) ? $contact['car']->car_name : '');  ?>
                        <?php echo render_input('car_name', 'car_name', $value); ?>
                        <div class="form-group contact-brand-option">
                            <label for="brand"><?php echo _l('brand'); ?></label>
                            <?php  $brands= $this->db->get(db_prefix() . 'brand')->result_array() ;   ?>
                            <select class="selectpicker" data-none-selected-text="<?php echo _l('brand'); ?>"
                                 data-width="100%" data-live-search="true"
                                name="brand" id="brand">
                                <option value="" ><?php echo _l('select_brand') ?> </option>
                                 <?php foreach($brands as $key => $brand){   ?>
                                    <?php if (isset($contact) && $contact['car']->id_brand==$brand['id']  ) { ?>
                                        <option value="<?php echo $contact['car']->id_brand ?>"  <?php echo 'selected'?>  ><?php echo $contact['car']->brand_name ?> </option>
                                  <?php } else { ?>
                                    <option value="<?php echo $brand['id'] ; ?>" ><?php echo $brand['brand_name'] ?> </option>
                                    <?php } ?>
                                <?php }?>
                            </select>
                        </div>
                        <style>
                        .style-model{
                            display: block;
                            width: 100%;
                            padding: 8px 8px;
                            border-radius: 6px;
                            border: 2px solid #3b82f6;
                        }
                        .style-model option {
                            margin: 40px;
                            background: rgba(0, 0, 0, 0.3);
                            color: #000;
                            text-shadow: 0 1px 0 rgba(0, 0, 0, 0.4);
                        }
                        .style-model:hover {
                            cursor: pointer;
                            background-color: #cbd5e124;
                        }
                        /* .style-model:focus{
                            border-bottom: 1px solid red !important;
                            box-shadow: 0 1px 0 0 red !important;
                        } */

                        .style-model option{
                                                --tw-bg-opacity: 1;
                            --tw-text-opacity: 1;
                            background-color: rgb(241 245 249/var(--tw-bg-opacity))!important;
                            color: rgb(15 23 42/var(--tw-text-opacity))!important;
                            height: 30px!important;
                        }
                        </style>
                        <div class="form-group contact-model-option">
                            <label for="model"><?php echo _l('model'); ?></label>
                            <select class="selectpicker"
                                 data-width="100%" data-live-search="true"
                                name="model" id="model">
                                <option value="" ><?php echo _l('select_model');?> </option>
                                <?php  if (isset($contact) && count($contact['model']) > 0 ) { ?>
                                 <?php foreach($contact['model'] as $key => $model){   ?>
                                    <?php if ($contact['car']->id_model == $model['id'] ) { ?>
                                        <option value="<?php echo $contact['car']->id_model ?>"  <?php echo 'selected'?>  ><?php echo _l($model['model_name']) ?> </option>
                                        <?php } else{?>
                                            <option value="<?php echo $model['id'] ; ?>" ><?php echo _l($model['model_name']) ?> </option>
                                        <?php }?>
                                    <?php }?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group contact-model_year-option">
                            <label for="model_year"><?php echo _l('model_year'); ?></label>
                            <select class="selectpicker" data-live-search="true"
                                data-none-selected-text="<?php echo _l('model_year'); ?>" data-width="100%"
                                name="model_year" id="model_year">
                                <?php for($i=1990 ; $i<= date("Y") ; $i++){ ?>
                                    <?php if ( isset($contact) && $contact['car']->model_year ==$i ) { ?>
                                        <option value="<?php echo $i ?>"  <?php echo 'selected'?>  ><?php echo _l($contact['car']->model_year) ?> </option>
                                        <?php } else{?>
                                            <option value="<?php echo $i ; ?>" ><?php echo _l($i) ?> </option>
                                        <?php }?>
                                    <?php }?>
                            </select>
                        </div>
                        <?php  $value = (isset($contact) ? $contact['car']->plate_source : ''); ?>
                        <?php echo render_input('plate_source', 'plate_source', $value); ?>
                        <?php $value = (isset($contact) ? $contact['car']->plate_code : ''); ?>
                        <?php echo render_input('plate_code', 'plate_code', $value); ?>
                        <?php $value = (isset($contact) ? $contact['car']->plate_number : ''); ?>
                        <?php echo render_input('plate_number', 'plate_number', $value ); ?>


                    </div>
                </div>
                <?php hooks()->do_action('after_contact_modal_content_loaded'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary submit-car" data-loading-text="<?php echo _l('wait_text'); ?>"
                    autocomplete="off" data-form="#contact-form"><?php echo _l('submit'); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php $this->load->view('admin/clients/select2'); ?>
<script>
$(document).ready(function() {

//   $("#model").select2();
 
}); 
</script>
<?php if (!isset($contact)) { ?>

<script>
    $(function() {
        // Guess auto email notifications based on the default contact permissios
        var permInputs = $('input[name="permissions[]"]');
        $.each(permInputs, function(i, input) {
            input = $(input);
            if (input.prop('checked') === true) {
                $('#contact_email_notifications [data-perm-id="' + input.val() + '"]').prop('checked',
                    true);
            }
        });
    });
</script>
<?php } ?>
<?php $this->load->view('admin/clients/client_js'); ?>
