<?php

namespace Framework\Model;

use Framework\Database\PaginatedResultSetInterface;

class PaginatedModelCollection extends ModelCollection implements PaginatedResultSetInterface
{
    protected int $page;

    protected int $total;

    private int $perpage;

    public function __construct($rows, $perpage, $page = 1, $total = 0)
    {
        parent::__construct($rows);
        $this->page = $page;
        $this->total = $total;
        $this->perpage = $perpage;
    }

    public function rows(): array
    {
        return $this->items;
    }

    public function page()
    {
        return $this->page;
    }

    public function total()
    {
        return $this->total;
    }

    public function perpage(): int
    {
        return $this->perpage;
    }

    public function links(): array
    {
        $totalPages = ceil($this->total / $this->perpage);
        $url = fn (int $page) => '?' . request()->merge(['pg' => $page])->buildQuery();

        $links = ['pages' => [], 'prev' => null, 'next' => null, 'first' => $url(1), 'last' => $url($totalPages)];
        for ($i = 1; $i <= $totalPages; $i++) {
            $links['pages'][$i] = $url($i);
        }

        if ($this->page > 1) {
            $links['prev'] = $url($this->page - 1);
        }

        if ($this->page < $totalPages) {
            $links['next'] = $url($this->page + 1);
        }

        return $links;
    }

    public function renderSmallPager(): string
    {
        return view('partials.simple-pager', ['route' => 'portal.news.page', 'total' => $this->total, 'page' => $this->page, 'perpage' => $this->perpage]);
    }
}
