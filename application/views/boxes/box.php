<div class="row">
    <div class="col-md-8">
        <h2><?php echo $page_title; ?></h2>
    </div>
    <div class="col-md-4">
        <a href="<?php echo base_url(); ?>boxes/add" class="btn btn-info float-right">Új doboz hozzáadása</a>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Azonosító</th>
            <th scope="col">Név</th>
            <th scope="col">Vonalkód</th>
            <th scope="col">Szülő doboz</th>
            <th scope="col">Eszközök száma</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row"><?php echo $box['id']; ?></th>
            <td><?php echo $box['name']; ?></td>
            <td>
                <img src="<?php echo base_url() . 'barcodes/generate/' . $box['barcode']; ?>" style="display:block; height:15%">
                <a href="<?php echo base_url() . 'barcodes/generate/' . $box['barcode']; ?>"><?php echo $box['barcode']; ?></a>
            </td>
            <td><a href="<?php echo base_url() . 'boxes/' . $box['parent_id']; ?>"><?php echo $box['parent']; ?></a></td>
            <td><?php echo $box['item_num']; ?></td>
            <td class="float-right">
                <a class="btn btn-primary" href="<?php echo base_url() . 'boxes/edit/' . $box['id']; ?>">Módosítás</a>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Törlés</button>
            </td>
        </tr>
    </tbody>
</table>

<h4>Eszközök ebben a dobozban</h4>
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
                    <th scope="row"><?php echo $item['id']; ?></th>
                    <td><a href="<?php echo base_url() . 'items/' . $item['id']; ?>"><?php echo $item['name']; ?></a></td>
                    <td><?php echo $item['barcode']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>Ebben a dobozban még nincs egy eszköz sem.</p>
<?php endif; ?>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Doboz törlése</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                A doboz törlésével az összes hozzárendelt eszköz átkerül a szülődobozba, ha azonban ilyen nincs, akkor doboz nélkül marad. Biztosan folytatni szeretnéd?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Mégsem</button>
                <a href="<?php echo base_url() . 'boxes/delete/' . $box['id']; ?>" class="btn btn-danger">Törlés</a>
            </div>
        </div>
    </div>
</div>
