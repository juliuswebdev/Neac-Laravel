<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Notifications\PasswordReset;
use DB;
use Auth;
use Helper;


class User extends Authenticatable
{
    use Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','user_type','first_name','middle_name','last_name','approval', 'profession', 'reseller_code', 'reseller_prize', 'reseller_code_used', 'lock_status', 'lock_user_id', 'lock_date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'approval'=>'boolean'
    ];

    public function role() {
        return DB::table('model_has_roles')->leftJoin('roles', function($join) {
            $join->on('model_has_roles.role_id', '=', 'roles.id');
        })->select( 'roles.*', 'model_has_roles.model_id' )
        ->where('model_has_roles.model_id', auth()->user()->id)
        ->where('model_has_roles.model_type', 'App\User')
        ->first();
    }

    public function profile() {
        return $this->hasOne(Profile::class,'user_id','id');
    }

    public function testimonial_profile() {
	return $this->hasOne(Profile::class,'user_id','id')->select('image');
    }

    public function employee() {
        return $this->hasOne(Employee::class,'user_id','id');
    }

    public function mails() {
        return $this->hasMany(Mails::class,'to','email')
        ->leftJoin('users', function($join) {
            $join->on('mails.user_id', '=', 'users.id');
        })->select('mails.*', 'users.first_name', 'users.last_name', 'users.email')->orderBy('created_at', 'DESC');
    }

    public function cart() {
        return $this->hasOne(Cart::class, 'user_id', 'id')->where('status', 0);
    }

    public function cart_success() {
        return $this->hasMany(Cart::class, 'user_id', 'id')->where('status', 1)->orderBy('carts.order_no', 'DESC');
    }

    public function sendPasswordResetNotification($token)
    {
        // $this->notify(new PasswordReset($token));
        $data = [
            $this->email
        ];
        $other['email_reset_password_portal_link'] = route('password.reset', ['token' => $token, 'email' => $this->email]);
        Helper::mail_formatter(16, $this, $other);
    }

    public function admin_locked($id){
        return User::find($id);
    }

}
