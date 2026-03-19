@extends('layouts.admin')

@section('content')
<style>
.analytics-reveal {
    opacity: 0;
    transform: translateY(18px);
    animation: analyticsFadeUp .55s ease forwards;
}

.analytics-delay-1 { animation-delay: .06s; }
.analytics-delay-2 { animation-delay: .14s; }
.analytics-delay-3 { animation-delay: .22s; }

.analytics-row {
    opacity: 0;
    transform: translateY(10px);
    animation: analyticsFadeUp .45s ease forwards;
}

@keyframes analyticsFadeUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<h1 class="text-2xl font-bold mb-6 text-white">Analytics Dashboard</h1>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <div class="bg-gray-800 p-5 rounded shadow analytics-reveal analytics-delay-1">
        <h2 class="text-xl font-semibold mb-3 text-white">Exam Summary</h2>
        <canvas id="examPerformanceChart" height="150"></canvas>
    </div>

    <div class="bg-gray-800 p-5 rounded shadow analytics-reveal analytics-delay-2">
        <h2 class="text-xl font-semibold mb-3 text-white">Daily Attempts</h2>
        <canvas id="dailyAttemptsChart" height="150"></canvas>
    </div>

    <div class="bg-gray-800 p-5 rounded shadow lg:col-span-2 analytics-reveal analytics-delay-3">
        <h2 class="text-xl font-semibold mb-3 text-white">Top Performing Students</h2>

        <table class="w-full text-left text-white">
            <thead class="bg-gray-700">
                <tr>
                    <th class="p-3">Student</th>
                    <th class="p-3">Average %</th>
                    <th class="p-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($topStudents as $row)
                    <tr class="border-t border-gray-700 analytics-row" style="animation-delay: {{ 0.06 + ($loop->index * 0.04) }}s;">
                        <td class="p-3">
                            <div class="flex items-center gap-2">
                                <img src="{{ $row->user->profilePhotoUrl() }}" alt="{{ $row->user->name }}" class="w-8 h-8 rounded-full object-cover border border-white/20">
                                <span>{{ $row->user->name }}</span>
                            </div>
                        </td>
                        <td class="p-3">{{ number_format($row->avg_percentage, 2) }}%</td>
                        <td class="p-3">
                            <a href="{{ route('admin.students.show', $row->user->id) }}"
                               class="admin-action-btn bg-indigo-600 text-white hover:bg-indigo-700 transition">
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<div id="dailyAttemptsModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4">
    <div class="w-full max-w-2xl bg-slate-900/90 border border-white/20 rounded-xl shadow-2xl">
        <div class="flex items-center justify-between p-4 border-b border-white/15">
            <h3 id="dailyAttemptsModalTitle" class="text-lg font-semibold text-white">Daily Attempts</h3>
            <button id="dailyAttemptsModalClose" type="button" class="text-gray-300 hover:text-white text-xl leading-none">&times;</button>
        </div>
        <div id="dailyAttemptsModalBody" class="max-h-[65vh] overflow-y-auto p-4 space-y-3"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const examLabels = {!! json_encode($examLabels) !!};
    const examScores = {!! json_encode($examScores) !!};
    const examBaseColor = 'rgba(230, 226, 2, 0.92)';
    const examDimColor = 'rgba(230, 226, 2, 0.20)';
    const examFocusBorder = 'rgba(255, 255, 255, 0.9)';

    function applyExamBarFocus(chart, focusedIndex) {
        const total = chart.data.labels.length;
        const bg = [];
        const border = [];
        const borderWidth = [];

        for (let i = 0; i < total; i++) {
            const isFocused = focusedIndex === null || i === focusedIndex;
            bg.push(isFocused ? examBaseColor : examDimColor);
            border.push(isFocused ? examFocusBorder : 'rgba(255, 255, 255, 0.12)');
            borderWidth.push(isFocused ? 1.4 : 0.6);
        }

        chart.data.datasets[0].backgroundColor = bg;
        chart.data.datasets[0].borderColor = border;
        chart.data.datasets[0].borderWidth = borderWidth;
        chart.update('none');
    }

    let examChart;
    let examChartRevealTimer;

    const dailyLabels = {!! json_encode($dailyLabels) !!};
    const dailyCounts = {!! json_encode($dailyCounts) !!};
    const dailyAttemptDetails = @json($dailyAttemptDetails->toArray());

    const modal = document.getElementById('dailyAttemptsModal');
    const modalBody = document.getElementById('dailyAttemptsModalBody');
    const modalTitle = document.getElementById('dailyAttemptsModalTitle');
    const closeModalBtn = document.getElementById('dailyAttemptsModalClose');
    const defaultStudentPhoto = @json(asset('images/default-male.png'));

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function openDailyAttemptsModal(day) {
        const attempts = Array.isArray(dailyAttemptDetails?.[day])
            ? dailyAttemptDetails[day]
            : Object.values(dailyAttemptDetails?.[day] || {});
        modalTitle.textContent = `Attempts on ${day}`;

        if (!attempts.length) {
            modalBody.innerHTML = '<p class="text-gray-300">No attempts found for this day.</p>';
        } else {
            modalBody.innerHTML = attempts.map((item) => {
                const studentName = escapeHtml(item?.student_name || 'Unknown');
                const studentReg = escapeHtml(item?.student_reg || 'N/A');
                const examTitle = escapeHtml(item?.exam_title || 'Exam');
                const status = escapeHtml(item?.status || 'Unknown');
                const time = escapeHtml(item?.time || 'Time unavailable');
                const percentage = Number(item?.percentage ?? 0).toFixed(2);
                const studentPhoto = escapeHtml(item?.student_photo || defaultStudentPhoto);

                return `
                <div class="rounded-lg border border-white/15 bg-white/5 p-3">
                    <div class="flex items-start gap-3">
                        <img src="${studentPhoto}" alt="${studentName}" class="w-10 h-10 rounded-full object-cover border border-white/20" />
                        <div class="flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-white font-semibold">${studentName}</p>
                                <span class="text-xs px-2 py-1 rounded ${status === 'Pass' ? 'bg-green-600/80' : 'bg-red-600/80'} text-white">${status}</span>
                            </div>
                            <p class="text-xs text-gray-300">Reg No: ${studentReg}</p>
                            <p class="text-sm text-cyan-300 mt-1">${examTitle}</p>
                            <div class="flex items-center justify-between mt-1 text-xs text-gray-300">
                                <span>${time}</span>
                                <span>${percentage}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            }).join('');
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDailyAttemptsModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    closeModalBtn.addEventListener('click', closeDailyAttemptsModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeDailyAttemptsModal();
    });

    let dailyAttemptsChart;

    function renderAnalyticsCharts() {
        window.clearTimeout(examChartRevealTimer);
        examChart?.destroy();
        dailyAttemptsChart?.destroy();

        examChart = new Chart(document.getElementById('examPerformanceChart'), {
            type: 'bar',
            data: {
                labels: examLabels,
                datasets: [{
                    label: 'Average %',
                    data: examScores,
                    backgroundColor: examBaseColor,
                    borderRadius: 0,
                    borderSkipped: false,
                    hidden: true
                }]
            },
            options: {
                animation: {
                    duration: 1400,
                    easing: 'easeOutQuart',
                    delay(context) {
                        return context.type === 'data' ? context.dataIndex * 110 : 0;
                    }
                },
                interaction: {
                    mode: 'nearest',
                    intersect: true
                },
                onHover: (event, activeElements, chart) => {
                    const focusedIndex = activeElements.length ? activeElements[0].index : null;
                    applyExamBarFocus(chart, focusedIndex);
                }
            }
        });

        examChartRevealTimer = window.setTimeout(() => {
            examChart.data.datasets[0].hidden = false;
            examChart.update();
            applyExamBarFocus(examChart, null);
        }, 180);

        dailyAttemptsChart = new Chart(document.getElementById('dailyAttemptsChart'), {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Attempts',
                    data: dailyCounts,
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.16)',
                    pointBackgroundColor: '#22c55e',
                    pointBorderColor: '#dcfce7',
                    pointBorderWidth: 1.5,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                animation: {
                    duration: 1600,
                    easing: 'easeOutQuart'
                },
                animations: {
                    x: {
                        type: 'number',
                        easing: 'easeOutCubic',
                        duration: 900,
                        from: 0,
                        delay(context) {
                            return context.type === 'data' ? context.dataIndex * 80 : 0;
                        }
                    },
                    y: {
                        type: 'number',
                        easing: 'easeOutCubic',
                        duration: 1100,
                        from(context) {
                            const chartArea = context.chart.chartArea;
                            return chartArea ? chartArea.bottom : 0;
                        },
                        delay(context) {
                            return context.type === 'data' ? context.dataIndex * 80 : 0;
                        }
                    }
                },
                onClick: (event, elements, chart) => {
                    if (!elements.length) return;
                    const index = elements[0].index;
                    const day = chart.data.labels[index];
                    openDailyAttemptsModal(day);
                }
            }
        });
    }

    renderAnalyticsCharts();
    window.addEventListener('pageshow', renderAnalyticsCharts);
</script>

@endsection
