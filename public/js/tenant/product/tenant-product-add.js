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

  // 圖片上傳相關變數
  let images = [];

  const previewTemplate = `<div class="dz-preview dz-file-preview">
    <div class="dz-details">
      <div class="dz-thumbnail">
        <img data-dz-thumbnail>
        <span class="dz-nopreview">No preview</span>
        <div class="dz-success-mark"></div>
        <div class="dz-error-mark"></div>
        <div class="dz-error-message"><span data-dz-errormessage></span></div>
        <div class="progress">
          <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
        </div>
      </div>
      <div class="dz-filename" data-dz-name></div>
      <div class="dz-size" data-dz-size></div>
    </div>
  </div>`;

  // 初始化 Dropzone
  const dropzoneImages = document.querySelector('#dropzone-images');
  if (dropzoneImages) {
    const myDropzoneMulti = new Dropzone(dropzoneImages, {
      previewTemplate: previewTemplate,
      parallelUploads: 1,
      maxFilesize: 10,
      addRemoveLinks: true,
      dictRemoveFile: '移除檔案',
      dictCancelUpload: '取消上傳',
      url: '#',
      init: function () {
        // 如果是編輯模式，載入現有圖片
        if (isEdit && window.existingImages) {
          console.log('Loading existing images:', window.existingImages);
          const dropzone = this;
          window.existingImages.forEach(function (imageData) {
            const mockFile = {
              id: imageData.id,
              name: imageData.filename,
              size: imageData.size
            };

            images.push({
              name: imageData.filename,
              size: 0,
              type: 'image/jpeg',
              data: imageData.url,
              isExisting: true,
              id: imageData.id
            });

            dropzone.displayExistingFile(mockFile, imageData.url);
          });
        }

        this.on('addedfile', function (file) {
          // 將檔案轉為 base64
          const reader = new FileReader();
          reader.onload = function (e) {
            const base64Data = e.target.result;
            images.push({
              name: file.name,
              size: file.size,
              type: file.type,
              data: base64Data
            });
          };
          reader.readAsDataURL(file);
        });

        this.on('removedfile', function (file) {
          const removedImage = images.find(f => f.name === file.name);

          // 如果是現有圖片，需要從資料庫刪除
          if (removedImage && removedImage.isExisting && removedImage.id) {
            axios
              .delete(`/${tenant}/admin/products/images/${removedImage.id}`)
              .then(function (response) {
                if (response.data.success) {
                  images = images.filter(f => f.name !== file.name);

                  Swal.fire({
                    icon: 'success',
                    title: '圖片刪除成功',
                    text: response.data.message,
                    confirmButtonText: '確定'
                  });
                } else {
                  images = images.filter(f => f.name !== file.name);

                  Swal.fire({
                    icon: 'error',
                    title: '圖片刪除失敗',
                    text: response.data.message,
                    confirmButtonText: '確定'
                  });
                }
              })
              .catch(function (error) {
                images = images.filter(f => f.name !== file.name);

                Swal.fire({
                  icon: 'error',
                  title: '圖片刪除失敗',
                  text: error.response.data.message,
                  confirmButtonText: '確定'
                });
              });
          } else {
            images = images.filter(f => f.name !== file.name);
          }
        });
      }
    });
  }

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

        const newImages = images.filter(img => !img.isExisting);
        formObject.images = newImages;

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
