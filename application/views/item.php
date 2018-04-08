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
    <tr>
      <th scope="row"><?php echo $item['id']; ?></th>
      <td><?php echo $item['name']; ?></td>
      <td><?php echo $item['barcode']; ?></td>
      <td><?php echo $item['category']; ?></td>
    </tr>
  </tbody>
</table>
