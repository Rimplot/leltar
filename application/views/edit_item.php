<div class="row">
    <h2><?php echo $page_title; ?></h2>
</div>

<hr>

<?php /* echo validation_errors('<div class="alert alert-danger alert-dismissible">' .
                             '<button type="button" class="close">&times;</button>' .
                             '<strong>Hiba!</strong> ', '</div>'); */ ?>

<?php echo form_open('items/edit/' . $item['id']); ?>

<div class="row">
    <div class="col-lg-4 offset-lg-1">
        <div class="form-group<?php echo (form_error('name')) ? ' has-danger' : '' ?>">
            <label class="form-control-label">Név</label>
            <input type="text" class="form-control<?php echo (form_error('name')) ? ' is-invalid' : '' ?>" name="name" value="<?php echo $item['name']; ?>">
            <div class="invalid-feedback"><?php echo form_error('name'); ?></div>
        </div>
        <div class="form-group<?php echo (form_error('barcode')) ? ' has-danger' : '' ?>">
            <label class="form-control-label">Vonalkód</label>
            <input type="text" class="form-control<?php echo (form_error('barcode')) ? ' is-invalid' : '' ?>" name="barcode" value="<?php echo $item['barcode']; ?>">
            <div class="invalid-feedback"><?php echo form_error('barcode'); ?></div>
        </div>
        <div class="form-group">
            <label class="form-control-label">Kategória</label>
            <select name="category_id" title="Kategória" class="form-control">
                <option value="0" <?php echo ($item['category_id'] == 0) ? 'selected' : '' ; ?>>&#60;semmi&#62;</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo ($item['category_id'] == $category['id']) ? 'selected' : '' ; ?>><?php echo $category['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-control-label">Típus</label>
            <select name="type" title="Típus" class="form-control">
                <?php for ($i = 0; $i < count(ITEM_TYPES); $i++) : ?>
                    <option value="<?php echo $i; ?>" <?php echo ($item['type'] == $i) ? 'selected' : '' ; ?>><?php echo ITEM_TYPES[$i]; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-primary" value="Mentés" />
        </div>
    </div>
</div>

<input type="hidden" name="id" value="<?php echo $item['id']; ?>">

<?php form_close(); ?>
