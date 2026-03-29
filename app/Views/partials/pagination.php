<?php
$paginationCurrentPage = max(1, (int) ($paginationCurrentPage ?? 1));
$paginationTotalPages = max(1, (int) ($paginationTotalPages ?? 1));
$paginationPageParam = (string) ($paginationPageParam ?? 'page');
$paginationLabel = (string) ($paginationLabel ?? 'Pagination');

if ($paginationTotalPages <= 1) {
    return;
}

$paginationPath = (string) parse_url((string) ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH);
$paginationQuery = $_GET;

$buildPaginationUrl = static function (int $page) use ($paginationPath, $paginationQuery, $paginationPageParam): string {
    $query = $paginationQuery;
    $query[$paginationPageParam] = $page;

    return htmlspecialchars($paginationPath . '?' . http_build_query($query), ENT_QUOTES);
};
?>
<nav class="pagination" aria-label="<?= htmlspecialchars($paginationLabel, ENT_QUOTES) ?>">
  <?php if ($paginationCurrentPage > 1): ?>
    <a class="page-btn" href="<?= $buildPaginationUrl($paginationCurrentPage - 1) ?>">&laquo;</a>
  <?php else: ?>
    <span class="page-btn page-btn--disabled">&laquo;</span>
  <?php endif; ?>

  <?php for ($page = 1; $page <= $paginationTotalPages; $page++): ?>
    <a class="page-btn<?= $page === $paginationCurrentPage ? ' page-btn--active' : '' ?>" href="<?= $buildPaginationUrl($page) ?>">
      <?= $page ?>
    </a>
  <?php endfor; ?>

  <?php if ($paginationCurrentPage < $paginationTotalPages): ?>
    <a class="page-btn" href="<?= $buildPaginationUrl($paginationCurrentPage + 1) ?>">&raquo;</a>
  <?php else: ?>
    <span class="page-btn page-btn--disabled">&raquo;</span>
  <?php endif; ?>
</nav>
