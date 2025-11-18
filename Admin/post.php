<?php
// Database connection
require_once '../config/database.php';
include './INC/header.php';
include './INC/slide.php';

// Fetch posts from the database
$stmt = $pdo->prepare("SELECT posts.*, categories.name AS category_name FROM posts LEFT JOIN categories ON posts.category_id = categories.id");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);







?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="py-4">

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2"> <i class="bi bi-file-text"></i> Posts Management </h1>

            <a href="addpost.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Post
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">All Posts</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($post['id']); ?></td>
                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                            <td><?php echo htmlspecialchars($post['category_name']); ?></td>
                            <td>
                                <?php if ($post['status'] == 'published'): ?>
                                <span class="badge bg-success">Published</span>
                                <?php else: ?>
                                <span class="badge bg-secondary">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                            <td>
                                <a href="editpost.php?edit_id=<?php echo htmlspecialchars($post['id']); ?>"
                                    class="btn btn-sm btn-primary">Edit</a>
                                <a href="post.php?del_id=<?php echo htmlspecialchars($post['id']); ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No posts found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php include './INC/footer.php'; ?>