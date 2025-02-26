<h2 class="text-center my-4">Student List</h2>

<a href="/students/create">Add Student</a>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Program</th>
            <th>Semester</th>
            <th>GPA</th>
            <th>Status</th>
            <th>Courses</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        {students}
        <tr>
            <td>{id}</td>
            <td>{name}</td>
            <td>{study_program}</td>
            <td>{current_semester}</td>
            <td>{gpa}</td>

            {# the {! variable !} make the raw to html so can show up the bootstrap class from cell too #}
            <td class="text-center">{!status_cell!}</td>
            <!-- <td class="text-center">{academic_status}</td> -->
            <td>
                <ul class="list-unstyled mb-0">
                    {!grade_cell!}

                    {#{courses}
                    <li> <span class="fw">{course_name}</span> (<span class="text-primary">{course_grade}</span>)
                    </li>
                    {/courses} #}
                </ul>
            </td>
            <td>
                <button class="btn btn-primary btn-sm" onclick="window.location.href='students/show/{id}'">Detail</button>
                <button class="btn btn-success btn-sm" onclick="window.location.href='students/update/{id}'">Edit</button>
                <form action="/students/delete/{id}" method="post" class="d-inline">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('Are you sure want to delete this student?');">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        {/students}
    </tbody>
</table>