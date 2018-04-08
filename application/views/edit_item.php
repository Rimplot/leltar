<h2><?php echo $page_title; ?></h2>

<?php echo validation_errors(); ?>

<?php echo form_open('items/edit/' . $item['id']); ?>

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
            <td><input type="text" name="name" value="<?php echo $item['name']; ?>"></td>
            <td><input type="text" name="barcode" value="<?php echo $item['barcode']; ?>"></td>
            <td><input type="text" name="category_id" value="<?php echo $item['category_id']; ?>"></td>
            <th><input type="submit" name="submit" class="btn btn-primary" value="Mentés" /></th>
        </tr>
    </tbody>
</table>

<input type="hidden" name="id" value="<?php echo $item['id']; ?>">

<?php form_close(); ?>
