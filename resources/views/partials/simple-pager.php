<div class="row">
    <div class="col-md-4">
        @if($page-1 > 0)
            <?php $first = http_build_query(array_merge($_REQUEST, ['pg' => 1])); ?>
            <?php $prev = http_build_query(array_merge($_REQUEST, ['pg' => $page-1])); ?>
            <a href="?{{ $first }}" class="left"><i class="fa fa-angle-double-left"></i> Első oldal</a>&nbsp;&nbsp;&nbsp;
            <a href="?pg={{ $prev }}" class="left"><i class="fa fa-angle-left"></i> Előző oldal</a>
        @endif
    </div>

    <div class="col-md-4 text-center">{{ $page }} / {{ $lastpage = ceil($total / $perpage) ?: 1 }}</div>

    <div class="col-md-4 text-right">
        @if($page+1 <= $lastpage)
            <?php $next = http_build_query(array_merge($_REQUEST, ['pg' => $page+1 ])); ?>
            <?php $last = http_build_query(array_merge($_REQUEST, ['pg' => $lastpage])); ?>
            <a href="?{{ $next }}"> Következő oldal <i class="fa fa-angle-right"></i></a>&nbsp;&nbsp;&nbsp;
            <a href="?{{ $last }}"> Utolsó oldal <i class="fa fa-angle-double-right"></i></a>
        @endif
    </div>
</div>
