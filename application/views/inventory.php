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

<script>
	$(document).ready(function(){
		var scanning = false;
		var lastKeypressTime;

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
                        alert('Siker!');
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
			e.preventDefault();
			var elapsedTime = new Date().getTime() - lastKeypressTime;
			lastKeypressTime = new Date().getTime();

			if (elapsedTime > 100)
				$(this).val(String.fromCharCode(e.which));
		});
	});
</script>