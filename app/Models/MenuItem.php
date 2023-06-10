<?php


namespace App\Models;


use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    public function descendants()
    {
        return $this->hasMany(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->descendants()->with('children');
    }
}
