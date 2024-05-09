<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\{Country, State, City};
use App\Models\UserVerify;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Hash;
use Session;
use Mail;
use DB;
use File;

class UserController extends Controller
{
    public function register()
    {
        $data['countries'] = Country::get(["country_id","country_name"]);
        return view('dashboard.user.register', $data);
    }
    public function getStates(Request $request)
    {
        $data['states'] = State::where("country_id",$request->country_id)->get(["state_id","state_name"]);
        return response()->json($data);
    }
    public function getCities(Request $request)
    {
        $data['cities'] = City::where("state_id",$request->state_id)->get(["city_id","city_name"]);
        return response()->json($data);
    }


    public function create(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'photo' => 'required',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|min:10|numeric',
            'password' => 'required|min:6|max:15',
            'cpassword' => 'required|same:password',
            'country_list' => 'required',
            'states_list' => 'required',
            'cities_list' => 'required',
            'address' => 'required',
            'pincode' => 'required|min:6|numeric',
            'captcha' => 'required|captcha',

        ],[
            'cpassword.required' => 'The Confirm Password is Required.',
            'cpassword.same' => 'The Password and Confirm Password must be same.',
            'captcha.captcha'=>'Invalid captcha code.',
        ]);

        $imageName = '';
        if ($image = $request->file('photo')){
            $imageName = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();
            $image->move('images/uploads', $imageName);
        }
        $token = Str::random(64);

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->photo = $imageName;
        $user->mobile = $request->mobile;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->gender = $request->gender;
        $user->country = $request->country_list;
        $user->state = $request->states_list;
        $user->city = $request->cities_list;
        $user->address = $request->address;
        $user->pincode = $request->pincode;
        $user->hobby = json_encode($request->hobby);
        $user->token = $token;
        $data = $user->save();
        if($data) {
            // $user_id = $user->id;
            // $user_verify = new UserVerify();
            // $user_verify->user_id = $user_id;
            // $user_verify->token = $token;
            // $data2 = $user_verify->save();

            Mail::send('emails.activationEmail', ['token' => $token], function($message) use($request){
                $message->to($request->email);
                $message->subject('Email Verification Mail');
            });

            return redirect()->route('user.login')->with('success','You are registered successfully. Activate your account for login. Check your email inbox/spam folder for Activation Link.');
        } else {
            return redirect()->back()->with('error','Registration failed.');
        }
    }

    public function verifyAccount($token)
    {
        //$verifyUser = UserVerify::where('token', $token)->first();
        $verifyUser = User::where('token', $token)->first();
        $message = 'Sorry your email cannot be identified.';

        if(!is_null($verifyUser) ){
            $userupdate = User::where('token',$token)->update([
                'is_active' => 1
            ]);
            $message = "Your account is activated successfully.";
        }

      return redirect()->route('user.login')->with('message', $message);
    }

    public function dologin(Request $request)
    {
        //dd($request->all());

        $checkIsActive = User::where('is_active', 1)->where('email', $request->email)->first();
        if(is_null($checkIsActive) ){
            return redirect()->route('user.login')->with('error','Your account is not active. Please check your email for activation link.');
        } else {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6|max:15',
            ]);
            $credentials = $request->only('email','password');
            if(Auth::guard('web')->attempt($credentials)) {
                return redirect()->route('user.home')->with('success','Welcome to user dashboard.');
            } else {
                return redirect()->back()->with('error','Login failed.');
            }
        }
    }
    public function logout()
    {
        //echo "bye bye"; exit();
        Auth::guard('web')->logout();
        return redirect()->route('user.login');
    }
    public function editprofile()
    {
        $countries = Country::get(["country_id","country_name"]);
        $states = State::where("country_id",Auth::guard('web')->user()->country)->get(["state_id","state_name"]);
        $cities = City::where("state_id",Auth::guard('web')->user()->state)->get(["city_id","city_name"]);
        return view('dashboard.user.editprofile',compact('countries', 'states','cities'));
    }
    public function refreshCaptcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }
    public function forgotpwdpost(Request $request)
    {
        $request->validate([
            'email'=>'required|email|max:50',
        ]);
        $token = Str::random(64);
        DB::table('resetpasword')->insert([
            'email' => $request->email,
            'token' => $token
        ]);

        Mail::send('emails.resetPasswordEmail',['token'=>$token],function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password Email from Chirags.in');
        });

        return redirect('user/forgot_password')->withSuccess('Reset password link sent to your email address. Please check your Inbox/Spam Folder for reset password link.');
    }

    public function resetPassword($token)
    {
        return view('dashboard.user.resetpassword',['token'=>$token]);
    }
    public function resetPasswordSubmit(Request $request)
    {

        //dd($request->all());
        $request->validate([
            'email'=>'required|email|max:50',
            'password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required'
        ]);

        $resetPasswordCheck = DB::table('resetpasword')
                            ->where([
                                'email' => $request->email,
                                'token'=> $request->token
                            ])->first();

        if(!$resetPasswordCheck) {
            return redirect('reset-password/'.$request->token)->withSuccess('Reset Password Link is expired.');
        }

        $userPasswordUpdate  = User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        //delete reset password data from resetpasword table
        DB::table('resetpasword')->where(['email'=>$request->email])->delete();

        Mail::send('emails.updateResetPwdEmail',['password'=>$request->password],function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password is updated successfully - Chirags.in');
        });

        return redirect('user/login')->withSuccess('your password is reset successfully. Please login with new password.');

    }

    public function update(Request $request)
    {
        //echo Auth::guard('web')->user()->email;
        //dd($request->all());
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile' => 'required|min:10|numeric',
            'country_list' => 'required',
            'states_list' => 'required',
            'cities_list' => 'required',
            'address' => 'required',
            'pincode' => 'required|min:6|numeric',
        ]);

        if ($image = $request->file('photo')){
            $imageName = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();
            $image->move('images/uploads', $imageName);
            if(File::exists(public_path('images/uploads/'.Auth::guard('web')->user()->photo))){
                File::delete(public_path('images/uploads/'.Auth::guard('web')->user()->photo));
            }
        } else {
            $imageName = Auth::guard('web')->user()->photo;
        }

        $token = Str::random(64);

        $data = User::where('email',Auth::guard('web')->user()->email)->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'photo' => $imageName,
            'mobile' => $request->mobile,
            'gender' => $request->gender,
            'country' => $request->country_list,
            'state' => $request->states_list,
            'city' => $request->cities_list,
            'address' => $request->address,
            'pincode' => $request->pincode,
            'hobby' => json_encode($request->hobby)
        ]);

        if($data) {
            Mail::send('emails.accountUpdateEmail', ['token' => $token], function($message) use($request){
                $message->to(Auth::guard('web')->user()->email);
                $message->subject('Your account is updated successfully - email.');
            });
            return redirect()->route('user.home')->with('success','You account is updated successfully.');
        } else {
            return redirect()->back()->with('error','Account update is failed.');
        }
    }
    public function password_update(Request $request)
    {
        //echo Auth::guard('web')->user()->email;
        //dd($request->all());
        $request->validate([
            'current_password' => 'required|min:6|max:15',
            'password' => 'required|min:6|max:15',
            'cpassword' => 'required|same:password'

        ],[
            'cpassword.required' => 'The Confirm Password is Required.',
            'cpassword.same' => 'The Password and Confirm Password must be same.'
        ]);

        $currentPasswordStatus = Hash::check($request->current_password, Auth::guard('web')->user()->password);
        if($currentPasswordStatus){
            User::findOrFail(Auth::guard('web')->user()->id)->update([
                'password' => Hash::make($request->password),
            ]);
            Mail::send('emails.changePasswordEmail', ['password' => $request->current_password], function($message) use($request){
                $message->to(Auth::guard('web')->user()->email);
                $message->subject('Your password is updated successfully - email.');
            });
            return redirect()->back()->with('success','Your password is updated successfully.');
        } else {
            return redirect()->back()->with('error','password update is failed.');
        }
    }


    public function products() {
        $user = Auth::guard('web')->user();
        $product = Product::all();
        return view('dashboard.user.products',compact('product','user'));
    }



    public function cart()
    {
        $user = Auth::guard('web')->user();
        return view('dashboard.user.cart',compact('user'));
    }

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart',[]);
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name' => $product->name,
                'quantity' => 1,
                'category' => $product->category,
                'description' => $product->description,
                'price' => $product->price,
                'seller' => $product->seller
            ];
        }
        session()->put('cart',$cart);
        return redirect('user/cart')->withSuccess('Product added in cart successfully.');
    /*$data = Product::where('id',$id)->first();
        return view('admin.editProduct',compact('data'));*/
    }
    public function deleteCartProduct(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart',$cart);
            }
            session()->flash('success','Product is deleted from your cart.');
        }
    }

    /*if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart',$cart);
            }
            session()->flash('success','Product is deleted from your cart.');
        }*/

        public function order()
        {

            //check session


            $cart = session()->get('cart',[]);
            $user = Auth::guard('web')->user();

            $total=0;
            foreach ($cart as $id => $product )
            {
                $total = $total + $product['price']*$product['quantity'];
            }
            $orders_id = DB::table('orders')->insertGetId([
                'name' => $user->name,
                'email' => $user->email,
                'address' => $user->address,
                'total_amount' => $total
            ]);

            //$sub_total=0;
            foreach ($cart as $id => $product )
            {
                //$sub_total=$sub_total + ;
                DB::table('orders_details')->insert([
                    'orders_id' => $orders_id,
                    'product_name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $product['quantity'],
                    'sub_amount' => $product['price']*$product['quantity']
                ]);
            }
            return redirect('user/home');

        }

        public function orderSave(Request $request)
        {
            //dd($request->all());
            $random = rand(00000000,99999999);

            $cart = session()->get('cart',[]);
            $user = Auth::guard('web')->user();

            $total=0;
            foreach ($cart as $id => $product )
            {
                $total = $total + $product['price']*$product['quantity'];
            }
            $orders_id = DB::table('orders')->insertGetId([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'total_amount' => $total,
                'payment_id' =>$random,
                //'payment_status'=> 'pending',
                //'payment_date' =>'',
                //'payment_card_details' => '',
            ]);

            //$sub_total=0;
            foreach ($cart as $id => $product )
            {
                //$sub_total=$sub_total + ;
                DB::table('orders_details')->insert([
                    'orders_id' => $orders_id,
                    'product_name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $product['quantity'],
                    'sub_amount' => $product['price']*$product['quantity']
                ]);
            }
            $cart = session()->get('cart');
            unset($cart);
            return redirect('user/payment/'.$random);

        }
        public function payment($payment_id)
        {
            //echo $payment_id; exit;

            $user = Auth::guard('web')->user();
            $orders_details = DB::table('orders')->where('payment_id','=',$payment_id)->first();
            return view('dashboard.user.payment',compact('user','orders_details','payment_id'));
        }

        public function paymentSave(Request $request)
        {
            //dd($request->all());
            //card_details
            $payment_id = $request->payment_id;
            $card_number = $request->card_number;
            $expire_date = $request->expire_date;
            $cvv = $request->cvv;
            $validatePayment = DB::table('card_details')->where('card_number','=',$card_number)->where('expire_date','=',$expire_date)->where('cvv','=',$cvv)->first();
            //echo "card number : ".$validatePayment->card_number; exit;
            if($validatePayment->card_number !='') {
                $paymentupdate = DB::table('orders')->where('payment_id','=',$payment_id)->update(
                    [
                        'payment_status' => 'Complete',
                        'payment_date' => date('Y-m-d'),
                        'payment_card_details' => $card_number,
                    ]
                );
                return redirect('user/orders');
            } else {
                return redirect('user/payment/'.$payment_id);
            }
        }
        public function orders(){
            $data = DB::table('orders')->where('email',Auth::guard('web')->user()->email)->get();
            return view('dashboard.user.orders',compact('data'));
        }

        public function order_details($orders_id){
            $data = DB::table('orders')->where('orders_id',$orders_id)->first();
            //echo $data->orders_id; exit;
            $order_details = DB::table('orders_details')->where('orders_id',$orders_id)->get();
            return view('dashboard.user.order_details',compact('data','order_details'));
        }
}
