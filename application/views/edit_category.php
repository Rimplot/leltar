<div class="row">
    <h2><?php echo $page_title; ?></h2>
</div>

<hr>

<?php /* echo validation_errors('<div class="alert alert-danger alert-dismissible">' .
                             '<button type="button" class="close">&times;</button>' .
                             '<strong>Hiba!</strong> ', '</div>'); */ ?>

<?php echo form_open('categories/edit/' . $category['id']); ?>

<div class="row">
    <div class="col-lg-4 offset-lg-1">
        <div class="form-group<?php echo (form_error('name')) ? ' has-danger' : '' ?>">
            <label class="form-control-label">Név</label>
            <input type="text" class="form-control<?php echo (form_error('name')) ? ' is-invalid' : '' ?>" name="name" value="<?php echo $category['name']; ?>">
            <div class="invalid-feedback"><?php echo form_error('name'); ?></div>
        </div>
        <div class="form-group">
            <label class="form-control-label">Szülő kategória</label>
            <select name="parent" title="Szülő kategória" class="form-control">
                <option value="0" <?php echo ($category['parent_id'] == 0) ? 'selected' : '' ; ?>>&#60;semmi&#62;</option>
                <?php foreach ($categories as $cat) : ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo ($category['parent_id'] == $cat['id']) ? 'selected' : '' ; ?>><?php echo $cat['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-primary" value="Mentés" />
        </div>
    </div>
</div>

<input type="hidden" name="id" value="<?php echo $category['id']; ?>">

<?php form_close(); ?>
