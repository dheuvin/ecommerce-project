@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Add Product</h3>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Product Name --}}
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name"
                value="{{ old('name') }}"
                class="form-control @error('name') is-invalid @enderror">

            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- SKU --}}
        <div class="mb-3">
            <label class="form-label">SKU</label>
            <input type="text" name="sku"
                value="{{ old('sku') }}"
                class="form-control @error('sku') is-invalid @enderror">

            @error('sku')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Category --}}
        <div class="mb-3">
            <label class="form-label">Category</label>

            <select id="category_id"
                name="category_id"
                class="form-select @error('category_id') is-invalid @enderror">

                <option value="">Select Category</option>

                @foreach($categories as $parent)
                    <option value="{{ $parent->id }}"
                        @selected(old('category_id') == $parent->id)>
                        {{ $parent->name }}
                    </option>
                @endforeach

            </select>

            @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Subcategory --}}
        <div class="mb-3">
            <label class="form-label">Subcategory</label>

            <select id="subcategory_id"
                name="subcategory_id"
                data-selected="{{ old('subcategory_id') }}"
                class="form-select @error('subcategory_id') is-invalid @enderror">

                <option value="">Select Subcategory</option>

                @foreach($categories as $parent)
                    @foreach($parent->children as $child)
                        <option value="{{ $child->id }}"
                            data-parent="{{ $parent->id }}"
                            @selected(old('subcategory_id') == $child->id)>
                            {{ $child->name }}
                        </option>
                    @endforeach
                @endforeach

            </select>

            @error('subcategory_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label class="form-label">Description</label>

            <textarea name="description"
                rows="4"
                class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Price --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Price</label>

                <input type="number"
                    name="price"
                    step="0.01"
                    min="0"
                    value="{{ old('price') }}"
                    class="form-control @error('price') is-invalid @enderror">

                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Size Variants --}}
        <div class="card mb-4">
            <div class="card-header">
                <strong>Size Variants</strong>
            </div>

            <div class="card-body">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th width="150">Size</th>
                            <th>Stock</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach(['S','M','L','XL'] as $size)
                            <tr>
                                <td>
                                    {{ $size }}
                                    <input type="hidden"
                                        name="sizes[]"
                                        value="{{ $size }}">
                                </td>

                                <td>
                                    <input type="number"
                                        name="stock[]"
                                        min="0"
                                        class="form-control"
                                        placeholder="Enter stock">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Main Image --}}
        <div class="mb-3">
            <label class="form-label">Main Image</label>

            <input type="file"
                name="main_image"
                class="form-control @error('main_image') is-invalid @enderror">

            @error('main_image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Gallery Images --}}
        <div class="mb-3">
            <label class="form-label">Gallery Images (Max 4)</label>

            <input type="file"
                name="images[]"
                multiple
                class="form-control">

            @error('images')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            @error('images.*')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        {{-- Buttons --}}
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                Save Product
            </button>

            <a href="{{ route('products.index') }}"
                class="btn btn-secondary">
                Cancel
            </a>
        </div>

    </form>
</div>
@endsection
