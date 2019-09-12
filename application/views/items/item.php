<div class="row">
    <div class="col-md-5">
        <h2><?php echo $item['name']; ?></h2>
    </div>
    <div class="col-md-7 text-right">
        <a href="<?php echo base_url(); ?>items/add/<?php echo $item['item_id']; ?>" class="btn btn-info">Új példány hozzáadása</a>
        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#inventoryModal">Manuális leltárazás</button>
        <button type="button" class="btn btn-success" id="btnPrint">Nyomtatás</button>
        <?php if (count($instances) > 1): ?>
        <div class="btn-group">
            <button id="edit-dropdown" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Módosítás</button>
            <div class="dropdown-menu" aria-labelledby="edit-dropdown">
                <a class="dropdown-item" href="<?php echo base_url() . 'items/edit/' . $item['id']; ?>">Eszköz módosítása</a>
                <a class="dropdown-item" href="<?php echo base_url() . 'items/edit/' . $item['id'] . '/instance'; ?>">Példány módosítása</a>
            </div>
        </div>
        <?php else: ?>
            <a class="btn btn-primary" href="<?php echo base_url() . 'items/edit/' . $item['id']; ?>">Módosítás</a>
        <?php endif; ?>
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Törlés</button>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Azonosító</th>
            <th scope="col">Vonalkód</th>
            <th scope="col">Leírás</th>
            <th scope="col">Kategória</th>
            <th scope="col">Típus</th>
            <th scope="col">Létrehozta</th>
            <th scope="col">Létrehozás ideje</th>
            <?php if ($item['last_modified_by']): ?>
                <th scope="col">Utoljára módosította</th>
                <th scope="col">Módosítás időpontja</th>
            <?php endif; ?>
            <th scope="col">Megvásárlás ideje</th>
            <th scope="col">Érték</th>
            <th scope="col">Tulajdonos</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row"><?php echo $item['id']; ?></th>
            <td>
                <img src="<?php echo base_url() . 'barcodes/generate/' . $item['barcode']; ?>" style="display:block; height:15%">
                <a href="<?php echo base_url() . 'barcodes/generate/' . $item['barcode']; ?>"><?php echo $item['barcode']; ?></a>
            </td>
            <td><?php echo $item['desc']; ?></td>
            <td><a href="<?php echo base_url() . 'categories/' . $item['category_id']; ?>"><?php echo $item['category']; ?></td>
            <td><?php echo $item['type']; ?></td>
            <td><?php echo ($item['created_by']) ? $item['creator_name'] : '<em>ismeretlen</em>'; ?></td>
            <td><?php echo ($item['date_created']) ? $item['date_created'] : '<em>ismeretlen</em>'; ?></td>
            <?php if ($item['last_modified_by']) {
                echo '<td>' . $item['last_modified_name'] . '</td>';
                echo '<td>' . $item['last_modified_date'] . '</td>';
            } ?>
            <td><?php echo ($item['date_bought']) ? $item['date_bought'] : '<em>ismeretlen</em>'; ?></td>
            <td><?php echo ($item['value'] !== null) ? $item['value'] . " €" : '<em>ismeretlen</em>'; ?></td>
            <td><?php echo ($item['owner']) ? $item['owner'] : '<em>miénk</em>'; ?></td>
        </tr>
    </tbody>
</table>

<?php if (count($instances) > 1): ?>
    <h4>Egyéb példányok<span class="badge badge-pill badge-warning float-right"><?php echo count($instances) - 1; ?> további példány</span></h4>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Azonosító</th>
                <th scope="col">Vonalkód</th>
                <th scope="col">Létrehozás ideje</th>
                <th scope="col">Megvásárlás ideje</th>
                <th scope="col">Érték</th>
                <th scope="col">Tulajdonos</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($instances as $instance): ?>
                <?php if ($instance['id'] == $item['id']) continue; ?>
                <tr>
                    <td><a href="<?php echo base_url() . 'items/' . $instance['id']; ?>"><?php echo $instance['id']; ?></a></td>
                    <td><?php echo $instance['barcode']; ?></td>
                    <td><?php echo ($instance['date_created']) ? $instance['date_created'] : '<em>ismeretlen</em>'; ?></td>
                    <td><?php echo ($instance['date_bought']) ? $instance['date_bought'] : '<em>ismeretlen</em>'; ?></td>
                    <td><?php echo ($instance['value'] !== null) ? $instance['value'] . " €" : '<em>ismeretlen</em>'; ?></td>
                    <td><?php echo ($instance['owner']) ? $instance['owner'] : '<em>miénk</em>'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<h4>Előzmények</h4>
<?php if (/* !is_null($inventory_history) && */ count($inventory_history)) : ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Raktár</th>
                <th scope="col">Szektor</th>
                <th scope="col">Session</th>
                <th scope="col">Időpont</th>
                <?php if ($item['type_id'] == ITEM_TYPE_ID['stock']) echo '<th scope="col">Mennyiség</th>'; ?>
            </tr>
        </thead>
        <tbody>
                <?php foreach ($inventory_history as $row) : ?>
                <tr>
                    <td><a href="<?php echo base_url() . 'storages/' . $row['storage_id']; ?>"><?php echo $row['storage']; ?></a></td>
                    <td><a href="<?php echo base_url() . 'sectors/' . $row['sector_id']; ?>"><?php echo $row['sector']; ?></a></td>
                    <td><?php echo ($row['session']) ? $row['session'] : "<em>manuálisan hozzáadva</em>"; ?></td>
                    <td><?php echo $row['time']; ?></td>
                    <?php if ($item['type_id'] == ITEM_TYPE_ID['stock']) echo '<td>' . $row['quantity'] . '</td>'; ?>
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

<!-- Inventory Modal -->
<div class="modal fade" id="inventoryModal" tabindex="-1" role="dialog" aria-labelledby="inventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inventoryModalLabel">Eszköz manuális leltárazása</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Ha nincs kéznél a vonalkódolvasó, ilyen módon manuálisan is bevihető a rendszerbe az adott eszköz jelenlegi helye.</p>
                <div class="form-group">
                    <label class="form-control-label">Szektor</label>
                    <select name="sector" id="sector" title="Szektor" class="form-control">
                        <?php foreach ($storages as $storage) : ?>
                            <optgroup label="<?php echo $storage['name']; ?>">
                            <?php foreach ($storage['sectors'] as $sector) : ?>
                                <option value="<?php echo $sector['id']; ?>"><?php echo $sector['name']; ?></option>
                            <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if ($item['type_id'] == ITEM_TYPE_ID['stock']): ?>
                    <div class="form-group">
                        <label class="form-control-label">Mennyiség</label>
                        <input type="text" class="form-control" name="quantity" id="quantity" value="<?php echo $inventory_history[0]['quantity']; ?>">
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Mégsem</button>
                <button type="button" class="btn btn-info" id="btnInventory">Leltárazás</a>
            </div>
        </div>
    </div>
</div>

<?php

$args = array(
    'id' => $item['id'],
    'name' => $item['name'],
    'category' => $item['category'],
    'barcode' => $item['barcode'],
    'storage' => $item['last_seen']['storage'],
    'sector' => $item['last_seen']['sector']
);

?>

<script>
    $(document).ready(function() {
        $('#btnInventory').click(function() {
            var barcode_id = "<?php echo $item['barcode_id']; ?>";

            $.ajax({
                url: "<?php echo base_url(); ?>" + "ajax/inventory",
                type: "post",
                dataType: "json",
                data: {
                    'session_id': "",
                    'barcode_id': barcode_id,
                    'sector': $('#sector').val(),
                    'quantity': $('#quantity').val()
                },
                success: function(data) {
                    if (data && data.success) {
                        location.reload();
                    }
                    else {
                        alert('Ismeretlen eszköz');
                    }
                },
                error: function(data) {
                    alert('Hiba! Nincs kapcsolat az adatbázissal.')
                }
            });
        });

        var printed = <?php echo $item['printed']; ?>;
        var label = `<?php echo labelBuilder($item['label'], $args); ?>`;

        $('#btnPrint').click(function() {
            if (label == "") {
                alert('Kategória nélkül nem tudunk címkét nyomtatni.');
                return;
            }

            if (!printed) {
                if (confirm('A címke kinyomtatása után később már nem módosíthatod a vonalkódot. Biztosan folytatni szeretnéd?')) {
                    connectAndPrint(label).then(() => {
                        $.ajax({
                            url: "<?php echo base_url(); ?>" + "ajax/set_barcode_printed",
                            type: "post",
                            dataType: "json",
                            data: {
                                'barcode': "<?php echo $item['barcode']; ?>"
                            }
                        });
                    });
                    printed = true;
                }
            } else {
                connectAndPrint(label);
            }
        });
    });
</script>