@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Создать статью</h2>
        {{-- Блок ошибок валидации --}}
        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('dashboard.articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Заголовок</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror" placeholder="Введите заголовок статьи">
                    @error('title')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="content" class="block text-sm font-medium text-gray-700">Контент</label>
                    <textarea id="content" name="content" rows="5" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-500 @enderror" placeholder="Введите содержание статьи">{{ old('content') }}</textarea>
                    @error('content')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700">Изображение</label>
                    <input type="file" id="image" name="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-700 @error('image') border-red-500 @enderror">
                    @error('image')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-6 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50">
                        Сохранить
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
