<h2><?php echo $page_title; ?></h2>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Azonosító</th>
            <th scope="col">Név</th>
            <th scope="col">Vonalkód</th>
            <th scope="col">Kategória</th>
            <th scope="col">Típus</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row"><?php echo $item['id']; ?></th>
            <td><?php echo $item['name']; ?></td>
            <td><?php echo $item['barcode']; ?></td>
            <td><a href="<?php echo base_url() . 'categories/' . $item['category_id']; ?>"><?php echo $item['category']; ?></td>
            <td><?php echo $item['type']; ?></td>
            <th class="float-right">
                <a class="btn btn-primary" href="<?php echo base_url() . 'items/edit/' . $item['id']; ?>">Módosítás</a>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Törlés</button>
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
                <th scope="col">Szektor</th>
                <th scope="col">Session</th>
                <th scope="col">Időpont</th>
            </tr>
        </thead>
        <tbody>
                <?php foreach ($inventory_history as $row) : ?>
                <tr>
                    <td><a href="<?php echo base_url() . 'storages/' . $row['storage_id']; ?>"><?php echo $row['storage']; ?></a></td>
                    <td><a href="<?php echo base_url() . 'sectors/' . $row['sector_id']; ?>"><?php echo $row['sector']; ?></a></td>
                    <td><?php echo $row['session']; ?></td>
                    <td><?php echo $row['time']; ?></td>
                </tr>
                <?php endforeach;?>
        </tbody>
    </table>
<?php else : ?>
    <p>Ez az eszköz még nem volt leltárazva.</p>
<?php endif; ?>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Eszköz törlése</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Az eszköz törlésével minden hozzárendelt adatot eltávolítasz a rendszerből. Biztosan folytatni szeretnéd?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Mégsem</button>
                <a href="<?php echo base_url() . 'items/delete/' . $item['id']; ?>" class="btn btn-danger">Törlés</a>
            </div>
        </div>
    </div>
</div>