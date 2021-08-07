<?php

namespace App;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Plank\Mediable\Mediable;
use Cache;
use App\UserLog;
use DB;
use Redirect;
use App\Hubstaff\HubstaffPaymentAccount;
use App\Hubstaff\HubstaffActivity;
use Carbon\Carbon;


class User extends Authenticatable
{

         /**
     * @var string
      * @SWG\Property(property="name",type="string")
      * @SWG\Property(property="email",type="string")
      * @SWG\Property(property="phone",type="string")
      * @SWG\Property(property="password",type="string")
      * @SWG\Property(property="responsible_user",type="string")
     
      * @SWG\Property(property="agent_role",type="string")
      * @SWG\Property(property="whatsapp_number",type="string")
      * @SWG\Property(property="amount_assigned",type="string")
      * @SWG\Property(property="auth_token_hubstaff",type="string")
      * @SWG\Property(property="payment_frequency",type="string")
      * @SWG\Property(property="fixed_price_user_or_job",type="string")
      * @SWG\Property(property="approve_login",type="string")
     */
    use HasApiTokens, Notifiable;
    use HasRoles;
    use SoftDeletes;
    use Mediable;

    const USER_ADMIN_ID = 6;

    protected $permission__ = null;
    protected $permission_role_ = null;
    protected $permission_user_role_ = null;
    protected $permission_u_role_ = null;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'responsible_user',
        'agent_role',
        'whatsapp_number',
        'amount_assigned',
        'auth_token_hubstaff',
        'payment_frequency',
        'fixed_price_user_or_job',
        'approve_login',
        'billing_frequency_day',
        'user_timeout',
        'mail_notification',
        'is_auto_approval',
        'last_mail_sent_payment',
    ];

    public function getIsAdminAttribute()
    {
        return true;
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function userFeedbackCategory()
    {
        return $this->hasOne(UserFeedbackCategory::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function actions()
    {
        return $this->hasMany(UserActions::class);
    }

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    public function contacts()
    {
        return $this->hasMany('App\Contact');
    }

    public function products()
    {
        return $this->belongsToMany('App\Product', 'user_products', 'user_id', 'product_id');
    }

    public function approved_products()
    {
        return $this->belongsToMany('App\Product', 'user_products', 'user_id', 'product_id')->where('is_approved', 1);
    }

    public function manualCropProducts()
    {
        return $this->belongsToMany(Product::class, 'user_manual_crop', 'user_id', 'product_id');
    }

    public function customers()
    {
        return $this->belongsToMany('App\Customer', 'user_customers', 'user_id', 'customer_id');
    }

    public function cropApproval()
    {
        return $this->hasMany(User::class)->where('action', 'CROP_APPROVAL');
    }

    public function cropRejection()
    {
        return $this->hasMany(ListingHistory::class)->where('action', 'CROP_REJECTED');
    }

    public function attributeApproval()
    {
        return $this->hasMany(ListingHistory::class)->where('action', 'LISTING_APPROVAL');
    }

    public function attributeRejected()
    {
        return $this->hasMany(ListingHistory::class)->where('action', 'LISTING_REJECTED');
    }

    public function cropSequenced()
    {
        return $this->hasMany(ListingHistory::class)->where('action', 'CROP_SEQUENCED');
    }

    public function whatsappAll()
    {
        return $this->hasMany('App\ChatMessage', 'user_id')->whereNotIn('status', ['7', '8', '9'])->latest();
    }

    public function instagramAutoComments()
    {
        return $this->hasManyThrough(AutoCommentHistory::class, 'users_auto_comment_histories', 'user_id', 'auto_comment_history_id', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
    public function chatMessage()
    {
        return $this->hasOne(ChatMessage::class)->latest();
    }

    public function webhookNotification(){
        return $this->hasOne(WebhookNotification::class)->latest();
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function teamLeads()
    {
        return $this->hasMany(Team::class);
    }

    /**
     * The attributes helps to check if User is Admin.
     *
     * @var array
     */
    public function isAdmin()
    {
        $roles = $this->roles->pluck('name')->toArray();
        if (in_array('Admin', $roles)) {
            return true;
        }
        return false;
    }

    /**
    * We can use this function to give same page rights like admin
    *
    */
    public function isReviwerLikeAdmin($page = "")
    {
        $roles = $this->roles->pluck('name')->toArray();

        $needToBeCheck = ["Admin","master-developer"];
        if(in_array($page,['final_listing'])) {
            $needToBeCheck[] = "Head of Listing";
        }

        foreach($needToBeCheck as $nc) {
            if (in_array($nc, $roles)) {
                return true;
            }
        }

        return false;
    }

    public function isInCustomerService()
    {
        $roles = $this->roles->pluck('name')->toArray();

        if (in_array('crm', $roles)) {
            return true;
        }

        return false;
    }



    /**
     * The attributes helps to check if User has Permission Using Route To Check Page.
     *
     * @var array
     */
    public function hasPermission($name)
    {
        if ($name == '/') {
            $genUrl = 'mastercontrol';
            header("Location: /development/list");
        } else {
            $url = explode('/', $name);
            $model = $url[0];
            $actions = end($url);
            if ($model != '') {
                if ($model == $actions) {
                    $genUrl = $model . '-list';
                } else {
                    $genUrl = $model . '-' . $actions;
                }
            } else {
                return true;
            }
        }

        $permission = null;

        if($this->permission__ !== null){
            $permission = $this->permission__;
        }else{
            $this->permission__ = $permission = Permission::where('route', $genUrl)->first();
        }
     

        if (empty($permission)) {
            echo 'unauthorized route doesnt not exist - new permission save' . $genUrl;
            $per = new Permission;
            $per->name = $genUrl;
            $per->route = $genUrl;
            $per->save();
            die();
            return false;
        }

        $role = null;

        if($this->permission_role_ !== null){
            $role = $this->permission_role_;
        }else{
            $this->permission_role_ =  $role = $permission->getRoleIdsInArray();
        }

        $user_role = null;

        if($this->permission_user_role_ !== null){
            $user_role = $this->permission_user_role_;
        }else{
            $this->permission_user_role_ =  $user_role = $this->roles()->pluck('id')->unique()->toArray();
        }

        foreach ($user_role as $key => $value) {
            if (in_array($value, $role)) {
                return true;
            }
        }

        $permission = $permission->toArray();

        $permission_role = null;

        if($this->permission_u_role_ !== null){
            $permission_role = $this->permission_u_role_;
        }else{
           $this->permission_u_role_ = $permission_role = $this->permissions()->pluck('id')->unique()->toArray();
        }

        

        foreach ($permission_role as $key => $value) {
            if (in_array($value, $permission)) {
                return true;
            }
        }
    }

    /**
     * The attributes helps to check if User has Permission Using Permission Name.
     *
     * @var array
     */

    public function checkPermission($permission)
    {
        //Check if user is Admin
        $authcheck = auth()->user()->isAdmin();
        //Return True if user is Admin
        if ($authcheck == true) {
            return true;
        }

        $permission = Permission::where('route', $permission)->first();
        if ($permission == null && $permission == '') {
            return true;
        }
        $role = $permission->getRoleIdsInArray();
        $user_role = $this->roles()->pluck('id')->unique()->toArray();
        //dd($user_role);
        foreach ($user_role as $key => $value) {
            if (in_array($value, $role)) {
                return true;
            }
        }
        return false;
    }

    /*public function hasRole($role)
    {

        $roles = Role::where('name', $role)->first();

        $role = ($roles) ? [$roles->id] : [];

        $user_role = $this->roles()
            ->pluck('id')->unique()->toArray();
        //dd($user_role);
        foreach ($user_role as $key => $value) {
            if (in_array($value, $role)) {
                return true;
            }
        }
        return false;
    }*/

    public function user_logs()
    {
        return $this->hasMany(UserLog::class);
    }

    public function getRoleNames()
    {
        $user_role = $this->roles()
            ->pluck('name')->unique()->toArray();
        return $user_role;
    }

    /**
     * Check if the user has a particular permission.
     * @param $permissionName
     * @return bool
     */
    public function can($permissionName, $arguements = [])
    {
        if ($this->email === 'guest') {
            return false;
        }

        // this is for testing purpose for now
        $isAdmin = in_array("Admin", $this->roles->pluck("name")->toArray());

        if ($isAdmin) {
            return true;
        }

        return true;

        return $this->permissions()->pluck('name')->contains($permissionName);
    }

    /**
     * Check if the user is the default public user.
     * @return bool
     */
    public function isDefault()
    {
        return $this->system_name === 'public';
    }

    /**
     * Returns the user's avatar,
     * @param int $size
     * @return string
     */
    public function getAvatar($size = 50)
    {
        $default = url('/user_avatar.png');
        $imageId = $this->image_id;
        if ($imageId === 0 || $imageId === '0' || $imageId === null) {
            return $default;
        }

        try {
            $avatar = $this->avatar ? url($this->avatar->getThumb($size, $size, false)) : $default;
        } catch (\Exception $err) {
            $avatar = $default;
        }
        return $avatar;
    }

    /**
     * Get a shortened version of the user's name.
     * @param int $chars
     * @return string
     */
    public function getShortName($chars = 8)
    {
        if (mb_strlen($this->name) <= $chars) {
            return $this->name;
        }

        $splitName = explode(' ', $this->name);
        if (mb_strlen($splitName[0]) <= $chars) {
            return $splitName[0];
        }

        return '';
    }

    public static function getNameById($id)
    {
        $q  = self::where("id", $id)->first();
        return ($q) ? $q->name : "";
    }

    public function currentRate()
    {


        return $this->hasOne(
            'App\UserRate',
            'user_id',
            'id'
        )
            ->latest();
    }

    public function latestRate()
    {
        return $this->hasOne(
            'App\UserRate',
            'user_id',
            'id'
        )->latest('start_date');
    }

    public static function selectList()
    {
        return self::pluck("name","id")->toArray();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }


    public function lastOnline() {
        $hubstaff_activity = HubstaffActivity::leftJoin('hubstaff_members', 'hubstaff_members.hubstaff_user_id', '=', 'hubstaff_activities.user_id')->where('hubstaff_members.user_id',$this->id)->orderBy('hubstaff_activities.starts_at','desc')->first();
        if($hubstaff_activity) {
            return $hubstaff_activity->starts_at;
        }
        else {
            return false;
        }
         
    }


    public function taskList()
    {
        return $this->hasMany(\App\ErpPriority::class, "user_id","id");
    }

    public function yesterdayHrs()
    {
        $records = \App\Hubstaff\HubstaffActivity::join("hubstaff_members as hm","hm.hubstaff_user_id","hubstaff_activities.user_id")
        ->where("hm.user_id",$this->id)
        ->whereDate("starts_at",date("Y-m-d", strtotime('-1 days')))
        ->groupBy('hubstaff_activities.user_id')
        ->select(\DB::raw("sum(hubstaff_activities.tracked) as total_seconds"))
        ->first();

        if($records) {
            return number_format((($records->total_seconds / 60) / 60),2,".",",");
        }

        return 0;
    }

    /**
     * Get supplier category permission
     */
    public function supplierCategoryPermission()
    {
        return $this->belongsToMany('App\SupplierCategory', 'supplier_category_permissions', 'user_id', 'supplier_category_id');
    }
    public function previousDue($lastPaidOn)
    {
        $pendingPyments = HubstaffPaymentAccount::where('user_id',$this->id)->where('billing_start','>',$lastPaidOn)->get();
        $total = 0;
        foreach($pendingPyments as $pending) {
            $total = $total + ($pending->hrs * $pending->rate * $pending->ex_rate);
        }
        return $total;
    }


    public function vendorCategoryPermission()
    {
        return $this->belongsToMany('App\VendorCategory', 'vendor_category_permission', 'user_id', 'vendor_category_id');
    }

    public function user_availabilities()
    {
        return $this->hasOne('App\UserAvaibility','user_id','id');
    }

}
