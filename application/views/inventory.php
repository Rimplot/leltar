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
            <option value="<?php echo $storages[$i]['id']; ?>"><?php echo $storages[$i]['name']; ?></option>
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


<!-- Box Content Modal -->
<div class="modal fade" id="boxContentModal" tabindex="-1" role="dialog" aria-labelledby="boxContentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="boxContentModalLabel">Doboz tartalma</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="boxContentItems" id="boxContentItems"></ul>
                <input type="text" name="barcode" id="secondaryBarcodeTextInput" title="barcode" style="position: absolute; top: -1000px;">
            </div>
            <div class="modal-footer">
                <button id="modalBtnSkip" class="btn btn-outline-danger">Hiányzó elemek átugrása</button>
            </div>
        </div>
    </div>
</div>


<audio id="beep">
    <source src="<?php echo base_url(); ?>assets/beep.mp3" type="audio/mp3">
</audio>


<script>
    const successNotification = window.createNotification({
        showDuration: 3500,
        positionClass: 'nfc-bottom-right'
    });
    const errorNotification = window.createNotification({
        showDuration: 3500,
        positionClass: 'nfc-bottom-right',
        theme: 'error'
    });
    const infoNotification = window.createNotification({
        showDuration: 3500,
        positionClass: 'nfc-bottom-right',
        theme: 'info'
    });
    const warningNotification = window.createNotification({
        showDuration: 3500,
        positionClass: 'nfc-bottom-right',
        theme: 'warning'
    });

    var storagesArr = JSON.parse('<?php echo json_encode($storages); ?>');
    var storages = [];

    for (var i = 0, l = storagesArr.length; i < l; i++) {
        storages[storagesArr[i].id] = storagesArr[i];
    }

	$(document).ready(function(){
        var scanning = false;
        var boxContentShown = false;
        var timeout = null;
        var content = null;

		var $stopBtn = $('#btnStop');
		var $startBtn = $('#btnStart');
		var $storageSelect = $('#storageSelect');
		var $sectionSelect = $('#sectionSelect');
        var $sessionSelect = $('#sessionSelect');
        var $barcodeTextInput = $('#barcodeTextInput');
        var $secondaryBarcodeTextInput = $('#secondaryBarcodeTextInput');
		var $message = $('#text');
        var $sessionName = $('input[name="session_name"]');

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
                    errorNotification({
                        title: 'Hiba!',
                        message: 'Nincs kapcsolat az adatbázissal.'
                    });
                }
            });
        });

		$startBtn.click(function(e) {
			e.preventDefault();
            if ($sessionSelect.val() == 'new') {
                $('#startSessionModal').modal('show');
            }
            else {
                switchStorage($storageSelect, $sectionSelect, $storageSelect.val());

                $startBtn.hide();
                $sessionSelect.hide();
                $storageSelect.hide();
                $sectionSelect.show();
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
			if (e.which === 13 && $(this).val() !== "") {
                timeout = null;

                var session = $sessionSelect.val();
				var barcode = $(this).val().toUpperCase();
                var sector = $sectionSelect.val();

                $.ajax({
					url: "<?php echo base_url(); ?>" + "ajax/check_type",
					type: "post",
					dataType: "json",
					data: {
						'barcode': barcode
					},
					success: function(data) {
						if (data.found) {
							switch (data.type) {
                                case "<?php echo BARCODE_TYPE_ID['item']; ?>":
                                    var barcode_id = data.id;
                                    var quantity = null;

                                    if (boxContentShown) {
                                        var found = false;
                                        content.forEach(item => {
                                            if (item.barcode == barcode) {
                                                found = true;
                                                if ($('#boxContentItems #' + item.id).hasClass('check')) {
                                                    infoNotification({
                                                        title: "Már leltárazva",
                                                        message: "Új adatbevitelre nem került sor."
                                                    });
                                                } else {
                                                    if (data.stock) {
                                                        quantity = prompt('Mennyiség:')
                                                    }

                                                    postInventory(barcode, barcode_id, session, sector, quantity);
                                                    $('#boxContentItems #' + item.id).addClass('check');
                                                    
                                                    var complete = true;
                                                    $('ul#boxContentItems li').each(function() {
                                                        if (!$(this).hasClass('check')) {
                                                            complete = false;
                                                        }
                                                    });

                                                    if (complete) {
                                                        $('#boxContentModal').modal('hide');
                                                        successNotification({
                                                            title: "Siker!",
                                                            message: "Doboz teljes tartalma leltárazva."
                                                        });
                                                        $('#beep')[0].play();
                                                    }
                                                }
                                            }
                                        });
                                        if (!found) {
                                            warningNotification({
                                                title: "Idegen eszköz",
                                                message: "Ennek az eszköznek nem ebben a dobozban van a helye. Nem került leltárazásra."
                                            })
                                        }
                                    } else {
                                        if (data.stock) {
                                            quantity = prompt('Mennyiség:')
                                        }

                                        postInventory(barcode, barcode_id, session, sector, quantity);
                                    }
                                    break;
                                case "<?php echo BARCODE_TYPE_ID['box']; ?>":
                                    if (!boxContentShown) {
                                        infoNotification({
                                            title: 'Doboz',
                                            message: data.box.name
                                        });
                                        content = data.items;
                                        $('#boxContentItems').empty();
                                        content.forEach(item => {
                                            $('#boxContentItems').append('<li id="' + item.id + '">' + item.name + '</li>')
                                        });
                                        $('#boxContentModal').modal('show');
                                        boxContentShown = true;
                                    } else {
                                        warningNotification({
                                            title: 'Figyelem!',
                                            message: 'Most egy másik doboz leltárazása van folyamatban.'
                                        });
                                    }
                                    break;
                                case "<?php echo BARCODE_TYPE_ID['sector']; ?>":
                                    if (!boxContentShown) {
                                        if ($storageSelect.val() != data.sector.storage_id) {
                                            if (confirm('Ez a szektor egy másik raktárban van. Biztosan át szeretnél váltani rá?')) {
                                                switchStorage($storageSelect, $sectionSelect, data.sector.storage_id);
                                                selectSector($sectionSelect, data.sector.id);
                                                infoNotification({
                                                    title: 'Info',
                                                    message: 'Szektor átváltva erre: ' + data.sector.name
                                                });
                                            }
                                        }
                                        else {
                                            selectSector($sectionSelect, data.sector.id);
                                            infoNotification({
                                                title: 'Info',
                                                message: 'Szektor átváltva erre: ' + data.sector.name
                                            });
                                        }
                                    } else {
                                        warningNotification({
                                            title: 'Figyelem!',
                                            message: 'A szektor megváltoztatása egy doboz leltárazása közben nem lehetséges.'
                                        });
                                    }
                                    break;
                                default:
                                    break;
                            }
						}
						else {
                            errorNotification({
                                title: 'Hiba!',
                                message: 'Ismeretlen eszköz.'
                            });
						}
					},
                    error: function(data) {
                        errorNotification({
                            title: 'Hiba!',
                            message: 'Nincs kapcsolat az adatbázissal.'
                        });
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

        $secondaryBarcodeTextInput.keypress(function(e){
            if (e.which === 13) {
                $barcodeTextInput.val($(this).val());
                $barcodeTextInput.trigger(
                    jQuery.Event('keypress', { which: 13 })
                );
            }
        });
        
        $(document.body).on('keydown', function(e) {
            if (scanning) {
                if (boxContentShown) {
                    $secondaryBarcodeTextInput.focus();
                } else {
                    $barcodeTextInput.focus();
                }
            }
        });

        function keyUpHandler(e) {
            clearTimeout(timeout);
            var elem = $(this);
            timeout = setTimeout(function() {
                elem.val('');
            }, 100);
        }
		$barcodeTextInput.keyup(keyUpHandler);
        $secondaryBarcodeTextInput.keyup(keyUpHandler);
        
        $('#boxContentModal').on('hidden.bs.modal', function () {
            boxContentShown = false;
        });

        $('#modalBtnSkip').click(function() {
            if (confirm('Még nem leltáraztál le mindent a doboz tartalmából. Biztosan át szeretnéd ugrani a fennmaradó elemeket?')) {
                $('#boxContentModal').modal('hide');
            }
        });
	});

    function switchStorage(storageSelect, sectionSelect, storage_id) {
        storageSelect.val(storage_id);
        var sections = storages[storage_id].sectors;

        sectionSelect.empty();
        for (var i = 0, l = sections.length; i < l; i++) {
            sectionSelect.append('<option value="' + sections[i].id + '">' + sections[i].name + '</option>');
        }
    }

    function selectSector(sectionSelect, sector_id) {
        sectionSelect.val(sector_id);
    }

    function postInventory(barcode, barcode_id, session, sector, quantity) {
        $.ajax({
            url: "<?php echo base_url(); ?>" + "ajax/inventory",
            type: "post",
            dataType: "json",
            data: {
                'session_id': session,
                'barcode_id': barcode_id,
                'sector': sector,
                'quantity': quantity
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
                        row = '<tr id="' + data.barcode + '">' +
                                '<th scope="row">' + data.id + '</th>' +
                                '<td><a href="' + '<?php echo base_url(); ?>' + 'items/' + data.item_id + '">' + data.name + '</a></td>' +
                                '<td>' + data.barcode + '</td>';
                        if (data.category_id) {
                            row += '<td><a href="' + '<?php echo base_url(); ?>' + 'categories/' + data.category_id + '">' + data.category + '<a/></td>';
                        } else {
                            row += '<td></td>';
                        }
                        row +=  '<td>' + data.time + '</td>' +
                                '<td><a href="' + '<?php echo base_url(); ?>' + 'sessions/' + data.session_id + '">' + data.session + '</a></td>' +
                                '<td><a href="' + '<?php echo base_url(); ?>' + 'storages/' + data.storage_id + '">' + data.storage + ', ' + data.sector + '</a></td>' +
                               '</tr>'
                        $('#results').find('tbody').prepend(row);
                    }

                    if (data.only_updated) {
                        infoNotification({
                            title: data.name,
                            message: "Ez az eszköz már leltárazva volt az imént ebben a szektorban. Időpont frissítve, új bejegyzés nem került létrehozásra."
                        });
                    } else {
                        successNotification({
                            title: data.name,
                            message: barcode
                        });
                        $('#beep')[0].play();
                    }
                }
                else {
                    errorNotification({
                        title: 'Hiba!',
                        message: 'Ismeretlen eszköz.'
                    });
                }
            },
            error: function(data) {
                errorNotification({
                    title: 'Hiba!',
                    message: 'Nincs kapcsolat az adatbázissal.'
                });
            }
        });
    }
</script>