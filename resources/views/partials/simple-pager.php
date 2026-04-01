<div class="pagination-wrapper">
    <?php
        $lastpage = ceil($total / $perpage) ?: 1;
    ?>

    @if($page > 1)
        <?php
            $first = request()->merge(['pg' => 1])->buildQuery();
            $prev = request()->merge(['pg' => $page - 1])->buildQuery();
        ?>

        <a href="?{{ $prev }}" class="page-btn">
            <i class="fas fa-arrow-left"></i>
        </a>
    @endif

    <div class="page-info">
        <span class="current">{{ $page }}</span>
        <span class="divider">/</span>
        <span class="total">{{ $lastpage }}</span>
    </div>

    @if($page < $lastpage)
        <?php
            $next = request()->merge(['pg' => $page + 1])->buildQuery();
        ?>

        <a href="?{{ $next }}" class="page-btn">
            <i class="fas fa-arrow-right"></i>
        </a>
    @endif
</div>
