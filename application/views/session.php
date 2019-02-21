<div class="row">
    <div class="col-md-6">
        <h2><?php echo $page_title; ?></h2>
    </div>
    <!-- <div class="col-md-6">
        <div class="float-right">
            <a href="<?php echo base_url(); ?>sectors/archived/<?php echo $session['id']; ?>" class="btn btn-secondary">Archivált szektorok</a>
            <a href="<?php echo base_url(); ?>sectors/add/<?php echo $session['id']; ?>" class="btn btn-info">Új szektor hozzáadása</a>
        </div>
    </div> -->
</div>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Azonosító</th>
            <th scope="col">Név</th>
            <th scope="col">Kezdet</th>
            <th scope="col">Vég</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row"><?php echo $session['id']; ?></th>
            <td><?php echo $session['name']; ?></td>
            <td><?php echo $session['start']; ?></td>
            <td><?php echo $session['end']; ?></td>
            <td class="float-right">
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#sessionModal"><?php echo /*($session['end']) ? "Újraindítás" : */"Lezárás"; ?></button>
            </td>
        </tr>
    </tbody>
</table>

<h4>Eszközök</h4>
<?php if (/* !is_null($inventory_history) && */ count($items)) : ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Azonosító</th>
                <th scope="col">Név</th>
                <th scope="col">Vonalkód</th>
                <th scope="col">Leltárazva</th>
                <th scope="col">Raktár</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item) : ?>
                <tr>
                    <th scope="row"><?php echo $item['id']; ?></th>
                    <td><a href="<?php echo base_url() . 'items/' . $item['id']; ?>"><?php echo $item['name']; ?></a></td>
                    <td><?php echo $item['barcode']; ?></td>
                    <td><?php echo $item['time']; ?></td>
                    <td><a href="<?php echo base_url() . 'storages/' . $item['storage_id']; ?>"><?php echo $item['storage']; ?></a>, <a href="<?php echo base_url() . 'sectors/' . $item['sector_id']; ?>"><?php echo $item['sector']; ?></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>Nem volt egy eszköz sem leltárazva ebben a sessionben.</p><br>
<?php endif; ?>

<!-- Stop Modal -->
<div class="modal fade" id="sessionModal" tabindex="-1" role="dialog" aria-labelledby="sessionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sessionModalLabel">Session lezárása</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                A session lezárásával elrejted azt a leltárazáskor kiválaszható lehetőségek közül, de az ebben a csoportban készített ellenőrzések eredményei továbbra is megtekinthetőek maradnak. A művelet később is viszavonható. Biztosan folytatni szeretnéd?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Mégsem</button>
                <a href="<?php echo base_url() . 'sessions/stop/' . $session['id']; ?>" class="btn btn-warning">Lezárás</a>
            </div>
        </div>
    </div>
</div>
