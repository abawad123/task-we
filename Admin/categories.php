<?php
require_once '../config/database.php';
include 'INC/header.php';
include 'INC/slide.php';

$message = '';
$message_type = '';

// ADD CATEGORY
if (isset($_POST['add_category'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if (empty($name)) {
        $message = "Category name is required!";
        $message_type = "danger";
    } else {
        $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        $stmt->execute([$name, $description]);
        $message = "Category added successfully!";
        $message_type = "success";
    }
}

// DELETE CATEGORY
if (isset($_GET['del_id'])) {
    $del_id = $_GET['del_id'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$del_id]);
    echo "<script>window.location.href='categories.php';</script>";
    exit();
}

// UPDATE CATEGORY
if (isset($_POST['update_category'])) {
    $edit_id = $_GET['edit_id'];
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if (empty($name)) {
        $message = "Category name is required!";
        $message_type = "danger";
    } else {
        $stmt = $pdo->prepare("UPDATE categories SET name=?, description=? WHERE id=?");
        $stmt->execute([$name, $description, $edit_id]);
        echo "<script>window.location.href='categories.php';</script>";
        exit();
    }
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
<div class="py-4">

    <h1 class="h2 mb-3 border-bottom"><i class="bi bi-tags"></i> Categories Management</h1>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- ADD OR EDIT FORM -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <?php echo isset($_GET['edit_id']) ? 'Edit Category' : 'Add New Category'; ?>
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <?php
                $nameValue = '';
                $descValue = '';

                if (isset($_GET['edit_id'])) {
                    $edit_id = $_GET['edit_id'];
                    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
                    $stmt->execute([$edit_id]);
                    $cat = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($cat) {
                        $nameValue = $cat['name'];
                        $descValue = $cat['description'];
                    }
                }
                ?>

                <div class="col-md-4">
                    <label for="categoryName" class="form-label">Category Name *</label>
                    <input type="text" class="form-control" id="categoryName" name="name" value="<?php echo htmlspecialchars($nameValue); ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="categoryDescription" class="form-label">Description</label>
                    <input type="text" class="form-control" id="categoryDescription" name="description" value="<?php echo htmlspecialchars($descValue); ?>">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn <?php echo isset($_GET['edit_id']) ? 'btn-warning' : 'btn-primary'; ?> w-100"
                        name="<?php echo isset($_GET['edit_id']) ? 'update_category' : 'add_category'; ?>">
                        <i class="bi <?php echo isset($_GET['edit_id']) ? 'bi-pencil-square' : 'bi-plus-circle'; ?>"></i>
                        <?php echo isset($_GET['edit_id']) ? 'Update' : 'Add'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- CATEGORIES TABLE -->
    <div class="card">
        <div class="card-header"><h5 class="card-title mb-0">All Categories</h5></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr><th>ID</th><th>Name</th><th>Description</th><th>Created At</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM categories ORDER BY created_at DESC");
                        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($categories as $category):
                        ?>
                            <tr>
                                <td><?php echo $category['id']; ?></td>
                                <td><?php echo htmlspecialchars($category['name']); ?></td>
                                <td><?php echo htmlspecialchars($category['description']); ?></td>
                                <td><?php echo $category['created_at']; ?></td>
                                <td>
                                    <a href="categories.php?edit_id=<?php echo $category['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="categories.php?del_id=<?php echo $category['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</main>

<?php include 'INC/footer.php'; ?>
