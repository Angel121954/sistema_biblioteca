<?php

session_start();
require_once "assets/modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

$id_usuario = $_SESSION["id_usuario"];
$usuario_result = $sql->efectuarConsulta("SELECT * FROM usuarios WHERE id_usuario = $id_usuario");
$usuario = $usuario_result->fetch_assoc();

$cantidades_result = $sql->efectuarConsulta("SELECT 
        (
            SELECT l.titulo_libro
            FROM libros l
            INNER JOIN reservas_has_libros rl ON rl.libros_id_libro = l.id_libro
            INNER JOIN reservas r ON rl.reservas_id_reserva = r.id_reserva
            WHERE l.estado_libro = 'Activo'
            GROUP BY l.id_libro
            ORDER BY COUNT(r.id_reserva) DESC
            LIMIT 1
        ) AS titulo_mas_reservado,
        (
            SELECT autor_libro
            FROM libros
            WHERE estado_libro = 'Activo'
            GROUP BY autor_libro
            ORDER BY COUNT(*) DESC
            LIMIT 1
        ) AS autor_mas_libros,
        (
            SELECT SUM(cantidad_libro)
            FROM libros
            WHERE disponibilidad_libro = 'Disponible'
            AND estado_libro = 'Activo'
        ) AS total_libros_disponibles,
        (
            SELECT COUNT(*) FROM libros
            WHERE categoria_libro = 'Historia'
            AND cantidad_libro > 0
        ) AS cantidad_historia");

$cantidades = $cantidades_result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Libros</title>

    <!--Font Awesome local-->
    <link href="assets/libs/awesome/css/all.min.css" rel="stylesheet" type="text/css">

    <!--SweetAlert local-->
    <link href="assets/libs/sweetAlert/sweetalert2.min.css" rel="stylesheet" type="text/css">

    <!--Bootstrap local-->
    <link href="assets/libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!--DataTable local-->
    <link href="assets/libs/datatables/datatables.min.css" rel="stylesheet">

    <!--Estilo personal-->
    <link href="assets/css/estilo_general.css" rel="stylesheet">
</head>

<body id="page-top">



    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <?php switch ($_SESSION["tipo_usuario"]):
                case "1": ?>
                    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                        <div class="sidebar-brand-icon rotate-n-15">
                            <i class="fas fa-laugh-wink"></i>
                        </div>
                        <div class="sidebar-brand-text mx-3">Biblioteca</div>
                    </a>
                <?php break;
                default: ?>
                    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index_libros.php">
                        <div class="sidebar-brand-icon rotate-n-15">
                            <i class="fas fa-laugh-wink"></i>
                        </div>
                        <div class="sidebar-brand-text mx-3">Biblioteca</div>
                    </a>
            <?php endswitch; ?>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <?php switch ($_SESSION["tipo_usuario"]):
                case "1": ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-fw fa-tachometer-alt"></i>
                            <span>Dashboard</span></a>
                    </li>
                <?php break;
                default: ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="index_libros.php">
                            <i class="fas fa-fw fa-tachometer-alt"></i>
                            <span>Dashboard</span></a>
                    </li>
            <?php endswitch; ?>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Funcionalidad
            </div>

            <?php if ($_SESSION["tipo_usuario"] === "1"): ?>
                <!-- Enlace: usuarios -->
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="bi bi-people-fill"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Enlace: libros -->
            <li class="nav-item">
                <a class="nav-link" href="index_libros.php">
                    <i class="bi bi-journal-bookmark-fill"></i>
                    <span>Libros</span>
                </a>
            </li>

            <!-- Reservas -->
            <?php switch ($_SESSION["tipo_usuario"]):
                case "1": ?>
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menu_reservas"
                            aria-expanded="true" aria-controls="collapsePages">
                            <i class="fas fa-fw fa-book-open"></i>
                            <span>Reservas</span>
                        </a>

                        <div id="menu_reservas" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <a class="collapse-item" href="index_reservas.php">Reservas</a>
                                <a class="collapse-item" href="assets/controladores/informes/historial_reserva.php">Historial de reservas</a>
                            </div>
                        </div>

                    </li>
                <?php break;
                default: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index_reservas.php">
                            <i class="fas fa-fw fa-book-open"></i>
                            <span>Reservas</span>
                        </a>
                    </li>
            <?php break;
            endswitch; ?>

            <!-- Enlace: prestamos -->
            <li class="nav-item">
                <a class="nav-link" href="index_prestamos.php">
                    <i class="bi bi-clock-history"></i>
                    <span>Prestamos</span>
                </a>
            </li>

            <?php if ($_SESSION["tipo_usuario"] === "1"): ?>
                <!-- Select: Informes -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menu_informes"
                        aria-expanded="true" aria-controls="collapsePages">
                        <i class="fas fa-fw fa-chart-line"></i>
                        <span>Informes</span>
                    </a>
                    <div id="menu_informes" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="assets/controladores/informes/libro_disponible.php">Libros disponibles</a>
                            <a class="collapse-item" href="assets/controladores/informes/libro_prestado.php">Libros prestados</a>
                            <a class="collapse-item" href="assets/controladores/informes/historial_prestamo.php">Historial prestamo</a>
                        </div>
                    </div>
                </li>

                <!-- Enlace: gráficos -->
                <li class="nav-item">
                    <a class="nav-link" href="index_graficos.php">
                        <i class="bi bi-bar-chart-fill"></i>
                        <span>Gráficos</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Enlace: perfil -->
            <li class="nav-item">
                <a class="nav-link" href="#"
                    onclick="actualizarPerfil(
                    '<?php echo $usuario['id_usuario']; ?>',
                    '<?php echo $usuario['nombre_usuario']; ?>',
                    '<?php echo $usuario['apellido_usuario']; ?>',
                    '<?php echo $usuario['email_usuario']; ?>')">
                    <i class="fas fa-user-cog"></i>
                    <span>Perfil</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <div id="content-wrapper" class="d-flex flex-column bg-light">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                    <li class="nav-item dropdown no-arrow d-sm-none">
                        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-search fa-fw"></i>
                        </a>
                        <!-- Parte buscador -->

                    </li>

                    <!-- Nav Item - Alerts -->
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bell fa-fw"></i>
                            <!-- Counter - Alerts -->
                            <span class="badge badge-danger badge-counter">3+</span>
                        </a>
                        <!-- Dropdown - Alerts -->
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="alertsDropdown">
                            <h6 class="dropdown-header">
                                Alerts Center
                            </h6>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">December 12, 2019</div>
                                    <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="mr-3">
                                    <div class="icon-circle bg-success">
                                        <i class="fas fa-donate text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">December 7, 2019</div>
                                    $290.29 has been deposited into your account!
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="mr-3">
                                    <div class="icon-circle bg-warning">
                                        <i class="fas fa-exclamation-triangle text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">December 2, 2019</div>
                                    Spending Alert: We've noticed unusually high spending for your account.
                                </div>
                            </a>
                            <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                        </div>
                    </li>

                    <!-- Nav Item - Messages -->
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-envelope fa-fw"></i>
                            <!-- Counter - Messages -->
                            <span class="badge badge-danger badge-counter">7</span>
                        </a>
                        <!-- Dropdown - Messages -->
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="messagesDropdown">
                            <h6 class="dropdown-header">
                                Message Center
                            </h6>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="dropdown-list-image mr-3">
                                    <img class="rounded-circle" src="img/undraw_profile_1.svg"
                                        alt="...">
                                    <div class="status-indicator bg-success"></div>
                                </div>
                                <div class="font-weight-bold">
                                    <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                        problem I've been having.</div>
                                    <div class="small text-gray-500">Emily Fowler · 58m</div>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="dropdown-list-image mr-3">
                                    <img class="rounded-circle" src="img/undraw_profile_2.svg"
                                        alt="...">
                                    <div class="status-indicator"></div>
                                </div>
                                <div>
                                    <div class="text-truncate">I have the photos that you ordered last month, how
                                        would you like them sent to you?</div>
                                    <div class="small text-gray-500">Jae Chun · 1d</div>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="dropdown-list-image mr-3">
                                    <img class="rounded-circle" src="img/undraw_profile_3.svg"
                                        alt="...">
                                    <div class="status-indicator bg-warning"></div>
                                </div>
                                <div>
                                    <div class="text-truncate">Last month's report looks great, I am very happy with
                                        the progress so far, keep up the good work!</div>
                                    <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="dropdown-list-image mr-3">
                                    <img src="assets/img/fondo_libro.jpg" alt="Libro">
                                    <div class="status-indicator bg-success"></div>
                                </div>
                                <div>
                                    <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                        told me that people say this to all dogs, even if they aren't good...</div>
                                    <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                </div>
                            </a>
                            <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                        </div>
                    </li>

                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION["nombre_usuario"] . " " . $_SESSION["apellido_usuario"]; ?></span>
                            <img class="img-profile rounded-circle"
                                src="img/undraw_profile.svg">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                Profile
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                Settings
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                Activity Log
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="container-fluid">
                <div class="row mb-4 align-items-center justify-content-between header">
                    <div class="col">
                        <h1 class="fw-bold mb-0">
                            Estadísticas del Sistema
                        </h1>
                    </div>
                </div>

                <!-- Content Row -->
                <div class="row">

                    <!-- Cantidad de libros de Historia -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Libros de Historia
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $cantidades['cantidad_historia'] ?? 0;
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-journal-text fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total de libros disponibles -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total de libros disponibles
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $cantidades['total_libros_disponibles'] ?? 0; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-book-half fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Libro más prestado -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Libro más prestado
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $cantidades['titulo_mas_reservado'] ?? ""; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-star-fill fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Autor con más libros -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Autor con más libros
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $cantidades['autor_mas_libros'] ?? ""; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-pen-fill fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="content">
                <div class="container-fluid py-1">
                    <div class="row">
                        <!-- Card: Totales en general -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow-lg border-0 rounded-3">
                                <div class="card-header bg-gradient bg-brown text-black d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 fw-bold">Totales en general</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="text-white-50" href="#" role="button" id="dropdownMenuLink"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownMenuLink">
                                            <li><a class="dropdown-item" href="#">Actualizar</a></li>
                                            <li><a class="dropdown-item" href="#">Exportar</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="#">Ver detalles</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body text-center">
                                    <div class="chart-area mx-auto" style="max-width: 700px;">
                                        <canvas id="grafico_totales"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card: Totales (Pie Chart) -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow-lg border-0 rounded-3">
                                <div class="card-header bg-gradient bg-brown text-black d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 fw-bold">Totales por categoría</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="text-white-50" href="#" role="button" id="dropdownPieMenu"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownPieMenu">
                                            <li><a class="dropdown-item" href="#">Actualizar</a></li>
                                            <li><a class="dropdown-item" href="#">Ver detalles</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body text-center">
                                    <div class="chart-pie pt-4 pb-2 mx-auto" style="max-width: 280px;">
                                        <canvas id="grafico_totales_pie"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="me-3"><i class="fas fa-circle text-brown"></i> Libros</span>
                                        <span class="me-3"><i class="fas fa-circle text-secondary"></i> Reservas</span>
                                        <span><i class="fas fa-circle text-warning"></i> Usuarios</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
        </div>
        <?php $sql->desconectar(); ?>

        <!-- ============================ -->
        <!-- 🔹 Librerías base y dependencias -->
        <!-- ============================ -->
        <script src="assets/libs/jquery/jquery.js"></script>
        <script src="assets/libs/jquery-easing/jquery.easing.min.js"></script>
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- ============================ -->
        <!-- 🔹 Librerías externas -->
        <!-- ============================ -->
        <script src="assets/libs/awesome/js/all.min.js"></script>
        <script src="assets/libs/sweetAlert/sweetalert2.all.min.js"></script>
        <script src="assets/libs/chart.js/Chart.bundle.min.js"></script>

        <!-- ============================ -->
        <!-- 🔹 Script principal del template -->
        <!-- ============================ -->
        <script src="assets/js/sb-admin-2.min.js"></script>

        <!-- ============================ -->
        <!-- 🔹 Script personalizado - Usuarios -->
        <!-- ============================ -->
        <script src="assets/public/js/usuarios/actualizar_perfil.js"></script>

        <!-- ============================ -->
        <!-- 🔹 Gráficos -->
        <!-- ============================ -->
        <script src="assets/public/js/graficos/gestion_total.js"></script>
        <script src="assets/public/js/graficos/gestion_total_pie.js"></script>

        <!--Funcionalidad menú-->
        <script src="assets/funcionalidad/app.js"></script>
</body>

</html>