<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;
use App\Employee;
use App\City;
use App\State;
use App\Country;
use App\Department;
use App\Division;

class InvasiveManagementController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
     protected $redirectTo = '/invasive-management';
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $users = User::paginate(5);

      return view('invasive/index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('invasive/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validateInput($request);
       User::create([
          'username' => $request['username'],
          'email' => $request['email'],
          'password' => bcrypt($request['password']),
          'firstname' => $request['firstname'],
          'lastname' => $request['lastname']
      ]);

      return redirect()->intended('/invasive-management');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $user = User::find($id);
      // Redirect to user list if updating user wasn't existed
      if ($user == null || count($user) == 0) {
          return redirect()->intended('/invasive-management');
      }

      return view('invasive/edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $user = User::findOrFail($id);
      $constraints = [
          'username' => 'required|max:20',
          'firstname'=> 'required|max:60',
          'lastname' => 'required|max:60'
          ];
      $input = [
          'username' => $request['username'],
          'firstname' => $request['firstname'],
          'lastname' => $request['lastname']
      ];
      if ($request['password'] != null && strlen($request['password']) > 0) {
          $constraints['password'] = 'required|min:6|confirmed';
          $input['password'] =  bcrypt($request['password']);
      }
      $this->validate($request, $constraints);
      User::where('id', $id)
          ->update($input);

      return redirect()->intended('/invasive-management');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      User::where('id', $id)->delete();
       return redirect()->intended('/invasive-management');
  }
    }

    /**
     * Search state from database base on some specific constraints
     *
     * @param  \Illuminate\Http\Request  $request
     *  @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
      $constraints = [
          'username' => $request['username'],
          'firstname' => $request['firstname'],
          'lastname' => $request['lastname'],
          'department' => $request['department']
          ];

     $users = $this->doSearchingQuery($constraints);
     return view('invasive/index', ['users' => $users, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints) {
      $query = User::query();
      $fields = array_keys($constraints);
      $index = 0;
      foreach ($constraints as $constraint) {
          if ($constraint != null) {
              $query = $query->where( $fields[$index], 'like', '%'.$constraint.'%');
          }

          $index++;
      }
      return $query->paginate(5);
    }

     /**
     * Load image resource.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function load($name) {
         $path = storage_path().'/app/avatars/'.$name;
        if (file_exists($path)) {
            return Response::download($path);
        }
    }

    private function validateInput($request) {
        $this->validate($request, [
            'lastname' => 'required|max:60',
            'firstname' => 'required|max:60',
            'middlename' => 'required|max:60',
            'address' => 'required|max:120',
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
            'zip' => 'required|max:10',
            'age' => 'required',
            'birthdate' => 'required',
            'date_hired' => 'required',
            'department_id' => 'required',
            'division_id' => 'required'
        ]);
    }

    private function createQueryInput($keys, $request) {
      $this->validate($request, [
      'username' => 'required|max:20',
      'email' => 'required|email|max:255|unique:users',
      'password' => 'required|min:6|confirmed',
      'firstname' => 'required|max:60',
      'lastname' => 'required|max:60'
  ]);
  }
}
