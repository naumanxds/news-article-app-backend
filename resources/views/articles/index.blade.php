@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Articles</h2>

    <!-- Filters -->
    <form id="filterForm" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="author" class="form-control" placeholder="Author">
        </div>
        <div class="col-md-3">
            <input type="text" name="source" class="form-control" placeholder="Source">
        </div>
        <div class="col-md-3">
            <input type="text" name="data_source" class="form-control" placeholder="Data Source">
        </div>
        <div class="col-md-3">
            <input type="text" name="tag_id" class="form-control" placeholder="Tag ID">
        </div>
        <div class="col-md-12 d-flex justify-content-end">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tableBody = document.getElementById('articlesBody');
        const pagination = document.getElementById('pagination');
        const filterForm = document.getElementById('filterForm');

        let currentPage = 1;

        function fetchArticles(page = 1, filters = {}) {
            tableBody.innerHTML = `<tr><td colspan="12" class="text-center">Loading...</td></tr>`;
            let query = new URLSearchParams({ ...filters, page });

            fetch(`/api/v1/articles?${query.toString()}`)
                .then(response => response.json())
                .then(data => {
                    renderArticles(data.data);
                    renderPagination(data.links);
                    document.getElementById('pageInfo').innerText = `Showing ${data.from}–${data.to} of ${data.total} articles`;
                })
                .catch(() => {
                    tableBody.innerHTML = `<tr><td colspan="12" class="text-center text-danger">Failed to load data.</td></tr>`;
                });
        }

        function renderArticles(articles) {
            if (articles.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="12" class="text-center">No articles found.</td></tr>`;
                return;
            }

            tableBody.innerHTML = '';
            articles.forEach(article => {
                tableBody.innerHTML += `
                    <tr>
                        <td>${article.id}</td>
                        <td>${article.title}</td>
                        <td>${article.author ?? ` - - `}</td>
                        <td>${article.content?.substring(0, 100) || ''}</td>
                        <td><a href="${article.url}" target="_blank">View</a></td>
                        <td>${article.image_url ? `<img src="${article.image_url}" alt="Image" class="img-thumbnail" style="max-width: 100px;">` : ''}</td>
                        <td>${article.source}</td>
                        <td>${article.data_source}</td>
                        <td>${article.published_at}</td>
                        <td>${article.tag.name}</td>
                    </tr>
                `;
            });
        }

        function renderPagination(meta) {
            pagination.innerHTML = '';

            if (!meta) return;

            meta.forEach(link => {
                const li = document.createElement('li');
                const isActive = link.active ? 'active' : '';
                const isDisabled = link.url === null ? 'disabled' : '';

                li.className = `page-item ${isActive} ${isDisabled}`;

                const a = document.createElement('a');
                a.className = 'page-link';
                a.innerHTML = link.label;
                a.href = '#';

                if (link.url !== null) {
                    const url = new URL(link.url);
                    const newPage = url.searchParams.get('page') || 1;

                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        currentPage = newPage;
                        fetchArticles(currentPage, getFilters());
                    });
                }

                li.appendChild(a);
                pagination.appendChild(li);
            });
        }


        function getFilters() {
            const formData = new FormData(filterForm);
            const filters = {};
            for (const [key, value] of formData.entries()) {
                if (value) filters[key] = value;
            }
            return filters;
        }

        filterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            currentPage = 1;
            fetchArticles(currentPage, getFilters());
        });

        // Initial load
        fetchArticles();
    });
</script>
@endpush
