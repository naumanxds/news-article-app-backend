import './bootstrap';
import $ from 'jquery';
window.jQuery = window.$ = $;
import Choices from 'choices.js';
import Litepicker from 'litepicker';

document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('articlesBody');
    const pagination = document.getElementById('pagination');
    const dataSourceChoices = new Choices('#dataSourceSelect', {
        removeItemButton: true,
        placeholderValue: 'Select Data Sources',
        choices: [
            { value: 'NewsApiOrg', label: 'NewsApiOrg' },
            { value: 'NewYorkTimes', label: 'NewYorkTimes' },
            { value: 'TheGuardian', label: 'TheGuardian' }
        ]
    });

    const tagSelect = new Choices('#tagSelect', {
        searchEnabled: true,
        placeholderValue: 'Select a Tag',
    });

    let currentPage = 1;

    function fetchArticles(page = 1, filters = {}) {
        tableBody.innerHTML = `<tr><td colspan="12" class="text-center">Loading...</td></tr>`;

        const queryParams = new URLSearchParams();

        queryParams.set('page', page);

        for (const key in filters) {
            const value = filters[key];
            if (Array.isArray(value)) {
                value.forEach(v => queryParams.append(`${key}[]`, v));
            } else {
                queryParams.append(key, value);
            }
        }

        fetch(`/api/v1/articles?${queryParams.toString()}`)
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

    // Fetch tags from API
    fetch('/api/v1/tags')
        .then(res => res.json())
        .then(res => {
            if (res.data) {
                const tagOptions = [
                    { value: '', label: '-- All Tags --', selected: true }, // <--- this line
                    ...res.data.map(tag => ({
                        value: tag.id,
                        label: tag.name
                    }))
                ];
                tagSelect.setChoices(tagOptions, 'value', 'label', true);
            }
        });

    function getFilters() {
        const filters = {};

        // data_source[] (array)
        const selectedSources = dataSourceChoices.getValue(true);
        if (selectedSources.length) {
            filters['filters[data_source]'] = selectedSources;
        }

        // tag_id
        const selectedTag = tagSelect.getValue(true);
        if (selectedTag) {
            filters['filters[tag_id]'] = selectedTag;
        }

        // published_at[from] & [to]
        const range = document.getElementById('publishedRange').value;
        if (range) {
            const [from, to] = range.split(' - ');
            if (from && to) {
                filters['filters[published_at][from]'] = from;
                filters['filters[published_at][to]'] = to;
            }
        }

        return filters;
    }

    // Date Range Picker
    const picker = new Litepicker({
        element: document.getElementById('publishedRange'),
        singleMode: false,
        format: 'YYYY-MM-DD',
        numberOfMonths: 2,
        numberOfColumns: 2
    });

    // Update fetchArticles() to pass proper filters
    const filterForm = document.getElementById('filterForm');
    filterForm.addEventListener('submit', function (e) {
        e.preventDefault();
        fetchArticles(1, getFilters());
    });

    // Initial load
    fetchArticles();
});