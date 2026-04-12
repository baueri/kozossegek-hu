<div class="pagination-wrapper">
    <a x:if="{ $page > 1 }"
        href="?{{ request()->merge(['pg' => $page - 1])->buildQuery() }}"
        class="page-btn">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div class="page-info">
        <span class="current">{{ $page }}</span>
        <span class="divider">/</span>
        <span class="total">{{ $lastPage }}</span>
    </div>
    <a x:if="{ $page < $lastPage }"
        href="?{{ request()->merge(['pg' => $page + 1])->buildQuery() }}"
        class="page-btn">
        <i class="fas fa-arrow-right"></i>
    </a>
</div>
