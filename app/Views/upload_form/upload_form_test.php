<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<?php isset($errors) ?: $errors = []; ?>
<?php foreach ($errors as $error): ?>
    <li><?= esc($error) ?></li>
<?php endforeach ?>
<!-- <form method="post" enctype="multipart/form-data" action="upload/upload"> -->

<!-- Using helpers : -->
<?= form_open_multipart('upload', ['id' => 'upload-form', 'class' => 'pristine-validate']) ?>
<div class="form-group">
    <label for="userfile">Pilih Gambar (JPG, JPEG, PNG, GIF - MAX 5MB)</label>
    <input type="file" name="userfile" id="userfile" size="20" required
        data-pristine-required="Silahkan Pilih file untuk diunggah">
    <div id="file-type-error" class="text-danger mt-2" style="display: none">
        File harus berupa gambar (JPG, JPEG, PNG, GIF)
    </div>
    <div id="file-size-error" class="text-danger mt-2" style="display: none">
        Ukuran file tidak boleh melebihi 5MB
    </div>

    <img id="image-preview" class="img-fluid" src="#" alt="Image Preview" style="display: none" />
</div>
<input type="submit" value="upload">
</form>

<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var form = document.getElementById("upload-form");
        var pristine = new Pristine(form);

        var fileInput = document.getElementById("userfile");
        var fileTypeError = document.getElementById("file-type-error");
        var fileSizeError = document.getElementById("file-size-error");
        var imagePreview = document.getElementById("image-preview");

        var maxSize = 5 * 1024 * 1024; // 5MB in bytes
        var allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        var allowedExtensions = ['.jpg', '.jpeg', '.png', '.gif'];

        pristine.addValidator(fileInput, function (value) {
            fileTypeError.style.display = 'none';
            fileSizeError.style.display = 'none';
            imagePreview.style.display = 'none';

            if (fileInput.files.length === 0) {
                return true;
            }

            var file = fileInput.files[0];
            var validType = allowedTypes.includes(file.type);
            if (!validType) {
                var fileName = file.name.toLowerCase();
                validType = allowedExtensions.some(function (ext) {
                    return fileName.endsWith(ext);
                })
            }

            if (!validType) {
                fileTypeError.style.display = 'block';
                return false;
            }

            if (file.size > maxSize) {
                fileSizeError.style.display = 'block';
                return false;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);

            return true;
        }, "validasi file gagal", 5, false);

        form.addEventListener("submit", function (e) {
            var valid = pristine.validate();
            if (!valid) {
                e.preventDefault();
            }
        });

        fileInput.addEventListener('change', function () {
            fileTypeError.style.display = 'none';
            fileSizeError.style.display = 'none';
            pristine.validate(fileInput);
        });

    });
</script>
<?= $this->endSection(); ?>