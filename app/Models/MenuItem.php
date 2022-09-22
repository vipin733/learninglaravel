<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    public function getAll()
    {
        if ($this->childs->count() > 0) {
            $this->childs()->each->getAll();
        }
        return $this;
    }
}
