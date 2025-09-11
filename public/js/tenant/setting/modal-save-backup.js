$(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  const apiUrl = `/${tenant}/admin/users`;

  let isEdit;
  $('#addUserBtn').on('click', function () {
    isEdit = false;

    $('#userId').val('');

    fv.resetForm(true);

    $('#userModal').find('.card-title').text('新增員工');
    $('#userModal').modal('show');
  });

  $(document).on('click', '.edit-record', function () {
    const userId = $(this).data('id');

    axios
      .get(`${apiUrl}/${userId}`)
      .then(function (response) {
        isEdit = true;

        fv.resetForm(true);
        fv.removeField('password');
        fv.addField('password', {
          validators: {
            stringLength: {
              min: 5,
              max: 20,
              message: '員工密碼至少需要5個字元'
            }
          }
        });

        // Modal
        $('#userModal').find('.card-title').text('編輯員工');
        $('#userModal').modal('show');

        // 填入資料
        $('#userId').val(response.data.data.user.id);
        $('#name').val(response.data.data.user.name);
        $('#email').val(response.data.data.user.email);
      })
      .catch(function (error) {
        Swal.fire({
          icon: 'error',
          title: '載入資料失敗',
          text: error.response.data.message,
          confirmButtonText: '確定'
        });
      });
  });

  const userForm = $('#userForm');
  const fv = FormValidation.formValidation(userForm[0], {
    fields: {
      name: {
        validators: {
          notEmpty: {
            message: '請輸入員工姓名'
          }
        }
      },
      email: {
        validators: {
          notEmpty: {
            message: '請輸入員工信箱'
          },
          emailAddress: {
            message: '請輸入有效的信箱'
          }
        }
      },
      password: {
        validators: {
          notEmpty: {
            message: '請輸入員工密碼'
          },
          stringLength: {
            min: 5,
            message: '員工密碼至少需要5個字元'
          }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        eleValidClass: '',
        rowSelector: function (field, ele) {
          switch (field) {
            default:
              return '.col-md-12';
          }
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  });

  // 儲存員工
  $('#saveBtn').on('click', function (e) {
    e.preventDefault();

    fv.validate().then(function (status) {
      if (status === 'Valid') {
        const formData = new FormData(userForm[0]);
        const formObject = Object.fromEntries(formData.entries());

        Swal.fire({
          title: '儲存中...',
          showConfirmButton: false,
          allowOutsideClick: false,
          willOpen: () => {
            Swal.showLoading();
          }
        });

        const url = isEdit ? `${apiUrl}/${formObject.userId}` : apiUrl;
        const method = isEdit ? 'PUT' : 'POST';

        if (isEdit && !formObject.password) {
          delete formObject.password;
        }

        axios({
          method: method,
          url: url,
          data: formObject
        })
          .then(function (response) {
            Swal.close();

            if (response.data.success) {
              table.ajax.reload();

              Swal.fire({
                icon: 'success',
                title: '儲存成功',
                text: response.data.message,
                customClass: {
                  confirmButton: 'btn btn-success'
                }
              });

              $('#userModal').modal('hide');
            } else {
              Swal.fire({
                icon: 'error',
                title: '儲存失敗',
                text: response.data.message,
                confirmButtonText: '確定'
              });
            }
          })
          .catch(function (error) {
            Swal.close();

            Swal.fire({
              icon: 'error',
              title: '儲存失敗',
              text: error.response.data.message,
              confirmButtonText: '確定'
            });
          });
      }
    });
  });

  // 刪除員工
  $(document).on('click', '.delete-record', function () {
    const id = $(this).data('id');

    Swal.fire({
      title: '確定要刪除嗎',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: '確定刪除',
      cancelButtonText: '取消'
    }).then(result => {
      if (result.isConfirmed) {
        Swal.fire({
          title: '刪除中...',
          showConfirmButton: false,
          allowOutsideClick: false,
          willOpen: () => {
            Swal.showLoading();
          }
        });

        axios
          .delete(`${apiUrl}/${id}`)
          .then(function (response) {
            Swal.close();

            if (response.data.success) {
              table.ajax.reload();

              Swal.fire({
                icon: 'success',
                title: '刪除成功',
                text: response.data.message,
                customClass: {
                  confirmButton: 'btn btn-success'
                }
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: '刪除失敗',
                text: response.data.message,
                confirmButtonText: '確定'
              });
            }
          })
          .catch(function (error) {
            Swal.close();

            Swal.fire({
              icon: 'error',
              title: '刪除失敗',
              text: error.response.data.message,
              confirmButtonText: '確定'
            });
          });
      }
    });
  });
});
