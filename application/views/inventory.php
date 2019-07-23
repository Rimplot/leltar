<input type="text" name="barcode" id="barcodeTextInput" title="barcode" style="position: absolute; top: -100px;">

<div class="row">
    <h2><?php echo $page_title; ?></h2>
</div>

<select id="sessionSelect" title="Session select">
	<?php foreach ($sessions as $session) : ?>
        <option value="<?php echo $session['id']; ?>"><?php echo $session['name']; ?></option>
    <?php endforeach; ?>
    <option value="new">&#60;új session&#62;</option>
</select>

<select id="storageSelect" title="Storage select">
    <?php for ($i = 0; $i < count($storages); $i++) :
        if (count($storages[$i]['sectors']) > 0) : ?>
            <option value="<?php echo $i; ?>"><?php echo $storages[$i]['name']; ?></option>
        <?php endif; ?>
    <?php endfor; ?>
</select>

<select id="sectionSelect" title="Sector select" class="d-none"></select>

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
    <td><?php if ($item['session_id']): ?><a href="<?php echo base_url() . 'sessions/' . $item['session_id']; ?>"><?php echo $item['session']; ?></a><?php else: ?><em>manuálisan hozzáadva</em><?php endif ?></td>
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
		var $sectionSelect = $('#sectionSelect');
        var $sessionSelect = $('#sessionSelect');
		var $barcodeTextInput = $('#barcodeTextInput');
		var $message = $('#text');
        var $sessionName = $('input[name="session_name"]');

        var storages = JSON.parse('<?php echo json_encode($storages); ?>');

        $stopBtn.hide().removeClass('d-none');
        $sectionSelect.hide().removeClass('d-none')
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
                var sections = storages[$storageSelect.val()].sectors;

                $sectionSelect.empty();
                for (var i = 0, l = sections.length; i < l; i++) {
                    $sectionSelect.append('<option value="' + sections[i].id + '">' + sections[i].name + '</option>');
                }
                
                if (sections.length) {
                    $sectionSelect.show();
                } else {
                    $sectionSelect.hide();
                }

                $startBtn.hide();
                $sessionSelect.hide();
                $storageSelect.hide();
                $stopBtn.show();
                $message.show();
                $barcodeTextInput.focus();
                scanning = true;
            }
		});

		$stopBtn.click(function(e) {
			e.preventDefault();
			$stopBtn.hide();
            $sessionSelect.show();
            $storageSelect.show();
			$sectionSelect.hide();
			$startBtn.show();
			$message.hide();
			$barcodeTextInput.blur();
			scanning = false;
		});

		$barcodeTextInput.keypress(function(e){
			if (e.which === 13) {
                timeout = null;

				var barcode = $(this).val().toUpperCase();
                var sector = $sectionSelect.val();

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
								$rowData.eq(4).html('<a href="' + '<?php echo base_url(); ?>' + 'sessions/' + data.session_id + '">' + data.session + '</a>');
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
                                        '<td><a href="' + '<?php echo base_url(); ?>' + 'sessions/' + data.session_id + '">' + data.session + '</a></td>' +
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

		/*$('html').click(function(e){
			if (scanning && e.target.id !== 'btnStop' && e.target.id !== 'sectionSelect') {
                alert(e.target);
				$barcodeTextInput.focus();
			}
        });*/
        
        $(document.body).on('keydown', function(e) {
            if(document.activeElement.tagName.toLowerCase() != 'input' && scanning) {
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