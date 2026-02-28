@extends('layouts.main')

@section('title', 'Все жанры • Музыка')

@section('content')

    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-950 dark:to-gray-900 py-8 md:py-12">
        @if (session('success'))
            <div class="fixed top-4 right-4 z-50 animate-fade-in">
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg max-w-md">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">

            <!-- Заголовок + кнопка создания -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-10 gap-4">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">
                    Управление жанрами
                </h1>

                @if(auth()->user()?->isAdmin())
                    <a href="{{ route('genres.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-all shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Добавить жанр
                    </a>
                @endif
            </div>

            <!-- Вкладки / переключатель -->
            <div class="mb-8 border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button type="button"
                            class="tab-btn px-4 py-2 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-500 font-medium text-lg transition-all"
                            data-tab="active" data-active="true">
                        Активные
                        <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">({{ $genres->total() }})</span>
                    </button>

                    <button type="button"
                            class="tab-btn px-4 py-2 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-500 font-medium text-lg transition-all"
                            data-tab="trashed">
                        В корзине
                        <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">({{ $trashedGenres->total() }})</span>
                    </button>
                </nav>
            </div>

            <!-- Активные жанры -->
            <div id="tab-active" class="tab-content">
                @if ($genres->isEmpty())
                    <div class="text-center py-16 text-gray-500 dark:text-gray-400 bg-white/50 dark:bg-gray-800/30 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-lg">Активных жанров пока нет</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($genres as $genre)
{{--                            <a href="{{ route('genres.show', $genre->id) }}"--}}
                            <a href="#"
                               class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:-translate-y-1">

                                <div class="h-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                        {{ $genre->label }}
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 font-mono">
                                        {{ $genre->value }}
                                    </p>

                                    <div class="mt-4 flex items-center justify-between text-sm">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/50">
                                            {{ $genre->musics_count ?? $genre->musics()->count() }} треков
                                        </span>

                                        <div class="flex items-center gap-3">
                                            <span class="text-gray-500 dark:text-gray-400">
                                                {{ $genre->created_at?->diffForHumans() ?? '—' }}
                                            </span>

                                            @if(auth()->user()?->isAdmin())
                                                <form action="{{ route('genres.destroy', $genre->id) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('Переместить жанр «{{ $genre->label }}» в корзину?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-sm">
                                                        Удалить
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-10">
                        {{ $genres->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>

            <!-- Удалённые жанры (в корзине) -->
            <div id="tab-trashed" class="tab-content hidden">
                @if ($trashedGenres->isEmpty())
                    <div class="text-center py-16 text-gray-500 dark:text-gray-400 bg-white/50 dark:bg-gray-800/30 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4M9 17h6m-3-4v4" />
                        </svg>
                        <p class="text-lg">Корзина пуста</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($trashedGenres as $genre)
                            <div class="group bg-gray-50 dark:bg-gray-900/50 rounded-xl shadow-sm border border-gray-300/50 dark:border-gray-700/50 overflow-hidden opacity-85 hover:opacity-100 transition-all duration-300">

                                <div class="h-2 bg-gradient-to-r from-red-400 via-rose-500 to-pink-500"></div>

                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors line-through">
                                        {{ $genre->label }}
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-500 font-mono">
                                        {{ $genre->value }}
                                    </p>

                                    <div class="mt-4 flex items-center justify-between text-sm">
{{--                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">--}}
{{--                                            {{ $genre->musics_count ?? $genre->musics()->withTrashed()->count() }} треков--}}
{{--                                        </span>--}}

                                        @if(auth()->user()?->isAdmin())
                                            <div class="flex gap-4">
                                                <form action="{{ route('genres.restore', $genre->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 font-medium">
                                                        Восстановить
                                                    </button>
                                                </form>

{{--                                                <form action="{{ route('genres.forceDelete', $genre->id) }}" method="POST" class="inline"--}}
                                                <form action="#" method="POST" class="inline"
                                                      onsubmit="return confirm('Удалить жанр «{{ $genre->label }}» навсегда? Это действие нельзя отменить.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-500 dark:hover:text-red-400 font-medium">
                                                        Удалить навсегда
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>

                                    <p class="mt-3 text-xs text-gray-500 dark:text-gray-400 italic">
                                        Удалён: {{ $genre->deleted_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-10">
                        {{ $trashedGenres->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Простой JS для переключения вкладок -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tabs = document.querySelectorAll('.tab-btn');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // убираем активность у всех
                    tabs.forEach(t => {
                        t.classList.remove('border-indigo-600', 'text-indigo-600', 'dark:border-indigo-500', 'dark:text-indigo-400');
                        t.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                    });
                    contents.forEach(c => c.classList.add('hidden'));

                    // активируем выбранную
                    tab.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                    tab.classList.add('border-indigo-600', 'text-indigo-600', 'dark:border-indigo-500', 'dark:text-indigo-400');

                    const target = document.getElementById(`tab-${tab.dataset.tab}`);
                    if (target) target.classList.remove('hidden');
                });
            });
        });
    </script>

@endsection
