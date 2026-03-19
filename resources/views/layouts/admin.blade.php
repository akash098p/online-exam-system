<x-app-layout>
    <style>
        .admin-page {
            position: relative;
            min-height: 100vh;
            padding: 2rem 1rem 2.5rem;
        }

        .admin-shell {
            position: relative;
            z-index: 1;
            max-width: 86rem;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .admin-shell > .flex:not(.fixed),
        .admin-shell > form,
        .admin-shell > .overflow-x-auto,
        .admin-shell > table,
        .admin-shell > .grid {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(200, 200, 194, 0.32);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.03)),
                rgba(8, 10, 34, 0.42);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.14), 0 20px 50px rgba(2, 6, 23, 0.28);
            border-radius: 1.5rem;
            padding: 1.25rem;
        }

        .admin-shell > .grid::before,
        .admin-shell > .flex:not(.fixed)::before,
        .admin-shell > form::before,
        .admin-shell > .overflow-x-auto::before,
        .admin-shell > table::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.12), transparent 34%);
            pointer-events: none;
        }

        .admin-shell h1,
        .admin-shell h2,
        .admin-shell h3 {
            color: #fff;
            text-shadow: 0 6px 24px rgba(15, 23, 42, 0.32);
        }

        .admin-shell > h1,
        .admin-shell > h2 {
            margin-bottom: -0.25rem;
        }

        .admin-shell .overflow-x-auto,
        .admin-shell table,
        .admin-shell [class*="bg-gray-800"],
        .admin-shell [class*="bg-gray-900"],
        .admin-shell [class*="dark:bg-gray-800"],
        .admin-shell [class*="dark:bg-gray-700"] {
            border-color: rgba(200, 200, 194, 0.28) !important;
        }

        .admin-shell .overflow-x-auto,
        .admin-shell table,
        .admin-shell .shadow,
        .admin-shell .shadow-sm,
        .admin-shell .shadow-lg {
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.12), 0 20px 50px rgba(2, 6, 23, 0.24) !important;
        }

        .admin-shell .overflow-x-auto,
        .admin-shell table,
        .admin-shell .rounded,
        .admin-shell .rounded-lg,
        .admin-shell .rounded-xl {
            border-radius: 1.2rem;
        }

        .admin-shell .overflow-x-auto,
        .admin-shell table,
        .admin-shell [class*="bg-gray-800"],
        .admin-shell [class*="bg-gray-900"],
        .admin-shell [class*="dark:bg-gray-800"],
        .admin-shell [class*="dark:bg-gray-700"] {
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.03)),
                rgba(8, 10, 34, 0.42) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .admin-shell thead,
        .admin-shell [class*="bg-gray-700"] {
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.03)),
                rgba(15, 23, 42, 0.62) !important;
        }

        .admin-shell tbody tr,
        .admin-shell tr.border-t,
        .admin-shell tr.border-b {
            border-color: rgba(255, 255, 255, 0.08) !important;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .admin-shell tbody tr:hover {
            background: rgba(255, 255, 255, 0.04) !important;
        }

        .admin-shell input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):not([type="hidden"]),
        .admin-shell select,
        .admin-shell textarea {
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            border-radius: 0.95rem !important;
            background: rgba(2, 6, 23, 0.58) !important;
            color: rgb(241 245 249) !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06);
        }

        .admin-shell input::placeholder,
        .admin-shell textarea::placeholder {
            color: rgb(148 163 184) !important;
        }

        .admin-shell label {
            color: rgb(226 232 240);
        }

        .admin-shell a[class],
        .admin-shell button[class] {
            border-radius: 0.95rem !important;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease, background-color 0.2s ease;
        }

        .admin-shell a[class]:hover,
        .admin-shell button[class]:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(2, 6, 23, 0.2);
        }

        .admin-shell .admin-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.55rem 0.95rem;
            font-size: 0.82rem;
        }

        .admin-shell .bg-green-600,
        .admin-shell .hover\:bg-green-700:hover {
            background-color: rgba(5, 150, 105, 0.88) !important;
        }

        .admin-shell .bg-blue-600,
        .admin-shell .hover\:bg-blue-700:hover {
            background-color: rgba(8, 145, 178, 0.88) !important;
        }

        .admin-shell .bg-indigo-600,
        .admin-shell .hover\:bg-indigo-700:hover {
            background-color: rgba(79, 70, 229, 0.88) !important;
        }

        .admin-shell .bg-yellow-600,
        .admin-shell .hover\:bg-yellow-700:hover {
            background-color: rgba(217, 119, 6, 0.88) !important;
        }

        .admin-shell .bg-red-600,
        .admin-shell .hover\:bg-red-700:hover {
            background-color: rgba(220, 38, 38, 0.88) !important;
        }

        .admin-shell .bg-gray-600,
        .admin-shell .bg-gray-700,
        .admin-shell .hover\:bg-gray-600:hover,
        .admin-shell .hover\:bg-gray-500:hover {
            background-color: rgba(71, 85, 105, 0.72) !important;
        }

        .admin-shell .text-gray-400,
        .admin-shell .text-gray-300 {
            color: rgb(203 213 225) !important;
        }

        .admin-shell .text-gray-200 {
            color: rgb(226 232 240) !important;
        }

        .admin-shell .pagination {
            gap: 0.5rem;
        }

        .admin-shell .pagination .page-link,
        .admin-shell nav[role="navigation"] a,
        .admin-shell nav[role="navigation"] span {
            border-radius: 0.85rem !important;
        }

        @media (max-width: 768px) {
            .admin-page {
                padding: 1rem 0.75rem 2rem;
            }

            .admin-shell > .flex:not(.fixed),
            .admin-shell > form,
            .admin-shell > .overflow-x-auto,
            .admin-shell > table,
            .admin-shell > .grid {
                padding: 1rem;
            }
        }
    </style>

    <div class="admin-page">
        <div class="admin-shell">
            @yield('content')
        </div>
    </div>
</x-app-layout>
