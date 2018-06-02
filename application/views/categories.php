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
                <th scope="row"><a href="<?php echo base_url() . 'categories/view/' . $category['id']; ?>"><?php echo $category['id']; ?></a></th>
                <td><?php echo $category['name']; ?></td>
                <td><?php echo $category['parent']; ?></td>
                <td><?php echo $category['item_num']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
