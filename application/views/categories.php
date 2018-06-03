<div class="row">
    <div class="col-md-8">
        <h2><?php echo $page_title; ?></h2>
    </div>
    <div class="col-md-4">
        <a href="<?php echo base_url(); ?>categories/add" class="btn btn-info float-right">Új kategória hozzáadása</a>
    </div>
</div>

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
        <?php foreach ($categories as $category) : ?>
            <tr>
                <th scope="row"><?php echo $category['id']; ?></th>
                <td><a href="<?php echo base_url() . 'categories/' . $category['id']; ?>"><?php echo $category['name']; ?></a></td>
                <td><a href="<?php echo base_url() . 'categories/' . $category['parent_id']; ?>"><?php echo $category['parent']; ?></a></td>
                <td><?php echo $category['item_num']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
