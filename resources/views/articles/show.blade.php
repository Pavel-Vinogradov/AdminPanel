@extends('layouts.public')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-8">
        <!-- Статья -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden p-6">
            <h1 class="text-3xl font-bold mb-4 text-center">{{ $article->title }}</h1>
            <p class="text-gray-500 text-sm text-center mb-4">
                Опубликовано {{ $article->created_at }}
            </p>

            <!-- Изображение статьи -->
            @if($article->image)
                <img src="{{ Storage::disk('s3')->url($article->image) }}" alt="Image"
                     class="w-full h-64 object-cover rounded-lg shadow mb-6">
            @endif

            <!-- Контент статьи -->
            <div class="prose lg:prose-lg max-w-none text-gray-800 mb-4">
                {!! nl2br(e($article->content)) !!}
            </div>

            <!-- Форма добавления нового комментария -->
            <form id="commentForm" class="mb-6">
                @csrf

                @guest
                    <input
                        type="text"
                        name="guest_name"
                        class="w-full p-4 border rounded-lg mb-3 focus:ring focus:ring-blue-300"
                        placeholder="Ваше имя"
                        required
                    >
                @endguest

                <textarea
                    name="body"
                    rows="3"
                    class="w-full p-4 border rounded-lg focus:ring focus:ring-blue-300 mb-4"
                    placeholder="Напишите комментарий..."
                    required
                ></textarea>

                <input type="hidden" name="article_id" value="{{ $article->id }}">

                @auth
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                @endauth

                <button type="submit"
                        class="inline-block bg-black text-white px-6 py-3 rounded-lg text-lg font-medium hover:bg-gray-800 transition duration-300">
                    Отправить
                </button>
            </form>


            <!-- Список комментариев -->
            <div id="commentsContainer">
                @foreach($comments as $comment)
                    <div class="bg-white p-4 rounded-lg shadow-sm mb-4" id="comment-{{ $comment->id }}">
                        <p class="text-gray-700">
                            <strong>{{ $comment->user ? $comment->user->name : 'Аноним' }}</strong>:
                        </p>
                        <p class="mt-1">{{ $comment->body }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ $comment->created_at }}</p>

                        <!-- Кнопка для ответа на комментарий -->
                        <button class="text-blue-500 mt-2" onclick="toggleReplyForm('{{ $comment->id }}')">Ответить</button>

                        <!-- Форма для ответа на комментарий -->
                        <form id="replyForm-{{ $comment->id }}" class="mt-4 hidden replyForm">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <input type="hidden" name="article_id" value="{{ $article->id }}">
                            @auth
                                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                            @endauth
                            <textarea name="body" rows="2" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300 mb-2" placeholder="Напишите ответ..." required></textarea>
                            <button type="submit" class="inline-block bg-black text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition duration-300">Отправить</button>
                        </form>

                        <!-- Ответы на комментарий -->
                        <div id="repliesContainer-{{ $comment->id }}" class="mt-2 pl-4 border-l-2 border-gray-200">
                            @foreach($comment->replies as $reply)
                                <div class="bg-gray-100 p-3 rounded-lg mb-2" id="reply-{{ $reply->id }}">
                                    <p class="text-gray-700">
                                        <strong>{{ $reply->user ? $reply->user->name : 'Аноним' }}</strong>:
                                    </p>
                                    <p class="mt-1">{{ $reply->body }}</p>
                                    <p class="text-xs text-gray-500 mt-2">{{ $reply->created_at }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Кнопка возврата к статьям -->
        <div class="mt-6 text-center">
            <a href="{{ route('articles.public.index') }}"
               class="inline-block bg-black text-white px-6 py-3 rounded-lg text-lg font-medium hover:bg-gray-800 transition duration-300">
                ← Вернуться к статьям
            </a>
        </div>
    </div>
@endsection
<script>
    function toggleReplyForm(commentId) {
        const replyForm = document.getElementById('replyForm-' + commentId);
        if (replyForm) {
            replyForm.classList.toggle('hidden');
        }
    }
</script>
<script type="module">
    document.addEventListener('DOMContentLoaded', function () {
        // Обработка формы добавления комментария
        const commentForm = document.querySelector('#commentForm');
        commentForm.addEventListener('submit', function (e) {
            e.preventDefault();

            axios.post('{{ route('api.comments.store') }}', new FormData(commentForm))
                .then(function (response) {
                    commentForm.reset();
                })
                .catch(function (error) {
                    console.error('Ошибка добавления комментария:', error);
                    alert('Ошибка при добавлении комментария.');
                });
        });

        // Обработка форм ответов
        document.querySelectorAll('.replyForm').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                axios.post('{{ route('api.comments.store') }}', new FormData(form))
                    .then(function (response) {
                        form.reset();
                    })
                    .catch(function (error) {
                        console.error('Ошибка добавления ответа:', error);
                        alert('Ошибка при добавлении ответа.');
                    });
            });
        });

        // Слушатели для реального времени
        window.Echo.channel('article.{{ $article->id }}')
            .listen('CommentAddedEvent', (event) => {
                document.querySelector('#commentsContainer').insertAdjacentHTML('beforeend', event.commentHtml);
            })
            // .listen('ReplyAddedEvent', (event) => {
            //     const repliesContainer = document.querySelector(`#repliesContainer-${event.parent_id}`);
            //     console.log(repliesContainer)
            //     if (repliesContainer) {
            //         repliesContainer.insertAdjacentHTML('beforeend', event.replyHtml);
            //     }
            // });
    });
</script>
