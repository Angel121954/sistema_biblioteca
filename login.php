<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Biblioteca - Iniciar Sesión</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

  <!--Estilo personalizado del login-->
  <link href="assets/css/estilo_login.css" rel="stylesheet" />
</head>

<body>
  <div class="bookshelf">
    <div class="book"></div>
    <div class="book"></div>
    <div class="book"></div>
    <div class="book"></div>
    <div class="book"></div>
    <div class="book"></div>
  </div>

  <div class="login-container">
    <div class="login-left">
      <div class="floating-shapes">
        <i class="bi bi-book shape shape1"></i>
        <i class="bi bi-journal-bookmark-fill shape shape2"></i>
        <i class="bi bi-pencil-fill shape shape3"></i>
      </div>
      <div class="logo-section">
        <div class="logo-icon">
          <i class="bi bi-book-half"></i>
        </div>
        <h1>BIBLIOTECA</h1>
        <p>Sistema de Gestión Bibliotecaria</p>
      </div>
    </div>

    <div class="login-right">
      <div class="welcome-text">
        <h2>¡Bienvenido!</h2>
        <p>Ingresa tus credenciales para acceder al sistema</p>
      </div>

      <form action="assets/controladores/login.php" method="post">
        <div class="form-group">
          <label for="email_usuario">Correo Electrónico</label>
          <div class="input-wrapper">
            <i class="fas fa-envelope input-icon"></i>
            <input
              type="email"
              class="form-control"
              id="email_usuario"
              name="email_usuario"
              placeholder="ejemplo@correo.com"
              required />
          </div>
        </div>

        <div class="form-group">
          <label for="contrasena_usuario">Contraseña</label>
          <div class="input-wrapper">
            <i class="fas fa-lock input-icon"></i>
            <input
              type="password"
              class="form-control"
              id="contrasena_usuario"
              name="contrasena_usuario"
              placeholder="Ingresa tu contraseña"
              required />
          </div>
        </div>

        <button type="submit" class="btn-login">
          <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
        </button>
      </form>
    </div>
  </div>
</body>

</html>