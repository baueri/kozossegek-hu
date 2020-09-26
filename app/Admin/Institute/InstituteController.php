<?php

namespace App\Admin\Institute;

/**
 * Description of InstituteController
 *
 * @author ivan
 */
class InstituteController extends \App\Admin\Controllers\AdminController
{
    public function list(\Framework\Http\Request $request, InstituteAdminTable $table)
    {
        return $this->view('admin.institute.list', compact('table'));
    }
}
