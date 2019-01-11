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
            <th scope="col">Szülő doboz</th>
            <th scope="col">Eszközök száma</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($boxes as $box) : ?>
            <tr>
                <th scope="row"><?php echo $box['id']; ?></th>
                <td><a href="<?php echo base_url() . 'boxes/' . $box['id']; ?>"><?php echo $box['name']; ?></a></td>
                <td><a href="<?php echo base_url() . 'boxes/' . $box['parent_id']; ?>"><?php echo $box['parent']; ?></a></td>
                <td><?php echo $box['item_num']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
