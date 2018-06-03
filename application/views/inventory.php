<input type="text" name="barcode" id="barcodeTextInput" title="barcode" style="position: absolute; top: -100px;">

<div class="row">
    <h2><?php echo $page_title; ?></h2>
</div>

<select id="storageSelect" title="Storage select">
	<?php foreach ($storages as $storage) : ?>
        <option value="<?php echo $storage['id']; ?>"><?php echo $storage['name']; ?></option>
    <?php endforeach; ?>
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
            <th scope="col">Hely</th>
        </tr>
    </thead>
    <tbody>
        <!--<td colspan="6">Még egy eszköz sem volt most leltárazva.</td>-->
    </tbody>
</table>


<script>
	$(document).ready(function(){
		var scanning = false;
        var timeout = null;

		var $stopBtn = $('#btnStop');
		var $startBtn = $('#btnStart');
		var $storageSelect = $('#storageSelect');
		var $barcodeTextInput = $('#barcodeTextInput');
		var $message = $('#text');

		$stopBtn.hide().removeClass('d-none');
        $message.hide().removeClass('d-none');

		$startBtn.click(function(e) {
			e.preventDefault();
			$startBtn.hide();
			$storageSelect.hide();
			$stopBtn.show();
			$message.show();
			$barcodeTextInput.focus();
			scanning = true;
		});

		$stopBtn.click(function(e) {
			e.preventDefault();
			$stopBtn.hide();
			$storageSelect.show();
			$startBtn.show();
			$message.hide();
			$barcodeTextInput.blur();
			scanning = false;
		});

		$barcodeTextInput.keypress(function(e){
			if (e.which === 13) {
                timeout = null;

				var barcode = $(this).val().toUpperCase();
				var storage = $storageSelect.val();

				$.ajax({
					url: "<?php echo base_url(); ?>" + "ajax/inventory",
					type: "post",
					dataType: "json",
					data: {
						'barcode': barcode,
						'storage': storage
					},
					success: function(data) {
						if (data.success) {
							var $row = $('#' + barcode);
							var storageName = $('option[value="' + storage + '"').eq(0).text();
							if ($row.length) {
								$row.prependTo('#results > tbody');
								var $rowData = $row.children().filter('td');
								$rowData.eq(4).text(data.time);
								$rowData.eq(5).text(data.storage);
							}
							else {
								$('#results').find('tbody').prepend(
                                    '<tr id="' + data.barcode + '">' +
                                        '<td>' + data.id + '</td>' +
                                        '<td>' + data.name + '</td>' +
                                        '<td>' + data.barcode + '</td>' +
                                        '<td>' + data.category + '</td>' +
                                        '<td>' + data.time + '</td>' +
                                        '<td>' + data.storage + '</td>' +
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