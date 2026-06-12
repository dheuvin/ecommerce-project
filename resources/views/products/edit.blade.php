@extends('layouts.app')

@section('content')
    @php
        $selectedParentId = old('category_id', $product->category?->parent_id ?? $product->category_id);
        $selectedSubcategoryId = old('subcategory_id', $product->category?->parent_id ? $product->category_id : '');
    @endphp

    <div class="container mt-4">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit Product</h4>
            </div>

            <div class="card-body">

                <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        <!-- Category -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <select id="category_id" name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror">

                                <option value="">Select Category</option>

                                @foreach ($categories as $parent)
                                    <option value="{{ $parent->id }}" @selected($selectedParentId == $parent->id)>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach

                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Subcategory -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Subcategory</label>

                            <select id="subcategory_id" name="subcategory_id" data-selected="{{ $selectedSubcategoryId }}"
                                class="form-select @error('subcategory_id') is-invalid @enderror">

                                <option value="">Select Subcategory</option>

                                @foreach ($categories as $parent)
                                    @foreach ($parent->children as $child)
                                        <option value="{{ $child->id }}" data-parent="{{ $parent->id }}"
                                            @selected($selectedSubcategoryId == $child->id)>
                                            {{ $child->name }}
                                        </option>
                                    @endforeach
                                @endforeach

                            </select>

                            @error('subcategory_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="row">

                        <!-- SKU -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                                class="form-control @error('sku') is-invalid @enderror">
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}"
                                class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">

                        <!-- Price -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" name="price" step="0.01" value="{{ old('price', $product->price) }}"
                                class="form-control @error('price') is-invalid @enderror">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stock -->
                        @php
                            $variantMap = $product->variants->keyBy('size');
                            $sizes = ['S', 'M', 'L', 'XL'];
                        @endphp

                        <div class="card mb-3">
                            <div class="card-header">
                                <strong>Size Variants</strong>
                            </div>

                            <div class="card-body">
                                <div class="row">

                                    @foreach ($sizes as $size)
                                        @php
                                            $variant = $variantMap[$size] ?? null;
                                        @endphp

                                        <div class="col-md-3 mb-3">
                                            <label class="form-label fw-bold">{{ $size }}</label>

                                            <input type="hidden" name="sizes[]" value="{{ $size }}">

                                            <input type="number" name="stock[]" min="0"
                                                value="{{ old('stock.' . $loop->index, $variant?->stock ?? 0) }}"
                                                class="form-control">

                                            <small class="text-muted">
                                                Current: {{ $variant?->stock ?? 0 }}
                                            </small>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Image -->
                    <div class="mb-3">
                        <label class="form-label">Main Image</label>

                        @if ($product->main_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $product->main_image) }}" width="100"
                                    class="rounded border">
                            </div>
                        @endif

                        <input type="file" name="main_image"
                            class="form-control @error('main_image') is-invalid @enderror">

                        @error('main_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <div>
                            @if ($product->status == 'draft')
                                <span class="badge bg-secondary">Draft</span>
                            @elseif($product->status == 'pending')
                                <span class="badge bg-warning">Pending Review</span>
                            @elseif($product->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($product->status == 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </div>
                        @if ($product->status === 'rejected' && $product->admin_note)
                            <div class="alert alert-danger mt-3 mb-0">
                                <strong>Rejection reason:</strong> {{ $product->admin_note }}
                            </div>
                        @endif
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            Update Product
                        </button>

                        <a href="{{ route('products.index') }}" class="btn btn-secondary px-4">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>

    <!-- JS for subcategory filter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const category = document.getElementById('category_id');
            const subcategory = document.getElementById('subcategory_id');
            const selectedSub = subcategory.dataset.selected;
            const options = Array.from(subcategory.querySelectorAll('option[data-parent]'));

            function filter(reset = false) {
                const catId = category.value;

                options.forEach(opt => {
                    opt.hidden = opt.dataset.parent !== catId;
                });

                if (reset) {
                    subcategory.value = '';
                    return;
                }

                if (selectedSub) {
                    const valid = options.find(o => o.value === selectedSub && !o.hidden);
                    subcategory.value = valid ? selectedSub : '';
                }
            }

            category.addEventListener('change', () => filter(true));
            filter();

        });
    </script>
@endsection
