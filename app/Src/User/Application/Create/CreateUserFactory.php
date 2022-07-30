<?php

namespace App\Src\User\Application\Create;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use InvalidArgumentException;

class CreateUserFactory
{
    public static function create(FormRequest $request)
    {
        $role = Role::find($request->role);
        // dd($role);
        switch ($role->name) {
            case 'admin':
                return new CreateAdminUser($request);
                break;
            case 'usuario':
                return new CreateUserUser($request);
                break;
            case 'evaluador':
                return new CreateEvaluatorUser($request);
                break;
            default:
                throw new InvalidArgumentException();
                break;
        }
    }
}
