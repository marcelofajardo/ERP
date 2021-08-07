<?php

namespace App\Http\Controllers\Hubstaff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hubstaff\Hubstaff;
use Hubstaff\Authentication\Token;
use Curl\Curl;

class HubstaffController extends Controller
{
	public $appToken;
	public $authToken;
	public $email;
	public $password;

	public function __construct(Request $request){
		
        $this->appToken = getenv('HUBSTAFF_APP_KEY');

	}

    public function checkAuthTokenPresent(){
        
        if(auth()->user()->auth_token_hubstaff){
            return true;
        }else{
            return false;
        }

    }

	public function getToken(Request $request){

		$token = new Token();

		$this->email = $request->email;

		$this->password = $request->password;

        if($this->checkAuthTokenPresent()){
            
            $authTokenDb = auth()->user()->auth_token_hubstaff;
            
            $hubstaff = Hubstaff::getInstance();

            $hubstaff->authenticate($this->appToken, $this->email, $this->password, $authTokenDb );

            $authToken = auth()->user()->auth_token_hubstaff;

        }else{

            $this->authToken = $token->getAuthToken($this->appToken, $this->email, $this->password);
            
            $hubstaff = Hubstaff::getInstance();

            $hubstaff->authenticate($this->appToken, $this->email, $this->password, $this->authToken);

            auth()->user()->update([
                'auth_token_hubstaff' => $this->authToken
            ]);

            $authToken = $this->authToken;

        }

		$users = $hubstaff->getRepository('user')->getAllUsers();

		session()->flash('message', 'Authentication Successful');	

		return view('hubstaff.show-auth-token', compact('users', 'authToken'));

		// }else{

		// 	return 'Credentials do not match!';

		// }

	}

	public function authenticationPage(){
		return view('hubstaff.hubstaff-api-show');
	}

	public function gettingUsersPage(){
		return view('hubstaff.get-user');
	}

	public function userDetails(Request $request){

		$url = 'https://api.hubstaff.com/v1/users';

	 	$curl = new Curl();

	 	$request_headers = [];

        $curl->setHeader("Auth-Token", $request->auth_token);

        if($this->checkAuthTokenPresent()){
            $curl->setHeader("App-Token", auth()->user()->auth_token_hubstaff);
        }else{
            $curl->setHeader("App-Token", $this->appToken);
        }

        $curl->get($url, array(
            'authorization_memberships' => $request->authorization_memberships,
            'project_memberships' => $request->project_memberships,
            'offset' => $request->offset
        ));

        if($curl->http_status_code == 401){
            $curl = $curl;
            return view('hubstaff.error-page', compact('curl'));
        }

        if ($curl->error) {
            $curl = $curl;
           return view('hubstaff.error-page', compact('curl'));
        }else {
            $response = json_decode($curl->response);
        }

        $curl->close();

        $results = $response;

        return view('hubstaff.users', compact('results'));

	}

	public function showFormUserById(){
		return view('hubstaff.user-with-id-page');
	}

	public function getUserById(Request $request){

		$id = $request->id;

		$url = 'https://api.hubstaff.com/v1/users/'. $id;

	 	$curl = new Curl();

        $curl->setHeader("Auth-Token", $request->auth_token);
        $curl->setHeader("App-Token", $this->appToken);

        $curl->get($url, array(
        	'id' => $request->id
        ));

        if($curl->http_status_code == 401){
            $curl = $curl;
            return view('hubstaff.error-page', compact('curl'));
        }

        if ($curl->error) {
            $curl = $curl;
           return view('hubstaff.error-page', compact('curl'));
        }else {
            $response = json_decode($curl->response);
        }

        $curl->close();

        $results = $response;

     	return view('hubstaff.user-with-id', compact('results'));

	}

	public function getProjectPage(){
		return view('hubstaff.get-projects');
	}

	public function getProjects(Request $request){
		
		$id = $request->id;

		$url = 'https://api.hubstaff.com/v1/users/'. $id . '/projects';

	 	$curl = new Curl();

        $curl->setHeader("Auth-Token", $request->auth_token);
        $curl->setHeader("App-Token", $this->appToken);

        $curl->get($url, array(
        	'id' => $request->id,
        	'offset' => $request->offset
        ));

        if($curl->http_status_code == 401){
            $curl = $curl;
            return view('hubstaff.error-page', compact('curl'));
        }

        if ($curl->error) {
            $curl = $curl;
           return view('hubstaff.error-page', compact('curl'));
        }else {
            $response = json_decode($curl->response);
        }

        $curl->close();

        $results = $response;

     	return view('hubstaff.user-projects', compact('results'));
	}
	
	// -------projects---------

	public function getUserProject(){
		return view('hubstaff.project.get-project-page');
	}

	public function postUserProject(Request $request){
		$id = $request->id;

		$url = 'https://api.hubstaff.com/v1/projects';

	 	$curl = new Curl();

        $curl->setHeader("Auth-Token", $request->auth_token);
        $curl->setHeader("App-Token", $this->appToken);

        $curl->get($url, array(
        	'status' => $request->status,
        	'offset' => $request->offset
        ));

        if($curl->http_status_code == 401){
            $curl = $curl;
            return view('hubstaff.error-page', compact('curl'));
        }

        if ($curl->error) {
            $curl = $curl;
           return view('hubstaff.error-page', compact('curl'));
        }else {
            $response = json_decode($curl->response);
        }

        $curl->close();

        $results = $response;

     	return view('hubstaff.project.user-projects', compact('results'));
	}


	// ---------Tasks----------

	public function getProjectTask(){
		return view('hubstaff.task.get-task-page');
	}

	public function postProjectTask(Request $request){

		$url = 'https://api.hubstaff.com/v1/tasks';

	 	$curl = new Curl();

        $curl->setHeader("Auth-Token", $request->auth_token);
        $curl->setHeader("App-Token", $this->appToken);

        $curl->get($url, array(
        	'projects' => $request->projects,
        	'offset' => $request->offset
        ));

        if($curl->http_status_code == 401){
            $curl = $curl;
            return view('hubstaff.error-page', compact('curl'));
        }

        if ($curl->error) {
            $curl = $curl;
           return view('hubstaff.error-page', compact('curl'));
        }else {
            $response = json_decode($curl->response);
        }

        $curl->close();

        $results = $response;

     	return view('hubstaff.task.tasks', compact('results'));
	}

	public function getTaskFromId(){
		return view('hubstaff.task.get-task-from-id');
	}

	public function postTaskFromId(Request $request){

		$url = 'https://api.hubstaff.com/v1/tasks/' . $request->id;

	 	$curl = new Curl();

        $curl->setHeader("Auth-Token", $request->auth_token);
        $curl->setHeader("App-Token", $this->appToken);

        $curl->get($url, array(
        	'projects' => $request->projects,
        	'offset' => $request->offset
        ));

        if($curl->http_status_code == 401){
            $curl = $curl;
            return view('hubstaff.error-page', compact('curl'));
        }

        if ($curl->error) {
            $curl = $curl;
           return view('hubstaff.error-page', compact('curl'));
        }else {
            $response = json_decode($curl->response);
        }

        $curl->close();

        $results = $response;

     	return view('hubstaff.task.specific-task-page', compact('results'));
	}

	public function getScreenshotPage(){
		return view('hubstaff.screenshot.screenshot-page');
	}

	public function postScreenshots(Request $request){
		
		$url = 'https://api.hubstaff.com/v1/screenshots/';

	 	$curl = new Curl();

        $curl->setHeader("Auth-Token", $request->auth_token);
        $curl->setHeader("App-Token", $this->appToken);

        $curl->get($url, array(
        	'start_time'=> $request->start_time,
        	'stop_time'=> $request->stop_time,
        	'organizations'=> $request->organizations,
        	'projects' => $request->projects,
        	'users' => $request->users,
        	'offset' => $request->offset
        ));

        if($curl->http_status_code == 401){
            $curl = $curl;
            return view('hubstaff.error-page', compact('curl'));
        }

        if ($curl->error) {
            $curl = $curl;
           return view('hubstaff.error-page', compact('curl'));
        }else {
            $response = json_decode($curl->response);
        }

        $curl->close();

        $results = $response;

        return view('hubstaff.screenshot.show-screenshots', compact('results'));

	}

	public function index(){
		return view('hubstaff.organization.index');
	}

	public function getOrganization(Request $request){

		$url = 'https://api.hubstaff.com/v1/organizations/';

	 	$curl = new Curl();

        $curl->setHeader("Auth-Token", $request->auth_token);
        $curl->setHeader("App-Token", $this->appToken);

        $curl->get($url, array(
        	'offset' => $request->offset
        ));

        if($curl->http_status_code == 401){
            $curl = $curl;
            return view('hubstaff.error-page', compact('curl'));
        }

        if ($curl->error) {
            $curl = $curl;
           return view('hubstaff.error-page', compact('curl'));
        }else {
            $response = json_decode($curl->response);
        }

        $curl->close();

        $results = $response;
        // dd($results);

        return view('hubstaff.organization.show-organizations', compact('results'));
	}

	public function organizationMemberPage(){
		return view('hubstaff.organization.organization-member-page');
	}

	public function showMembers(Request $request){

		$url = 'https://api.hubstaff.com/v1/organizations/'. $request->id . '/members';

	 	$curl = new Curl();

        $curl->setHeader("Auth-Token", $request->auth_token);
        $curl->setHeader("App-Token", $this->appToken);

        $curl->get($url, array(
        	'offset' => $request->offset
        ));

        if($curl->http_status_code == 401){
            $curl = $curl;
            return view('hubstaff.error-page', compact('curl'));
        }

        if ($curl->error) {
            $curl = $curl;
           return view('hubstaff.error-page', compact('curl'));
        }else {
            $response = json_decode($curl->response);
        }

        $curl->close();

        $results = $response;
        
        return view('hubstaff.organization.show-members', compact('results'));

	}

	public function getTeamPaymentPage(){
		return view('hubstaff.team.payment-page');
	}

	public function getPaymentDetail(Request $request){

		$url = 'https://api.hubstaff.com/v1/team_payments/';

	 	$curl = new Curl();

        $curl->setHeader("Auth-Token", $request->auth_token);
        $curl->setHeader("App-Token", $this->appToken);

        $curl->get($url, array(
        	'start_time'=> $request->start_time,
        	'stop_time'=> $request->stop_time,
        	'organizations'=> $request->organizations,
        	'projects' => $request->projects,
        	'users' => $request->users,
        	'offset' => $request->offset
        ));

        if($curl->http_status_code == 401){
            $curl = $curl;
            return view('hubstaff.error-page', compact('curl'));
        }

        if ($curl->error) {
            $curl = $curl;
           return view('hubstaff.error-page', compact('curl'));
        }else {
            $response = json_decode($curl->response);
        }
        $curl->close();

        $results = $response;

        return view('hubstaff.team.show-payments-detail', compact('results'));
	}
}
