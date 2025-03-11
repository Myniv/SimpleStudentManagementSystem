<div class="container mt-5">
    <h2 class="text-center mb-4">Academic Courses</h2>

    <a href="/lecturer/courses/create" class="btn btn-primary mb-1">Add Courses</a>
    <form action="{baseUrl}" method="get" class="form-inline mb-3">
        <div class="row mb-4">
            <div class="col-md-5">
                <div class="input-group mr-2">
                    <input type="text" class="form-control" name="search" value="{search}" placeholder="Search...">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="input-group ml-2">
                    <select name="credits" class="form-select" onchange="this.form.submit()">
                        <option value="">All Credits</option>
                        {filterCredits}
                        <option value="{value}" {selected}>
                            Credits {name}
                        </option>
                        {/filterCredits}
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="input-group ml-2">
                    <select name="semester" class="form-select" onchange="this.form.submit()">
                        <option value="">All Semester</option>
                        {filterSemester}
                        <option value="{value}" {selected}>
                            Semester {name}
                        </option>
                        {/filterSemester}
                    </select>
                </div>
            </div>

            <div class="col-md-1">
                <a href="{reset}" class="btn btn-secondary">
                    Reset
                </a>
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
                        <form action="/lecturer/courses/delete/{id}" method="post" class="d-inline">
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

        {!pager!}
    </div>
</div>