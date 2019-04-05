<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php echo $page_title; ?> | Leltár</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/styles.css" />
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/javascript/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/javascript/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/javascript/scripts.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/javascript/rsvp-3.1.0.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/javascript/sha-256.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/javascript/qz-tray.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <a class="navbar-brand" href="<?php echo base_url(); ?>">Leltár</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav mr-auto">
        <li class="nav-item<?php echo ($menu == 'inventory') ? ' active' : '' ?> ">
            <a class="nav-link" href="<?php echo base_url(); ?>inventory">Leltárazás</span></a>
        </li>
        <li class="nav-item<?php echo ($menu == 'items') ? ' active' : '' ?>">
            <a class="nav-link" href="<?php echo base_url(); ?>items">Eszközök</a>
        </li>
        <li class="nav-item<?php echo ($menu == 'categories') ? ' active' : '' ?>">
            <a class="nav-link" href="<?php echo base_url(); ?>categories">Kategóriák</a>
        </li>
        <li class="nav-item<?php echo ($menu == 'boxes') ? ' active' : '' ?>">
            <a class="nav-link" href="<?php echo base_url(); ?>boxes">Dobozok</a>
        </li>
        <li class="nav-item<?php echo ($menu == 'storages') ? ' active' : '' ?>">
            <a class="nav-link" href="<?php echo base_url(); ?>storages">Raktárak</a>
        </li>
        <li class="nav-item<?php echo ($menu == 'sessions') ? ' active' : '' ?>">
            <a class="nav-link" href="<?php echo base_url(); ?>sessions">Sessions</a>
        </li>
        <li class="nav-item<?php echo ($menu == 'labels') ? ' active' : '' ?>">
            <a class="nav-link" href="<?php echo base_url(); ?>labels">Címkék</a>
        </li>
        <li class="nav-item<?php echo ($menu == 'printer') ? ' active' : '' ?>">
            <a class="nav-link" href="<?php echo base_url(); ?>printer">Nyomtatás</a>
        </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" action="<?php echo base_url(); ?>barcodes">
            <input class="form-control mr-sm-2" type="text" name="barcode" placeholder="Vonalkód...">
            <button class="btn btn-secondary my-2 my-sm-0" type="submit">Keresés</button>
        </form>
    </div>
    </nav>

    <div class="container">
