<?php
include 'inc/header.php';
include 'inc/slide.php';
require_once '../config/database.php';

// Initialize variables
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize inputs
    $title = htmlspecialchars(trim($_POST['title']));
    $content = htmlspecialchars(trim($_POST['content']));
    $category_id = intval($_POST['category_id']);
    $user_id = intval($_POST['user_id']);
    $status = htmlspecialchars(trim($_POST['status']));

    // Validate required fields
    if (empty($title) || empty($content) || empty($category_id) || empty($status)) {
        $message = "All fields are required.";
        $message_type = "danger";
    } else {
        // Handle image upload
        $image = '';
        if (!empty($_FILES['image']['name'])) {
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

            if (in_array(strtolower($file_extension), $allowed_extensions)) {
                if ($_FILES['image']['size'] <= 2 * 1024 * 1024) { // Max 2MB
                    $fileName = time() . '_' . $_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $fileName);
                    $image = $fileName;
                } else {
                    $message = "Image size must be less than 2MB.";
                    $message_type = "danger";
                }
            } else {
                $message = "Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.";
                $message_type = "danger";
            }
        }

        // Save to database if no errors
        if (empty($message)) {
            $stmt = $pdo->prepare("INSERT INTO posts (title, content, image, category_id, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $content, $image, $category_id, $status]);
            $message = "Post added successfully!";
            $message_type = "success";

            echo "<script>window.location.href = 'post.php';</script>";
            exit();
        }
    }
}
?>
<!-- Display Success or Error Message above the form -->
<?php if ($message): ?>
<div class="alert alert-<?php echo htmlspecialchars($message_type); ?> alert-dismissible fade show" role="alert">
    <?php echo htmlspecialchars($message); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Main Content Column -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="py-4">
        <!-- Page Title -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2"><i class="bi bi-file-text"></i> Add New Post </h1>
            <a href="post.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Posts </a>
        </div> <!-- d-flex -->
        <!-- Add New Post Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-plus-circle"></i> Create New Blog Post
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Post Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea class="form-control" name="content" rows="6" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" name="category_id" required>
                            <?php
                            $stmt = $pdo->prepare("SELECT * FROM categories");
                            $stmt->execute();
                            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($categories as $category) {
                                echo '<option value="' . htmlspecialchars($category['id']) . '">' . htmlspecialchars($category['name']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Featured Image</label>
                        <input type="file" class="form-control" name="image">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus-circle"></i> Create Post
                    </button>
                </form>
            </div> <!-- card-body -->
        </div> <!-- card -->
    </div> <!-- py-4 -->
</main>
<?php include 'inc/footer.php'; ?>