<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class Login extends Component
{
    public string $username = '';

    public string $password = '';

    public bool $remember = false;

    protected $rules = [
        'username' => 'required',
        'password' => 'required',
    ];

    public function authenticate()
    {
        $this->validate();

        if (Auth::attempt(['username' => $this->username, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        $this->addError('username', 'Kredensial yang diberikan tidak cocok dengan data kami.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
