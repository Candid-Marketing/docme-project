<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserAddedMail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;

class UserManage extends Component
{

    public $users = [];
    public $first_name, $last_name, $email, $user_status, $is_verified, $email_verified, $password;
    public $isModalOpen = false;
    public $searchQuery = '';

    public function mount()
    {
        $this->users = User::where('user_status', 3)->get();
    }
    public function updatedSearchQuery()
    {
        $this->searchUsers();
        if($this->searchQuery == ''){
            $this->users = User::where('user_status', 3)->get();
        }else{
            $this->searchUsers();
        }
    }

    public function searchUsers()
    {
        $this->users = User::query()
        ->join('invoices', 'invoices.user_id', '=', 'users.id')
        ->when($this->searchQuery, function($query) {
            $query->where(function($q) {
                // Search by first name, last name, or email
                $q->where('first_name', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('last_name', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('email', 'like', '%' . $this->searchQuery . '%')
                  // Search by user status (Admin, User, Guest)
                  ->orWhere(function($q) {
                      $q->where('user_status', 1)
                        ->whereRaw("CONCAT('Admin') like ?", ["%{$this->searchQuery}%"])
                        ->orWhere('user_status', 2)
                        ->whereRaw("CONCAT('User') like ?", ["%{$this->searchQuery}%"])
                        ->orWhere('user_status', 3)
                        ->whereRaw("CONCAT('Guest') like ?", ["%{$this->searchQuery}%"]);
                  })
                  ->orWhere(function($q) {
                      $q->where('is_verified', 1)
                        ->whereRaw("CONCAT('Verified') like ?", ["%{$this->searchQuery}%"])
                        ->orWhere('is_verified', 0)
                        ->whereRaw("CONCAT('Not Verified') like ?", ["%{$this->searchQuery}%"]);
                  })
                  ->orWhere(function($q) {
                    $q->where('status', 1)
                      ->whereRaw("CONCAT('Settled') like ?", ["%{$this->searchQuery}%"])
                      ->orWhere('status', 0)
                      ->whereRaw("CONCAT('Pending') like ?", ["%{$this->searchQuery}%"]);
                });
            });
        })
        ->get();
    }

    public function render()
    {
        return view('livewire.user-manage');
    }

    public function addUser()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'user_status' => 'required|in:1,2,3',
        ]);

        // Insert user into the database
        User::insert([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'user_status' => $this->user_status,
            'is_verified' => 0,
            'password' => Hash::make('password123'),
            'expiration_date' => now()->addDays(7),
            'created_at' => now(),

        ]);
        $user = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'user_status' => $this->user_status,
        ];
        Mail::to($this->email)->send(new UserAddedMail($user));
        $this->resetForm();
        $this->emit('userAddedSuccessfully');
        session()->flash('success', 'User successfully added!');
    }



    public function prepareEdit($user_id)
    {
        $user = User::findOrFail($user_id);
        $this->user_id = $user->id;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->user_status = $user->user_status;
    }


    public function updateUser()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'user_status' => 'required|in:1,2,3',
        ]);

        $user = User::findOrFail($this->user_id);
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->email = $this->email;
        $user->user_status = $this->user_status;
        $user->save();

        $this->resetForm();
        $this->emit('userUpdated');
    }


    public function deleteUser($user_id)
    {

        $user = User::findOrFail($user_id);
        $user->delete();
        $this->emit('userDeleted');
    }

    private function resetForm()
    {
        $this->first_name = '';
        $this->last_name = '';
        $this->email = '';
        $this->user_status = '';
        $this->user_id = null;
    }

    public function refreshComponent()
    {
        $this->emit('refresh');
    }

}
