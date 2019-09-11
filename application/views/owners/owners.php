<div class="row">
    <div class="col-md-6">
        <h2><?php echo $page_title; ?></h2>
    </div>
    <div class="col-md-6">
        <div class="float-right">
            <a href="<?php echo base_url(); ?>owners/add" class="btn btn-info">Új tulajdonos hozzáadása</a>
        </div>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Azonosító</th>
            <th scope="col">Név</th>
            <th scope="col">Birtokolt eszközök száma</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($owners as $owner) : ?>
            <tr>
                <th scope="row"><?php echo $owner['id']; ?></th>
                <td><a href="<?php echo base_url() . 'owners/' . $owner['id']; ?>"><?php echo $owner['name']; ?></a></td>
                <td><?php echo $owner['item_num']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
