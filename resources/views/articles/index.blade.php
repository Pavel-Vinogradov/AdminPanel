@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-gray-900">Список статей</h2>
            <a href="{{ route('dashboard.articles.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Создать статью
            </a>
        </div>

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">
                        <a href="{{ route('dashboard.articles.index', ['sort_by' => 'id', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}">
                            ID
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Название</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">
                        <a href="{{ route('dashboard.articles.index', ['sort_by' => 'created_at', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}">
                            Дата создания
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($articles as $article)
                    <tr class="border-b">
                        <td class="px-6 py-3 text-sm text-gray-800">{{ $article->id }}</td>
                        <td class="px-6 py-3 text-sm text-gray-800">{{ $article->title }}</td>
                        <td class="px-6 py-3 text-sm text-gray-800">{{ $article->created_at->format('d-m-Y') }}</td>
                        <td class="px-6 py-3 text-sm text-gray-800">
                            <a href="{{ route('dashboard.articles.edit', $article->id) }}"
                               class="text-blue-600 hover:text-blue-800">Редактировать</a> |
                            <form action="{{ route('dashboard.articles.destroy', $article->id) }}" method="POST"
                                  class="inline"
                                  onsubmit="return confirm('Вы уверены, что хотите удалить эту статью?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $articles->links('pagination::tailwind') }}
        </div>
    </div>
@endsection
