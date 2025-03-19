<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="text-center my-4">Student Grades</h2>

<a class="btn btn-primary mb-1" href="/lecturer/student-grades/create">Add Grades</a>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Student</th>
            <th>Course</th>
            <th>Grade</th>
            <th>Status</th>
            <th>Completed_at</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($student_grades as $student_grade): ?>
            <tr>
                <td><?= $student_grade->id ?></td>
                <td><?= $student_grade->student_name ?></td>
                <td><?= $student_grade->course_name ?></td>
                <td><?= $student_grade->grade_letter && $student_grade->grade_value ? $student_grade->grade_value . ' / ' . $student_grade->grade_letter : 'N/A' ?>
                </td>
                <td><?= $student_grade->status ?? 'N/A' ?></td>
                <td><?= !empty($student_grade->completed_at) ? $student_grade->completed_at->humanize() : 'N/A' ?>
                </td>
                <td>
                    <a href="/lecturer/student-grades/update/<?= $student_grade->id; ?>"
                        class="btn btn-primary btn-sm">Edit</a>
                    <form action="<?= base_url("/lecturer/student-grades/delete/{$student_grade->id}") ?>" method="post"
                        class="d-inline">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure want to delete this student?');">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>