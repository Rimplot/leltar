<h2><?php echo $page_title; ?></h2>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Azonosító</th>
            <th scope="col">Név</th>
            <th scope="col">Tartalom</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row"><?php echo $label['id']; ?></th>
            <td><?php echo $label['name']; ?></td>
            <td><code><?php echo substr($label['content'], 0, 80); echo (strlen($label['content']) > 80) ? "..." : ""; ?></code></td>
            <td class="float-right">
                <a class="btn btn-primary" href="<?php echo base_url() . 'labels/edit/' . $label['id']; ?>">Módosítás</a>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Törlés</button>
            </td>
        </tr>
    </tbody>
</table>

<h4>Kategóriák ilyen címkével</h4>
<?php if (/* !is_null($inventory_history) && */ count($categories)) : ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Azonosító</th>
                <th scope="col">Név</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category) : ?>
                <tr>
                    <th scope="row"><?php echo $category['id']; ?></th>
                    <td><a href="<?php echo base_url() . 'categories/' . $category['id']; ?>"><?php echo $category['name']; ?></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>Ez a címke még nincs hozzárendelve egy kategóriához sem.</p>
<?php endif; ?>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Címke törlése</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                A címke törlésével az összes hozzárendelt kategória elveszíti a címkéjét. Biztosan folytatni szeretnéd?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Mégsem</button>
                <a href="<?php echo base_url() . 'labels/delete/' . $label['id']; ?>" class="btn btn-danger">Törlés</a>
            </div>
        </div>
    </div>
</div>
