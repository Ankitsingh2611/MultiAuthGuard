<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use App\Models\Product;
use App\Models\{Country, State, City};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Hash;
use Session;
use Mail;
use DB;
use File;

class AdminController extends Controller
{
    public function dologin(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|max:15',
        ]);
        $credentials = $request->only('email','password');
        if(Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.home')->with('success','Welcome to admin dashboard.');
        } else {
            return redirect()->back()->with('error','Admin Login failed.');
        }
    }
    public function logout()
    {
        //echo "bye bye"; exit();
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
    public function update(Request $request)
    {
        //echo Auth::guard('web')->user()->email;
        //dd($request->all());
        $request->validate([
            'name' => 'required',
            'mobile' => 'required|min:10|numeric',
        ]);

        $data = Admin::where('email',Auth::guard('admin')->user()->email)->update([
            'name' => $request->name,
            'mobile' => $request->mobile,
        ]);

        if($data) {
            /*Mail::send('emails.adminAccountUpdateEmail', ['token' => 'token'], function($message) use($request){
                $message->to(Auth::guard('admin')->user()->email);
                $message->subject('Your account is updated successfully - email.');
            });*/
            return redirect()->route('admin.home')->with('success','You account is updated successfully.');
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

        $currentPasswordStatus = Hash::check($request->current_password, Auth::guard('admin')->user()->password);
        if($currentPasswordStatus){
            Admin::findOrFail(Auth::guard('admin')->user()->id)->update([
                'password' => Hash::make($request->password),
            ]);
            /*Mail::send('emails.adminChangePasswordEmail', ['password' => $request->current_password], function($message) use($request){
                $message->to(Auth::guard('admin')->user()->email);
                $message->subject('Your password is updated successfully - email.');
            });*/
            return redirect()->back()->with('success','Your password is updated successfully.');
        } else {
            return redirect()->back()->with('error','password update is failed.');
        }
    }
    public function users()
    {
        $data = User::get();
        return view('dashboard.admin.users',compact('data'));
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
    public function editUser($id)
    {
        $user = User::where("id",$id)->first();
        //echo "<pre>"; print_r($user); echo "</pre>"; exit();
        $countries = Country::get(["country_id","country_name"]);
        $states = State::where("country_id",$user['country'])->get(["state_id","state_name"]);
        $cities = City::where("state_id",$user['state'])->get(["city_id","city_name"]);
        return view('dashboard.admin.editUser',compact('user','countries','states','cities'));
    }

    public function updateUser(Request $request)
    {
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
        $user = User::where("id",$request->id)->first();

        if ($image = $request->file('photo')){
            $imageName = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();
            $image->move('images/uploads', $imageName);
            if(File::exists(public_path('images/uploads/'.$user['photo']))){
                File::delete(public_path('images/uploads/'.$user['photo']));
            }
        } else {
            $imageName = $user['photo'];
        }

        $token = Str::random(64);

        $data = User::where('email',$user['email'])->update([
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
            return redirect()->back()->with('success','User account is updated successfully.');
        } else {
            return redirect()->back()->with('error','User Account update is failed.');
        }
    }
    public function products(){
        $data = Product::get();
        return view('dashboard.admin.products',compact('data'));
    }

    public function add_product(){
        return view('dashboard.admin.add_product');
    }
    public function saveProduct(Request $request){

        $photoName = time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads/products'),$photoName);

        Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'photo' => $photoName,
            'price' => $request->price,
            'seller' => $request->seller,
        ]);
        return redirect('admin/add-product')->withSuccess('Product added successfully.');
    }
    public function orders(){
        $data = DB::table('orders')->get();
        return view('dashboard.admin.orders',compact('data'));
    }
    public function editProduct($id)
    {
        $data = Product::where("id",$id)->first();
        //echo "<pre>"; print_r($product); echo "</pre>"; exit();
        return view('dashboard.admin.editProduct',compact('data'));
    }
    public function updateProduct(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'seller' => 'required',
        ]);
        $product = Product::where("id",$request->id)->first();

        if ($image = $request->file('photo')){
            $imageName = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();
            $image->move('uploads/products/', $imageName);
            if(File::exists(public_path('uploads/products/'.$product['photo']))){
                File::delete(public_path('uploads/products/'.$product['photo']));
            }
        } else {
            $imageName = $product['photo'];
        }

        $data = Product::where('id',$product['id'])->update([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'photo' => $imageName,
            'price' => $request->price,
            'seller' => $request->seller,
        ]);

        if($data) {
            return redirect()->back()->with('success','Product has been updated successfully.');
        } else {
            return redirect()->back()->with('error','Update failed.');
        }
    }
    public function deleteProduct($id)
    {
        //echo $id; exit;
        $product = Product::where("id",$id)->first();
        if(File::exists(public_path('uploads/products/'.$product['photo']))){
            File::delete(public_path('uploads/products/'.$product['photo']));
        }
        $data = Product::where('id',$id)->delete();
        return redirect()->back()->with('success','Product has been deleted successfully.');
    }
}
