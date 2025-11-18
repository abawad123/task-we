<?php

require_once './config/database.php';




if ($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $comment = $_POST['comment'];
    if ($name && $email && $comment) {
    $pdo->prepare("INSERT INTO comments (post_id, name, email, 
    comment) VALUES (?, ?, ?, ?)")
    ->execute([$post_id, $name, $email, $comment]);
    }
    }
    $comments_stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? AND status = 'approved' ORDER BY created_at DESC");
    $comments_stmt->execute([$post_id]);
    $comments = $comments_stmt->fetchAll();
?>












<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.mi
n.css" rel="stylesheet">
</head>

<body>
<div class="mt-5">
    <h3>Comments</h3>
    <!-- Comment Form -->
    <form method="POST" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="name" class="form-control mb-2"
                    placeholder="Your Name" required>
            </div>
            <div class="col-md-6">
                <input type="email" name="email" class="form-control mb-2"
                    placeholder="Your Email" required>
            </div>
        </div><!-- row -->
        <textarea name="comment" class="form-control mb-2" rows="3"
            placeholder="Your Comment" required></textarea>
        <button type="submit" class="btn btn-primary"> Post Comment
        </button>
    </form>
    <?php foreach ($comments as $comment): ?>
    </div class="border p-3 mb-2">
     <strong><?php echo $comment['name']; ?></strong>
     <small class="text-muted"> - <?php echo $comment['created_at']; ?> 
     </small>
     <p class="mb-0"><?php echo $comment['comment']; ?></p>
     </div>
     <?php endforeach; ?>
</div> <!-- mt-5 -->

</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bund
le.min.js"></script>
