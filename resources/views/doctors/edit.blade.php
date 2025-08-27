@extends('layouts.app')

@section('title', 'Edit Doctor')

@push('css')
    <link href="{{ URL::asset('build/plugins/input-tags/css/tagsinput.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="text-white">Edit Doctor</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('doctors.update', $doctor->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Doctor Name</label>
                    <input type="text" name="name" id="name" value="{{ $doctor->name }}" class="form-control" placeholder="Enter full name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ $doctor->email }}" class="form-control" placeholder="Enter email" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ $doctor->phone }}" class="form-control" placeholder="Enter phone number" required>
                </div>

                <div class="mb-3">
                    <label for="specialization" class="form-label">Specialization</label>
                    <input type="text" name="specialization" id="specialization" value="{{ $doctor->specialization }}" class="form-control" placeholder="Enter specialization" required>
                </div>

                <button type="submit" class="btn btn-success">Update Doctor</button>
                <a href="{{ route('doctors.index') }}" class="btn btn-secondary ms-2">Cancel</a>
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
