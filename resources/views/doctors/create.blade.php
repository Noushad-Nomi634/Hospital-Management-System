@extends('layouts.app')

@section('title', 'Add Doctor')

@push('css')
    <link href="{{ URL::asset('build/plugins/input-tags/css/tagsinput.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <!-- Card Header: White text for light theme -->
        <div class="card-header bg-primary">
            <h3 class="mb-0 text-white">Add Doctor</h3>
        </div>
        <div class="card-body">

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Add Doctor Form -->
            <form method="POST" action="{{ route('doctors.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Doctor Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="Enter full name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" placeholder="Enter email" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-control" placeholder="Enter phone number">
                </div>

                <div class="mb-3">
                    <label for="specialization" class="form-label">Specialization</label>
                    <input type="text" name="specialization" id="specialization" value="{{ old('specialization') }}" class="form-control" placeholder="Enter specialization" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Create Doctor</button>
                    <a href="{{ route('doctors.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('script')
    <!-- Plugins -->
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/input-tags/js/tagsinput.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/main.js') }}"></script>
@endpush
