<input type="text" name="barcode" id="barcodeTextInput" title="barcode" style="position: absolute; top: -100px;">

<div class="row">
    <h2><?php echo $page_title; ?></h2>
</div>

<select id="storageSelect" title="Storage select" class="d-none">
	<?php foreach ($storages as $storage) : ?>
        <optgroup label="<?php echo $storage['name']; ?>">
            <?php foreach ($storage['sectors'] as $sector) : ?>
                <option value="<?php echo $sector['id']; ?>"><?php echo $sector['name']; ?></option>
            <?php endforeach; ?>
        </optgroup>
    <?php endforeach; ?>
</select>

<!--<button role="button" id="btnStart" class="btn btn-success">Start scanning</button>
<button role="button" id="btnStop" class="d-none btn btn-danger">Stop scanning</button>-->

<select id="sessionSelect" title="Session select">
	<?php foreach ($sessions as $session) : ?>
        <option value="<?php echo $session['id']; ?>"><?php echo $session['name']; ?></option>
    <?php endforeach; ?>
    <option value="new">&#60;új session&#62;</option>
</select>

<button role="button" id="btnStart" class="btn btn-success">Start scanning</button>
<button role="button" id="btnStop" class="d-none btn btn-danger">Stop scanning</button>

<p id="text" class="d-none">Listening to barcode scanner...</p>

<table class="table" id="results">
    <thead>
        <tr>
            <th scope="col">Azonosító</th>
            <th scope="col">Név</th>
            <th scope="col">Vonalkód</th>
            <th scope="col">Kategória</th>
            <th scope="col">Utoljára leltározva</th>
            <th scope="session">Session</th>
            <th scope="col">Hely</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($inventory) > 0) : ?>
            <?php foreach($inventory as $item) : ?>
                <tr id="<?php echo $item['barcode']; ?>">
                    <th scope="row"><?php echo $item['id']; ?></th>
                    <td><a href="<?php echo base_url() . 'items/' . $item['item_id']; ?>"><?php echo $item['name']; ?></a></td>
                    <td><?php echo $item['barcode']; ?></td>
                    <td><a href="<?php echo base_url() . 'categories/' . $item['category_id']; ?>"><?php echo $item['category']; ?></a></td>
                    <td><?php echo $item['time']; ?></td>
                    <td><a href=""></a><?php echo $item['session']; ?></a></td>
                    <td><a href="<?php echo base_url() . 'storages/' . $item['storage_id']; ?>"><?php echo $item['storage'] . ', ' . $item['sector']; ?></a></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">Még egy eszköz sem volt most leltárazva.</td></tr>
        <?php endif; ?>
    </tbody>
</table>


<!-- Start Session Modal -->
<div class="modal fade" id="startSessionModal" tabindex="-1" role="dialog" aria-labelledby="startSessionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="startSessionModalLabel">Session indítása</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-control-label">Név</label>
                    <input type="text" class="form-control" name="session_name">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Mégsem</button>
                <button id="modalBtnStartSession" class="btn btn-success">Indítás</button>
            </div>
        </div>
    </div>
</div>


<script>
	$(document).ready(function(){
		var scanning = false;
        var timeout = null;

		var $stopBtn = $('#btnStop');
		var $startBtn = $('#btnStart');
		var $storageSelect = $('#storageSelect');
        var $sessionSelect = $('#sessionSelect');
		var $barcodeTextInput = $('#barcodeTextInput');
		var $message = $('#text');
        var $sessionName = $('input[name="session_name"]');

        $stopBtn.hide().removeClass('d-none');
        $storageSelect.hide().removeClass('d-none')
        $message.hide().removeClass('d-none');

        $('#modalBtnStartSession').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo base_url(); ?>" + "ajax/start_session",
                type: "post",
                dataType: "json",
                data: {
                    'name': $sessionName.val()
                },
                success: function(id) {
                    $sessionSelect.children().last().remove();
                    $sessionSelect.append('<option value="' + id + '" selected>' + $sessionName.val() + '</option>');
                    $sessionSelect.append('<option value="new">&#60;új session&#62;</option>');
                    $sessionName.val('');
                    $('#startSessionModal').modal('hide');
                    $startBtn.click();
                },
                error: function() {
                    alert('Hiba! Nincs kapcsolat az adatbázissal.')
                }
            });
        });

		$startBtn.click(function(e) {
			e.preventDefault();
            if ($sessionSelect.val() == 'new') {
                $('#startSessionModal').modal('show');
            }
            else {
                $startBtn.hide();
                $storageSelect.show();
                $sessionSelect.hide();
                $stopBtn.show();
                $message.show();
                $barcodeTextInput.focus();
                scanning = true;
            }
		});

		$stopBtn.click(function(e) {
			e.preventDefault();
			$stopBtn.hide();
			$storageSelect.hide();
            $sessionSelect.show();
			$startBtn.show();
			$message.hide();
			$barcodeTextInput.blur();
			scanning = false;
		});

		$barcodeTextInput.keypress(function(e){
			if (e.which === 13) {
                timeout = null;

				var barcode = $(this).val().toUpperCase();
				var sector = $storageSelect.val();

				$.ajax({
					url: "<?php echo base_url(); ?>" + "ajax/inventory",
					type: "post",
					dataType: "json",
					data: {
                        'session_id': $sessionSelect.val(),
						'barcode': barcode,
						'sector': sector
					},
					success: function(data) {
						if (data.success) {
							var $row = $('#' + barcode);
							if ($row.length) {
								$row.prependTo('#results > tbody');
								var $rowData = $row.children().filter('td');
								$rowData.eq(3).text(data.time);
								$rowData.eq(4).text(data.session);
								$rowData.eq(5).html('<a href="' + '<?php echo base_url(); ?>' + 'storages/' + data.storage_id + '">' + data.storage + ', ' + data.sector + '</a>');
							}
							else {
								$('#results').find('tbody').prepend(
                                    '<tr id="' + data.barcode + '">' +
                                        '<th scope="row">' + data.id + '</th>' +
                                        '<td><a href="' + '<?php echo base_url(); ?>' + 'items/' + data.item_id + '">' + data.name + '</a></td>' +
                                        '<td>' + data.barcode + '</td>' +
                                        '<td><a href="' + '<?php echo base_url(); ?>' + 'categories/' + data.category_id + '">' + data.category + '<a/></td>' +
                                        '<td>' + data.time + '</td>' +
                                        '<td>' + data.session + '</td>' +
                                        '<td><a href="' + '<?php echo base_url(); ?>' + 'storages/' + data.storage_id + '">' + data.storage + ', ' + data.sector + '</a></td>' +
                                    '</tr>');
							}
						}
						else {
							alert('Ismeretlen eszköz');
						}
					},
                    error: function(data) {
                        alert('Hiba! Nincs kapcsolat az adatbázissal.')
                    }
				});

				$(this).val('');
				return false;
			}
		});

		$('html').click(function(e){
			if (scanning && e.target.id !== 'btnStop') {
				$barcodeTextInput.focus();
			}
		});

		$barcodeTextInput.keyup(function (e) {
			clearTimeout(timeout);
            timeout = setTimeout(function() {
                $barcodeTextInput.val('');
            }, 100);
		});
	});
</script>