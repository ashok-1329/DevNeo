<?php
namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function all()
    {
        return User::with('role')->latest()->get();
    }

    public function store($data)
    {
        return User::create($data);
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function update($id, $data)
    {
        $user = $this->find($id);
        return $user->update($data);
    }

    public function delete($id)
    {
        return User::destroy($id);
    }
}
