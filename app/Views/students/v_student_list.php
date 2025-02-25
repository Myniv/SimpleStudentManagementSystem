<h2 class="text-center my-4">Student List</h2>

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
                <button class="btn btn-primary" onclick="window.location.href='students/show/{id}'">Detail</button>
            </td>
        </tr>
        {/students}
    </tbody>
</table>