<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="text-center my-4">Enrollment List</h2>

<?php if (in_groups('lecturer') || in_groups('student')): ?>
    <a class="btn btn-primary mb-2" href="/enrollments/create">Add Enrollment</a>
<?php endif; ?>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Student</th>
            <th>Course</th>
            <th>Academic Year</th>
            <th>Semester</th>
            <th>Status</th>
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
                <?php if (in_groups('lecturer')): ?>
                    <td>
                        <a href="/lecturer/enrollments/update/<?= $enrollment->id; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <form action="<?= base_url("/lecturerenrollments/delete/{$enrollment->id}") ?>" method="post"
                            class="d-inline">
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

<?= $this->endSection() ?>