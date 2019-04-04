<div class="row">
    <div class="col-md-8">
        <h2><?php echo $page_title; ?></h2>
    </div>
    <div class="col-md-4">
        <a href="<?php echo base_url(); ?>storages" class="btn btn-secondary float-right">Aktív raktárak</a>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Azonosító</th>
            <th scope="col">Név</th>
            <th scope="col">Cím</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($storages as $storage) : ?>
            <tr>
                <th scope="row"><?php echo $storage['id']; ?></th>
                <td><a href="<?php echo base_url() . 'storages/' . $storage['id']; ?>"><?php echo $storage['name']; ?></a></td>
                <td><a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($storage['address']); ?>" target="_blank"><?php echo $storage['address']; ?></td>
                <td><a class="btn btn-outline-primary float-right" href="<?php echo base_url() . 'storages/restore/' . $storage['id']; ?>">Visszaállítás</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
