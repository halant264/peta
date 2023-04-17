<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s panel-table-full">
                    <div class="panel-body">
                        <?php if (isset($consent_purposes)) { ?>
                        <div class="row mbot15">
                            <div class="col-md-3 contacts-filter-column">
                                <div class="select-placeholder">
                                    <select name="custom_view" title="<?php echo _l('gdpr_consent'); ?>"
                                        id="custom_view" class="selectpicker" data-width="100%">
                                        <option value=""></option>
                                        <?php foreach ($consent_purposes as $purpose) { ?>
                                        <option value="consent_<?php echo $purpose['id']; ?>">
                                            <?php echo $purpose['name']; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                                <div class="clearfix"></div>
                                <?php
                            $table_data = [];
                            if (is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1') {
                                array_push($table_data, [
                            'name'     => _l('gdpr_consent') . ' (' . _l('gdpr_short') . ')',
                            'th_attrs' => ['id' => 'th-consent', 'class' => 'not-export'],
                            ]);
                            }
                        $table_data = array_merge($table_data, [
                            _l('car_name'),
                            _l('brand'),
                            _l('model'),
                            _l('model_year'),
                            _l('plate_source'),
                            _l('plate_code'),
                            _l('plate_number'),
                        ]);
                        $custom_fields = get_custom_fields('contacts', ['show_on_table' => 1]);
                        foreach ($custom_fields as $field) {
                            array_push($table_data, [
                            'name'     => $field['name'],
                            'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
                            ]);
                        }
                        // var_dump(render_datatable($table_data, 'all-contacts')); exit();
                        echo render_datatable($table_data, 'all-contacts');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<?php $this->load->view('admin/clients/client_js'); ?>
<div id="contact_data"></div>
<div id="consent_data"></div>
<script>
$(function() {
    var optionsHeading = [];
    var allContactsServerParams = {
        "custom_view": "[name='custom_view']",
    }
    <?php if (is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1') { ?>
    optionsHeading.push($('#th-consent').index());
    <?php } ?>
    _table_api = initDataTable('.table-all-contacts', window.location.href, optionsHeading, optionsHeading,
        allContactsServerParams, [0, 'asc']);
    if (_table_api) {
        <?php if (is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1') { ?>
        _table_api.on('draw', function() {
            var tableData = $('.table-all-contacts').find('tbody tr');
            $.each(tableData, function() {
                $(this).find('td:eq(2)').addClass('bg-neutral');
            });
        });
        $('select[name="custom_view"]').on('change', function() {
            _table_api.ajax.reload()
                .columns.adjust()
                .responsive.recalc();
        });
        <?php } ?>
    }
});
</script>
</body>

</html>