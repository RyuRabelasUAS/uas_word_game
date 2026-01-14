@extends('layouts.admin')

@section('title', 'Create Category')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Create New Category</h2>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">Back to Categories</a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Category Name *</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid rgba(255, 255, 255, 0.05);">
                <button type="submit" class="btn btn-success" style="margin-right: 1rem;">Create Category</button>
                <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('admin.categories.index') }}'">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
