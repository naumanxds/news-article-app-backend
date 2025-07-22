@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Articles</h2>

    <div id="notifications" class="alert alert-info" style="display: none;">
        <ul id="notificationList"></ul>
    </div>

    <!-- Filters -->
    <form id="filterForm" class="row g-3 mb-4">
        <div class="col-md-3">
            <select id="dataSourceSelect" name="data_source[]" multiple></select>
        </div>

        <div class="col-md-3">
            <select id="tagSelect" name="tag_id" placeholder="Select Tag"></select>
        </div>

        <div class="col-md-3">
            <input type="text" id="publishedRange" name="published_at" class="form-control" placeholder="Select date range" readonly>
        </div>

        <div class="col-md-3 d-flex justify-content-end align-items-start gap-2">
            <button class="btn btn-primary" type="submit">Filter</button>
        </div>
    </form>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle" id="articlesTable">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Content</th>
                    <th>URL</th>
                    <th>Image</th>
                    <th>Source</th>
                    <th>Data Source</th>
                    <th>Published At</th>
                    <th>Tag</th>
                </tr>
            </thead>
            <tbody id="articlesBody">
                <tr>
                    <td colspan="12" class="text-center">Loading...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-center" id="pagination"></ul>
    </nav>
    <div class="text-center my-2" id="pageInfo"></div>
</div>
@endsection

