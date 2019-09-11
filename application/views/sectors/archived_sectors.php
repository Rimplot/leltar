<div class="row">
    <div class="col-md-8">
        <h2><?php echo $page_title; ?></h2>
    </div>
    <div class="col-md-4">
        <a href="<?php echo base_url(); ?>storages/<?php echo $storage_id; ?>" class="btn btn-secondary float-right">Aktív szektorok</a>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Azonosító</th>
            <th scope="col">Név</th>
            <th scope="col">Vonalkód</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($sectors as $sector) : ?>
            <tr>
                <th scope="row"><?php echo $sector['id']; ?></th>
                <td><a href="<?php echo base_url() . 'sectors/' . $sector['id']; ?>"><?php echo $sector['name']; ?></a></td>
                <td><?php echo $sector['barcode']; ?></td>
                <td><a class="btn btn-outline-primary float-right" href="<?php echo base_url() . 'sectors/restore/' . $sector['storage_id'] . '/' . $sector['id']; ?>">Visszaállítás</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
