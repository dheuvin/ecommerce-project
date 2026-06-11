@extends(
    in_array(auth()->user()->role, ['admin', 'seller'])
        ? 'layouts.app'
        : 'layouts.user'
)
@section('sidebar')
    @include('user.sidebar')
@endsection

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Card -->
            <div class="card border-0 shadow-sm">

                <!-- Header (White + Blue Accent) -->
                <div class="card-header bg-white border-bottom">
                    <h4 class="mb-0 text-primary fw-bold">
                        Create Support Ticket
                    </h4>
                    <small class="text-muted">
                        Please describe your issue clearly so we can help you faster
                    </small>
                </div>

                <div class="card-body p-4">

                    <!-- Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Oops!</strong> Please fix the errors below
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST"
                          action="{{ route('tickets.store') }}"
                          enctype="multipart/form-data">

                        @csrf

                        <!-- Subject -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">
                                Subject <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="subject"
                                   class="form-control"
                                   value="{{ old('subject') }}"
                                   placeholder="Enter ticket subject">

                        </div>

                        <!-- Category & Priority -->
                        <div class="row">

                            <!-- Category -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark">
                                    Category
                                </label>

                                <select name="ticket_category_id"
                                        class="form-select">

                                    <option value="">Select Category</option>

                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('ticket_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <!-- Priority -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark">
                                    Priority
                                </label>

                                <select name="priority" class="form-select">

                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>

                                </select>
                            </div>

                        </div>

                        <!-- Message -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">
                                Message <span class="text-danger">*</span>
                            </label>

                            <textarea name="message"
                                      rows="5"
                                      class="form-control"
                                      placeholder="Explain your issue in detail...">{{ old('message') }}</textarea>
                        </div>

                        <!-- Attachment -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">
                                Attachment (Optional)
                            </label>

                            <input type="file"
                                   name="attachment"
                                   class="form-control">

                            <small class="text-muted">
                                JPG, PNG only (Max 2MB)
                            </small>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between align-items-center">

                            <a href="{{ route('tickets.index') }}"
                               class="btn btn-light border">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="btn btn-primary px-4">
                                Submit Ticket
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection
