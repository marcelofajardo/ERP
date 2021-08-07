<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{

		/**
     * @var string
   * @SWG\Property(property="message",type="string")
     * @SWG\Property(property="role",type="string")

        * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="user_id",type="integer")
     * @SWG\Property(property="sent_to",type="integer")
     * @SWG\Property(property="sale_id",type="integer")
     * @SWG\Property(property="task_id",type="integer")
     * @SWG\Property(property="message_id",type="integer")
     * @SWG\Property(property="reminder",type="string")
     */
	protected $fillable = [
		"message",
		"role",
		"product_id",
		"user_id",
		'sent_to',
		"sale_id",
		'task_id',
		'message_id',
		'reminder'
	];

	public static function getUserNotificationByRoles($limit = 10){

		//		$notifications = self::all()->whereIn('role',$roles);
		$notifications = DB::table('notifications as n')
		                    ->select('n.message','n.isread','n.id','n.product_id','n.sale_id','p.sku','p.name as pname','u.name as uname','n.created_at')
							->whereIn('role',\Auth::user()->getRoleNames())
							->orWhere('sent_to',\Auth::id())
							->latest('n.created_at')
							->limit($limit)
							->leftJoin('products as p','n.product_id','=','p.id')
							->leftJoin('users as u','n.user_id','=','u.id')
							->get();
		return $notifications;
	}

	public static function getUserNotificationByRolesPaginate( Request $request ){

		$orderBy = 'n.created_at';
		$direction = 'desc';

		if( $request->has('sort_by') )
		{
			if( $request->input('sort_by') == 'by_user' ) {
				$orderBy = 'n.user_id';
				$direction = 'asc';
			}

			if( $request->input('sort_by') == 'by_task' ) {
				$orderBy = 'n.role';
				$direction = 'asc';
			}
		}

		$notifications = DB::table('notifications as n')
		                    ->select('n.message','n.isread','n.id','n.product_id','n.sale_id','p.sku','p.name as pname','u.name as uname','n.created_at')
		                    ->whereIn('role',\Auth::user()->getRoleNames())
							->orWhere('sent_to',\Auth::id())
		                    ->leftJoin('products as p','n.product_id','=','p.id')
		                    ->leftJoin('users as u','n.user_id','=','u.id')
							->orderBy($orderBy,$direction)
							->paginate(20);

		return $notifications;
	}

	public function product(){

		return $this->belongsTo('App\Product','product_id','id');
	}

	public function role(){

		return $this->belongsTo('Spatie\Permission\Models\Role','role','name');
	}

	public function user(){

		return $this->belongsTo('App\User','user_id','id');
	}

	public function getAll(){
		return self::all();
	}

}
