<?php
// admin/comments.php - DESIGN ONLY
include 'inc/header.php';
include 'inc/slide.php';
require_once '../config/database';




$message = '';
$message_type = '';
$stmt = $pdo->query("SELECT * FROM comments ORDER BY created_at DESC");
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get comment counts
$total_comments = count($comments);
$pending_comments = $pdo->query("SELECT COUNT(*) FROM comments WHERE status = 'pending'")->fetchColumn();
$approved_comments = $pdo->query("SELECT COUNT(*) FROM comments WHERE status = 'approved'")->fetchColumn();
$rejected_comments = $pdo->query("SELECT COUNT(*) FROM comments WHERE status = 'rejected'")->fetchColumn();









$message = '';
$message_type = '';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $comment_id = (int)$_GET['id'];
    $action = $_GET['action'];

    if ($action == 'approve') {
        $stmt = $pdo->prepare("UPDATE comments SET status = 'approved'
    WHERE id = ?");
        $stmt->execute([$comment_id]);
        $message = "Comment approved!";
        $message_type = "success";
        echo "<script>
    window.location.href = 'comments.php';
    </script>";
        exit();
    }

    if ($action == 'reject') {
        $stmt = $pdo->prepare("UPDATE comments SET status = 'rejected'
    WHERE id = ?");
        $stmt->execute([$comment_id]);
        $message = "Comment rejected!";
        $message_type = "warning";
        echo "<script>
    window.location.href = 'comments.php';
    </script>";
        exit();
    }
    if ($action == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->execute([$comment_id]);
        $message = "Comment deleted!";
        $message_type = "danger";
        echo "<script>
    window.location.href = 'comments.php';
    </script>";
        exit();
    }
}
?>
<!-- Display Messages -->
<?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?> alert￾dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs￾dismiss="alert"></button>
    </div>
<?php endif; ?>






<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="py-4">
        <!-- Page Title -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap 
align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="bi bi-chat-dots"></i> Comments Management
            </h1>
        </div>
        <!-- Comments Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">All Comments</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($comments as $comment): ?>
                                <tr>
                                    <td><?php echo $comment['id']; ?></td>
                                    <td><?php echo htmlspecialchars($comment['name']); ?></td>

                                    <td> <?php echo htmlspecialchars($comment['comment']); ?> </td>
                                    <td>
                                        <?php if ($comment['status'] == 'approved'): ?>
                                            <span class="badge bg-success">Approved</span>
                                        <?php elseif ($comment['status'] == 'pending'): ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Rejected</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo date('M j, Y', strtotime($comment['created_at'])); ?>
                                    </td>

                                    <td>
                                        <?php if ($comment['status'] != 'approved'): ?>
                                            <a href="comments.php?action=approve&id=<?php echo $comment['id']; ?>"
                                                class="btn btn-sm btn-success">
                                                <i class="bi bi-check"></i> Approve
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($comment['status'] != 'rejected'): ?>
                                            <a href="comments.php?action=reject&id=<?php echo $comment['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-x"></i> Reject
                                            </a>
                                        <?php endif; ?>

                                        <a href="comments.php?action=delete&id=<?php echo $comment['id'] ?>"
                                            class="btn btn-sm btn-danger" onclick="return confirm('Delete this comment?')">
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
        <!-- Stats Cards -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4><?php echo $total_comments; ?></h4>
                                <p class="mb-0">Total Comments</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-chat-dots fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>><?php echo $pending_comments; ?></h4>
                                <p class="mb-0">Pending</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-clock fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4><?php echo $approved_comments?></h4>
                                <p class="mb-0">Approved</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check-circle fs-1"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4><?php echo $rejected_comments; ?></h4>
                                <p class="mb-0">Rejected</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-x-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include 'inc/footer.php'; ?>