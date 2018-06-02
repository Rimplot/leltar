<h2><?php echo $page_title; ?></h2>

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
            <td><?php echo $item['name']; ?></td>
            <td><?php echo $item['barcode']; ?></td>
            <td><?php echo $item['category']; ?></td>
            <th class="float-right">
                <a class="btn btn-primary" href="<?php echo base_url() . 'items/edit/' . $item['id']; ?>">Módosítás</a>
                <a class="btn btn-danger" href="<?php echo base_url() . 'items/delete/' . $item['id']; ?>">Törlés</a>
            </th>
        </tr>
    </tbody>
</table>

<h4>Előzmények</h4>
<?php if (/* !is_null($inventory_history) && */ count($inventory_history)) : ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Raktár</th>
                <th scope="col">Időpont</th>
            </tr>
        </thead>
        <tbody>
                <?php foreach ($inventory_history as $row) : ?>
                <tr>
                    <td><?php echo $row['storage']; ?></td>
                    <td><?php echo $row['time']; ?></td>
                </tr>
                <?php endforeach;?>
        </tbody>
    </table>
<?php else : ?>
    <p>Ez az eszköz még nem volt leltárazva.</p>
<?php endif; ?>
