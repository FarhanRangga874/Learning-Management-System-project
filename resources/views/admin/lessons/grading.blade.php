<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Koreksi Jawaban: {{ $user->name }}
            </h2>
            {{-- Link kembali ke list user --}}
            <a href="{{ route('admin.lessons.users.index', $lesson->id) }}" class="text-sm text-gray-500 hover:text-gray-700">Kembali ke Daftar</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4 mb-6 flex justify-between items-center">
                <span class="text-indigo-800 font-bold">Total Nilai Saat Ini:</span>
                <span class="text-2xl font-extrabold text-indigo-700">{{ $answers->sum('score') }}</span>
            </div>

            <div class="space-y-6">
                @foreach($answers as $index => $ans)
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        
                        {{-- SOAL --}}
                        <div class="mb-4">
                            <span class="text-xs font-bold bg-gray-100 text-gray-600 px-2 py-1 rounded uppercase">
                                {{ $ans->question->type == 'multiple_choice' ? 'Pilihan Ganda' : 'Essay' }}
                            </span>
                            <span class="text-xs ml-2 text-gray-400">Max Poin: {{ $ans->question->points }}</span>
                            <p class="mt-2 font-bold text-gray-900 text-lg">{{ $index + 1 }}. {{ $ans->question->question_text }}</p>
                        </div>

                        {{-- JAWABAN USER --}}
                        <div class="mb-4 p-4 bg-gray-50 rounded-md border-l-4 {{ $ans->is_correct ? 'border-green-500' : 'border-gray-300' }}">
                            <p class="text-xs text-gray-500 font-bold mb-1">Jawaban User:</p>
                            
                            @if($ans->question->type == 'multiple_choice')
                                <p class="text-lg font-mono font-bold">{{ $ans->answer }}</p>
                                <p class="text-xs text-green-600 mt-2">
                                    Kunci: {{ $ans->question->correct_answer }} 
                                    @if($ans->answer == $ans->question->correct_answer) (Benar) @else (Salah) @endif
                                </p>
                            @else
                                <p class="text-gray-800 whitespace-pre-line">{{ $ans->answer }}</p>
                            @endif
                        </div>

                        {{-- FORM UPDATE NILAI --}}
                        <form action="{{ route('admin.answers.updateScore', $ans->id) }}" method="POST" class="flex items-end gap-4 border-t pt-4 mt-4">
                            @csrf
                            @method('PUT')
                            
                            <div class="w-32">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Berikan Nilai</label>
                                <input type="number" name="score" value="{{ $ans->score }}" max="{{ $ans->question->points }}" min="0" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-bold text-center">
                            </div>
                            
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm font-bold transition">
                                Update Nilai
                            </button>
                        </form>

                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>