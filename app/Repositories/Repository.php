<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

abstract class Repository extends BaseRepository
{
    public function boot()
    {
        parent::boot();
    }
}
