<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class BookCollection extends Collection
{
    public function borrowed()
    {
        $user = auth()->user();
        return $user->books()->get();
    }

}
