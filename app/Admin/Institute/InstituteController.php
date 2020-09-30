<?php

namespace App\Admin\Institute;

use App\Admin\Controllers\AdminController;

/**
 * Description of InstituteController
 *
 * @author ivan
 */
class InstituteController extends AdminController
{
    /**
     * @param InstituteAdminTable $table
     * @return string
     */
    public function list(InstituteAdminTable $table)
    {
        return $this->view('admin.institute.list', compact('table'));
    }
}
