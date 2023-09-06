<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Status extends Model
{
    use HasFactory;


    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }

    public static function getStatusCounts()
    {
        $counters = Idea::select(
            DB::raw('count(*) as all_counts'),
            DB::raw('count(CASE WHEN status_id = 1 THEN 1 END) as statusOpen'),
            DB::raw('count(CASE WHEN status_id = 2 THEN 1 END) as statusConsidering'),
            DB::raw('count(CASE WHEN status_id = 3 THEN 1 END) as statusInProgress'),
            DB::raw('count(CASE WHEN status_id = 4 THEN 1 END) as statusImplemented'),
            DB::raw('count(CASE WHEN status_id = 5 THEN 1 END) as statusClosed')
        )
            ->first()
            ->makeHidden(["user", "category", "status"])
            ->toArray();
        return $counters;
    }
}
