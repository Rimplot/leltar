<div class="row">
    <h2><?php echo $page_title; ?></h2>
</div>

<hr>

<?php /* echo validation_errors('<div class="alert alert-danger alert-dismissible">' .
                             '<button type="button" class="close">&times;</button>' .
                             '<strong>Hiba!</strong> ', '</div>'); */ ?>

<?php echo form_open('storages/edit/' . $storage['id']); ?>

<div class="row">
    <div class="col-lg-4 offset-lg-1">
        <div class="form-group<?php echo (form_error('name')) ? ' has-danger' : '' ?>">
            <label class="form-control-label">Név</label>
            <input type="text" class="form-control<?php echo (form_error('name')) ? ' is-invalid' : '' ?>" name="name" value="<?php echo $storage['name']; ?>">
            <div class="invalid-feedback"><?php echo form_error('name'); ?></div>
        </div>
        <div class="form-group<?php echo (form_error('address')) ? ' has-danger' : '' ?>">
            <label class="form-control-label">Cím</label>
            <input type="text" class="form-control<?php echo (form_error('address')) ? ' is-invalid' : '' ?>" name="address" value="<?php echo $storage['address']; ?>">
            <div class="invalid-feedback"><?php echo form_error('address'); ?></div>
        </div>
        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-primary" value="Mentés" />
        </div>
    </div>
</div>

<input type="hidden" name="id" value="<?php echo $storage['id']; ?>">

<?php form_close(); ?>
