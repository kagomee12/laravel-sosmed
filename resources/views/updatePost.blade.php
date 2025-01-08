<x-app-layout>
    <!-- Form Update Post -->
    <div class="w-[90%] mx-auto">
        <div class="p-4 bg-white rounded-lg shadow-md mb-6">
            <h2 class="font-bold text-lg text-gray-800 mb-4">{{ __('Update Post') }}</h2>
            <form method="POST" action="{{ route('posts.update', $post->id) }}">
                @csrf
                @method('PATCH')

                <!-- Judul Post -->
                <div>
                    <x-input-label for="title" :value="__('Judul Post')" />
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" value="{{ $post->title }}" required autofocus />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <!-- Konten Post -->
                <div class="mt-4">
                    <x-input-label for="content" :value="__('Konten Post')" />
                    <textarea id="content"
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        name="content" rows="4" required>{{ $post->content }}</textarea>
                    <x-input-error :messages="$errors->get('content')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button>
                        {{ __('Update') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

        <!-- Preview Post -->
        <div class="p-4 bg-gray-100 rounded-lg shadow-md">
            <h4 class="font-bold text-gray-800 mb-2">{{ __('Preview Post') }}</h4>
            <div class="border-t pt-4">
                <h5 class="font-semibold text-gray-700">{{ $post->title }}</h5>
                <p class="text-gray-600 mt-2">{{ $post->content }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
