<h2><?php echo $page_title; ?></h2>

<?php echo validation_errors('<div class="alert alert-danger alert-dismissible">' .
                             '<button type="button" class="close">&times;</button>' .
                             '<strong>Hiba!</strong> ', '</div>'); ?>

<?php echo form_open('items/edit/' . $item['id']); ?>

<div class="form-group">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Azonosító</th>
                <th scope="col">Név</th>
                <th scope="col">Vonalkód</th>
                <th scope="col">Kategória</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row"><?php echo $item['id']; ?></th>
                <td><input type="text" class="form-control" name="name" value="<?php echo $item['name']; ?>"></td>
                <td><input type="text" class="form-control" name="barcode" value="<?php echo $item['barcode']; ?>"></td>
                <td><input type="text" class="form-control" name="category_id" value="<?php echo $item['category_id']; ?>"></td>
                <th><input type="submit" name="submit" class="btn btn-primary" value="Mentés" /></th>
            </tr>
        </tbody>
    </table>
</div>
<input type="hidden" name="id" value="<?php echo $item['id']; ?>">

<?php form_close(); ?>
