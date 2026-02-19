<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

class MyAccount extends Component
{
    public $user;
    public $addresses;

    // Address Form Properties
    public $receiver_name;
    public $phone;
    public $address_detail; // renamed to avoid conflict with $address model
    public $city;
    public $province;
    public $postal_code;
    
    public $isAddingAddress = false;

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadAddresses();
    }
    
    public function loadAddresses()
    {
        $this->addresses = $this->user->addresses()->latest()->get();
    }
    
    public function toggleAddAddress()
    {
        $this->isAddingAddress = !$this->isAddingAddress;
        $this->resetForm();
    }
    
    public function resetForm()
    {
        $this->receiver_name = '';
        $this->phone = '';
        $this->address_detail = '';
        $this->city = '';
        $this->province = '';
        $this->postal_code = '';
    }

    public function saveAddress()
    {
        $this->validate([
            'receiver_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_detail' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
        ]);

        $this->user->addresses()->create([
            'receiver_name' => $this->receiver_name,
            'phone' => $this->phone,
            'address' => $this->address_detail, // map generic name back to db column
            'city' => $this->city,
            'province' => $this->province,
            'postal_code' => $this->postal_code,
        ]);

        $this->loadAddresses();
        $this->isAddingAddress = false;
        $this->resetForm();
        
        $this->dispatch('notify', message: 'Address saved successfully!');
    }
    
    public function deleteAddress($id)
    {
        $address = $this->user->addresses()->find($id);
        
        if ($address) {
            $address->delete();
            $this->loadAddresses();
            $this->dispatch('notify', message: 'Address deleted.');
        }
    }

    #[Layout('layouts.app')]
    #[Title('My Account')]
    public function render()
    {
        return view('livewire.my-account');
    }
}
