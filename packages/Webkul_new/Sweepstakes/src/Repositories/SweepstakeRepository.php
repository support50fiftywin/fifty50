<?php

namespace Webkul\Sweepstakes\Repositories;

use Webkul\Core\Eloquent\Repository;

class SweepstakeRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\Sweepstakes\Models\Sweepstake';
    }
}