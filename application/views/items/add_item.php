<div class="row">
    <h2><?php echo $page_title; ?></h2>
</div>

<hr>

<?php /* echo validation_errors('<div class="alert alert-danger alert-dismissible">' .
                             '<button type="button" class="close">&times;</button>' .
                    W         '<strong>Hiba!</strong> ', '</div>'); */ ?>

<?php echo form_open('items/add/' . $id); ?>

<div class="row">
    <div class="col-lg-4 offset-lg-1">
        <?php if (!isset($item)): ?>
            <div class="form-group<?php echo (form_error('name')) ? ' has-danger' : '' ?>">
                <label class="form-control-label">Név</label>
                <input type="text" class="form-control<?php echo (form_error('name')) ? ' is-invalid' : '' ?>" name="name" value="<?php echo set_value('name'); ?>">
                <div class="invalid-feedback"><?php echo form_error('name'); ?></div>
            </div>
        <?php else: ?>
            <div class="form-group">
                <label class="form-control-label">Név</label>
                <input type="text" class="form-control" name="name" value="<?php echo $item['name']; ?>" disabled>
            </div>
        <?php endif; ?>
        <div class="form-group<?php echo (form_error('barcode')) ? ' has-danger' : '' ?>">
            <label class="form-control-label">Vonalkód</label>
            <div class="input-group">
                <input type="text" class="form-control<?php echo (form_error('barcode')) ? ' is-invalid' : '' ?>" name="barcode" id="barcode" value="<?php echo set_value('barcode'); ?>">
                <div class="input-group-append">
                    <button class="btn btn-outline-success" id="btnGenerateBarcode" type="button">Generálás</button>
                </div>
            </div>
            <div class="invalid-feedback"><?php echo form_error('barcode'); ?></div>
        </div>
        <div class="form-group">
            <label class="form-control-label">Leírás</label>
            <textarea class="form-control" name="description" id="description"><?php echo set_value('description'); ?></textarea>
        </div>
        <div class="form-group">
            <label class="form-control-label">Kategória</label>
            <select name="category_id" title="Kategória" class="form-control" <?php if (isset($item)) echo "disabled" ?>>
                <option value="0" <?php echo ((isset($item) && $item['category_id'] == 0) || set_value('category_id') == 0) ? 'selected' : '' ; ?>>&#60;semmi&#62;</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo ((isset($item) && $item['category_id'] == $category['id']) || set_value('category_id') == $category['id']) ? 'selected' : '' ; ?>><?php echo $category['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-control-label">Típus</label>
            <select name="type" id="type" title="Típus" class="form-control" <?php if (isset($item)) echo "disabled" ?>>
                <?php foreach (ITEM_TYPE_ID as $id) : ?>
                    <?php if ($id == BOX_TYPE_ID) continue; ?>
                    <option value="<?php echo $id; ?>" <?php echo ((isset($item) && $item['type_id'] == $id) || set_value('type') == $id) ? 'selected' : '' ; ?>><?php echo ITEM_TYPES[$id]; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" id="stock-form-group" <?php if (set_value('type') != ITEM_TYPE_ID['stock']) echo 'style="display: none;"' ?>>
            <label class="form-control-label">Mennyiség</label>
            <input type="text" class="form-control<?php echo (form_error('stock')) ? ' is-invalid' : '' ?>" name="stock" value="<?php echo set_value('stock'); ?>">
        </div>
        <div class="form-group">
            <label class="form-control-label">Doboz</label>
            <select name="box" id="box" title="Doboz" class="form-control">
                <option value="0">&#60;semmi&#62;</option>
                <?php foreach ($boxes as $box) : ?>
                    <option value="<?php echo $box['id']; ?>" <?php echo (set_value('box') == $box['id']) ? 'selected' : '' ; ?>><?php echo $box['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-control-label">Megvásárlás ideje</label>
            <input type="date" class="form-control<?php echo (form_error('date_bought')) ? ' is-invalid' : '' ?>" name="date_bought" value="<?php echo set_value('date_bought'); ?>">
        </div>
        <div class="form-group">
            <label class="form-control-label">Érték</label>
            <div class="input-group">
                <input type="number" class="form-control<?php echo (form_error('value')) ? ' is-invalid' : '' ?>" name="value" value="<?php echo set_value('value'); ?>">
                <div class="input-group-append">
                    <span class="input-group-text">€</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="form-control-label">Tulajdonos</label>
            <select name="owner" id="owner" title="Tulajdonos" class="form-control">
                <option value="0">&#60;senki&#62;</option>
                <?php foreach ($owners as $owner) : ?>
                    <option value="<?php echo $owner['id']; ?>" <?php echo (set_value('owner') == $owner['id']) ? 'selected' : '' ; ?>><?php echo $owner['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-control-label">Tulajdonságok JSON</label>
            <textarea class="form-control" name="properties" id="properties"><?php echo set_value('properties'); ?></textarea>
        </div>
        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-primary" value="Mentés" />
        </div>
    </div>
</div>

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

    $('#type').on('change', function() {
        if ($(this).val() == <?php echo ITEM_TYPE_ID['stock']; ?>) {
            $('#stock-form-group').show();
        } else {
            $('#stock-form-group').hide();
        }
    })
</script>
