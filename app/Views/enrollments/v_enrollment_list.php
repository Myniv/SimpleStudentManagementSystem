<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="text-center my-4">Enrollment List</h2>

<?php if (in_groups('lecturer') || in_groups('student')): ?>
    <a class="btn btn-primary mb-2" href="/enrollments/create">Add Enrollment</a>
<?php endif; ?>

<form action="<?= $baseUrl ?>" method="get" class="form-inline mb-3">
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="input-group mr-2">
                <input type="text" class="form-control" name="search" value="<?= $params->search ?>"
                    placeholder="Search...">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </div>

        <?php if (in_groups('lecturer')): ?>
            <div class="col-md-2">
                <div class="input-group ml-2">
                    <select name="student_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Students</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?= $student->student_id ?>" <?= ($params->student_id == $student->student_id) ? 'selected' : '' ?>>
                                <?= ucfirst($student->student_name) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
        <?php endif; ?>

        <div class="col-md-2">
            <div class="input-group ml-2">
                <select name="course_id" class="form-select" onchange="this.form.submit()">
                    <option value="">All Course</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= $course->course_id ?>" <?= ($params->course_id == $course->course_id) ? 'selected' : '' ?>>
                            <?= ucfirst($course->course_name) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="input-group ml-2">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <?php foreach ($statuss as $status): ?>
                        <option value="<?= $status->status ?>" <?= ($params->status == $status->status) ? 'selected' : '' ?>>
                            <?= ucfirst($status->status) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>


        <div class="col-md-2">
            <div class="input-group ml-2">
                <select name="perPage" class="form-select" onchange="this.form.submit()">
                    <option value="2" <?= ($params->perPage == 2) ? 'selected' : '' ?>>
                        2 per Page
                    </option>
                    <option value="5" <?= ($params->perPage == 5) ? 'selected' : '' ?>>
                        5 per Page
                    </option>
                    <option value="10" <?= ($params->perPage == 10) ? 'selected' : '' ?>>
                        10 per Page
                    </option>
                    <option value="25" <?= ($params->perPage == 25) ? 'selected' : '' ?>>
                        25 per Page
                    </option>
                </select>
            </div>
        </div>

        <div class="col-md-1">
            <a href="<?= $params->getResetUrl($baseUrl) ?>" class="btn btn-secondary ml-2">
                Reset
            </a>
        </div>

        <input type="hidden" name="sort" value="<?= $params->sort; ?>">
        <input type="hidden" name="order" value="<?= $params->order; ?>">

    </div>
</form>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>
                <a class="text-white text-decoration-none" href="<?= $params->getSortUrl('id', $baseUrl) ?>">
                    ID <?= $params->isSortedBy('id') ? ($params->getSortDirection() == 'asc' ? '↑' : '↓') : '↕' ?>
                </a>
            </th>
            <th>
                <a class="text-white text-decoration-none" href="<?= $params->getSortUrl('students.name', $baseUrl) ?>">
                    Student
                    <?= $params->isSortedBy('students.name') ? ($params->getSortDirection() == 'asc' ? '↑' : '↓') : '↕' ?>
                </a>
            </th>
            <th>
                <a class="text-white text-decoration-none" href="<?= $params->getSortUrl('courses.name', $baseUrl) ?>">
                    Course
                    <?= $params->isSortedBy('courses.name') ? ($params->getSortDirection() == 'asc' ? '↑' : '↓') : '↕' ?>
                </a>
            </th>
            <th>
                <a class="text-white text-decoration-none"
                    href="<?= $params->getSortUrl('enrollments.academic_year', $baseUrl) ?>">
                    Academic Year
                    <?= $params->isSortedBy('enrollments.academic_year') ? ($params->getSortDirection() == 'asc' ? '↑' : '↓') : '↕' ?>
                </a>
            </th>
            <th>
                <a class="text-white text-decoration-none"
                    href="<?= $params->getSortUrl('enrollments.semester', $baseUrl) ?>">
                    Semester
                    <?= $params->isSortedBy('enrollments.semester') ? ($params->getSortDirection() == 'asc' ? '↑' : '↓') : '↕' ?>
                </a>
            </th>
            <th>
                <a class="text-white text-decoration-none"
                    href="<?= $params->getSortUrl('enrollments.status', $baseUrl) ?>">
                    Status
                    <?= $params->isSortedBy('enrollments.status') ? ($params->getSortDirection() == 'asc' ? '↑' : '↓') : '↕' ?>
                </a>
            </th>
            <th>
                <a class="text-white text-decoration-none"
                    href="<?= $params->getSortUrl('student_grades.grade_letter', $baseUrl) ?>">
                    Grades
                    <?= $params->isSortedBy('student_grades.grade_letter') ? ($params->getSortDirection() == 'asc' ? '↑' : '↓') : '↕' ?>
                </a>
            </th>
            <?php if (in_groups('lecturer')): ?>
                <th>Action</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($enrollments as $enrollment): ?>
            <tr>
                <td><?= $enrollment->id ?></td>
                <td><?= $enrollment->student_name ?></td>
                <td><?= $enrollment->course_name ?></td>
                <td><?= $enrollment->academic_year ?></td>
                <td><?= $enrollment->semester ?></td>
                <td><?= $enrollment->status ?></td>
                <td><?= $enrollment->grade_letter ?? 'N/A' ?></td>
                <?php if (in_groups('lecturer')): ?>
                    <td>
                        <a href="/enrollments/update/<?= $enrollment->id; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <form action="<?= base_url("/enrollments/delete/{$enrollment->id}") ?>" method="post" class="d-inline">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure want to delete this student?');">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $pager->links('enrollments', 'custom_pager') ?>
<div class="text-center mt-2">
    <small>Show <?= count($enrollments) ?> of <?= $total ?> total data (Page
        <?= $params->page ?>)</small>
</div>

<?= $this->endSection() ?>