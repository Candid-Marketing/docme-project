<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;

class InvoiceManage extends Component
{
    public $users = [];
    public $searchQuery = '';

    public function mount()
    {
        $this->users = User::all();
    }

    public function updatedSearchQuery()
    {
       if($this->searchQuery == ''){
            $this->users = User::all();
        }else{
            $this->searchUsers();
        }
    }


    public function searchUsers()
    {
        $this->users = User::query()
            ->join('invoices', 'invoices.user_id', '=', 'users.id')
            ->when($this->searchQuery, function ($query) {
                $query->where(function ($q) {
                    $q->where('users.first_name', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('users.last_name', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('invoices.invoice_file', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('invoices.invoice_date', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('invoices.amount', 'like', '%' . $this->searchQuery . '%');
                });
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.invoice-manage');
    }


}
