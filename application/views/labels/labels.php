<div class="row">
    <div class="col-md-8">
        <h2><?php echo $page_title; ?></h2>
    </div>
    <div class="col-md-4">
        <a href="<?php echo base_url(); ?>labels/add" class="btn btn-info float-right">Új címke hozzáadása</a>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Azonosító</th>
            <th scope="col">Név</th>
            <th scope="col">Tartalom</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($labels as $label) : ?>
            <tr>
                <th scope="row"><?php echo $label['id']; ?></th>
                <td><a href="<?php echo base_url() . 'labels/' . $label['id']; ?>"><?php echo $label['name']; ?></a></td>
                <td><code><?php echo substr($label['content'], 0, 120); echo (strlen($label['content']) > 120) ? "..." : ""; ?></code></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
