<div class="row">
    <h2><?php echo $page_title; ?></h2>
</div>

<hr>

<?php /* echo validation_errors('<div class="alert alert-danger alert-dismissible">' .
                             '<button type="button" class="close">&times;</button>' .
                             '<strong>Hiba!</strong> ', '</div>'); */ ?>

<?php echo form_open('sectors/edit/' . $sector['id']); ?>

<div class="row">
    <div class="col-lg-4 offset-lg-1">
        <div class="form-group<?php echo (form_error('name')) ? ' has-danger' : '' ?>">
            <label class="form-control-label">Név</label>
            <input type="text" class="form-control<?php echo (form_error('name')) ? ' is-invalid' : '' ?>" name="name" value="<?php echo $sector['name']; ?>">
            <div class="invalid-feedback"><?php echo form_error('name'); ?></div>
        </div>
        <fieldset disabled>
            <div class="form-group<?php echo (form_error('barcode')) ? ' has-danger' : '' ?>">
                <label class="form-control-label">Vonalkód</label>
                <div class="input-group">
                    <input type="text" class="form-control<?php echo (form_error('barcode')) ? ' is-invalid' : '' ?>" name="barcode" id="barcode" value="<?php echo $sector['barcode']; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-success" id="btnGenerateBarcode" type="button">Generálás</button>
                    </div>
                </div>
                <div class="invalid-feedback"><?php echo form_error('barcode'); ?></div>
            </div>
        </fieldset>
        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-primary" value="Mentés" />
        </div>
    </div>
</div>

<input type="hidden" name="id" value="<?php echo $sector['id']; ?>">

<?php form_close(); ?>

<script>
    $(document).ready(function() {
        $('#btnGenerateBarcode').click(function() {
            $.ajax({
                url: "<?php echo base_url(); ?>" + "ajax/get_unique_barcode",
                type: "get",
                dataType: "json",
                success: function(data) {
                    $('#barcode').val(data.barcode);
                }
            });
        });
    });
</script>
