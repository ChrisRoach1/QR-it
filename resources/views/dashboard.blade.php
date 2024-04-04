<x-app-layout>
    <div>
        <form method="POST" action="/create-code" role="form">
            @csrf
            <div class="content-center">
                <div class="flex flex-row m-4">
                    <label class="flex-1 form-control w-full max-w-xl mr-4">
                        <input name="url" type="text" placeholder="www.google.com" class="input input-bordered w-full max-w-xl" />
                    </label>
                    <div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </div>
            @error('url')
            <div class="alert alert-error">{{ $message }}</div>
            @enderror
        </form>
    </div>


    <div class="lg:grid lg:grid-cols-3">
        @foreach ($codes as $code)
            <div class="m-4 card w-80 bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">{{$code->url}}</h2>
                    {!! QrCode::size(256)->generate($code->url) !!}
                    <div class="flex flex-row m-1 justify-evenly">
                        <a href="{{ route('download', $code->id) }}"><button type="button" class="btn btn-secondary">Download</button></a>
                        <form method="post" action="{{route('code.destroy',$code->id)}}">
                            @method('delete')
                            @csrf
                            <button type="submit" class="btn btn-accent">Delete</button>
                        </form>
                    </div>


                </div>
            </div>
        @endforeach
    </div>

    {{ $codes->links() }}

</x-app-layout>
