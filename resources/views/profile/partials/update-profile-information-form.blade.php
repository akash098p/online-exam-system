<section>
    @php
        $fieldGroups = [
            [
                'title' => 'Personal Information',
                'description' => 'Core details used across the student workspace.',
                'fields' => [
                    ['id' => 'name', 'label' => 'Full Name', 'type' => 'text', 'value' => old('name', $user->name), 'required' => true, 'autocomplete' => 'name'],
                    ['id' => 'email', 'label' => 'Email Address', 'type' => 'email', 'value' => old('email', $user->email), 'required' => true, 'autocomplete' => 'username'],
                ],
            ],
            [
                'title' => 'Academic Information',
                'description' => 'Details that help with assignment, filtering, and academic context.',
                'fields' => [
                    ['id' => 'registration_no', 'label' => 'Registration Number', 'type' => 'text', 'value' => old('registration_no', $user->registration_no)],
                    ['id' => 'college_name', 'label' => 'College Name', 'type' => 'text', 'value' => old('college_name', $user->college_name)],
                ],
            ],
            [
                'title' => 'Contact Information',
                'description' => 'Ways the platform or admins can reach you when needed.',
                'fields' => [
                    ['id' => 'phone', 'label' => 'Phone Number', 'type' => 'text', 'value' => old('phone', $user->phone)],
                ],
            ],
        ];

        $hasProfileErrors = $errors->hasAny([
            'name',
            'email',
            'sex',
            'college_name',
            'registration_no',
            'semester',
            'phone',
            'profile_photo',
            'remove_profile_photo',
        ]);
    @endphp

    <header class="flex flex-col gap-4 border-b border-white/10 pb-6 sm:flex-row sm:items-start sm:justify-between">
        <div class="max-w-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-200">Profile Details</p>
            <h2 class="mt-2 text-2xl font-semibold text-white">
                {{ __('Manage your information') }}
            </h2>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <span class="inline-flex items-center rounded-full border border-emerald-400/25 bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-200">
                {{ $user->hasVerifiedEmail() ? 'Verified account' : 'Verification pending' }}
            </span>

            <button
                type="button"
                id="editBtn"
                class="inline-flex items-center justify-center rounded-xl bg-cyan-500 px-4 py-2.5 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400"
            >
                Edit Profile
            </button>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form
        method="post"
        action="{{ route('profile.update') }}"
        class="mt-8 space-y-8"
        enctype="multipart/form-data"
        id="profileForm"
        data-start-editing="{{ $hasProfileErrors ? 'true' : 'false' }}"
    >
        @csrf
        @method('patch')

        <div class="grid gap-6 lg:grid-cols-[1.2fr,0.8fr]">
            <section class="profile-form-card">
                <div class="flex items-center gap-4">
                    <img
                        src="{{ $user->profilePhotoUrl() }}"
                        alt="Current Profile Photo"
                        class="h-20 w-20 rounded-2xl object-cover ring-4 ring-white/10"
                    >

                    <div class="min-w-0">
                        <h3 class="text-lg font-semibold text-white">Profile photo</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-300">Use a clean headshot or recognizable profile image for a more credible academic record.</p>
                    </div>
                </div>

                <div class="mt-5">
                    <x-input-label for="profile_photo" :value="__('Upload New Photo')" class="text-slate-200" />
                    <input
                        id="profile_photo"
                        name="profile_photo"
                        type="file"
                        class="profile-input mt-2 block w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm text-slate-300 file:mr-4 file:rounded-xl file:border-0 file:bg-cyan-500 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-950"
                        disabled
                    >

                    @if($user->profile_photo)
                        <label class="mt-3 inline-flex items-center gap-2 text-sm text-slate-300">
                            <input type="checkbox" name="remove_profile_photo" value="1" class="profile-input rounded border-white/20 bg-slate-900 text-cyan-400" disabled>
                            Remove current photo
                        </label>
                    @endif

                    <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
                    <x-input-error class="mt-2" :messages="$errors->get('remove_profile_photo')" />
                </div>
            </section>

            <section class="profile-form-card">
                <h3 class="text-lg font-semibold text-white">Academic status</h3>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="sex" :value="__('Sex')" class="text-slate-200" />
                        <select id="sex" name="sex" class="profile-control profile-input mt-2" disabled>
                            <option value="">Select</option>
                            <option value="male" @selected(old('sex', $user->sex) == 'male')>Male</option>
                            <option value="female" @selected(old('sex', $user->sex) == 'female')>Female</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('sex')" />
                    </div>

                    <div>
                        <x-input-label for="semester" :value="__('Semester')" class="text-slate-200" />
                        <select id="semester" name="semester" class="profile-control profile-input mt-2" disabled>
                            <option value="">Select</option>
                            <option value="1st" @selected(old('semester', $user->semester) == '1st')>1st</option>
                            <option value="2nd" @selected(old('semester', $user->semester) == '2nd')>2nd</option>
                            <option value="3rd" @selected(old('semester', $user->semester) == '3rd')>3rd</option>
                            <option value="4th" @selected(old('semester', $user->semester) == '4th')>4th</option>
                            <option value="5th" @selected(old('semester', $user->semester) == '5th')>5th</option>
                            <option value="6th" @selected(old('semester', $user->semester) == '6th')>6th</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('semester')" />
                    </div>
                </div>
            </section>
        </div>

        @foreach ($fieldGroups as $group)
            <section class="profile-form-card">
                <div class="mb-5">
                    <h3 class="text-lg font-semibold text-white">{{ $group['title'] }}</h3>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    @foreach ($group['fields'] as $field)
                        <div class="{{ count($group['fields']) === 1 ? 'sm:col-span-2' : '' }}">
                            <x-input-label :for="$field['id']" :value="__($field['label'])" class="text-slate-200" />
                            <x-text-input
                                :id="$field['id']"
                                :name="$field['id']"
                                :type="$field['type']"
                                class="profile-control profile-input mt-2 block w-full"
                                :value="$field['value']"
                                :required="$field['required'] ?? false"
                                :autocomplete="$field['autocomplete'] ?? null"
                                :autofocus="$field['id'] === 'name'"
                                disabled
                            />
                            <x-input-error class="mt-2" :messages="$errors->get($field['id'])" />

                            @if ($field['id'] === 'email' && $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <p class="mt-3 text-sm text-amber-200">
                                    {{ __('Your email address is unverified.') }}
                                    <button form="send-verification" class="font-semibold text-cyan-300 underline transition hover:text-cyan-200">
                                        {{ __('Resend verification email') }}
                                    </button>
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach

        <div id="saveBox" class="hidden items-center justify-between gap-4 rounded-2xl border border-cyan-400/20 bg-cyan-400/8 px-4 py-4 sm:px-5">
            <div>
                <p class="text-sm font-semibold text-white">Editing enabled</p>
                <p class="text-sm text-slate-300">Review each field carefully before saving to keep your academic record accurate.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button type="button" id="cancelEditBtn" class="inline-flex items-center justify-center rounded-xl border border-white/15 px-4 py-2.5 text-sm font-semibold text-slate-200 transition hover:bg-white/5">
                    Cancel
                </button>

                <x-primary-button class="!rounded-xl !bg-cyan-500 !px-5 !py-2.5 !text-sm !font-semibold !text-slate-950 hover:!bg-cyan-400 focus:!bg-cyan-400">
                    {{ __('Save Changes') }}
                </x-primary-button>
            </div>
        </div>

        @if (session('status') === 'profile-updated')
            <p class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                {{ __('Profile updated successfully.') }}
            </p>
        @endif
    </form>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const editBtn = document.getElementById("editBtn");
    const cancelEditBtn = document.getElementById("cancelEditBtn");
    const form = document.getElementById("profileForm");
    const inputs = document.querySelectorAll(".profile-input");
    const saveBox = document.getElementById("saveBox");
    const shouldStartEditing = form?.dataset.startEditing === "true";

    if (!editBtn || !form || !saveBox) {
        return;
    }

    const setEditingState = (isEditing) => {
        inputs.forEach((input) => {
            if (isEditing) {
                input.removeAttribute("disabled");
            } else {
                input.setAttribute("disabled", "disabled");
            }
        });

        saveBox.classList.toggle("hidden", !isEditing);
        saveBox.classList.toggle("flex", isEditing);
        editBtn.textContent = isEditing ? "Editing..." : "Edit Profile";
        editBtn.disabled = isEditing;
        editBtn.classList.toggle("opacity-60", isEditing);
        editBtn.classList.toggle("cursor-not-allowed", isEditing);
    };

    editBtn.addEventListener("click", () => {
        setEditingState(true);
    });

    cancelEditBtn?.addEventListener("click", () => {
        form.reset();
        setEditingState(false);
    });

    if (shouldStartEditing) {
        setEditingState(true);
    }
});
</script>

<style>
    .profile-form-card {
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.12);
        background:
            linear-gradient(180deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.025)),
            rgba(15, 23, 42, 0.22);
        box-shadow:
            inset 0 1px 0 rgba(255, 255, 255, 0.14),
            0 16px 34px rgba(2, 6, 23, 0.16);
        border-radius: 24px;
        padding: 1.25rem;
    }

    .profile-form-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.12), transparent 32%);
        pointer-events: none;
    }

    .profile-control {
        border-radius: 16px !important;
        border: 1px solid rgba(255, 255, 255, 0.14) !important;
        background:
            linear-gradient(180deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.02)),
            rgba(2, 6, 23, 0.4) !important;
        color: rgb(241 245 249) !important;
        min-height: 3rem;
        box-shadow:
            inset 0 1px 0 rgba(255, 255, 255, 0.08),
            0 8px 20px rgba(2, 6, 23, 0.12) !important;
    }

    .profile-control:disabled,
    .profile-input:disabled {
        cursor: not-allowed;
        opacity: 0.72;
    }

    .profile-control::placeholder {
        color: rgb(148 163 184);
    }
</style>
