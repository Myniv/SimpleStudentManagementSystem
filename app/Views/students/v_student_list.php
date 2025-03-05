<h2 class="text-center my-4">Student List</h2>

<a href="/students/create" class="btn btn-primary mb-1">Add Student</a>
<form action="{baseUrl}" method="get" class="form-inline mb-3">
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="input-group mr-2">
                <input type="text" class="form-control" name="search" value="{search}" placeholder="Search...">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="input-group ml-2">
                <select name="study_program" class="form-select" onchange="this.form.submit()">
                    <option value="">Program</option>
                    {filterStudyProgram}
                    <option value="{value}" {selected}>
                        {name}
                    </option>
                    {/filterStudyProgram}
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="input-group ml-1">
                <select name="academic_status" class="form-select" onchange="this.form.submit()">
                    <option value="">Status</option>
                    {filterAcademicStatus}
                    <option value="{value}" {selected}>
                        {name}
                    </option>
                    {/filterAcademicStatus}
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="input-group ml-1">
                <select name="entry_year" class="form-select" onchange="this.form.submit()">
                    <option value="">Entry Year</option>
                    {filterEntryYear}
                    <option value="{value}" {selected}>
                        {name}
                    </option>
                    {/filterEntryYear}
                </select>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="input-group">
                <select name="perPage" class="form-select" onchange="this.form.submit()">
                    {perPageOptions}
                    <option value="{value}" {selected}>
                        {value} per Page
                    </option>
                    {/perPageOptions}
                </select>
            </div>
        </div>

        <div class="col-md-1">
            <a href="{reset}" class="btn btn-secondary">
                Reset
            </a>
        </div>


        <input type="hidden" name="sort" value="{sort}">
        <input type="hidden" name="order" value="{order}">

    </div>
</form>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                {tableHeader}
                <th>
                    <a class="text-white text-decoration-none" href="{href}">
                        {name} {is_sorted}
                    </a>
                </th>
                {/tableHeader}
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            {students}
            <tr>
                <td>{student_id}</td>
                <td>{name}</td>
                <td>{study_program}</td>
                <td>{current_semester}</td>
                <td>{academic_status}</td>
                <td>{entry_year}</td>
                <td>{gpa}</td>
                <td>
                    <button class="btn btn-primary btn-sm"
                        onclick="window.location.href='students/show/{id}'">Detail</button>
                    <button class="btn btn-success btn-sm"
                        onclick="window.location.href='students/update/{id}'">Edit</button>
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
    {!pager!}
</div>