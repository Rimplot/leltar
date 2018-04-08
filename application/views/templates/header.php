<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php echo $page_title; ?> | Leltár</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/styles.css" />
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <a class="navbar-brand" href="<?php echo base_url(); ?>">Leltár</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
            <a class="nav-link" href="<?php echo base_url(); ?>">Főoldal</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>items">Eszközök</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>categories">Kategóriák</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>storages">Raktárak</a>
        </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="text" placeholder="Keresés">
        <button class="btn btn-secondary my-2 my-sm-0" type="submit">Keresés</button>
        </form>
    </div>
    </nav>

    <div class="container">
