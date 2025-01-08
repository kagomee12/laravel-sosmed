<x-app-layout>
    <!-- Form untuk Membuat Post Baru -->
    <div class="w-[90%]">
        <div class="p-4 bg-white rounded-lg shadow-md mb-6 ">
            <h2 class="font-bold text-lg text-gray-800 mb-4">{{ __('Buat Post Baru') }}</h2>
            <form method="POST" action="{{ route('posts.store') }}">
                @csrf
                <div>
                    <x-input-label for="title" :value="__('Judul Post')" />
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" required
                        autofocus />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="content" :value="__('Konten Post')" />
                    <textarea id="content"
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        name="content" rows="4" required></textarea>
                    <x-input-error :messages="$errors->get('content')" class="mt-2" />
                </div>
                <div class="flex items-center justify-end mt-4">
                    <x-primary-button>{{ __('Post') }}</x-primary-button>
                </div>
            </form>
        </div>

        <!-- Daftar Post -->
        <div class="mt-6">
            @forelse ($posts as $post)
                <div class="p-4 border rounded-lg bg-white shadow-md mb-4">
                    <!-- Judul Post -->
                    <h4 class="font-semibold text-gray-800 text-lg">{{ $post->title }}</h4>

                    <!-- Konten Post -->
                    <p class="mt-2 text-gray-700">{{ $post->content }}</p>

                    <!-- Tombol Like dan Jumlah Komentar -->
                    <div class="mt-4 flex items-center justify-between">
                        <div>
                            <button
                                class="like-btn text-sm text-gray-700 px-2 py-1 border rounded-md hover:bg-indigo-100"
                                data-post-id="{{ $post->id }}">
                                <span class="like-icon"><i class="fas fa-heart"
                                        style="color: {{ $post->likes->contains('user_id', auth()->id()) ? 'red' : 'grey' }};"></i></span>
                                <span id="like-count-{{ $post->id }}">{{ count($post->likes) }} </span>
                            </button>
                            <span class="text-sm text-gray-600 ml-2">
                                <i class="fas fa-comments"></i> {{ $post->comments->count() }}
                            </span>
                        </div>

                        <!-- Tombol Edit dan Hapus Post -->
                        @if (auth()->id() === $post->user_id)
                            <div>
                                <a href="{{ route('posts.update', $post->id) }}"
                                    class="text-blue-500 hover:underline mr-2">{{ __('Edit') }}</a>
                                <form method="POST" action="{{ route('posts.destroy', $post->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-500 hover:underline">{{ __('Hapus') }}</button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Komentar -->
                    <div class="mt-6">
                        <h5 class="text-sm font-bold text-gray-800 mb-2">{{ __('Komentar') }}</h5>
                        <div class="max-h-48 overflow-y-auto space-y-4">
                            @foreach ($post->comments as $comment)
                                <!-- Komentar -->
                                <div class="bg-gray-100 p-2 rounded-md text-gray-700 flex justify-between items-center">
                                    <div class="flex flex-col gap-y-4">
                                        <span class="font-semibold">{{ $comment->user->name }}</span>
                                        {{ $comment->comment_text }}
                                        <div class="gap-x-2 flex items-center">
                                            <i class="fas fa-comments"></i>{{ $comment->replies->count() }}
                                        </div>
                                    </div>
                                    <div>
                                        <a href="{{ route('comments.index', $comment->id) }}"
                                            class="text-blue-500 text-sm hover:underline">
                                            {{ __('Balas') }}
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Form Balasan Postingan -->
                        <form method="POST" action="{{ route('comments.store') }}" class="mt-4">
                            @csrf
                            <div>
                                <input type="text" name="post_id" value="{{ $post->id }}" hidden>
                                <x-input-label for="comment_text" :value="__('Balas Postingan:')" />
                                <textarea id="comment_text"
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    name="comment_text" rows="4" required placeholder="Tulis balasan..."></textarea>
                                <x-input-error :messages="$errors->get('comment_text')" class="mt-2" />
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button>{{ __('Kirim Balasan') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-4 bg-gray-100 text-gray-500 text-center rounded-lg shadow-md">
                    {{ __('Belum ada post.') }}
                </div>
            @endforelse
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const likeButtons = document.querySelectorAll('.like-btn');
            likeButtons.forEach(button => {
                button.addEventListener('click', async (e) => {
                    const postId = button.getAttribute('data-post-id');
                    const likeCountElement = document.getElementById(`like-count-${postId}`);
                    const likeIcon = button.querySelector('.like-icon');

                    try {
                        const response = await fetch(`/likes/toggle`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                post_id: postId
                            })
                        });

                        if (response.ok) {
                            const data = await response.json();
                            likeCountElement.textContent = data.likes_count;

                            if (data.status === 'liked') {
                                likeIcon.innerHTML =
                                    '<i class="fas fa-heart" style="color: red;"></i>';
                            } else {
                                likeIcon.innerHTML =
                                    '<i class="fas fa-heart" style="color: grey;"></i>';
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
</x-app-layout>
