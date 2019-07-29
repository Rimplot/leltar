<?php if ($this->session->auth_required): ?>
    <div class="alert alert-warning alert-dismissible" id="success-alert">
        <button type="button" class="close">&times;</button>
        Ennek az oldalnak az eléréséhez előbb be kell jelentkezned.
    </div>
<?php endif; ?>

<h2><?php echo $page_title; ?></h2>

<?php if (!$this->session->logged_in): ?>
    <?php echo form_open(''); ?>
    <div class="row">
        <div class="col-lg-4 offset-lg-1">
            <div class="form-group<?php echo (form_error('login')) ? ' has-danger' : '' ?>">
                <label class="form-control-label">Felhasználónév vagy e-mail cím</label>
                <input type="text" class="form-control<?php echo (form_error('login')) ? ' is-invalid' : '' ?>" name="login" value="<?php echo set_value('login'); ?>">
                <div class="invalid-feedback"><?php echo form_error('login'); ?></div>
            </div>
            <div class="form-group<?php echo (form_error('password')) ? ' has-danger' : '' ?>">
                <label class="form-control-label">Jelszó</label>
                <input type="password" class="form-control<?php echo (form_error('password')) ? ' is-invalid' : '' ?>" name="password">
                <div class="invalid-feedback"><?php echo form_error('password'); ?></div>
            </div>
            <div class="form-group">
                <input type="submit" name="submit" class="btn btn-info" value="Bejelentkezés" />
            </div>
        </div>
    </div>
    <?php form_close(); ?>
<?php else: ?>
    <div class="container">
        <h4>Bejelentkezve</h4>
        <p>Peace, kedves <?php echo $this->session->user['name']; ?>!</p>
        <a href="<?php echo base_url(); ?>logout" class="btn btn-info">Kijelentkezés</a>
    </div>
<?php endif; ?>
