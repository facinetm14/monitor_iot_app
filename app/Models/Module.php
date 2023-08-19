<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Module extends Model
{
    //use HasFactory;

    protected $table = 'modules';

    public function nbOfDataSent() {
        return (
                DB::table('datastreams')
                    ->where('module', $this->id)
                    ->count()
                );
    }
}
