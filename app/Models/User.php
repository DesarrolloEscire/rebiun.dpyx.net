<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'last_login_at',
        'first_time',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * ==========
     * RELATIONSHIPS
     * ==========
     */

    public function repositories()
    {
        return $this->hasMany('App\Models\Repository', 'responsible_id');
    }

    public function evaluators()
    {
        return $this->hasMany('App\Models\Evaluator', 'evaluator_id');
    }

    public function asEvaluator()
    {
        return $this->hasOne(
            Evaluator::class,
            'evaluator_id',
            'id'
        );
    }

    public function evaluations()
    {
        return $this->belongsToMany('App\Models\Evaluation', 'evaluation_evaluator', 'user_id', 'evaluation_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function historicals()
    {
        return $this->hasMany(Historical::class);
    }

    /**
     * ============
     * SCOPE METHODS
     * ============
     */

    public function scopeWhereRole($query, $rolName)
    {
        return $query->whereHas('roles', function ($query) use ($rolName) {
            return $query->where('name', $rolName);
        });
    }

    public function scopeUsers($query)
    {
        return $query->whereHas('roles', function ($query) {
            return $query->where('name', 'usuario');
        });
    }

    public function scopeEvaluatorRole($query)
    {
        return $query->whereHas('roles', function ($query) {
            return $query->where('name', 'evaluador');
        });
    }

    public function scopeAdministrators($query)
    {
        return $query->whereHas('roles', function ($query) {
            return $query->where('name', 'admin');
        });
    }

    public function scopeUnverified($query)
    {
        return $query->where('email_verified_at', null);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * ========
     * ATTRIBUTES
     * ========
     */

    public function getRoleColorAttribute()
    {
        if ($this->is_evaluator) return 'warning';
        if ($this->is_admin) return 'danger';
        if ($this->hasRole('usuario')) return 'info';
        return '';
    }

    public function getIsVerifiedAttribute()
    {
        return $this->email_verified_at != null;
    }

    /**
     * =======
     * BOOLEANS
     * =======
     */

    public function getHasRepositoriesAttribute()
    {
        return $this->repositories->count() > 0;
    }

    public function getIsEvaluatorAttribute()
    {
        return $this->hasRole('evaluador');
    }

    public function getIsAdminAttribute()
    {
        return $this->hasRole('admin');
    }
    
    public function getIsUserAttribute()
    {
        return $this->hasRole('usuario');
    }

    public function getIsActiveAttribute()
    {
        if (!$this->last_login_at) return false;

        $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', date('Y-m-d H:i:s'));
        $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $this->last_login_at);
        $diffInMonths = $to->diffInMonths($from);
        if ($diffInMonths >= 1) return false;

        return true;
    }

    /**
     * METHODS
     * 
     */
    public function verify()
    {
        $this->update(['email_verified_at' => Carbon::now()]);
    }

    public function unverify()
    {
        $this->update(['email_verified_at' => null]);
    }
}
