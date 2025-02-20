<div class="container mt-5 mb-5" >
    <div class="card shadow mx-auto" style="width: 24rem;">
        <div class="card-body text-center">
            <!-- Profile Picture -->
            <img src="{profile_picture}" alt="{name}" class="rounded-circle mb-3"
                style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #ddd;">

            <h4 class="card-title">{name}</h4>
            <h6 class="text-muted">{program} - Semester {semester}</h6>
            {!status_cell!}
            <hr>
            <h5>Grades</h5>
            <ul class="list-unstyled">
                {!grade_cell!}
            </ul>
        </div>
    </div>
</div>