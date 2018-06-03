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
            <th scope="col">Utoljára leltározva</th>
            <th scope="col">Hely</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item) : ?>
            <tr>
                <th scope="row"><a href="<?php echo base_url() . 'items/' . $item['id']; ?>"><?php echo $item['id']; ?></a></th>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo $item['barcode']; ?></td>
                <td><?php echo $item['category']; ?></td>
                <td><?php echo $item['last_seen']['time']; ?></td>
                <td><?php echo $item['last_seen']['storage_name']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
