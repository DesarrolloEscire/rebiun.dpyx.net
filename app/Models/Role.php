<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    use HasFactory;

    const ADMIN_ROLE = "admin";
    const EVALUATOR_ROLE = "evaluador";
    const USER_ROLE = "usuario";
}
