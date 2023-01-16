<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $this->getLibPath(); ?>bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $this->getCssPath(); ?>style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arvo&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link href="/favicon.ico" rel="icon" type="image/x-icon">
    <title>Sebastián Cámara - Pixel-Artist</title>
</head>

<body>
	<!-- Vertically centered modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Text here.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
	<!-- Brand -->
    <main>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <!-- Brand -->
                <a class="navbar-brand" href="<?php echo URL; ?>" id="brand">HITAITARO</a>

                <!-- Toggler -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <!-- Navigation -->
                    <ul class="navbar-nav ms-auto" id="links">
                        <li class="nav-item" id="patreon">
                            <a class="nav-link" id="navbarLandings" href="http://patreon.com/hitaitaro" aria-haspopup="true" aria-expanded="false">
                                Patreon
                            </a>
                        </li>
                        <li class="nav-item" id="twitter">
                            <a class="nav-link" id="navbarLandings" href="http://twitter.com/HITAITARO" aria-haspopup="true" aria-expanded="false">
                                Twitter
                            </a>
                        </li>
                        <li class="nav-item" id="itchio">
                            <a class="nav-link" id="navbarLandings" href="http://hitaitaro.itch.io/" aria-haspopup="true" aria-expanded="false">
                                Itch.io
                            </a>
                        </li>
                        <li class="nav-item" id="email">
                            <a class="nav-link" id="navbarLandings" href="mailto:sebastian@hitaitaro.com" aria-haspopup="true" aria-expanded="false">
                                E-mail
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto" id="secs">
                        <li class="nav-item">
                            <a class="nav-link" id="navbarLandings" href="<?php echo URL; ?>#portfolio" aria-haspopup="true"
                                aria-expanded="false">
                                Portfolio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="navbarLandings" href="<?php echo URL; ?>#commissions" aria-haspopup="true"
                                aria-expanded="false">
                                Commissions
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>