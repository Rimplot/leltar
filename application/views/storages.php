<div class="row">
    <div class="col-md-8">
        <h2><?php echo $page_title; ?></h2>
    </div>
    <div class="col-md-4">
        <a href="<?php echo base_url(); ?>storages/add" class="btn btn-info float-right">Új raktár hozzáadása</a>
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
                <td><?php echo $storage['address']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
