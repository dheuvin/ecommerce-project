@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-3">Add Product</h3>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">SKU</label>
                <input type="text" name="sku" value="{{ old('sku') }}"
                    class="form-control @error('sku') is-invalid @enderror" required>
                @error('sku')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                    <option value="">Select Category</option>
                    @foreach ($categories as $parent)
                        <option value="{{ $parent->id }}" @selected(old('category_id') == $parent->id)>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Subcategory</label>
                <select id="subcategory_id" name="subcategory_id" data-selected="{{ old('subcategory_id') }}"
                    class="form-select @error('subcategory_id') is-invalid @enderror">
                    <option value="">Select Subcategory</option>
                    @foreach ($categories as $parent)
                        @foreach ($parent->children as $child)
                            <option value="{{ $child->id }}" data-parent="{{ $parent->id }}"
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

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" name="price" step="0.01" min="0" value="{{ old('price') }}"
                        class="form-control @error('price') is-invalid @enderror" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" min="0" value="{{ old('stock') }}"
                        class="form-control @error('stock') is-invalid @enderror" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Main Image</label>
                <input type="file" name="main_image" class="form-control @error('main_image') is-invalid @enderror">
                @error('main_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Gallery Images (Max 4)</label>
                <input type="file" name="images[]" multiple
                    class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror">
                @error('images')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @error('images.*')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror">
                    <option value="1" @selected(old('status', '1') == '1')>Active</option>
                    <option value="0" @selected(old('status') == '0')>Inactive</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-success">Save Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const category = document.getElementById('category_id');
            const subcategory = document.getElementById('subcategory_id');
            const selectedSubcategory = subcategory.dataset.selected;
            const options = Array.from(subcategory.querySelectorAll('option[data-parent]'));

            function filterSubcategories(resetSelection = false) {
                const categoryId = category.value;

                options.forEach((option) => {
                    const isVisible = option.dataset.parent === categoryId;
                    option.hidden = !isVisible;
                });

                if (resetSelection) {
                    subcategory.value = '';
                    return;
                }

                if (selectedSubcategory) {
                    const activeOption = options.find((option) => option.value === selectedSubcategory && !option
                        .hidden);
                    subcategory.value = activeOption ? selectedSubcategory : '';
                    return;
                }

                const currentOption = options.find((option) => option.value === subcategory.value);
                if (currentOption && currentOption.hidden) {
                    subcategory.value = '';
                }
            }

            category.addEventListener('change', function() {
                filterSubcategories(true);
            });

            filterSubcategories();
        });
    </script>
@endsection
