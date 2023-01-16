<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta name="description" content="" />
		<meta name="author" content="" />
		<title><?php echo WEBSITE; ?> - <?php if (isset($this->title)){ echo $this->title; } ?></title>
		<link
			rel="stylesheet"
			href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
			integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD"
			crossorigin="anonymous"
		/>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
        <link rel="stylesheet" href="<?php echo $this->getCssPath(); ?>styles.css">
	</head>
	<body>
		<div class="d-flex bg-light" id="wrapper">
			<div class="border-end bg-white" id="sidebar-wrapper">
				<div class="sidebar-heading border-bottom fs-3 fw-semibold p-4 text-center" style="min-height: 5.7rem">
					<a href="<?php echo URL; ?>" class="text-dark text-decoration-none"><?php echo WEBSITE; ?></a>
				</div>
				<div class="list-group list-group-flush">
					<?php
					$menu = $this->getMenu();	
					foreach ($menu as $list => $modules)
					{
						foreach ($modules as $module => $items)
						{
                            echo '
                            <small class="px-3 pt-3 mb-1">
                                <span class="bg-primary text-white px-2 rounded-pill">' . $this->__localization[$module] . '</span>
                            </small>';
                            foreach ($items as $item)
                            {
                                $active = '';
                                if ($this->isFlagged($item['flag'])) {
                                    $active = 'active';
                                }
                                echo '
                                <a class="list-group-item list-group-item-action list-group-item-light ' . $active . ' px-3" href="' . URL . $item['url'] . '">
                                    ' . $this->__localization[$item['name']] . '
                                </a>';
                            }
						}
					}
					?>
				</div>
			</div>
			<div id="page-content-wrapper">
				<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom p-4 bg-white" style="min-height: 5.7rem">
					<div class="container-fluid">
						<a class="link-secondary fs-3 opacity-25 ps-3" id="sidebarToggle" href="#">
							<i class="bi bi-toggle2-on"></i>
						</a>
						<button
							class="navbar-toggler"
							type="button"
							data-bs-toggle="collapse"
							data-bs-target="#navbarSupportedContent"
							aria-controls="navbarSupportedContent"
							aria-expanded="false"
							aria-label="Toggle navigation"
						>
							<span class="navbar-toggler-icon"></span>
						</button>
						<div class="collapse navbar-collapse" id="navbarSupportedContent">
							<ul class="navbar-nav ms-auto mt-2 mt-lg-0">
								<li class="nav-item"><a class="nav-link" href="<?php echo ROOT_URL; ?>"><?php echo $this->__localization['PUBLIC_VIEW']; ?></a></li>
								<li class="nav-item"><a class="nav-link" href="<?php echo URL; ?>"><?php echo $this->__localization['MODULES']; ?></a></li>
								<li class="nav-item"><a class="nav-link" href="<?php echo URL; ?>profile/"><?php echo $this->__localization['PROFILE']; ?></a></li>
								<li class="nav-item"><a class="nav-link" href="<?php echo URL; ?>login/logout/"><?php echo $this->__localization['LOGOUT']; ?></a></li>
							</ul>
						</div>
					</div>
				</nav>