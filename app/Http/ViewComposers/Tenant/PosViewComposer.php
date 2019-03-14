<?php

namespace App\Http\ViewComposers\Tenant;

use App\Models\Tenant\Pos;

class PosViewComposer
{
    public function compose($view)
    {

        $view->vc_pos = Pos::active();
    }
}
