<h2><?php echo $page_title; ?></h2>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Azonosító</th>
            <th scope="col">Név</th>
            <th scope="col">Szülő kategória</th>
            <th scope="col">Eszközök száma</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row"><a href="<?php echo base_url() . 'categories/view/' . $category['id']; ?>"><?php echo $category['id']; ?></a></th>
            <td><?php echo $category['name']; ?></td>
            <td><?php echo $category['parent']; ?></td>
            <td><?php echo $category['item_num']; ?></td>
        </tr>
    </tbody>
</table>

<h4>Eszközök ebben a kategóriában</h4>
<?php if (/* !is_null($inventory_history) && */ count($items)) : ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Azonosító</th>
                <th scope="col">Név</th>
                <th scope="col">Vonalkód</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item) : ?>
                <tr>
                    <th scope="row"><a href="<?php echo base_url() . 'items/view/' . $item['id']; ?>"><?php echo $item['id']; ?></a></th>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo $item['barcode']; ?></td>
                </tr>
            <?php endforeach; ?>
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
