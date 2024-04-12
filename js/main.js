let edit = false;
function fetchUsers() {
  $.ajax({
    url: "list.php",
    type: "get",
    dataType: "json",
    success: (res) => {
      if (res.ok) {
        let users = res.data;
        let template = "";
        users.forEach((user) => {
          template += `
                  <tr class="text-center">
                      <td>${user.nit}</td>
                      <td>${user.full_name}</td>
                      <td>${user.birth_date}</td>
                      <td>${user.phone}</td>
                      <td>${user.email}</td>
                      <td style="text-align:center;">
                          <button class="btn btn-primary btn-sm rounded-4 user-edit" data-id="${user.id}">
                              <span class="material-symbols-outlined">
                                  edit
                              </span>
                          </button>
                          <button class="btn btn-danger btn-sm rounded-4 user-delete" data-id="${user.id}">
                              <span class="material-symbols-outlined">
                                  delete
                              </span>
                          </button>
                      </td>
                  </tr>
              `;
        });
        $("#users").html(template);
      }
    },
  });
}

function cancelEdit() {
  let template = `<input type="submit" name="save" value="Guardar" class="btn btn-success w-100">`;
  $(document).find("form#form_add")[0].reset();
  $("#actions").empty().append(template);
  formValido = {
    nit: false,
    first_name: false,
    first_last_name: false,
    middle_last_name: false,
    birth_date: false,
    phone: false,
    email: false,
  };
}
function getDifferentValues(obj, targetValue) {
  return Object.entries(obj)
    .filter(([key, value]) => value !== targetValue)
    .reduce((acc, [key, value]) => {
      acc[key] = value;
      return acc;
    }, {});
}

function areAllValuesEqualTo(obj, targetValue) {
  return Object.values(obj).every((value) => value === targetValue);
}

function prepare_all() {
  if (!location.href.includes("login")) {
    fetchUsers();
  }

  $("#form_add").on("submit", (evt) => {
    evt.preventDefault();
    let endpoint = edit === true ? "edit.php" : $(evt.target).attr("action");
    let hiddenInput =
      edit === true ? ["edit", "Actualizar"] : ["save", "Guardar"];
    const formData = new FormData(evt.target);
    formData.append(hiddenInput[0], hiddenInput[1]);
    const formProps = Object.fromEntries(formData);
    if (areAllValuesEqualTo(formValido, true)) {
      let names = Object.keys(formValido);
      names.forEach((name) => {
        let input = document.querySelector(`input[name="${name}"]`);
        input.style.border = "";
      });
      $.ajax({
        url: endpoint,
        type: "post",
        data: formProps,
        dataType: "json",
        success: (res) => {
          if (res.ok) {
            if (endpoint.includes("edit")) {
              edit = false;
              cancelEdit();
            }
            fetchUsers();
            $(evt.target)[0].reset();
          }
          let template = `<div class="alert alert-${res.tipo} alert-dismissible fade show" role="alert">
            ${res.mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
          $(".card.card-body").find(".alert").remove();
          $(".card.card-body").prepend(template);
        },
      });
    } else {
      if (!areAllValuesEqualTo(formValido, false)) {
        let differents = Object.keys(getDifferentValues(formValido, true));
        differents.forEach((diff) => {
          let input = document.querySelector(`input[name="${diff}"]`);
          input.style.border = "1px solid red";
        });
        let template = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Tiene un error en el/los campo(s) marcado(s) en rojo
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
        $(".card.card-body").find(".alert").remove();
        $(".card.card-body").prepend(template);
      }
    }
  });

  $("#form_login").on("submit", (evt) => {
    evt.preventDefault();
    let errorMsg = "";
    if (areAllValuesEqualTo(formLoginValido, true)) {
      let template = `<span style="font-size: 14px;padding-left: 5px;color: green;">Sus credenciales son correctas</span>`;
      $("#errors_login").empty();
      $("#errors_login").prev().removeClass("mb-3");
      $("#errors_login").css("display", "").prepend(template);
      //document.getElementById("form_login").submit();
    } else {
      if (!areAllValuesEqualTo(formLoginValido, false)) {
        let differents = Object.keys(getDifferentValues(formLoginValido, true));
        if (differents.length === 1 && differents[0].includes("user")) {
          errorMsg = "El usuario no es valido";
        }
        if (differents.length === 1 && differents[0].includes("pass")) {
          errorMsg = "La contraseña no es valida";
        }
      } else {
        errorMsg = "El usuario y contraseña no es valido";
      }
      let template = `<span style="font-size: 14px;padding-left: 5px;color: red;">${errorMsg}</span>`;
      $("#errors_login").empty();
      $("#errors_login").prev().removeClass("mb-3");
      $("#errors_login").css("display", "").prepend(template);
    }
  });

  $(document).on("click", ".user-delete", function () {
    if (confirm("El proceso de eliminar no se puede revertir.")) {
      let id = $(this).data("id");
      $.ajax({
        url: "delete.php",
        type: "post",
        data: { id },
        dataType: "json",
        success: (res) => {
          if (res.ok) {
            fetchUsers();
          }
          let template = `<div class="alert alert-${res.tipo} alert-dismissible fade show" role="alert">
            ${res.mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
          $(".card.card-body").find(".alert").remove();
          $(".card.card-body").prepend(template);
        },
      });
    }
  });

  $(document).on("click", ".user-edit", function () {
    let id = $(this).data("id");
    $.ajax({
      url: "single.php",
      type: "post",
      data: { id },
      dataType: "json",
      success: (res) => {
        if (res.ok) {
          const user = res.data[0];
          $('input[name="nit"]').val(user.nit);
          $('input[name="first_name"]').val(user.first_name);
          $('input[name="middle_name"]').val(user.middle_name);
          $('input[name="first_last_name"]').val(user.first_last_name);
          $('input[name="middle_last_name"]').val(user.middle_last_name);
          $('input[name="birth_date"]').val(user.birth_date);
          $('input[name="phone"]').val(user.phone);
          $('input[name="email"]').val(user.email);
          let template = `
                <input type="hidden" name="id" value="${user.id}">
                <div class="row mx-auto w-100">
                    <div class="col-md-8"><input type="submit" name="edit" value="Actualizar" class="btn btn-success w-100"></div>
                    <div class="col-md-4"><button class="btn btn-danger btn-cancel w-100">Cancelar</button></div>
                </div>
            `;
          $("#actions").empty().append(template);
          edit = true;
          formValido = {
            nit: true,
            first_name: true,
            first_last_name: true,
            middle_last_name: true,
            birth_date: true,
            phone: true,
            email: true,
          };
        }
      },
    });
  });

  $(document).on("click", ".btn-cancel", function (evt) {
    evt.preventDefault();
    edit = false;
    cancelEdit();
  });
}

$(document).ready(prepare_all);
