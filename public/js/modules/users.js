$(document).ready(function () {
  Dropzone.autoDiscover = false;

  if ($("#usersTable").length) {
    $("#usersTable").DataTable({
      processing: true,
      ajax: {
        url: usersDataUrl,
        dataSrc: "",
      },
      columns: [
        {
          data: null,
          render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          },
        },
        {
          data: null,
          render: function (data) {
            return (data.first_name ?? "") + " " + (data.last_name ?? "");
          },
        },
        { data: "email" },
        {
          data: "role",
          render: function (data) {
            return data ? data.name : "-";
          },
        },
        {
          data: "status",
          render: function (data) {
            if (data === 1 || data === "1") {
              return '<span class="badge bg-success">Active</span>';
            } else {
              return '<span class="badge bg-danger">Inactive</span>';
            }
          },
        },
        {
          data: "id",
          render: function (data) {
            return `<a href="${userEditUrl}/${data}/edit" class="btn btn-success btn-sm">Edit</a>`;
          },
        },
      ],
    });
  }

  let userId = null;
  if (typeof existingUser !== "undefined" && existingUser !== null) {
    // Fill Step 1 fields
    $("#first_name").val(existingUser.first_name);
    $("#last_name").val(existingUser.last_name);
    $("#email").val(existingUser.email);

    userId = existingUser.id; // VERY IMPORTANT
    // Optional dates
    $("#start_date").val(existingUser.start_date);
    $("#end_date").val(existingUser.finish_date);

    // Disable email (recommended)
    $("#email").prop("readonly", true);
  }

  let certifications = [];
  let contractFile = "";

  /* =========================
   SELECT2
========================= */

  function initSelect2() {
    $("#roleSelect").select2({
      placeholder: "Select Access Level",
      width: "100%",
    });
  }

  $(".step-btn").click(function () {
    let step = $(this).data("step");

    $(".step").addClass("d-none");
    $("#step" + step).removeClass("d-none");
  });

  $("#first_step").on("click", function () {
    nextStep(1);
  });

  $("#second_step").on("click", function () {
    nextStep(2);
  });

  $("#third_step").on("click", function () {
    nextStep(3);
  });

  $("#last").on("click", function () {
    nextStep(4);
  });

  $("#second_prev").on("click", function () {
    prevStep(2);
  });

  $("#third_prev").on("click", function () {
    prevStep(3);
  });

  $("#last_prev").on("click", function () {
    prevStep(4);
  });
  /* =========================
   STEP NAVIGATION
========================= */
  function nextStep(step) {
    if (!validateStep(step)) {
      return;
    }
    let formData = new FormData();
    formData.append("step", step);

    if (step == 1) {
      formData.append("first_name", $("[name=first_name]").val());
      formData.append("last_name", $("[name=last_name]").val());
      formData.append("email", $("[name=email]").val());
      //formData.append('password', $('[name=password]').val());

      if (userId) {
        formData.append("user_id", userId);
      }
    }

    if (step == 2) {
      formData.append("user_id", userId);
      formData.append("certifications", JSON.stringify(certifications));
    }

    if (step == 3) {
      formData.append("user_id", userId);
      formData.append("contract_file", contractFile);
      setTimeout(() => {
        initSelect2();
      }, 200);
    }

    if (step == 4) {
      formData.append("user_id", userId);
      formData.append("role_id", $("#roleSelect").val());
    }

    $.ajax({
      url: userStepUrl,
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      success: function (res) {
        if (!res.success) {
          //showToast(res.message, 'error'); // you can replace with toaster
          return;
        }

        if (step == 1) {
          userId = res.user_id;
          if (userId) {
            $("[name=email]").prop("readonly", true);
          }
        }

        if (step < 4) {
          $(".step").addClass("d-none");
          $("#step" + (step + 1)).removeClass("d-none");
        } else {
          window.location.href = res.redirect;
        }
      },
    });
  }

  function prevStep(step) {
    $(".step").addClass("d-none");
    $("#step" + (step - 1)).removeClass("d-none");
  }

  /* =========================
   CERTIFICATION
========================= */

  $(document).on("click", "#addCertBtn", function () {
    $("#certModal").modal("show");
  });


$(document).on('click', '#save_cart', function () {
    saveCert();
  });

function saveCert() {

    let title = $('#certTitle option:selected').text();
    let titleId = $('#certTitle').val();
    let expiry = $('#certExpiry').val();

    $.ajax({
        url: userStepUrl,
        method: "POST",
        data: {
            step: 2,
            user_id: userId,
            certifications: [
                {
                title_id: titleId,
                expiry_date: expiry,
                file: certFile || ''
            }
            ],

            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {

            if (res.success) {

                res.data.forEach(function (cert) {

                    let rowCount = $('#certTable tbody tr').length + 1;

                    $('#certTable tbody').append(`
                        <tr id="cert-${cert.id}">
                            <td>${rowCount}</td>
                            <td>${cert.title}</td>
                            <td>${cert.expiry_date}</td>
                            <td>
                                <i class="fa fa-pen me-2 text-primary" onclick="editCert(${cert.id})"></i>
                                <i class="fa fa-eye me-2 text-success" onclick="viewCert('${cert.file}')"></i>
                                <i class="fa fa-plus me-2 text-dark"></i>
                                <i class="fa fa-trash text-danger" onclick="deleteCert(${cert.id})"></i>
                            </td>
                        </tr>
                    `);

                });

                $('#certModal').modal('hide');
                showToast('Certificates added successfully');

            } else {
                showToast(res.message, 'error');
            }
        }
    });
}

  let certFile = "";


  function validateStep(step) {
    let isValid = true;

    // Clear old errors
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    function showError(input, message) {
      input.addClass("is-invalid");
      input.after(`<div class="invalid-feedback">${message}</div>`);
      isValid = false;
    }

    // STEP 1
    if (step == 1) {
      let first = $("[name=first_name]");
      let last = $("[name=last_name]");
      let email = $("[name=email]");
      //let pass = $('[name=password]');

      if (first.val().trim() == "") showError(first, "First name required");
      if (last.val().trim() == "") showError(last, "Last name required");

      if (email.val().trim() == "") {
        showError(email, "Email required");
      } else if (!/^\S+@\S+\.\S+$/.test(email.val())) {
        showError(email, "Invalid email");
      }

      // if (!userId) { // only on create
      //     if (pass.val().length < 6) {
      //         showError(pass, 'Password must be 6+ characters');
      //     }
      // }
    }

    // STEP 2 (optional)
    if (step == 2) {
      // optional → skip or enforce
    }

    // STEP 3
    if (step == 3) {
      if (!contractFile) {
        alert("Please upload contract file");
        isValid = false;
      }
    }

    // STEP 4
    if (step == 4) {
      if (!$("#roleSelect").val()) {
        alert("Select role");
        isValid = false;
      }
    }

    return isValid;
  }
});

$(document).on("change", "#certTitle", function () {
  if ($(this).val() === "other") {
    $("#otherTitleDiv").removeClass("d-none");
  } else {
    $("#otherTitleDiv").addClass("d-none");
    $("#otherTitleInput").val("");
  }
});

document.addEventListener("DOMContentLoaded", function () {
  flatpickr(".datepicker", {
    dateFormat: "d/m/Y",
    allowInput: true,
  });
});
