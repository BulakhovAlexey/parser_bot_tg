@extends('layouts.main')

@section('content')
    <div class="form flex justify-center items-center py-3">
        <div class="form_container max-w-[500px] w-full my-0 mx-auto">
            <h1 class="text-center text-2xl mb-2">Авторизация</h1>
            <form
                method="post"
                action="{{ route('login_process') }}"
                class="flex flex-col gap-6 p-4">
                @csrf
                <div class="form__group">
                    <input name="email" class="login @error('email') hasError @enderror" type="text"
                           value="{{ old('email') }}"
                           placeholder="Email">
                    @error('email')
                    <span class="input-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form__group">
                    <input name="password" class="login @error('password') hasError @enderror" type="password"
                           placeholder="Password">
                    @error('password')
                    <span class="input-error">{{ $message }}</span>
                    @enderror

                </div>

                <div class="form__buttons flex justify-center">
                    <button type="submit" class="login">Войти</button>
                </div>
            </form>
        </div>
    </div>
@endsection
