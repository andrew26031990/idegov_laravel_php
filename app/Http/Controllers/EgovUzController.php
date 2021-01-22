<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateusersRequest;
use App\Http\Requests\UpdateusersRequest;
use App\Models\User;
use App\Repositories\usersRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Response;

class EgovUzController extends AppBaseController
{
    /** @var  usersRepository */
    private $usersRepository;
    private $authorizationurl;
    private $clientid;
    private $clientsecret;
    private $scope;
    private $stateArr;

    public function __construct(usersRepository $usersRepo)
    {
        $this->usersRepository = $usersRepo;
        $this->authorizationurl = env('AUTHORIZATION_URL');
        $this->clientid = env('CLIENT_ID');
        $this->clientsecret = env('CLIENT_SECRET');
        $this->scope = env('scope');
        $this->stateArr = array('method' => env('METHOD'));
    }

    public function index(Request $request)
    {

        $state = json_encode($this->stateArr);
        $state = base64_encode ($state);

        return view('auth.egov')->
            with('authorizationurl', $this->authorizationurl)->
            with('clientid', $this->clientid)->
            with('clientsecret', $this->clientsecret)->
            with('scope', $this->scope)->
            with('state', $state);
    }

    public function authenticate()
    {
        $authCode = $_GET["code"];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$this->authorizationurl);
        curl_setopt($ch, CURLOPT_POST, true);

        $param = "grant_type=" . rawurlencode('one_authorization_code');
        $param = $param . "&client_id=" . rawurlencode($this->clientid);
        $param = $param . "&client_secret=" . rawurlencode($this->clientsecret);
        $param = $param . "&code=" . rawurlencode($authCode);
        $param = $param . "&scope=" . rawurlencode($this->scope);
        $param = $param . "&redirect_uri=" . rawurlencode("");

        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec ($ch);
        curl_close ($ch);

        $obj = json_decode($result);

        $access_token = $obj->{'access_token'};

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$this->authorizationurl);
        curl_setopt($ch, CURLOPT_POST, true);

        $param = "grant_type=" . rawurlencode('one_access_token_identify');
        $param = $param . "&client_id=" . rawurlencode($this->clientid);
        $param = $param . "&client_secret=" . rawurlencode($this->clientsecret);
        $param = $param . "&scope=" . rawurlencode($this->scope);
        $param = $param . "&access_token=" . rawurlencode($access_token);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $results = curl_exec ($ch);
        curl_close ($ch);

        $result = json_decode($results);
        $user = $result->full_name;
        $pin = $result->pin;
        $tin = $result->tin;
        $email = $result->email;
        $phone = $result->mob_phone_no;

        $count = DB::table('users')->where('pin', '=', $pin)->count();
        $deleted_at = DB::table('users')->where('pin', '=', $pin)->get('deleted_at');
        //dd($deleted_at[0]->deleted_at);

        if ($count > 0) {
            if($deleted_at[0]->deleted_at !== null){
                return view('auth.login')->with('error', 'Ваш аккаунт был заблокирован');
            }
            User::where('pin', '=', $result->pin)->update(array('email' => $email, 'name' => $user, 'tin' => $tin, 'phone' => $phone));

            $user = User::where('pin', '=', $result->pin)->first();
            if (empty($user)) {
                abort(404, 'Пользователь не найден');
            }

            if(Auth::loginUsingId($user->id)){
                return redirect('home');
            }
        }else{
            return view('auth.register')->with('pin', $pin)->with('tin', $tin)->with('user', $user)->with('email', $email)->with('phone', $phone);
        }

        return redirect('login')->with('error', 'Oppes! You have entered invalid credentials');
    }

    public static function Login(User $user)
    {
        Auth::login($user);
        return redirect('home'); //user will be logged in and then redirect
    }


}
