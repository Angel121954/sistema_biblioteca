<?php

session_start();
require_once "assets/modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();
$id_usuario = intval($_SESSION["id_usuario"]);

$consulta = "SELECT rl.id_reserva_has_libro, r.id_reserva, r.fecha_reserva, rl.estado_has_reserva, 
                    rl.cantidad_libros, u.nombre_usuario, l.titulo_libro FROM reservas AS r 
                    INNER JOIN reservas_has_libros rl 
                    ON rl.reservas_id_reserva = r.id_reserva INNER JOIN
                    usuarios AS u ON r.usuarios_id_usuario = u.id_usuario
                    INNER JOIN libros AS l ON rl.libros_id_libro = l.id_libro
                    WHERE rl.estado_has_reserva = 'Activa'";

switch ($_SESSION["tipo_usuario"]) {
    case "1":
        $reservas = $sql->efectuarConsulta($consulta);
        break;
    default:
        $consulta .= "AND u.id_usuario = $id_usuario";
        $reservas = $sql->efectuarConsulta($consulta);
        break;
}
$usuario_result = $sql->efectuarConsulta("SELECT * FROM usuarios WHERE id_usuario = $id_usuario");
$usuario = $usuario_result->fetch_assoc();

$titulos = $sql->efectuarConsulta("SELECT id_libro, titulo_libro FROM libros
                                WHERE disponibilidad_libro = 'Disponible' AND
                                estado_libro = 'Activo'");
$titulo_libro = [];
while ($valor = $titulos->fetch_assoc()) {
    $titulo_libro[] = $valor;
}

$libros_json = json_encode($titulo_libro, JSON_UNESCAPED_UNICODE);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Reservas</title>

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
                <!-- Informes en PDF -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menu_informes"
                        aria-expanded="true" aria-controls="collapsePages">
                        <i class="fas fa-fw fa-chart-line"></i>
                        <span>Informes</span>
                    </a>
                    <div id="menu_informes" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="assets/controladores/informes/libro_disponible.php">Libros disponibles</a>
                            <a class="collapse-item" href="assets/controladores/informes/libro_sin_ejemplar.php">Libros sin ejemplares</a>
                            <a class="collapse-item" href="assets/controladores/informes/libro_prestado.php">Libros prestados</a>
                            <a class="collapse-item" href="assets/controladores/informes/libro_mas_prestado.php">Libros más prestados</a>
                            <a class="collapse-item" href="assets/controladores/informes/libro_menos_prestado.php">Libros menos prestados</a>
                            <a class="collapse-item" href="assets/controladores/informes/usuario_moroso.php">Usuarios morosos</a>
                            <a class="collapse-item" href="assets/controladores/informes/historial_prestamo.php">Historial prestamo</a>
                            <a class="collapse-item" href="assets/controladores/informes/historial_reserva.php">Historial reserva</a>
                        </div>
                    </div>
                </li>

                <!-- Informes en Excel -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menu_informes_excel"
                        aria-expanded="true" aria-controls="collapsePages">
                        <i class="fas fa-fw fa-chart-line"></i>
                        <span>Informes en Excel</span>
                    </a>
                    <div id="menu_informes_excel" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="assets/controladores/informes_excel/libro_disponible_excel.php">Libros disponibles</a>
                            <a class="collapse-item" href="assets/controladores/informes_excel/libro_sin_ejemplar_excel.php">Libros sin ejemplares</a>
                            <a class="collapse-item" href="assets/controladores/informes_excel/libro_prestado_excel.php">Libros prestados</a>
                            <a class="collapse-item" href="assets/controladores/informes_excel/libro_mas_prestado_excel.php">Libros más prestados</a>
                            <a class="collapse-item" href="assets/controladores/informes_excel/libro_menos_prestado_excel.php">Libros menos prestados</a>
                            <a class="collapse-item" href="assets/controladores/informes_excel/usuario_moroso_excel.php">Usuarios morosos</a>
                            <a class="collapse-item" href="assets/controladores/informes_excel/historial_prestamo_excel.php">Historial prestamo</a>
                            <a class="collapse-item" href="assets/controladores/informes_excel/historial_reserva_excel.php">Historial reserva</a>
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

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
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
                <!-- End of Topbar -->
                <!-- Content Wrapper -->
                <div id="content-wrapper" class="d-flex flex-column">

                    <!-- Main Content -->
                    <div id="content">

                        <!-- Begin Page Content -->
                        <div class="container-fluid">
                            <!-- Page Heading -->
                            <?php switch ($_SESSION["tipo_usuario"]):
                                case "1": ?>
                                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                        <h1>Gestión de Reservas</h1>
                                    </div>
                                <?php break;
                                default: ?>
                                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                        <h1>Gestión de Reservas</h1>
                                        <button id="btn_registro_reserva" data-libros="<?php echo htmlspecialchars($libros_json, ENT_QUOTES, 'UTF-8'); ?>"><i
                                                class="fas fa-download fa-sm text-white-50"></i>Agregar reserva</button>
                                    </div>
                            <?php break;
                            endswitch; ?>
                            <!-- DataTales Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <?php switch ($_SESSION["tipo_usuario"]):
                                        case "1": ?>
                                            <h6 class="m-0 font-weight-bold text-primary">Tabla de las reservas realizadas por los usuarios</h6>
                                        <?php break;
                                        case "2": ?>
                                            <h6 class="m-0 font-weight-bold text-primary">Tabla de mis reservas</h6>
                                            <?php break; ?>
                                    <?php endswitch; ?>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered tabla_dt" id="tbl_reservas" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>ID reserva</th> <!--ID reserva tabla pivote-->
                                                    <th>Fecha</th>
                                                    <th>Estado</th>
                                                    <th>Nombre usuario</th>
                                                    <th>Titulo libro</th>
                                                    <th>Cantidad del libro</th>
                                                    <?php switch ($_SESSION["tipo_usuario"]):
                                                        case "1": ?>
                                                            <th class="text-center">Acción frente a la reserva</th>
                                                        <?php break;
                                                        case "2": ?>
                                                            <th class="text-center">Acción de mi reserva</th>
                                                    <?php break;
                                                    endswitch; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($fila = $reservas->fetch_assoc()): ?>
                                                    <tr>
                                                        <th><?php echo $fila["id_reserva_has_libro"]; ?></th>
                                                        <th><?php echo $fila["fecha_reserva"]; ?></th>
                                                        <th><?php echo $fila["estado_has_reserva"]; ?></th>
                                                        <th><?php echo $fila["nombre_usuario"]; ?></th>
                                                        <th><?php echo $fila["titulo_libro"]; ?></th>
                                                        <th><?php echo $fila["cantidad_libros"]; ?></th>

                                                        <td class="text-center">
                                                            <?php switch ($_SESSION["tipo_usuario"]):
                                                                case "1": ?>
                                                                    <button class="btn btn-success btn-sm"
                                                                        onclick="actualizarReserva(<?php echo $fila['id_reserva_has_libro'] ?>, 'Aceptar')">
                                                                        <i class="bi bi-check-circle"></i> Aceptar
                                                                    </button>

                                                                    <button class="btn btn-danger btn-sm"
                                                                        onclick="actualizarReserva(<?= $fila['id_reserva_has_libro'] ?>, 'Cancelar')">
                                                                        <i class="bi bi-x-circle"></i> Cancelar
                                                                    </button>
                                                                <?php break;
                                                                default: ?>
                                                                    <button class="btn btn-danger btn-sm"
                                                                        onclick="eliminarReserva(<?= $fila['id_reserva_has_libro'] ?>)">
                                                                        <i class="bi bi-x-circle"></i> Eliminar
                                                                    </button>
                                                            <?php break;
                                                            endswitch; ?>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.container-fluid -->

                    </div>
                    <!-- End of Main Content -->

                </div>
            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <?php $sql->desconectar(); ?>

    <!-- jQuery -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/jquery-easing/jquery.easing.min.js"></script>

    <!-- Bootstrap -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert -->
    <script src="assets/libs/sweetAlert/sweetalert2.all.min.js"></script>

    <!-- Font Awesome -->
    <script src="assets/libs/awesome/js/all.min.js"></script>

    <!-- SB Admin 2 -->
    <script src="assets/js/sb-admin-2.min.js"></script>

    <!-- JS reservas -->
    <?php if ($_SESSION["tipo_usuario"] !== "1"): ?>
        <!--JS registro reserva-->
        <script src="assets/public/js/reservas/registro_reserva.js"></script>

        <!--JS eliminar reserva-->
        <script src="assets/public/js/reservas/eliminar_reserva.js"></script>
    <?php endif; ?>

    <!-- JS actualizar perfil -->
    <script src="assets/public/js/usuarios/actualizar_perfil.js"></script>

    <?php if ($_SESSION["tipo_usuario"] === "1"): ?>
        <!--JS actualizar estado de la reserva-->
        <script src="assets/public/js/reservas/actualizar_reserva.js"></script>
    <?php endif; ?>

    <!--Funcionalidad menú-->
    <script src="assets/funcionalidad/app.js"></script>

    <!--DataTables local-->
    <script src="assets/libs/datatables/datatables.min.js"></script>
    <script src="assets/funcionalidad/tablas.js"></script>
</body>

</html>