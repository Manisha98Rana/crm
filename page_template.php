<?php
include 'db_conn.php';
include 'header.php';  // Include your header file for consistent layout

$content = "<p>No page specified.</p>"; // Default message if page parameter is missing
$pageTitle = "Page"; // Default title if page name is not set

if (isset($_GET['page'])) {
    $pageName = mysqli_real_escape_string($conn, $_GET['page']);
    $query = "SELECT * FROM page_contents WHERE page_name = '$pageName'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $pageData = mysqli_fetch_assoc($result);
        $content = $pageData['content'];
        $html_code = $pageData['html_code'];
        $pageTitle = ucwords(str_replace('-', ' ', $pageName));
    } else {
        $content = "<p>Page not found.</p>";
    }
}
?>

<section class="pt-70-fixed">
    <div class="container my-5">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0"><?php echo $pageTitle; ?></h5>
            </div>
            <div class="card-body">
                <?php if ($content): ?>
                    <h1 class="card-title text-center mb-4"><?php echo $pageTitle; ?></h1>
                    <div class="card-text">
                        <?php echo nl2br(htmlspecialchars($content)); ?> <!-- This ensures content is displayed correctly -->
                        <div class="html-content">
                            <?php echo $html_code; ?> <!-- Display HTML code as actual HTML, no htmlspecialchars -->
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning text-center">Content not found.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; // Include your footer file for consistent layout ?>
