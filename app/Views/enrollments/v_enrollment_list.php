<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="text-center my-4">Enrollment List</h2>

<a href="/enrollments/create">Add Enrollment</a>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Student</th>
            <th>Course</th>
            <th>Academic Year</th>
            <th>Semester</th>
            <th>Status</th>
            <th>Action</th>
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
                <td>
                    <a href="/enrollments/update/<?= $enrollment->id; ?>" class="btn btn-primary btn-sm">Edit</a>
                    <form action="<?= base_url("enrollments/delete/{$enrollment->id}") ?>" method="post" class="d-inline">
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