@if($accounts->isEmpty())

<tr>
 <td>
   No Result Found
 </td>
</tr>
@else

@foreach ($accounts as $account)

<tr>

 <td>{{ $account->last_name }}</td>
 <td>@if(isset($account->storeWebsite)) {{ $account->storeWebsite->title }} @endif</td>
 
 <td>{{ $account->password }}</td>
 <td>{{ $account->number }}</td>
 <td>{{ $account->email }}</td>
 <td>{{ $account->platform }}</td>
 <td>{{ $account->provider }}</td>
 <td>{{ $account->frequency }}</td>
 <td>@if($account->is_customer_support == 1) Yes @else No @endif</td>
 <td>
   
   @if(strtolower($account->platform) == 'instagram')
   <a href="javascript:;" onclick="openModel({{ $account->id }})" ><i aria-hidden="true" class="fa fa-upload" title="Post Images"></i></a>
   <a href="javascript:;" onclick="likeUserPost({{ $account->id }})"><i class="fa fa-thumbs-up" title="Like Posts"></i></a>
   <a href="javascript:;" onclick="sendRequest({{ $account->id }})"><i class="fa fa-send" title="Send Request"></i></a>
   <a href="javascript:;" onclick="acceptRequest({{ $account->id }})"><i class="fa fa-envelope-open" title="Accept Request"></i></a>
   <a href="javascript:;" data-id="{{ $account->id }}" class="account-history"><i class="fa fa-history" title="History"></i></a>
     <!-- <button onclick="postImage({{ $account->id }})" class="btn btn-secondary btn-sm">Post Images</button>
     <button onclick="likeUserPost({{ $account->id }})" class="btn btn-secondary btn-sm">Like Posts</button>
     <button onclick="sendRequest({{ $account->id }})" class="btn btn-secondary btn-sm">Send Request</button>
     <button onclick="acceptRequest({{ $account->id }})" class="btn btn-secondary btn-sm">Accept Request</button> -->

   @endif
   <a href="javascript:;" onclick="editAccount({{ $account->id }})"><i class="fa fa-edit" title="Edit"></i></a>
   <!-- <button onclick="editAccount({{ $account->id }})" class="btn btn-secondary btn-sm">Edit</button> -->
   @if(Auth::user()->hasRole('Admin'))
   <a href="javascript:;" onclick="deleteConfig({{ $account->id }})"><i class="fa fa-trash" title="Delete"></i></a>
   <!-- <button onclick="deleteConfig({{ $account->id }})" class="btn btn-sm">Delete</button> -->
   @endif
 </td>
</tr>

@include('marketing.accounts.partials.edit-modal')
@endforeach

@endif