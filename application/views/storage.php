<h2><?php echo $page_title; ?></h2>

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
        <tr>
            <th scope="row"><a href="<?php echo base_url() . 'storages/view/' . $storage['id']; ?>"><?php echo $storage['id']; ?></a></th>
            <td><?php echo $storage['name']; ?></td>
            <td><?php echo $storage['address']; ?></td>
            <td class="float-right">
                <a class="btn btn-primary" href="<?php echo base_url() . 'storages/edit/' . $storage['id']; ?>">Módosítás</a>
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#archiveModal">Archiválás</button>
            </td>
        </tr>
    </tbody>
</table>

<h4>Eszközök, amelyeket utoljára itt voltak leltárazva</h4>
<?php if (/* !is_null($inventory_history) && */ count($items)) : ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Azonosító</th>
                <th scope="col">Név</th>
                <th scope="col">Kategória</th>
                <th scope="col">Utoljára leltárazva</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item) : ?>
                <tr>
                    <th scope="row"><a href="<?php echo base_url() . 'items/view/' . $item['id']; ?>"><?php echo $item['id']; ?></a></th>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo $item['category']; ?></td>
                    <td><?php echo $item['time']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>Ez a kategória még nincs hozzárendelve egy eszközhöz sem.</p>
<?php endif; ?>

<!-- Delete Modal -->
<div class="modal fade" id="archiveModal" tabindex="-1" role="dialog" aria-labelledby="archiveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="archiveModalLabel">Raktár archiválása</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                A raktár archiválásával elrejted azt a leltárazáskor kiválaszható helyek közül, de az itt készített ellenőrzések eredményei továbbra is megtekinthetőek maradnak. A művelet később is viszavonható. Biztosan folytatni szeretnéd?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Mégsem</button>
                <a href="<?php echo base_url() . 'storages/archive/' . $storage['id']; ?>" class="btn btn-warning">Archiválás</a>
            </div>
        </div>
    </div>
</div>
