<?php foreach ($latestCourse as $value): ?>
    <li> <span class="fw"><?= $value['course_name'] ?></span> (<span
            class="text-primary"><?= $value['course_grade'] ?></span>)</li>
<?php endforeach ?>