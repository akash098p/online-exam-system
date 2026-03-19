<section class="space-y-6">
    <header class="border-b border-white/10 pb-6">
        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-300">Advanced Controls</p>
    </header>

    <details class="group overflow-hidden rounded-2xl border border-white/10 bg-white/[0.04]">
        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-5 py-4 text-left">
            <div>
                <p class="text-sm font-semibold text-white">{{ __('Danger zone') }}</p>
                <p class="mt-1 text-sm text-slate-300">{{ __('Reveal sensitive account controls only when needed.') }}</p>
            </div>

            <span class="inline-flex items-center rounded-full border border-red-300/20 bg-red-500/10 px-3 py-1 text-xs font-semibold text-red-200 transition group-open:bg-red-500/15">
                {{ __('Hidden by default') }}
            </span>
        </summary>

        <div class="border-t border-white/10 px-5 py-5">
            <div class="rounded-2xl border border-red-300/12 bg-red-500/[0.06] p-5">
                <h3 class="text-lg font-semibold text-white">{{ __('Delete Account') }}</h3>
                <p class="mt-2 max-w-xl text-sm leading-6 text-slate-300">
                    {{ __('Deleting your account removes your access to the app, but your exam history and records stay available for admin records. Enter your password to confirm.') }}
                </p>

                <x-danger-button
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                    class="mt-4 !rounded-xl !bg-red-600 !px-5 !py-2.5 text-sm font-semibold hover:!bg-red-500"
                >{{ __('Delete Account') }}</x-danger-button>
            </div>
        </div>
    </details>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('This removes your access to the app and logs you out, but your records stay in the database for reporting and audit purposes. Please enter your password to confirm.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="profile-control mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
