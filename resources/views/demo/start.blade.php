<x-app-layout>
@php
    $remainingSeconds = max(0, ((int) ($test['duration_minutes'] ?? 0)) * 60);
    $questions = collect($test['questions'])->map(function ($q) {
        return [
            'id' => $q['id'],
            'question_text' => $q['question_text'],
            'options' => collect($q['options'])->map(function ($o) {
                return [
                    'id' => $o['id'],
                    'option_text' => $o['text'],
                ];
            })->values(),
        ];
    })->values();
@endphp

<div class="max-w-7xl mx-auto px-4 py-6 relative z-50">
    <div class="exam-shell">
        <div class="exam-header">
            <div>
                <h2 class="text-2xl font-semibold text-white">{{ $test['title'] }}</h2>
                <p class="text-sm text-gray-300">{{ $test['description'] }}</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="timer-box">
                    <div class="text-[11px] uppercase tracking-wide text-gray-300">Time Left</div>
                    <div id="timer" class="text-lg font-mono text-red-300">00:00</div>
                </div>
                <button type="button" id="exitBtn" class="btn btn-exit">Exit</button>
            </div>
        </div>

        <div class="mt-4">
            <div class="w-full bg-gray-700/80 rounded-full h-2.5">
                <div id="progressBar" class="h-2.5 bg-blue-500 rounded-full transition-all duration-200" style="width: 0%"></div>
            </div>
            <div id="progressText" class="mt-2 text-sm text-gray-300">Question 1 of {{ count($questions) }}</div>
        </div>

        <form id="demoForm" method="POST" action="{{ route('demo.submit', $test['slug']) }}" class="mt-5">
            @csrf

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                <section class="xl:col-span-8 question-glass">
                    <div id="questionContainer" class="min-h-[240px]"></div>

                    <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
                        <button type="button" id="prevBtn" class="btn btn-secondary hidden">Previous</button>

                        <div class="flex flex-wrap gap-2">
                            <button type="button" id="flagBtn" class="btn btn-warning">Flag</button>
                            <button type="button" id="nextBtn" class="btn btn-primary">Next</button>
                            <button type="submit" id="submitBtnMain" class="btn btn-success hidden">Submit</button>
                        </div>
                    </div>
                </section>

                <aside class="xl:col-span-4 side-glass">
                    <h3 class="panel-title">Question Palette</h3>
                    <div id="questionPalette" class="palette-grid"></div>

                    <h3 class="panel-title mt-5">Question Navigator</h3>
                    <div class="flex gap-2">
                        <button type="button" id="navPrevBtn" class="btn btn-secondary text-sm px-3 py-2">Previous</button>
                        <button type="button" id="navNextBtn" class="btn btn-secondary text-sm px-3 py-2">Next</button>
                        <button type="submit" id="submitBtnSide" class="btn btn-success text-sm px-3 py-2">Submit Exam</button>
                    </div>

                    <h3 class="panel-title mt-5">Question Overview</h3>
                    <div class="overview-list">
                        <div><span class="dot bg-green-500"></span>Answered <span id="answeredCount" class="v text-green-400">0</span></div>
                        <div><span class="dot bg-gray-500"></span>Not Answered <span id="notAnsweredCount" class="v text-gray-300">0</span></div>
                        <div><span class="dot bg-yellow-400"></span>Flagged <span id="flaggedCount" class="v text-yellow-300">0</span></div>
                        <div><span class="dot bg-blue-500"></span>Current <span id="currentCount" class="v text-blue-300">1</span></div>
                    </div>
                </aside>
            </div>
        </form>
    </div>
</div>

<style>
.exam-shell {
    border: 1px solid rgba(255, 255, 255, 0.18);
    background: rgba(8, 12, 28, 0.58);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border-radius: 16px;
    padding: 1.25rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.34);
}

.exam-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}

.timer-box {
    min-width: 110px;
    border: 1px solid rgba(255, 255, 255, 0.22);
    background: rgba(17, 24, 39, 0.45);
    border-radius: 10px;
    padding: 0.45rem 0.7rem;
    text-align: right;
}

.question-glass,
.side-glass {
    border: 1px solid rgba(255, 255, 255, 0.16);
    background: rgba(3, 7, 18, 0.42);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 14px;
    padding: 1rem;
}

.side-glass {
    position: sticky;
    top: 16px;
    height: fit-content;
}

.panel-title {
    color: #f3f4f6;
    font-size: 0.95rem;
    font-weight: 700;
    margin-bottom: 0.6rem;
}

.palette-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 0.45rem;
}

.palette-btn {
    border-radius: 8px;
    height: 36px;
    font-size: 13px;
    font-weight: 700;
    border: 1px solid transparent;
    transition: transform 0.15s ease, filter 0.15s ease;
}

.palette-btn:hover {
    transform: translateY(-1px);
    filter: brightness(1.07);
}

.palette-current { background: #2563eb; color: #fff; }
.palette-answered { background: #16a34a; color: #fff; }
.palette-flagged { background: #facc15; color: #111827; }
.palette-pending { background: #4b5563; color: #fff; }

.overview-list {
    color: #d1d5db;
    font-size: 0.88rem;
    display: grid;
    gap: 0.35rem;
}

.overview-list .v {
    font-weight: 700;
    margin-left: 0.35rem;
}

.dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 9999px;
    margin-right: 0.45rem;
    vertical-align: middle;
}

input[type="radio"] {
    appearance: auto !important;
    -webkit-appearance: radio !important;
    accent-color: #22c55e;
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.option-item {
    cursor: pointer;
    user-select: none;
    transition: background-color 0.15s ease, border-color 0.15s ease;
}

.btn {
    border-radius: 8px;
    padding: 0.52rem 0.95rem;
    font-weight: 600;
    transition: filter 0.15s ease;
}

.btn:hover { filter: brightness(1.08); }

.btn-primary { background: #2563eb; color: #fff; }
.btn-secondary { background: #374151; color: #fff; }
.btn-warning { background: #facc15; color: #111827; }
.btn-success { background: #16a34a; color: #fff; }
.btn-exit { background: #92400e; color: #fff; }
</style>

<script>
const questions = @json($questions);
let index = 0;
let answers = {};
let flagged = {};
let visited = {};
let warnings = 0;
let remainingSeconds = Number({{ $remainingSeconds }});
let isAutoSubmit = false;
const defaultDocumentTitle = document.title;
const faviconElements = Array.from(document.querySelectorAll("link[rel*='icon']"));
const originalFaviconUrls = faviconElements.map((element) => element.href);
let warningFaviconUrl = null;

document.addEventListener('DOMContentLoaded', () => {
    if (document.documentElement.requestFullscreen) {
        document.documentElement.requestFullscreen().catch(() => {});
    }
});

function ensureWarningFavicon() {
    if (warningFaviconUrl || !faviconElements.length) {
        return Promise.resolve(warningFaviconUrl);
    }

    return new Promise((resolve) => {
        const image = new Image();
        image.crossOrigin = 'anonymous';
        image.onload = () => {
            const canvas = document.createElement('canvas');
            canvas.width = 32;
            canvas.height = 32;
            const ctx = canvas.getContext('2d');

            ctx.drawImage(image, 0, 0, 32, 32);
            ctx.beginPath();
            ctx.fillStyle = '#ef4444';
            ctx.arc(24, 8, 6, 0, Math.PI * 2);
            ctx.fill();
            ctx.lineWidth = 2;
            ctx.strokeStyle = '#ffffff';
            ctx.stroke();

            warningFaviconUrl = canvas.toDataURL('image/png');
            resolve(warningFaviconUrl);
        };
        image.onerror = () => resolve(null);
        image.src = originalFaviconUrls[0];
    });
}

async function setTabWarningState(active) {
    document.title = active ? `● ${defaultDocumentTitle}` : defaultDocumentTitle;

    if (!faviconElements.length) {
        return;
    }

    if (active) {
        const badgeUrl = await ensureWarningFavicon();
        if (!badgeUrl) {
            return;
        }
        faviconElements.forEach((element) => {
            element.href = badgeUrl;
        });
        return;
    }

    faviconElements.forEach((element, index) => {
        element.href = originalFaviconUrls[index] || originalFaviconUrls[0];
    });
}

function syncHiddenAnswer(questionId, optionId) {
    let input = document.querySelector(`input[name="answers[${questionId}]"]`);
    if (!input) {
        input = document.createElement('input');
        input.type = 'hidden';
        input.name = `answers[${questionId}]`;
        document.getElementById('demoForm').appendChild(input);
    }
    input.value = optionId;
}

function isAnswered(qid) {
    return answers[qid] !== undefined && answers[qid] !== null && answers[qid] !== '';
}

function getQuestionState(qid, qIndex) {
    if (qIndex === index) return 'current';
    if (flagged[qid]) return 'flagged';
    if (isAnswered(qid)) return 'answered';
    return 'pending';
}

function renderPalette() {
    const palette = document.getElementById('questionPalette');
    palette.innerHTML = questions.map((q, i) => {
        const state = getQuestionState(q.id, i);
        const stateClass = {
            current: 'palette-current',
            flagged: 'palette-flagged',
            answered: 'palette-answered',
            pending: 'palette-pending'
        }[state];
        return `<button type="button" class="palette-btn ${stateClass}" onclick="goToQuestion(${i})">${i + 1}</button>`;
    }).join('');
}

function updateOverview() {
    const answeredCount = questions.filter(q => isAnswered(q.id)).length;
    const flaggedCount = questions.filter(q => !!flagged[q.id]).length;
    const notAnsweredCount = questions.length - answeredCount;

    document.getElementById('answeredCount').innerText = answeredCount;
    document.getElementById('flaggedCount').innerText = flaggedCount;
    document.getElementById('notAnsweredCount').innerText = notAnsweredCount;
    document.getElementById('currentCount').innerText = index + 1;
}

function renderQuestion() {
    const q = questions[index];
    visited[q.id] = true;

    document.getElementById('questionContainer').innerHTML = `
        <h3 class="text-xl font-semibold mb-4 text-white">${index + 1}. ${q.question_text}</h3>
        <div class="space-y-3">
            ${q.options.map(o => `
                <label class="option-item flex items-center gap-3 p-3 border rounded ${
                    answers[q.id] == o.id ? 'bg-gray-100/20 border-blue-400' : 'border-gray-500/70'
                }">
                    <input type="radio"
                           name="ui_q_${q.id}"
                           data-qid="${q.id}"
                           value="${o.id}"
                           ${answers[q.id] == o.id ? 'checked' : ''}>
                    <span class="text-gray-100">${o.option_text}</span>
                </label>
            `).join('')}
        </div>
    `;

    updateUI();
}

function updateUI() {
    document.getElementById('progressText').innerText = `Question ${index + 1} of ${questions.length}`;
    document.getElementById('progressBar').style.width = `${((index + 1) / questions.length) * 100}%`;

    document.getElementById('prevBtn').classList.toggle('hidden', index === 0);
    document.getElementById('nextBtn').classList.toggle('hidden', index === questions.length - 1);
    document.getElementById('submitBtnMain').classList.toggle('hidden', index !== questions.length - 1);

    document.getElementById('navPrevBtn').disabled = index === 0;
    document.getElementById('navNextBtn').disabled = index === questions.length - 1;
    document.getElementById('navPrevBtn').classList.toggle('opacity-50', index === 0);
    document.getElementById('navNextBtn').classList.toggle('opacity-50', index === questions.length - 1);

    const q = questions[index];
    document.getElementById('flagBtn').innerText = flagged[q.id] ? 'Unflag' : 'Flag';

    renderPalette();
    updateOverview();
}

function autoSubmitExam() {
    if (isAutoSubmit) return;
    isAutoSubmit = true;
    const form = document.getElementById('demoForm');
    if (typeof form.requestSubmit === 'function') {
        form.requestSubmit();
    } else {
        form.submit();
    }
}

function updateTimer() {
    const safe = Math.max(0, remainingSeconds);
    const hrs = Math.floor(safe / 3600);
    const mins = Math.floor((safe % 3600) / 60);
    const secs = safe % 60;
    const text = hrs > 0
        ? `${String(hrs).padStart(2, '0')}:${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`
        : `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;

    document.getElementById('timer').innerText = text;

    if (remainingSeconds <= 0) {
        autoSubmitExam();
        return;
    }
    remainingSeconds -= 1;
}

setInterval(updateTimer, 1000);
updateTimer();

document.addEventListener('change', e => {
    if (e.target.type === 'radio' && e.target.dataset.qid) {
        const qid = e.target.dataset.qid;
        answers[qid] = e.target.value;
        syncHiddenAnswer(qid, e.target.value);
        updateUI();
    }
});

document.getElementById('demoForm').addEventListener('submit', async function (e) {
    if (isAutoSubmit) return;
    e.preventDefault();
    const unanswered = questions.filter(q => !answers[q.id]).length;

    const ok = unanswered > 0
        ? await appConfirm(`You have ${unanswered} unanswered question(s).\nSubmit anyway?`, {
            title: 'Submit Demo Exam',
            confirmText: 'Submit'
        })
        : await appConfirm('Are you sure you want to submit the exam?', {
            title: 'Submit Demo Exam',
            confirmText: 'Submit'
        });

    if (ok) this.submit();
});

document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        warnings++;
        setTabWarningState(true);
        appAlert(`Tab switch detected. Warning ${warnings}/3`, {
            title: 'Demo Warning',
            eyebrow: 'Attention'
        });
        if (warnings >= 3) autoSubmitExam();
    } else {
        setTabWarningState(false);
    }
});

document.addEventListener('contextmenu', e => e.preventDefault());
document.addEventListener('fullscreenchange', () => {
    if (!document.fullscreenElement) autoSubmitExam();
});

function goToQuestion(qIndex) {
    if (qIndex < 0 || qIndex >= questions.length) return;
    index = qIndex;
    renderQuestion();
}

document.getElementById('nextBtn').onclick = () => goToQuestion(index + 1);
document.getElementById('prevBtn').onclick = () => goToQuestion(index - 1);
document.getElementById('navNextBtn').onclick = () => goToQuestion(index + 1);
document.getElementById('navPrevBtn').onclick = () => goToQuestion(index - 1);

document.getElementById('flagBtn').onclick = () => {
    const q = questions[index];
    if (flagged[q.id]) delete flagged[q.id];
    else flagged[q.id] = true;
    updateUI();
};

document.getElementById('exitBtn').onclick = async () => {
    const confirmed = await appConfirm('Exit exam? Your current answers will be lost.', {
        title: 'Leave Demo Exam',
        confirmText: 'Exit'
    });
    if (confirmed) {
        window.location = "{{ route('demo.index') }}";
    }
};

renderQuestion();
</script>
</x-app-layout>
