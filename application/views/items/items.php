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
                <td><?php echo $item['barcode']; ?></td>
                <td><a href="<?php echo base_url() . 'categories/' . $item['category_id']; ?>"><?php echo $item['category']; ?></a></td>
                <td><?php echo ($item['owner']) ? $item['owner'] : '<em>miénk</em>'; ?></td>
                <td><?php echo ($item['last_seen']['time']) ? $item['last_seen']['time'] : '<em>ismeretlen</em>'; ?></td>
                <td><?php echo ($item['last_seen']['session']) ? $item['last_seen']['session'] : '<em>ismeretlen</em>'; ?></td>
                <td><?php echo ($item['last_seen']['storage_id']) ? '<a href="' . base_url() . 'storages/' . $item['last_seen']['storage_id'] . '">' . $item['last_seen']['storage'] . '</a>' : '<em>ismeretlen</em>'; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
