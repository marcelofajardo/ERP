<?php

namespace App\Http\Controllers;

use App\Account;
use GuzzleHttp\Client;
use InstagramAPI\Exception\ChallengeRequiredException;
use InstagramAPI\Instagram;
use Illuminate\Http\Request;
use InstagramAPI\Response\GenericResponse;
use Wa72\HtmlPageDom\HtmlPageCrawler;

Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;

class AccountController extends Controller
{

    private  $ig;
    public function show($id) {
        $account = Account::findOrFail($id);
        $accounts = Account::where('platform', 'instagram')->get();
        return view('reviews.show', compact('account', 'accounts'));
    }

    public function sendMessage($id, Request $request, Instagram $instagram) {

        $this->validate($request, [
            'username' => 'required',
            'message' => 'required'
        ]);

        $account = Account::findOrFail($id);
        $last_name = $account->last_name;
        $password = $account->password;

        $apiUrl = "https://www.instagram.com/$request->username";
        $guzzle = new Client();
        $data = $guzzle->get($apiUrl);
        $content = $data->getBody()->getContents();

        $c = new HtmlPageCrawler($content);
        $firstScript = $c->filter('body script')->getInnerHtml();

        $firstScript = str_replace('window._sharedData = ', '', $firstScript);

        $firstScript = substr($firstScript, 0, strlen($firstScript)-1);

        $firstScript = json_decode($firstScript, true);

        if (!isset($firstScript['entry_data']['ProfilePage'][0]['graphql']['user']['id'])) {
            return response()->json([
                'status' => 'User Not Found!'
            ]);
        }

        $id = $firstScript['entry_data']['ProfilePage'][0]['graphql']['user']['id'];

        $instagram->login($last_name, $password);
        $instagram->direct->sendText(['users' => [$id]], $request->get('message'));
        return response()->json([
            'status' => 'Message Sent successfully!'
        ]);

    }

    public function test($id) {
        $account = Account::find($id);
//        $this->ig = new Instagram();
//        try {
//            $this->ig->login($account->last_name, $account->password);
//        } catch (\Exception $exception) {
//            dd($exception);
//            $account->forceDelete();
//        }


        $Instagram = new Instagram();

        try {
            $Instagram->login($account->last_name, $account->password);

        } catch (\Exception $Exception) {
            if ($Exception instanceof ChallengeRequiredException) {
                sleep(5);

                dd($Exception->getResponse());

                $resp = $Exception->getResponse()->asStdClass();

                $customResponse = $Instagram->request(substr($resp->challenge['api_path'], 1))->setNeedsAuth(false)->addPost('choice', 0)->getDecodedResponse();

                if (is_array($customResponse)) {
                    dd($customResponse);
                    $ifId = $customResponse['user_id'];
                    $code = $customResponse['nonce_code'];

                    dd($ifId, $code);

                    //Other stuff.
                } else {
                    //Other stuff.
                }
            } else {
                //Other stuff.
            }
        }

        return redirect()->back()->with('message', 'test passed!');
    }

    public function startAccountGrowth($id) {
        $account = Account::findOrFail($id);
        $this->ig = new Instagram();

        try {
            $this->ig->login($account->last_name, $account->password);
        } catch (\Exception $exception) {
            return redirect()->back()->with('message', 'Please connect your account to server before starting growth!');
        }

        $account->is_seeding = 1;
        $account->seeding_stage = 0;
        $account->save();

        return redirect()->back()->with('message', 'Account started for growth!');
    }

    public function agreeConsentFirstStep()
    {
        return $this->ig->request('consent/existing_user_flow/')
            ->setNeedsAuth(false)
            ->addPost('_csrftoken', $this->ig->client->getToken())
            ->addPost('_uid', $this->ig->account_id)
            ->addPost('_uuid', $this->ig->uuid)
            ->getResponse(new GenericResponse());
    }

    public function agreeConsentSecondStep()
    {
        return $this->ig->request('consent/existing_user_flow/')
            ->setNeedsAuth(false)
            ->addPost('current_screen_key', 'qp_intro')
            ->addPost('updates', ['existing_user_intro_state' => '2'])
            ->addPost('_csrftoken', $this->ig->client->getToken())
            ->addPost('_uid', $this->ig->account_id)
            ->addPost('_uuid', $this->ig->uuid)
            ->getResponse(new GenericResponse());
    }

    public function agreeConsentThirdStep()
    {
        return $this->ig->request('consent/existing_user_flow/')
            ->setNeedsAuth(false)
            ->addPost('current_screen_key', 'tos_and_two_age_button')
            ->addPost('updates', ['age_consent_state' => '2', 'tos_data_policy_consent_state' => '2'])
            ->addPost('_csrftoken', $this->ig->client->getToken())
            ->addPost('_uid', $this->ig->account_id)
            ->addPost('_uuid', $this->ig->uuid)
            ->getResponse(new GenericResponse());
    }

    /**
     * @SWG\Post(
     *   path="/instagram/create",
     *   tags={"Account"},
     *   summary="Create Account",
     *   operationId="create-inst-account",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="mytest",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
     * )
     *
     */
    public function createAccount(Request $request) {

        $this->validate($request, [
            'username' => 'required',
            'name' => 'required',
            'password' => 'required'
        ]);

        $account = new Account();
        $account->first_name = $request->get('name');
        $account->email = $request->get('email');
        $account->last_name = $request->get('username');
        $account->password = $request->get('password');
        $account->gender = 'female';
        $account->platform = 'instagram';
        $account->save();

        return response()->json([
            'status' => 'success'
        ]);
    }
}
