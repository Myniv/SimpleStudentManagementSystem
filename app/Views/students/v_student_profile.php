<div class="container mt-5 mb-5">
    <div class="card shadow mx-auto" style="width: 24rem;">
        <div class="card-body text-center">
            <!-- Profile Picture -->
            <img src="{profile_picture}" alt="{name}" class="rounded-circle mb-3"
                style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #ddd;">

            <h4 class="card-title">{name}</h4>
            <h6 class="text-muted">{study_program} - Semester {current_semester}</h6>
            <h6 class="text-muted">Entry Year : {entry_year}</h6>
            <h6 class="text-muted">GPA : {gpa}</h6>
            {!status_cell!}
            <hr>
            <h6>High School Diploma Certificate</h6>
            <div class="mb-3">
                {!high_school_diploma!}
            </div>
            {!button_upload_diploma!}
            <p class="text-success">{success}</p>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadDiplomaModal" tabindex="-1" aria-labelledby="uploadDiplomaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadDiplomaModalLabel">Upload High School Diploma</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="profile/upload-diploma" method="post" enctype="multipart/form-data" id="upload-form">
                <div class="modal-body">

                    <p class="text-danger">{validation_errors}</p>
                    <div id="file-type-error" class="text-danger mt-2" style="display: none">
                        File must be in format PDF, DOC, DOCX
                    </div>
                    <div id="file-size-error" class="text-danger mt-2" style="display: none">
                        Ukuran file tidak boleh melebihi 5MB
                    </div>

                    <div class="mb-3">
                        <label for="diploma_file" class="form-label">Choose File</label>
                        <input type="file" class="form-control" name="high_school_diploma" id="diploma_file" required
                            data-pristine-required-message="File must be uploaded.">
                        <small class="text-muted">Accepted formats: PDF, DOC, DOCX</small>
                    </div>

                    <div id="previewContainer" class="mt-3" style="display: none;">
                        <h6>Preview:</h6>
                        <iframe id="diplomaPreview" style="width: 100%; height: 400px; border: none;"></iframe>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var modalError = "{modal_error}";
        if (modalError === "true") {
            var uploadModal = new bootstrap.Modal(document.getElementById("uploadDiplomaModal"));
            uploadModal.show();
        }

        var form = document.getElementById("upload-form");
        var pristine = new Pristine(form);

        var fileInput = document.getElementById("diploma_file");
        var fileTypeError = document.getElementById("file-type-error");
        var fileSizeError = document.getElementById("file-size-error");

        var maxSize = 5 * 1024 * 1024; // 5MB in bytes
        var allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        var allowedExtensions = ['.pdf', '.doc', '.docx'];

        pristine.addValidator(fileInput, function (value) {
            fileTypeError.style.display = 'none';
            fileSizeError.style.display = 'none';

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

            return true;
        }, "Invalid file type or size.", 5, false);

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

    document.getElementById("diploma_file").addEventListener("change", function (event) {
        var file = event.target.files[0];
        if (file) {
            var fileType = file.type;
            var fileURL = URL.createObjectURL(file);

            if (fileType === "application/pdf" || fileType === "application/msword" || fileType === "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
                document.getElementById("diplomaPreview").src = fileURL;
                document.getElementById("previewContainer").style.display = "block";
            }
        }
    });
</script>