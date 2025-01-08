<x-app-layout>
    <div  class="w-[90%]">
        <form method="POST" action="{{ route('comments.reply') }}">
            @csrf

            <div>
                <x-input-label for="reply_text" :value="__('Balasan untuk komentar:')" />
                <p class="text-gray-600 italic">{{ $comments->comment_text }}</p>

                <textarea id="reply_text"
                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    name="comment_text" rows="4" required placeholder="Tulis balasan..."></textarea>
                <x-input-error :messages="$errors->get('reply_text')" class="mt-2" />
            </div>
            <input type="text" name="comment_id" value="{{ $comments->id }}" hidden />
            <input type="text" name="post_id" value="{{ $comments->post_id }}" hidden />
            <div class="flex items-center justify-end mt-4">
                <x-primary-button>
                    {{ __('Kirim Balasan') }}
                </x-primary-button>
            </div>
        </form>

        <!-- Tampilkan Balasan -->
        <div class="mt-6">
            <h5 class="text-sm font-bold text-gray-800 mb-2">{{ __('Balasan') }}</h5>
            <div class="max-h-48 overflow-y-auto space-y-4">
                @foreach ($comments->replies as $reply)
                    <div class="bg-gray-100 p-2 rounded-md text-gray-700">
                        <div class="flex justify-between">
                            <span class="font-semibold">{{ $reply->user->name }}</span>
                            <span class="text-sm text-gray-600">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mt-1">{{ $reply->comment_text }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
