document
  .getElementById("btn_registro_reserva")
  .addEventListener("click", function (e) {
    console.log("boton detectado");
    const btn = e.currentTarget;
    const libros = JSON.parse(btn.dataset.libros); // Recibe los libros del atributo data-libros

    Swal.fire({
      title: "Nueva reserva",
      html: `
        <form id="formReserva">
          <div id="librosContainer">
            <div class="libro-item mb-2">
              <select name="id_libro[]" id="libros" class="form-control mb-2">
                <option disabled selected>Seleccione un libro</option>
              </select>
              <input type="number" name="cantidad[]" class="form-control mb-2" min="1" placeholder="Cantidad">
            </div>
          </div>
          <button type="button" id="btnAgregar" class="swal2-confirm swal2-styled" style="background-color:#3085d6;">+ Agregar libro</button>
        </form>
      `,
      showCancelButton: true,
      confirmButtonText: "Guardar reserva",
      cancelButtonText: "Cancelar",

      didOpen: () => {
        // Llenar el primer select con los libros del dataset
        const selectLibro = document.querySelector("#libros");
        libros.forEach((l) => {
          const option = document.createElement("option");
          option.value = l.id_libro;
          option.textContent = l.titulo_libro;
          selectLibro.appendChild(option);
        });

        // Botón para agregar más selects dinámicos
        document.querySelector("#btnAgregar").addEventListener("click", () => {
          const container = document.querySelector("#librosContainer");
          const nuevoLibro = document.createElement("div");
          nuevoLibro.classList.add("libro-item", "mb-2");

          nuevoLibro.innerHTML = `
            <select name="id_libro[]" class="form-control mb-2">
              <option disabled selected>Seleccione un libro</option>
              ${libros
                .map(
                  (l) =>
                    `<option value="${l.id_libro}">${l.titulo_libro}</option>`
                )
                .join("")}
            </select>
            <input type="number" name="cantidad[]" class="form-control mb-2" min="1" placeholder="Cantidad">
            <button type="button" class="btnEliminar btn btn-danger btn-sm mb-2">Eliminar</button>
          `;

          container.appendChild(nuevoLibro);

          // Evento eliminar
          nuevoLibro
            .querySelector(".btnEliminar")
            .addEventListener("click", () => {
              container.removeChild(nuevoLibro);
            });
        });
      },

      preConfirm: () => {
        console.log("enviando datos al servidor..");
        const formData = new FormData();

        // Puedes dejar id_usuario en 0, PHP lo ignora y usa $_SESSION
        formData.append("id_usuario", 0);

        const selects = document.querySelectorAll(
          ".libro-item select[name='id_libro[]']"
        );
        const cantidades = document.querySelectorAll(
          ".libro-item input[name='cantidad[]']"
        );

        let valid = false;

        selects.forEach((select, i) => {
          const idLibro = select.value;
          const cantidad = cantidades[i].value;
          if (idLibro && cantidad > 0) {
            formData.append("id_libro[]", idLibro);
            formData.append("cantidad[]", cantidad);
            valid = true;
          }
        });

        if (!valid) {
          Swal.showValidationMessage(
            "Debe agregar al menos un libro con cantidad válida"
          );
          return false;
        }

        Swal.fire({
          title: "Registrando reserva...",
          text: "Por favor espere un momento.",
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading(),
        });

        // Enviar al PHP
        return fetch("assets/controladores/reservas/registro_reserva.php", {
          method: "POST",
          body: formData,
        })
          .then((response) => response.text())
          .then((data) => {
            console.log("Respuesta del servidor:", data);
            if (data.trim() !== "ok") {
              throw new Error("Error en el servidor: " + data);
            }
          })
          .catch((error) => {
            console.error("Error en fetch:", error);
            Swal.showValidationMessage("Error al registrar la reserva");
          });
      },
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire(
          "¡Reserva registrada!",
          "Los libros fueron añadidos correctamente.",
          "success"
        ).then(() => {
          location.reload(); // Recarga para actualizar lista de reservas
        });
      }
    });
  });
