<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-red-600 dark:text-red-400">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Permanently delete your account.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" class="mt-6 space-y-6" onsubmit="return confirm('{{ __('Are you sure you want to delete your account?') }}');">
        @csrf
        @method('delete')

        <div class="mt-6">
            <x-danger-button>
                {{ __('Delete Account') }}
            </x-danger-button>
        </div>
    </form>
</section>
