$(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  const apiUrl = `/${tenant}/admin/products`;
  const productForm = $('#productForm');

  const productId = $('#product_id').val();
  const isEdit = productId !== '';

  const fv = FormValidation.formValidation(productForm[0], {
    fields: {
      name: {
        validators: {
          notEmpty: {
            message: '請輸入商品名稱'
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
              return '.col-12';
          }
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  });

  $('#saveButton').on('click', function (e) {
    e.preventDefault();

    fv.validate().then(function (status) {
      if (status === 'Valid') {
        const formData = new FormData(productForm[0]);
        const formObject = Object.fromEntries(formData.entries());

        let categories = [];
        $('#categories option:selected').each(function () {
          categories.push($(this).val());
        });

        formObject.categories = categories;

        showLoading('儲存中...');

        const url = isEdit ? `${apiUrl}/${productId}` : apiUrl;
        const method = isEdit ? 'PUT' : 'POST';

        axios({
          method: method,
          url: url,
          data: formObject
        })
          .then(function (response) {
            hideLoading();

            if (response.data.success) {
              Swal.fire({
                icon: 'success',
                title: '儲存成功！',
                text: '正在跳轉到商品管理頁面...',
                timer: 1500,
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false
              }).then(function () {
                window.location.href = response.data.data.redirect_url;
              });
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
            hideLoading();

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

  $('#deleteBtn').on('click', function (e) {
    e.preventDefault();

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
          .delete(`${apiUrl}/${productId}`)
          .then(function (response) {
            hideLoading();

            if (response.data.success) {
              Swal.fire({
                icon: 'success',
                title: '刪除成功',
                text: '正在跳轉到商品管理頁面...',
                timer: 1500,
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false
              }).then(function () {
                window.location.href = response.data.data.redirect_url;
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
            hideLoading();

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
