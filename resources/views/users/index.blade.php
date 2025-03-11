@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-8">

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">
                        <a href="{{ route('dashboard.users.index', ['sort_by' => 'id', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}">
                            ID
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Имя</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">
                        <a href="{{ route('dashboard.users.index', ['sort_by' => 'created_at', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}">
                            Дата создания
                        </a>
                    </th>
                </tr>
                </thead>
                <tbody>

                @foreach ($users as $user)
                    <tr class="border-b">
                        <td class="px-6 py-3 text-sm text-gray-800">{{ $user->id }}</td>
                        <td class="px-6 py-3 text-sm text-gray-800">{{ $user->name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-800">{{ $user->email }}</td>
                        <td class="px-6 py-3 text-sm text-gray-800">{{ $user->created_at->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links('pagination::tailwind') }}
        </div>
    </div>
@endsection
