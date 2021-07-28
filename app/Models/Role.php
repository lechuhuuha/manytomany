<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $guarded =[];
    public function permissions(){
        return $this->belongsToMany(Permission::class, 'permissions_roles','role_id', 'permission_id');
    }
    public function users(){
        return $this->belongsToMany(User::class, 'roles_users','role_id', 'user_id');
    }

}
