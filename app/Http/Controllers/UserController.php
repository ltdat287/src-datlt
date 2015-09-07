<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Requests\UserSearchFormRequest;
use App\Http\Requests\UserAddFormRequest;
use App\Http\Requests\UserEditFormRequest;
use Input;
use App\Helpers\MemberHelper;
use Session;
use Redirect;
use Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // List user employ with non disable DESC </dg>;
        $users = User::getUsers();

        // Redirect to user detail if has employ role.
        if (MemberHelper::getCurrentUserRole() == 'employee')
        {
            $users->where('id', '=', Auth::user()->id);
        }
        
        // Only listing employ of current user own
        if (MemberHelper::getCurrentUserRole() == 'boss')
        {
            $users->where('boss_id', '=', Auth::user()->id);
        }
        
        //max record display on page
        $users = $users->paginate(VP_LIMIT_PAGINATE);
        
        // Set data for view
        $data = array(
            'users'    => $users,
        );
        
        return view('members.top', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Clear user session.
        //Session::forget('user');
        
        // create array roles to display
        $roles = array(
            'admin'    => ADMIN,
            'boss'     => BOSS,
            'employee' => EMPLOYEE
        );
        
        // Get bosses
        $bosses = User::getBosses()->get();

        // Get user session
        $user = Session::get('user');
        
        // Build data for views
        $data = array(
            'roles'  => $roles,
            'bosses' => $bosses,
            'user'   => $user
        );

        return view('members.add', $data);
    }

    /**
     * [POST] Show the member add confirm view.
     *
     * @param UserAddFormRequest $request
     * @return \Illuminate\View\$this
     */
    public function add_conf(UserAddFormRequest $request)
    {
        // Get user data
        $data = self::_confirmUser($request);
        
        return view('members.add_conf', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        // Get user from session
        $user = Session::get('user');
        
        if (isset(Input::all()['back']) || empty($user)) {
            return Redirect::route('add');
        } else {
            $record = new User();
            $record = self::_saveUser($record, $user);
            
            // Clear session
            Session::forget('user');
            
            $message = self::_linkToDetail($record->id) . trans('として追加しました。');
            $data = array(
                'label'   => trans('追加（完了）'),
                'message' => $message
            );
            
            return view('members.common.member_comp', $data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        // Clear user session.
        Session::forget('user');
        
        $user = User::find($id);
        
        // Get role.
        $role = $user->role;
        
        // Get boss.
        $boss = User::find($user->boss_id);
        
        // Prepare data for view.
        $data = array (
            'user' => $user,
            'role' => $role,
            'boss' => $boss,
            'id'   => $id
        );
        
        return view('members.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        // Clear user session.
        Session::forget('user');
        
        // Get roles
        
        $roles = array(
            'admin'    => ADMIN,
            'boss'     => BOSS,
            'employee' => EMPLOYEE,
        );
        
        // Get bosses
        $bosses = User::getBosses()->get();
        
        // Get user from db
        $user = User::find($id);
        
        // Get user session
        if (Session::has('user'))
        {
            $user = Session::get('user');
        } else {
            Session::put('user', $user);
        }
        
        // Prepare data for view.
        $data = array(
            'id'     => $id,
            'roles'  => $roles,
            'bosses' => $bosses,
            'user'   => $user
        );
        
        return view('members.edit', $data);
    }

    /**
     * [POST] Process validation form edit member submit
     * 
     * @param integer $id
     * @param UserEditFormRequest $request
     */
    public function edit_conf($id, UserEditFormRequest $request)
    {
        // Get user data
        $data = self::_confirmUser($request);
        
        $data['id'] = $id;
        
        return view('members.edit_conf', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        // Get user from session.
        $user   = Session::get('user');

        $record = User::find($id);
        if (! $record) {
            return Redirect::route('not_found');
        }
        $record = self::_updateUser($record, $user);
        
        // Clear session
        Session::forget('user');
        
        $message = self::_linkToDetail($record->id) . trans('として追加しました。');
        $data = array(
            'label' => trans('追加（完了）'),
            'message' => $message
        );
        
        return view('members.common.member_comp', $data);
    }

    /**
     * [POST] Delete user.
     * 
     * @param integer $id
     * @return \Illuminate\View\View
     */
    public function delete_conf($id)
    {
        // Clear user session.
        Session::forget('user');
        
        // Get user.
        $user = User::find($id);
        
        // Get role.
        $role = $user->role;
         
        // Get boss.
        $boss = User::find($user->boss_id);
        
        $errors = array();
        // Check current role is BOSS and not same boss_id of user delete.
        if (MemberHelper::getCurrentUserRole() == 'boss' && $user->boss_id != Auth::user()->id)
        {
            $errors[] = trans('validation.user_not_delete_boss');
        }
        
        // Prepare data for view.
        $data = array(
            'id'     => $id,
            'user'   => $user,
            'role'   => $role,
            'boss'   => $boss,
            'errors' => $errors
        );
        
        return view('members.delete_conf', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $record = User::find($id);
        
        // Destroy 
        $record->disabled = true;
        $record->save();
        
        $data = array(
            'label' => trans('削除（完了）'),
            'message' => trans('削除しました。')
        );
        
        return view('members.common.member_comp', $data);
    }

    /**
     * Show the page search user 
     * @param  UserSearchFormRequest $request [/search?{search_query}]
     * @return [view ('members.search')] 
     */
    public function search(UserSearchFormRequest $request)
    {
        $users = null;
        $arr_cons = $arr_vals = null;
        $arr_define = array('name', 'email', 'kana', 'telephone_no');
        
        // Check roles checked
        $user_ids = array();
        $arr_checked = ['admin', 'boss', 'employee'];
        foreach ($arr_checked as $checked)
        {
            if (Input::has($checked) && Input::get($checked) == 1)
            {
                $users = User::getUsers()->where('role', '=', $checked)->get();
                foreach ($users as $user)
                {
                    if (! in_array($user->id, $user_ids))
                    {
                        $user_ids[] = $user->id;
                    }
                }
            }  
        }

        /*  */
        foreach ($arr_define as $define)
        {
            if (Input::has($define))
            {
                $arr_cons[] = $define . ' = ?';
                $arr_vals[] = Input::get($define);
            }
        }
        
        // Check conditions exists.
        if ($arr_cons)
        {
            $cons = implode(' AND ', $arr_cons);
        }
        
        // Get users with user ids.
        $users = User::getUsers();
        
        // Only listing employ of current user own
        if (MemberHelper::getCurrentUserRole() == 'boss')
        {
            $users->where('boss_id', '=', Auth::user()->id);
        }
        
        if (count($user_ids))
        {
            $users = $users->whereIn('id', $user_ids);
        }
        
        // Check exists search conditions.
        if (count($arr_cons))
        {
            $users = $users->whereRaw($cons, $arr_vals);
        }

        // Paginate users.
        if (count($users))
        {
            $users = $users->paginate(VP_LIMIT_PAGINATE)->setPath('search');
        }
        
        // Get role.
        $roles = array(
            'admin' => ADMIN,
            'boss' => BOSS,
            'employee' => EMPLOYEE
        );
        
        // Build data for view.
        $data = array(
            'users' => $users,
            'roles' => $roles
        );
        
        return view('members.search', $data);
    }

    /**
     * prepare user data for confirm view.
     * 
     * @param object $request
     * @return array
     */
    private static function _confirmUser($request)
    {
        // Prepare cache data.
        $user = new \stdClass;
        $user->email              = $request->get('email');
        $user->email_confirmation = $request->get('email_confirmation');
        $user->name               = $request->get('name');
        $user->kana               = $request->get('kana');
        $user->password           = $request->get('password');
        $user->telephone_no       = $request->get('telephone_no');
        $user->birthday           = $request->get('birthday');
        $user->note               = ($request->get('note')) ? $request->get('note') : '';
        $user->role               = $request->get('use_role');

        if (MemberHelper::getCurrentUserRole() == 'boss')
        {
            $user->boss_id = MemberHelper::checkLogin()->id;
        }
        else
        {
            $user->boss_id = $request->get('boss_id');
        }
        
        // Get role.
        $role = $user->role;
        
        // Get boss.
        $boss = User::find($user->boss_id);
        
        // Set user from session.
        Session::put('user', $user);
        
        // Prepare data for view.
        $data = array (
            'user' => $user,
            'role' => $role,
            'boss' => $boss
        );
        
        return $data;
    }

    /**
     * Get link to member detail page.
     * 
     * @param string $userId
     * @return string
     */
    private static function _linkToDetail($userId = '')
    {
        return html_entity_decode(trans('ID') . ': <a href="' . url('/member/' . $userId . '/detail') . '">' . $userId . '</a>');
    }

    /**
     * Common function for save user.
     * 
     * @param object $record
     * @param object $user
     * @return object
     */
    private static function _saveUser(&$record, $user)
    {
        if (! $user) {
            return null;
        }
        
        // Build data.
        $record->email        = $user->email;
        $record->name         = $user->name;
        $record->kana         = $user->kana;
        $record->password     = bcrypt($user->password);
        $record->telephone_no = $user->telephone_no;
        $record->birthday     = $user->birthday;
        $record->note         = ($user->note) ? $user->note : '';
        if (MemberHelper::getCurrentUserRole() == 'boss')
        {
            $user->role  = 'employee';
            $record->boss_id = MemberHelper::checkLogin()->id;
        }
        else
        {
            if ($user->role == 'employee') {
                $record->boss_id = (int) $user->boss_id;
            } else {
                $record->boss_id = 0;
            }
        }
        
        // Add role for user.
        if (MemberHelper::getCurrentUserRole() != 'employee')
        {
            $record->role = $user->role;
        }

        $record->save();
        
        return $record;
    }

    /**
     * Common function for update user.
     * 
     * @param object $record
     * @param object $user
     * @return object
     */
    private static function _updateUser(&$record, $user)
    {
        if (! $user) {
            return null;
        }
        
        // Build data.
        $record->name         = $user->name;
        $record->kana         = $user->kana;
        $record->password     = bcrypt($user->password);
        $record->telephone_no = $user->telephone_no;
        $record->birthday     = $user->birthday;

        if (MemberHelper::getCurrentUserRole() != 'employee')
        {
            $record->email        = $user->email;
            $record->note         = ($user->note) ? $user->note : '';
            if (MemberHelper::getCurrentUserRole() == 'boss')
            {
                $user->role  = 'employee';
                $record->boss_id = MemberHelper::checkLogin()->id;
            }
            else
            {
                if ($user->role == 'employee') {
                    $record->boss_id = (int) $user->boss_id;
                } else {
                    $record->boss_id = 0;
                }
            }
            
            // Add role for user.
            $record->role = $user->role;
        }
        
        $record->save();
        
        return $record;
    }
}
