@extends('layouts.main')

@section('title', 'Редактирование жанра • ' . $genre->label)

@section('content')

    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-950 dark:to-gray-900 py-8 md:py-12">

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">

            <!-- Кнопка назад -->
            <a href="{{ route('genres.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 mb-8 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Назад к списку жанров
            </a>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-200/50 dark:border-gray-700/50">

                <div class="p-6 md:p-10">

                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                                Редактировать жанр
                            </h1>
                            <p class="mt-1 text-gray-500 dark:text-gray-400">
                                ID: <code class="text-xs bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">{{ $genre->id }}</code>
                            </p>
                        </div>

                        <span class="px-4 py-1.5 rounded-full text-sm font-medium bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-700/50">
                            {{ $genre->musics()->count() }} треков
                        </span>
                    </div>

                    @if ($errors->any())
                        <div class="mb-8 rounded-lg bg-red-50 dark:bg-red-950/30 border border-red-400/50 dark:border-red-600/40 text-red-700 dark:text-red-300 px-5 py-4">
                            <ul class="list-disc list-inside space-y-1.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('genres.update', $genre->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-7">

                            <!-- Поле Label (отображаемое название) -->
                            <div>
                                <label for="label" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Отображаемое название <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="label"
                                    id="label"
                                    value="{{ old('label', $genre->label) }}"
                                    required
                                    class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-900/60 border border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 transition-all"
                                    placeholder="Например: Электронная музыка"
                                >
                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                                    Это название увидят пользователи на сайте
                                </p>
                            </div>

                            <!-- Поле Value (slug / системное имя) -->
                            <div>
                                <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Системное имя (value) <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="value"
                                    id="value"
                                    value="{{ old('value', $genre->value) }}"
                                    required
                                    pattern="[a-z0-9\-]+"
                                    title="Только строчные буквы, цифры и дефис"
                                    class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-900/60 border border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 transition-all font-mono"
                                    placeholder="electronic"
                                >
                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                                    Используется в URL, фильтрах и коде. Рекомендуется: латиница, дефис вместо пробелов
                                </p>
                            </div>

                            <!-- Кнопки действий -->
                            <div class="flex flex-wrap gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">

                                <button type="submit"
                                        class="inline-flex items-center px-7 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-all shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Сохранить изменения
                                </button>

                                <a href="{{ route('genres.index') }}"
                                   class="inline-flex items-center px-7 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-xl transition-all">
                                    Отмена
                                </a>

                                <!-- Удаление (только для админа с подтверждением) -->
                                @if(auth()->user()?->isAdmin())
                                    <form action="{{ route('genres.destroy', $genre->id) }}" method="POST" class="ml-auto"
                                          onsubmit="return confirm('Вы действительно хотите удалить жанр «{{ $genre->label }}»? Это действие нельзя отменить.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-6 py-3 bg-red-50 dark:bg-red-950/40 text-red-700 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/60 rounded-xl transition-all border border-red-200 dark:border-red-800/50">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Удалить жанр
                                        </button>
                                    </form>
                                @endif

                            </div>

                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
