<div class="row">
    <div class="col-md-4">
        @if($page-1 > 0)
            <a href="?pg={{ 1 }}" class="left"><i class="fa fa-angle-double-left"></i> Első oldal</a>&nbsp;&nbsp;&nbsp;
            <a href="?pg={{ $page-1 }}" class="left"><i class="fa fa-angle-left"></i> Előző oldal</a>
        @endif
    </div>

    <div class="col-md-4 text-center">{{ $page }} / {{ $lastpage = ceil($total / $perpage) ?: 1 }}</div>

    <div class="col-md-4 text-right">
        @if($page+1 <= $lastpage)
            <a href="?pg={{ $page+1 }}"> Következő oldal <i class="fa fa-angle-right"></i></a>&nbsp;&nbsp;&nbsp;
            <a href="?pg={{ $lastpage }}"> Utolsó oldal <i class="fa fa-angle-double-right"></i></a>
        @endif
    </div>
</div>