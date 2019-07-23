<div class="row">
    <div class="col-md-6">
        <h2><?php echo $page_title; ?></h2>
    </div>
    <div class="col-md-6">
        <div class="float-right">
            <button id="btnStartSession" class="btn btn-info">Új session hozzáadása</button>
        </div>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Azonosító</th>
            <th scope="col">Név</th>
            <th scope="col">Kezdet</th>
            <th scope="col">Vég</th>
            <th scope="col">Csippantások száma</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($sessions as $session) : ?>
            <tr>
                <th scope="row"><?php echo $session['id']; ?></th>
                <td><a href="<?php echo base_url() . 'sessions/' . $session['id']; ?>"><?php echo $session['name']; ?></a></td>
                <td><?php echo $session['start']; ?></td>
                <td><?php echo $session['end']; ?></td>
                <td><?php echo $session['item_num']; ?></td>
            </tr>
        <?php endforeach; ?>
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
                    <input type="text" class="form-control" name="session_name" id="sessionName">
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
    $(document).ready(function() {
        $('#btnStartSession').click(function() {
            $('#startSessionModal').modal().show();
        });

        $('#modalBtnStartSession').click(function(e) {
            $.ajax({
                url: "<?php echo base_url(); ?>" + "ajax/start_session",
                type: "post",
                dataType: "json",
                data: {
                    'name': $('#sessionName').val()
                },
                success: function(id) {
                    location.reload();
                },
                error: function() {
                    alert('Hiba! Nincs kapcsolat az adatbázissal.')
                }
            });
        });
    });
</script>
