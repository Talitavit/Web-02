<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;

class PostPolicy
{
    public function view(User $user, Post $post)
    {
        return $user->role === 'admin' || $user->role === 'bibliotecario' || $user->role === 'cliente';
    }

    public function create(User $user)
    {
        return $user->role === 'admin' || $user->role === 'bibliotecario';
    }

    public function update(User $user, Post $post)
    {
        return $user->id === $post->user_id || $user->role === 'admin';
    }

    public function delete(User $user, Post $post)
    {
        return $user->id === $post->user_id || $user->role === 'admin';
    }
}