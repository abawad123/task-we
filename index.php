<?php
require_once 'config/database.php';
$posts = $pdo->query("SELECT * FROM posts WHERE 
status='published' ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial￾scale=1.0">
    <title>My Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.mi
n.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">My Blog</a>
        </div>
    </nav>
    <div class="container mt-4">
        <h1 class="mb-4">Latest Posts</h1>


        <?php foreach ($posts as $post): ?>

        <div class="d-flex border-bottom pb-3 mb-3">
            <?php if (!empty($post['image'])): ?>
            <div class="flex-shrink-0 me-3">
                <img src="uploads/<?php echo $post['image']; ?>" alt="<?php echo $post['title']; ?>" width="150"
                    class="rounded">
            </div>
            <?php endif; ?>

            <div class="flex-grow-1">

                <h4><?php echo $post['title']; ?></h4>

                <p class="text-muted mb-2">
                    <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                </p>

                <p class="mb-2">
                    <?php
                        $content = strip_tags($post['content']);
                        echo substr($content, 0, 200) . '...';
                        ?>
                </p>
                <a href="post.php" class="btn btn-secondary">Read More</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">&copy; 2025 My Blog</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bund
le.min.js"></script>
</body>

</html>