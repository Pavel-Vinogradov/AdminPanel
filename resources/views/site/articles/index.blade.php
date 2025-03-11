@extends('layouts.public')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-center">Новостной блог</h1>

        @if ($articles->isEmpty())
            <p class="text-gray-500 text-center">Статей пока нет.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($articles as $article)
                    <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-xl transition duration-300">
                        @if($article->image)
                            <img
                                src="{{ Storage::disk('s3')->url($article->image) }}"
                                alt="Image"
                                class="w-full h-48 object-cover rounded-t-lg mb-4"
                            >
                        @endif

                        <h2 class="text-xl font-semibold mb-2">{{ $article->title }}</h2>
                        <p class="text-gray-700 text-sm line-clamp-3">{{ Str::limit($article->content, 100) }}</p>
                        <a
                            href="{{ route('articles.show', $article->id) }}"
                            class="block mt-4 text-blue-500 hover:text-blue-700 font-medium transition duration-300"
                        >
                            Читать далее →
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Пагинация -->
            <div class="mt-8 flex justify-center">
                {{ $articles->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
@endsection
