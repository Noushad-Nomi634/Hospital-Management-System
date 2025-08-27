@extends('layouts.app')

@section('title')
    Doctors
@endsection

@push('css')
    <link href="{{ URL::asset('build/plugins/input-tags/css/tagsinput.css') }}" rel="stylesheet">
@endpush

@section('content')
<x-page-title title="Doctors" subtitle="List of all doctors" />

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f8f9fa;">
                <h5 class="mb-0 text-dark">Doctors List</h5>
                <a href="{{ route('doctors.create') }}" 
                   class="btn btn-primary btn-lg" 
                   style="font-weight: 500;">
                   Add Doctor
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Specialization</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctors as $doctor)
                                <tr>
                                    <td>{{ $doctor->name }}</td>
                                    <td>{{ $doctor->email }}</td>
                                    <td>{{ $doctor->specialization }}</td>
                                    <td>
                                        <div class="d-flex gap-2 flex-wrap">
                                            {{-- Edit Button --}}
                                            <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                            
                                            {{-- Delete Button --}}
                                            <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this doctor?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                            
                                            {{-- Availability Button (Last) --}}
                                            <a href="{{ route('doctors.availability', $doctor->id) }}" class="btn btn-sm btn-info">Availability</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/input-tags/js/tagsinput.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/main.js') }}"></script>

    <script>
        // Perfect Scrollbar for responsive tables
        new PerfectScrollbar('.table-responsive');
    </script>
@endpush
