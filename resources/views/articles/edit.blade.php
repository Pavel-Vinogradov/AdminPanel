@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-gray-900">Редактировать статью</h2>
            <a href="{{ route('dashboard.articles.index') }}" class="btn btn-secondary border border-gray-300 text-gray-700 hover:bg-gray-100 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                Назад
            </a>
        </div>

        <div class="overflow-x-auto bg-white shadow rounded-lg p-6">
            <form action="{{ route('dashboard.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Заголовок статьи -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Заголовок</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $article->title) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    @error('title')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Контент статьи -->
                <div class="mb-4">
                    <label for="content" class="block text-sm font-medium text-gray-700">Контент</label>
                    <textarea id="content" name="content" rows="6" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>{{ old('content', $article->content) }}</textarea>
                    @error('content')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Изображение статьи -->
                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700">Изображение</label>
                    <input type="file" id="image" name="image" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @if($article->image)
                        <div class="mt-2 relative">
                            <img src="{{ Storage::disk('s3')->url($article->image) }}" alt="Image" class="w-32 h-32 object-cover">
                        </div>
                    @endif
                    @error('image')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Кнопка обновления -->
                <div class="mt-6 flex space-x-4">
                    <button type="submit" class="px-4 py-2 border border-blue-500 text-blue-500 rounded-md hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
