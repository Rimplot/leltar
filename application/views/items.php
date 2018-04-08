<h2><?php echo $page_title; ?></h2>

<table class="table">
  <thead>
    <tr>
      <th scope="col">Azonosító</th>
      <th scope="col">Név</th>
      <th scope="col">Vonalkód</th>
      <th scope="col">Kategória</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($items as $item) : ?>
    <tr>
      <th scope="row"><a href="<?php echo base_url() . 'items/view/' . $item['id']; ?>"><?php echo $item['id']; ?></a></th>
      <td><?php echo $item['name']; ?></td>
      <td><?php echo $item['barcode']; ?></td>
      <td><?php echo $item['category']; ?></td>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>
