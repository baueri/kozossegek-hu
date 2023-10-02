<div class="columns">
    <div class="column">
        @if($page-1 > 0)
        <?php $first = request()->merge(['pg' => 1])->buildQuery(); ?>
        <?php $prev = request()->merge(['pg' => $page-1])->buildQuery(); ?>
        <a href="?{{ $first }}" class="left"><i class="fa fa-angle-double-left"></i> Első oldal</a>&nbsp;&nbsp;&nbsp;
        <a href="?{{ $prev }}" class="left"><i class="fa fa-angle-left"></i> Előző oldal</a>
        @endif
    </div>

    <div class="column has-text-centered">{{ $page }} / {{ $lastpage = ceil($total / $perpage) ?: 1 }}</div>

    <div class="column has-text-right">
        @if($page+1 <= $lastpage)
        <?php $next = request()->merge(['pg' => $page + 1])->buildQuery(); ?>
        <?php $last = request()->merge(['pg' => $lastpage])->buildQuery(); ?>
        <a href="?{{ $next }}"> Következő oldal <i class="fa fa-angle-right"></i></a>&nbsp;&nbsp;&nbsp;
        <a href="?{{ $last }}"> Utolsó oldal <i class="fa fa-angle-double-right"></i></a>
        @endif
    </div>
</div>
