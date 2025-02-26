<div class="container mt-5">
    <h2 class="text-center mb-4">Academic Courses</h2>

    <a href="/courses/create">Add Courses</a>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Course Name</th>
                    <th>Code</th>
                    <th>Credits</th>
                    <th>Semester</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                {courses}
                <tr>
                    <td>{id}</td>
                    <td>{name}</td>
                    <td>{code}</td>
                    <td>{credits}</td>
                    <td>{semester}</td>
                    <td>
                        <button class="btn btn-success btn-sm"
                            onclick="window.location.href='courses/update/{id}'">Edit</button>
                        <form action="/courses/delete/{id}" method="post" class="d-inline">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure want to delete this student?');">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                {/courses}
            </tbody>
        </table>
    </div>
</div>