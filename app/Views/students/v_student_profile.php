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

            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                data-bs-target="#uploadDiplomaModal">
                Upload Diploma
            </button>
        </div>
    </div>
</div>

<!-- Upload Diploma Modal -->
<div class="modal fade" id="uploadDiplomaModal" tabindex="-1" aria-labelledby="uploadDiplomaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadDiplomaModalLabel">Upload High School Diploma</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="profile/upload-diploma" method="post" enctype="multipart/form-data">
                <div class="modal-body">

                    <p class="text-danger">{validation_errors}</p>
                    <p class="text-success">{success}</p>

                    <div class="mb-3">
                        <label for="diploma_file" class="form-label">Choose File</label>
                        <input type="file" class="form-control" name="high_school_diploma" id="diploma_file" required>
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
    });

    document.getElementById("diploma_file").addEventListener("change", function (event) {
        var file = event.target.files[0];
        if (file) {
            var fileType = file.type;
            var fileURL = URL.createObjectURL(file);

            // Only allow preview for PDFs
            if (fileType === "application/pdf") {
                document.getElementById("diplomaPreview").src = fileURL;
                document.getElementById("previewContainer").style.display = "block";
            } else {
                alert("Preview is only available for PDF files. The file will still be uploaded.");
                document.getElementById("previewContainer").style.display = "none";
            }
        }
    });
</script>