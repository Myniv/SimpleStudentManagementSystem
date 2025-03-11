<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<div class="container">
    <h1><?= $title; ?></h1>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('message'); ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="<?= base_url('admin/users/create'); ?>" class="btn btn-primary">Add New User</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Grup</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $user->username; ?></td>
                        <td><?= $user->email; ?></td>
                        <td>
                            <?php if ($user->active == 1): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger">In Active</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $groupModel = new \Myth\Auth\Models\GroupModel();
                            $groups = $groupModel->getGroupsForUser($user->id);
                            foreach ($groups as $group) {
                                echo '<span class="badge bg-info me-1">' . $group['name'] . '</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <a href="<?= base_url('admin/users/edit/' . $user->id); ?>"
                                class="btn btn-sm btn-warning">Edit</a>
                            <form action="<?= base_url('admin/users/delete/' . $user->id); ?>" method="post"
                                class="d-inline" onsubmit="return confirm('Are you sure want to delete this user?')">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="text-center">No Users Data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection(); ?>