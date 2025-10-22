<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

require_once "assets/modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();
$fila = $sql->efectuarConsulta("SELECT u.id_usuario, u.nombre_usuario, u.apellido_usuario,
                    u.email_usuario, contrasena_usuario, u.fk_tipo_usuario, t.id_tipo_usuario, t.nombre_tipo_usuario 
                    FROM usuarios AS u INNER JOIN tipos_usuarios AS t ON t.id_tipo_usuario = u.fk_tipo_usuario
                    WHERE estado_usuario = 'Activo'");

$tipos_usuarios_c = $sql->efectuarConsulta("SELECT * FROM tipos_usuarios");
$tipos_usuarios = [];
while ($fila_tipos = $tipos_usuarios_c->fetch_assoc()) {
    $tipos_usuarios[] = $fila_tipos;
}

$tipos_usuarios_json = json_encode($tipos_usuarios, JSON_UNESCAPED_UNICODE);

$id_usuario = $_SESSION["id_usuario"];
$usuario_result = $sql->efectuarConsulta("SELECT * FROM usuarios WHERE id_usuario = $id_usuario");
$usuario = $usuario_result->fetch_assoc();

$inactivos_result = $sql->efectuarConsulta("SELECT COUNT(*) AS cantidad_inactivos FROM usuarios
                            WHERE estado_usuario = 'Inactivo'");
$inactivos = $inactivos_result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Inicio</title>

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
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Papelera usuarios -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="btn_papelera_usuarios" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bi bi-trash3-fill"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter"><?= $inactivos['cantidad_inactivos']; ?></span>
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

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="btn_restaurar_uno" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bi bi-person-up"></i>
                                <span class="badge badge-danger badge-counter"><?= $inactivos['cantidad_inactivos']; ?></span>
                            </a>
                        </li>

                        <!-- Nav Item - Restaurar usuarios -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <?php if ($_SESSION["tipo_usuario"] === "1"): ?>
                                <a class="nav-link dropdown-toggle" id="btn_restaurar_usuarios" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                    <!-- Counter - Messages -->
                                    <span class="badge badge-danger badge-counter"><?= $inactivos['cantidad_inactivos']; ?></span>
                                </a>
                            <?php endif; ?>
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
                            <?php if ($_SESSION["tipo_usuario"] === "1"): ?>
                                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                    <h1>Gestión de Usuarios</h1>
                                    <button
                                        id="btn_registro_usuario"
                                        data-tipos-usuarios="<?php echo htmlspecialchars($tipos_usuarios_json, ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class="fas fa-user-plus fa-sm text-white-50"></i> Agregar usuario
                                    </button>
                                </div>
                            <?php endif; ?>
                            <!-- DataTales Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Tabla de usuarios</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered tabla_dt" id="tbl_usuarios" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>ID usuario</th>
                                                    <th>Nombres</th>
                                                    <th>Apellidos</th>
                                                    <th>Gmail</th>
                                                    <th>Tipo de usuario</th>
                                                    <?php if ($_SESSION["tipo_usuario"] === "1"): ?>
                                                        <th class="text-center">Acciones</th>
                                                    <?php endif; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($filas = $fila->fetch_assoc()): ?>
                                                    <tr>
                                                        <th><?= $filas["id_usuario"]; ?></th>
                                                        <th><?= $filas["nombre_usuario"]; ?></th>
                                                        <th><?= $filas["apellido_usuario"]; ?></th>
                                                        <th><?= $filas["email_usuario"]; ?></th>
                                                        <th><?= $filas["nombre_tipo_usuario"]; ?></th>
                                                        <?php if ($_SESSION["tipo_usuario"] === "1"): ?>
                                                            <td class="text-center">

                                                                <button class="btn btn-sm btn-warning" onclick="editarUsuario('<?= htmlspecialchars($filas['id_usuario'], ENT_QUOTES, 'UTF-8'); ?>',
                                                            '<?= htmlspecialchars($filas['nombre_usuario'], ENT_QUOTES, 'UTF-8'); ?>',
                                                            '<?= htmlspecialchars($filas['apellido_usuario'], ENT_QUOTES, 'UTF-8'); ?>',
                                                            '<?= htmlspecialchars($filas['email_usuario'], ENT_QUOTES, 'UTF-8'); ?>',
                                                            '<?= htmlspecialchars($filas['contrasena_usuario'], ENT_QUOTES, 'UTF-8'); ?>',
                                                            this.dataset.tiposUsuarios)"
                                                                    data-tipos-usuarios='<?= htmlspecialchars($tipos_usuarios_json, ENT_QUOTES, "UTF-8"); ?>'><i class="bi bi-pencil-square"></i></button>
                                                                <button class="btn btn-sm btn-danger" onclick="eliminarUsuario('<?= $filas['id_usuario']; ?>')">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>

                                                            </td>
                                                        <?php endif; ?>
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
                <!-- End of Content Wrapper -->
            </div>
            <!-- End of Main Content -->

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

    <!-- ======================== -->
    <!-- Librerías principales -->
    <!-- ======================== -->

    <!-- jQuery -->
    <script src="assets/libs/jquery/jquery.min.js"></script>

    <!-- jQuery Easing -->
    <script src="assets/libs/jquery-easing/jquery.easing.min.js"></script>

    <!-- Bootstrap -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome -->
    <script src="assets/libs/awesome/js/all.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="assets/libs/sweetAlert/sweetalert2.all.min.js"></script>


    <!-- ======================== -->
    <!-- Scripts generales -->
    <!-- ======================== -->

    <!-- Plantilla SB Admin 2 -->
    <script src="assets/js/sb-admin-2.min.js"></script>

    <!-- ======================== -->
    <!-- Gestión de usuarios -->
    <!-- ======================== -->

    <?php if ($_SESSION["tipo_usuario"] == "1"): ?>
        <!-- Registro de usuario -->
        <script src="assets/public/js/usuarios/registro_usuario.js"></script>

        <!-- Edición de usuario -->
        <script src="assets/public/js/usuarios/editar_usuario.js"></script>

        <!-- Eliminación de usuario -->
        <script src="assets/public/js/usuarios/eliminar_usuario.js"></script>

        <!-- Restaurar usuarios -->
        <script src="assets/public/js/usuarios/restaurar_usuario.js"></script>

        <!--Restaurar uno-->
        <script src="assets/public/js/usuarios/restaurar_un_usuario.js"></script>

        <!-- Papelera usuarios -->
        <script src="assets/public/js/usuarios/vaciar_papelera_usuario.js"></script>
    <?php endif; ?>

    <!-- Actualización de perfil -->
    <script src="assets/public/js/usuarios/actualizar_perfil.js"></script>

    <!--Funcionalidad menú-->
    <script src="assets/funcionalidad/app.js"></script>

    <!--DataTables local-->
    <script src="assets/libs/datatables/datatables.min.js"></script>
    <script src="assets/funcionalidad/tablas.js"></script>
</body>

</html>