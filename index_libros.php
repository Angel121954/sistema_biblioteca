<?php
session_start();
require_once "assets/modelos/MySQL.php";

$sql = new MySQL();
$sql->conectar();

$fila = $sql->efectuarConsulta("SELECT l.id_libro, l.titulo_libro, l.autor_libro,
                    l.isbn_libro, l.disponibilidad_libro, l.cantidad_libro,
                    cl.id_categoria, cl.nombre_categoria
                    FROM libros l INNER JOIN categorias cl ON cl.id_categoria = l.fk_categoria
                    WHERE l.estado_libro != 'Inactivo'");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];
$usuario_result = $sql->efectuarConsulta("SELECT * FROM usuarios WHERE id_usuario = $id_usuario");
$usuario = $usuario_result->fetch_assoc();

$inactivos_result = $sql->efectuarConsulta("SELECT COUNT(*) AS cantidad_inactivos
                                            FROM libros WHERE estado_libro = 'Inactivo'");
$inactivos = $inactivos_result->fetch_assoc();

$categorias_result = $sql->efectuarConsulta("SELECT id_categoria, nombre_categoria
                                            FROM categorias");
$categorias = [];
while ($valor = $categorias_result->fetch_assoc()) {
    $categorias[] = $valor;
}
$categorias_json = json_encode($categorias, JSON_UNESCAPED_UNICODE);

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

    <!--FontAwesome CDN-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" type="text/css">

    <!--SweetAlert CDN-->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.min.css" rel="stylesheet" type="text/css">

    <!--Bootstrap CDN-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!--DataTable CDN-->
    <link href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

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

            <!-- Enlace: Categorias -->
            <li class="nav-item">
                <a class="nav-link" href="index_categorias.php">
                    <i class="bi bi-collection"></i>
                    <span>Categorias</span>
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
                            <a class="collapse-item" href="assets/controladores/informes/historial_usuario.php">Historial de usuarios</a>
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
                            <a class="collapse-item" href="assets/controladores/informes_excel/historial_usuario.php">Historial de usuarios</a>
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
                    data-id="<?= $usuario['id_usuario']; ?>"
                    data-nombre="<?= $usuario['nombre_usuario']; ?>"
                    data-apellido="<?= $usuario['apellido_usuario']; ?>"
                    data-email="<?= $usuario['email_usuario']; ?>"
                    onclick="actualizarPerfil(this)">
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

                    <?php if ($_SESSION["tipo_usuario"] !== "1"): ?>
                        <!-- filtrar libro por titulo, categoría, autor o ISBN -->
                        <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group">
                                <input type="text" id="filtrar_libro" class="form-control bg-light border-0 small" placeholder="Buscar... (titulo, autor, categoría, ISBN)"
                                    aria-label="Buscar" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button id="btn_filtrar_libros" class="btn btn-primary" type="button">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <?php if ($_SESSION["tipo_usuario"] === "1"): ?>
                            <!--Restaurar un libro-->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="btn_restaurar_un_libro" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-journal-arrow-up"></i>
                                    <span class="badge badge-danger badge-counter"><?= $inactivos['cantidad_inactivos']; ?></span>
                                </a>
                            </li>

                            <!-- Nav Item - Restaurar libros -->
                            <li class="nav-item dropdown no-arrow mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="btn_restaurar_libros" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                    <!-- Counter - Messages -->
                                    <span class="badge badge-danger badge-counter"><?= $inactivos['cantidad_inactivos']; ?></span>
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
                        <?php endif; ?>

                        <!--Cerrar sesión-->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="btn_cerrar_session" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="text-danger bi bi-houses"></i>
                            </a>
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
                            <!-- Botones superiores -->

                            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                <h1>Gestión de Libros</h1>
                                <?php if ($_SESSION["tipo_usuario"] === "1"): ?>
                                    <button id="btn_registro_libro"
                                        data-categorias="<?php echo htmlspecialchars($categorias_json, ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class="fas fa-plus fa-sm text-white-50"></i> Agregar libro
                                    </button>
                                <?php endif; ?>
                            </div>

                            <!-- Tabla de libros -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Tabla de libros</h6>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered tabla_dt" id="tbl_libros" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>ID libro</th>
                                                    <th>Título</th>
                                                    <th>Autor</th>
                                                    <th>Categoría</th>
                                                    <th>Disponibilidad</th>
                                                    <th>Cantidad</th>
                                                    <th>ISBN</th>
                                                    <?php if ($_SESSION["tipo_usuario"] === "1"): ?>
                                                        <th class="text-center">Acciones</th>
                                                    <?php endif; ?>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php while ($filas = $fila->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo $filas["id_libro"]; ?></td>
                                                        <td><?php echo $filas["titulo_libro"]; ?></td>
                                                        <td><?php echo $filas["autor_libro"]; ?></td>
                                                        <td><?php echo $filas["nombre_categoria"]; ?></td>
                                                        <td><?php echo $filas["disponibilidad_libro"]; ?></td>
                                                        <td><?php echo $filas["cantidad_libro"]; ?></td>
                                                        <td><?php echo $filas["isbn_libro"]; ?></td>
                                                        <?php if ($_SESSION["tipo_usuario"] === "1"): ?>
                                                            <td class="text-center">
                                                                <button
                                                                    class="btn btn-sm btn-warning"
                                                                    data-id="<?= $filas['id_libro']; ?>"
                                                                    data-titulo="<?= $filas['titulo_libro']; ?>"
                                                                    data-autor="<?= $filas['autor_libro']; ?>"
                                                                    data-categoria="<?php echo htmlspecialchars($categorias_json, ENT_QUOTES, 'UTF-8'); ?>"
                                                                    data-cantidad="<?= $filas['cantidad_libro']; ?>"
                                                                    onclick="editarLibro(this)">
                                                                    <i class="bi bi-pencil-square"></i>
                                                                </button>

                                                                <button
                                                                    class="btn btn-sm btn-danger"
                                                                    data-id="<?= $filas['id_libro']; ?>"
                                                                    data-nombre="<?= $filas['titulo_libro']; ?>"
                                                                    onclick="eliminarLibro(this)">
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
                        <!-- End Page Content -->

                        <!-- /.container-fluid -->

                    </div>
                    <!-- End of Main Content -->

                </div>
                <!-- End of Content Wrapper -->
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

        <!-- ============================ -->
        <!-- 🔹 Librerías base y dependencias -->
        <!-- ============================ -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- ============================ -->
        <!-- 🔹 Librerías externas -->
        <!-- ============================ -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.all.min.js"></script>

        <!-- ============================ -->
        <!-- 🔹 Scripts personalizados - Libros -->
        <!-- ============================ -->
        <?php if ($_SESSION["tipo_usuario"] !== "1"): ?>
            <script src="assets/public/js/libros/filtrar_libro.js"></script>
        <?php endif; ?>
        <?php if ($_SESSION["tipo_usuario"] === "1"): ?>
            <script src="assets/public/js/libros/registro_libro.js"></script>
            <script src="assets/public/js/libros/editar_libro.js"></script>
            <script src="assets/public/js/libros/eliminar_libro.js"></script>
            <script src="assets/public/js/libros/restaurar_libro.js"></script>
            <script src="assets/public/js/libros/restaurar_un_libro.js"></script>
        <?php endif; ?>

        <!-- ============================ -->
        <!-- 🔹 Script personalizado - Usuarios -->
        <!-- ============================ -->
        <script src="assets/public/js/usuarios/actualizar_perfil.js"></script>

        <!-- Cerrar sesión -->
        <script src="assets/public/js/usuarios/cerrar_sesion_usuario.js"></script>

        <!-- Funcionalidad menú -->
        <script src="assets/funcionalidad/app.js"></script>

        <!-- ============================ -->
        <!-- 🔹 DataTables CDN -->
        <!-- ============================ -->
        <script src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.min.js"></script>
        <script src="assets/funcionalidad/tablas.js"></script>
</body>

</html>