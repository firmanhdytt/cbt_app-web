@extends('layouts.' . Auth::user()->role)

@section('title', 'Profil Saya')
@section('page-title', 'Pengaturan Akun & Profil')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Profil</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">
        
        <!-- Update Profile Info Card -->
        <div class="card-modern mb-4">
            @include('profile.partials.update-profile-information-form')
        </div>

        <!-- Update Password Card -->
        <div class="card-modern mb-4">
            @include('profile.partials.update-password-form')
        </div>

        <!-- Delete User Card -->
        <div class="card-modern border-danger border-opacity-25 mb-4">
            @include('profile.partials.delete-user-form')
        </div>
        
    </div>
</div>
@endsection
