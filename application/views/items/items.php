<div class="row">
    <div class="col-md-8">
        <h2><?php echo $page_title; ?></h2>
    </div>
    <div class="col-md-4">
        <a href="<?php echo base_url(); ?>items/add" class="btn btn-info float-right">Új eszköz hozzáadása</a>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Azonosító</th>
            <th scope="col">Név</th>
            <th scope="col">Vonalkód</th>
            <th scope="col">Kategória</th>
            <th scope="col">Típus</th>
            <th scope="col">Tulajdonos</th>
            <th scope="col">Utoljára leltározva</th>
            <th scope="col">Session</th>
            <th scope="col">Hely</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item) : ?>
            <tr>
                <th scope="row"><?php echo $item['id']; ?></th>
                <td><a href="<?php echo base_url() . 'items/' . $item['id']; ?>"><?php echo $item['name']; ?></a></td>
                <td>
                    <img src="<?php echo base_url() . 'barcodes/generate/' . $item['barcode']; ?>" style="display:block; height:15%">
                    <?php echo $item['barcode']; ?>
                </td>
                <td><a href="<?php echo base_url() . 'categories/' . $item['category_id']; ?>"><?php echo $item['category']; ?></a></td>
                <td><?php echo $item['type']; ?></td>
                <td><?php echo $item['owner']; ?></td>
                <td><?php echo $item['last_seen']['time']; ?></td>
                <td><?php echo $item['last_seen']['session']; ?></td>
                <td><a href="<?php echo base_url() . 'storages/' . $item['last_seen']['storage_id']; ?>"><?php echo $item['last_seen']['storage_name']; ?></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
