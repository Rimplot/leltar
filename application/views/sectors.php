<div class="row">
    <div class="col-md-6">
        <h2><?php echo $page_title; ?></h2>
    </div>
    <div class="col-md-6">
        <div class="float-right">
            <a href="<?php echo base_url(); ?>storages/archived" class="btn btn-secondary">Archivált raktárak</a>
            <a href="<?php echo base_url(); ?>storages/add" class="btn btn-info">Új raktár hozzáadása</a>
        </div>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Azonosító</th>
            <th scope="col">Név</th>
            <th scope="col">Cím</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($storages as $storage) : ?>
            <tr>
                <th scope="row"><?php echo $storage['id']; ?></th>
                <td><a href="<?php echo base_url() . 'storages/' . $storage['id']; ?>"><?php echo $storage['name']; ?></a></td>
                <td><a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($storage['address']); ?>" target="_blank"><?php echo $storage['address']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
