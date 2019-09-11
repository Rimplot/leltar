<div class="row">
    <div class="col-md-8">
        <h2><?php echo $page_title; ?></h2>
    </div>
    <div class="col-md-4">
        <a href="<?php echo base_url(); ?>storages" class="btn btn-secondary float-right">Aktív raktárak</a>
    </div>
</div>

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
        <?php foreach ($storages as $storage) : ?>
            <tr>
                <th scope="row"><?php echo $storage['id']; ?></th>
                <td><a href="<?php echo base_url() . 'storages/' . $storage['id']; ?>"><?php echo $storage['name']; ?></a></td>
                <td><a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($storage['address']); ?>" target="_blank"><?php echo $storage['address']; ?></td>
                <td class="text-right">
                    <?php if ($storage['deletable']): ?><button class="btn btn-outline-danger" data-toggle="modal" data-target="#deleteModal">Törlés</button><?php endif; ?>
                    <a class="btn btn-outline-primary" href="<?php echo base_url() . 'storages/restore/' . $storage['id']; ?>">Visszaállítás</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Raktár eltávolítása</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                A raktár törlésével teljesen eltávolítod az a rendszerből. Ez csak olyan raktárak esetében lehetséges, ahol még semmilyen eszköz nem volt leltárazva. A művelet nem viszavonható. Biztosan folytatni szeretnéd?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Mégsem</button>
                <a href="<?php echo base_url() . 'storages/delete/' . $storage['id']; ?>" class="btn btn-danger">Törlés</a>
            </div>
        </div>
    </div>
</div>
